    var daysInWeek = {'Sunday':0, 'Monday':1, 'Tuesday':2, 'Wednesday':3, 'Thursday':4, 'Friday':5, 'Saturday':6};
    var daysInWeek_arr = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    
    window.crnt_location = "<?=$lat?>,<?=$lon?>";
    window.crnt_day = daysInWeek_arr["<?=$day?>"];
    window.crnt_hour = <?=$hour?>;
    window.crnt_min = <?=$min?>;
    
    console.log("Loaded Data: \n"+window.crnt_day+"\n"+window.crnt_hour+":"+window.crnt_min+"\n"+window.crnt_location);
    
    
    //Scrollers
    $(document).ready(function(){
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
     	
     	var temp_date = new Date("01/01/2013 "+(window.crnt_hour < 10 ? '0'+window.crnt_hour : window.crnt_hour)+":"+(window.crnt_min < 10 ? '0'+window.crnt_min : window.crnt_min)+":00 PST");
     	$("#time").mobiscroll('setDate', temp_date, true, 300);
     	
     	$("#save").click(function() {
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
                 url: "/i-parked-here/set_position.php",
                 data: {
                 	day: dayofweek,
                 	hour: tmp_hour,
                 	min: tmp_min,
                 	lat: tmp_lat,
                 	lon: tmp_lon                      
                 },
                 success: function(data) {
                 	console.log("Saved: "+data);
                 }
            });
     	});
     	
	});
    
    
    
    
    
    //Google Maps Stuff
      function initialize() {
      	
        var mapOptions = {
          center: new google.maps.LatLng(37.79012474939018,-122.42666244506836),
          zoom: 17,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        /*
        new google.maps.LatLng(37.785799, -122.421508),
    		new google.maps.LatLng(37.796668, -122.423654),
    		new google.maps.LatLng(37.795227, -122.435091),
    		new google.maps.LatLng(37.784425, -122.432859),
    		new google.maps.LatLng(37.784425, -122.432859),
    		new google.maps.LatLng(37.785799, -122.421508)

        */
        var map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions);
            var boundryCoordinates = [
    		new google.maps.LatLng(37.78849684470596,-122.42193102836609),
    		new google.maps.LatLng(37.79302436577276,-122.42287516593933),
    		new google.maps.LatLng(37.79195609929177,-122.43112564086914),
    		new google.maps.LatLng(37.78746242830388,-122.4301278591156),
    		new google.maps.LatLng(37.78849684470596,-122.42193102836609)
    		//new google.maps.LatLng(37.784425, -122.432859),
    		//new google.maps.LatLng(37.785799, -122.421508)
  		];
  		var boundry = new google.maps.Polyline({
    		path: boundryCoordinates,
    		strokeColor: "#FF0000",
    		strokeOpacity: 1.0,
    		strokeWeight: 2
  		});
  		var home_icon = "home.png";
  		var car_icon = "car.png";
  		//var home = new google.maps.LatLng(37.788817,-122.424405);
  		var home = new google.maps.LatLng(37.79077759692252,-122.42433428764343);
  			var home_marker = new google.maps.Marker({
      			position: home,
      			map: map,
      			title:"Home.",
      			icon: home_icon
  			});
  		var car_marker;
  		
  		car_loaded_loc = new google.maps.LatLng(<?=$lat?>,<?=$lon?>);
  			car_marker = new google.maps.Marker({
      			position: car_loaded_loc,
      			map: map,
      			title:"This is where I parked. Loaded from file.",
      			icon: car_icon
  			});
  		
   
  		
  		google.maps.event.addListener(map, 'click', function(event) { 
  			if(car_marker) { car_marker.setMap(null); }
  			var car_location = new google.maps.LatLng(event.latLng.lat(),event.latLng.lng());
  			window.crnt_location = event.latLng.lat()+","+event.latLng.lng();
  			console.log(window.crnt_location);
  			car_marker = new google.maps.Marker({
      			position: car_location,
      			map: map,
      			title:"This is where I parked. Changed by you.",
      			icon: car_icon
  			});
	    });

  		boundry.setMap(map); 		
      }
      
      google.maps.event.addDomListener(window, 'load', initialize);
