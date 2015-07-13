<?php
session_start();
$after = "";
if(isset($_SESSION['email']) && isset($_SESSION['pin'])) { 
	$home_set = "";
	if(isset($_SESSION['home_lat']) && isset($_SESSION['home_lon'])) { 
		if(is_numeric($_SESSION['home_lat']) && is_numeric($_SESSION['home_lon'])) { 
			$home_set .= "window.home_lat = ".($_SESSION['home_lat'] ? $_SESSION['home_lat'] : 0).";";
			$home_set .= "window.home_lon = ".($_SESSION['home_lon'] ? $_SESSION['home_lon'] : 0).";";
		}
	}
	//$after .= "<style>#login-container { display: none !important; }</style>";
	//$after .= "<script>$(document).ready(function(){ $home_set $('#buttons-container').slideDown(300, function() { $('#load-position').click(); }); });</script>";
	$after .= "<script>$(document).ready(function(){ "
		.$home_set 
		."$('#email').val('".$_SESSION['email']."'); $('#pin').val('".$_SESSION['pin']."'); $('#login-register').click(); });</script>";
}
?>
<!DOCTYPE html>
<html>
  <head>
  	<title>I Parked Here</title>
	<link rel="stylesheet" href="inc/style.css" type="text/css" />
	<link rel="stylesheet" href="inc/mobiscroll-2.4.5/css/mobiscroll.custom-2.4.5.min.css" type="text/css" />
	<link rel="shortcut icon" type="image/x-icon" href="inc/favicon.png" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script type="text/javascript" src="inc/mobiscroll-2.4.5/js/mobiscroll.custom-2.4.5.min.js"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKkKDJv0w_PNNvXsvn_Kp1-GZnSUbID1s&sensor=true"></script>
	<?php 
		$info = json_decode(file_get_contents("position.json"), true);
		$days = array('Sunday','Monday', 'Tuesday','Wednesday','Thursday','Friday','Saturday');
		$day_str = $days[$info['day']];
		/*
	<script type="text/javascript">
		window.crnt_location = "<?=$info['lat']?>,<?=$info['lon']?>";
		window.crnt_day = daysInWeek_arr["<?=$info['day']?>"];
		window.crnt_hour = <?=$info['hour']?>;
		window.crnt_min = <?=$info['min']?>;
		window.lat = "<?=$info['lat']?>";
		window.lon = "<?=$info['lon']?>";
	</script>
		*/
	?>
	<script type="text/javascript" src="inc/control.js"></script>
</head>
<body>
	<div id="page-container">
		<div id="login-container">
			Email: <input type="text" id="email" name="email" placeholder="Email address..." /><br />
			Pin: <input type="text" id="pin" name="pin" placeholder="0-9 (5+ digits)" /><br />
			<button id="login-register" name="login-register">Log in/Register</button>
		</div>
		<div id="set-home-container">
			Set Home: <input type="text" id="home-input" name="home-input" placeholder="31 Spooner St..." />
			<button id="set-home-button" name="set-home-button">Save</button>
		</div>
		<div id="buttons-container">
	  		<div id="streetcleaning">Street Cleaning</div>
	  		<select id="dayofweek" name="Day of Week" style="display:none;">
	  			<option id="day-sunday" value="Sunday" <?=($day_str == 'Sunday' ? 'selected' : '')?>>Sunday</option>
	  			<option id="day-monday" value="Monday" <?=($day_str == 'Monday' ? 'selected' : '')?>>Monday</option>
	  			<option id="day-tuesday" value="Tuesday" <?=($day_str == 'Tuesday' ? 'selected' : '')?>>Tuesday</option>
	  			<option id="day-wednesday" value="Wednesday" <?=($day_str == 'Wednesday' ? 'selected' : '')?>>Wednesday</option>
	  			<option id="day-thursday" value="Thursday" <?=($day_str == 'Thursday' ? 'selected' : '')?>>Thursday</option>
	  			<option id="day-friday" value="Friday" <?=($day_str == 'Friday' ? 'selected' : '')?>>Friday</option>
	  			<option id="day-saturday" value="Saturday" <?=($day_str == 'Saturday' ? 'selected' : '')?>>Saturday</option>
	  		</select>
	  		<div id="time"></div>
			<button id="get-position" name="get-position">Get Position</button>
			<button id="load-position" name="load-position">Load Position</button>
			<button id="clear-home" name="clear-home">Clear Home</button>
	  		<button id="save">Save</button>
	  		<button id="logout">Log Out</button>
			<br class="clear" />
	  	</div>
		<br class="clear" />
		<div id="debug"></div>
	  	<div id="canvas-holder"><div id="map-canvas"></div></div>
	</div>
	<?=$after?>
</body>
</html>
