var daysInWeek = {'Sunday':0, 'Monday':1, 'Tuesday':2, 'Wednesday':3, 'Thursday':4, 'Friday':5, 'Saturday':6};
var daysInWeek_arr = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
if(!window.lat) { window.lat = 0; }
if(!window.lon) { window.lon = 0; }
if(!window.home_lat) { window.home_lat = 0; }
if(!window.home_lon) { window.home_lon = 0; }
$(document).ready(function(){
    	//console.log("Loaded Data: \n"+window.crnt_day+"\n"+window.crnt_hour+":"+window.crnt_min+"\n"+window.crnt_location);
	//$("body").css("height", $(document).height()+"px");
	var button_padding = (parseInt($("#buttons-container").css("margin-top").replace(/px/, ""))+parseInt($("#buttons-container").css("margin-bottom").replace(/px/, "")));
	var map_height = ($(document).height()-$("#buttons-container").height()-button_padding-100);
	console.log("Button Padding: "+button_padding+"\nMap Height: "+map_height);
	$("#canvas-holder").css("height", map_height+"px");
	$("#dayofweek").mobiscroll().select({ 
		theme: 'iOS', 
		display: 'inline',
		mode: 'scroller',
		inputClass: 'dayofweek_output'
	});
	$("#time").mobiscroll().time({ 
    		theme: 'iOS', 
    		display: 'inline'    		
     	});
     	
     	//var loaded_date = new Date("01/01/2013 "+(window.crnt_hour < 10 ? '0'+window.crnt_hour : window.crnt_hour)+":"+(window.crnt_min < 10 ? '0'+window.crnt_min : window.crnt_min)+":00 PST");
     	//$("#time").mobiscroll('setDate', loaded_date, true, 300);
     	

	$(document).on("click", "#logout", function() { 
		console.log("[BUTTON CLICK]: Log Out");
		$("#login-container").show();
		$.ajax({
			type: "POST",
			url: "../login.php",
			data: { 
				"logout": true
			},
			success: function(res) { 
				if(res['error']) { 
					if(res['message']) { 
						console.error(res['message']);
						alert(res['message']);
					} else { console.error(res); }
				} else { 
					if(res['message']) { 
						console.log(res['message']);
						$("#buttons-container,#canvas-holder").hide();
					} else { console.error(res); } 
				}
			},
			error: function(data, ajaxOptions, thrownError) {
				console.error(data, ajaxOptions, thrownError);
			}
		});
	});

	$(document).on("click", "#login-register", function() { 
		console.log("[BUTTON CLICK]: Login/Register");
		$("#login-container").hide();
		var email	= $("#email").val();
		var pin		= parseInt($("#pin").val());
		$.ajax({
			type: "POST",
			url: "../login.php",
			data: { 
				"login-register": true,
				email: email,
				pin: pin
			},
			success: function(res) { 
				$("#email,#pin").val("");
				if(res['error']) { 
					if(res['message']) { 
						console.error(res['message']);
						alert(res['message']);
						$("#login-container").show();
					} else { console.error(res); }
				} else { 
					if(res['message']) { 
						console.log(res['message']);
						$.ajax({
							type: "POST",
							url: "../set-home.php",
							data: { 
								"get-home": true
							},
							success: function(home_res) { 
								console.log("Get Home Response:");
								console.log(home_res);
								if(parseInt(home_res['error']) > 1) { 
									console.log("Home not set... Set it!");
									$("#set-home-container").show();
								} else if(home_res['error']) { 
									console.log("Error response: ");
									console.error(home_res['message']);
								} else {
									// User has defined "home"
									console.log("Home previously set!");
									window.home_lat = home_res['lat'];
									window.home_lon = home_res['lon'];
									$("#buttons-container").slideDown(300, function() {
										$("#load-position").click();
									});
								}
							},
							error: function(data, ajaxOptions, thrownError) {
								console.log(data, ajaxOptions, thrownError);
							}
						});

					} else { console.error(res); } 
				}
			},
			error: function(data, ajaxOptions, thrownError) {
				console.error(data, ajaxOptions, thrownError);
			}
		});
	});



	$(document).on("click", "#clear-home", function() { 
		console.log("[BUTTON CLICK]: Clear Home Position");
		$.ajax({
			type: "POST",
			url: "../set-home.php",
			data: { 
				"clear-home": true
			},
			success: function(res) { 
				console.log("Clear Home Response:");
				console.log(res);
				$("#buttons-container,#canvas-holder").hide();
				$("#set-home-container").show();
			},
			error: function(data, ajaxOptions, thrownError) {
				console.error(data, ajaxOptions, thrownError);
			}
		});
	});

	$(document).on("click", "#set-home-button", function() { 
		console.log("[BUTTON CLICK]: Set Home Position");
		var geocoder = new google.maps.Geocoder();
		var address = $("#home-input").val();
		console.log("Look up: "+address);
		geocoder.geocode({ 'address': address }, function (results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				var latitude = results[0].geometry.location.lat();
				var longitude = results[0].geometry.location.lng();
				window.home_lat = latitude;
				window.home_lon = longitude;
				console.log("Latitude: " + latitude + "\nLongitude: " + longitude);
				$.ajax({
					type: "POST",
					url: "../set-home.php",
					data: { 
						"set-home": true,
						address: address,
						lat: latitude,
						lon: longitude
					},
					success: function(res) { 
						console.log("Set Home Response:");
						console.log(res);
						$("#buttons-container").slideDown(300);
						//google.maps.event.addDomListener(window, 'load', initialize);
						//initialize();
						$("#get-position").click();
					},
					error: function(data, ajaxOptions, thrownError) {
						console.error(data, ajaxOptions, thrownError);
					}
				});

			} else {
				console.error("Request failed.");
			}
		});

	});


	$(document).on("click", "#get-position", function() { 
		console.log("[BUTTON CLICK]: Get Position()");
		var startPos;
		var geoSuccess = function(position) {
			startPos = position;
			console.log("Lat: "+startPos.coords.latitude);
			console.log("Lon: "+startPos.coords.longitude);
			draw_map(window.home_lat, window.home_lon, startPos.coords.latitude, startPos.coords.longitude);
		};
		navigator.geolocation.getCurrentPosition(geoSuccess);
	});

	$(document).on("click", "#load-position", function() { 
		console.log("[BUTTON CLICK]: Load Position()");
  		$.ajax({
			type: "POST",
			url: "../set_position.php",
			data: {
				"get-position": true
			},
			success: function(data) {
				console.log("Car Position Loaded: ");
				console.log(data);
				if(data['error']) { 
					$("#get-position").click();
					return false;
				} else { 
					draw_map(window.home_lat, window.home_lon, data['lat'], data['lon']);
					var loaded_date = new Date("01/01/2015 "+pad_zero(data['hour'])+":"+pad_zero(data['min'])+":00 PST");
					console.log("Date Loaded: "+loaded_date);
					$("#time").mobiscroll('setDate', loaded_date, true, 300);
					$("#dayofweek li").prop("selected", "");
					$("#day-"+daysInWeek_arr[data['day']].toLowerCase()).prop("selected", "selected");
				}
			},
			error: function(data, ajaxOptions, thrownError) {
				console.error(data, ajaxOptions, thrownError);
			}
		});
	});

     	$(document).on("click", "#save", function() {
		console.log("[BUTTON CLICK]: Save Position");
     		var time_str = $("#time").mobiscroll('getDate');
     		var time = String(time_str).replace(/:00\ GMT.*/, "").substring(16);
     		var time_spl = time.split(/:/);
     		var tmp_hour = time_spl[0];
     		var tmp_min = time_spl[1];
     		
     		var dayofweek_str = $(".dayofweek_output").val();
     		var dayofweek = daysInWeek[dayofweek_str];
     		console.log(time+"\n"+dayofweek);
     		
     		var spl = window.crnt_location.split(/,/);
     		var tmp_lat = spl[0];
     		var tmp_lon = spl[1];
     		
     		
  		$.ajax({
			type: "POST",
			url: "../set_position.php",
			data: {
				"set-position": true,
				day: dayofweek,
				hour: tmp_hour,
				min: tmp_min,
				lat: tmp_lat,
				lon: tmp_lon                      
			},
			success: function(data) {
				console.log("Car Position Saved: ");
				console.log(data);
				//$("#debug").html(data);
			},
			error: function(data, ajaxOptions, thrownError) {
				console.error(data, ajaxOptions, thrownError);
			}
		});
	});     	
});


function pad_zero(number) { 
	return parseInt((number < 10 ? number : '0'+number));
}
    
function draw_map(home_lat, home_lon, car_lat, car_lon) { 
	console.log("Draw Map!");
	var home_icon = "inc/home.png";
	var car_icon = "inc/car.png";
	var mapOptions = {
		center: new google.maps.LatLng(home_lat, home_lon),
		zoom: 16,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	console.log("Map options set");
	var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
	car_loaded_loc = new google.maps.LatLng(car_lat,car_lon);
	car_marker = new google.maps.Marker({
		position: car_loaded_loc,
		map: map,
		title:"This is where I parked. Loaded from file.",
		icon: car_icon
	});
	console.log("Car marker set");
	var home = new google.maps.LatLng(home_lat,home_lon);
	var home_marker = new google.maps.Marker({
		position: home,
		map: map,
		title:"Home.",
		icon: home_icon
	});
	console.log("Home marker set");
	google.maps.event.addListener(map, 'click', function(event) { 
		if(car_marker) { car_marker.setMap(null); }
		var car_location = new google.maps.LatLng(event.latLng.lat(),event.latLng.lng());
		window.crnt_location = event.latLng.lat()+","+event.latLng.lng();
		console.log(window.crnt_location);
		$("#save").click();
		car_marker = new google.maps.Marker({
			position: car_location,
			map: map,
			title:"This is where I parked. Changed by you.",
			icon: car_icon
		});
	});
}
