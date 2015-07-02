<!DOCTYPE html>
<html>
  <head>
  	<title>I Parked Here</title>
	<link rel="stylesheet" href="inc/style.css" type="text/css" />
	<link rel="stylesheet" href="inc/mobiscroll-2.4.5/css/mobiscroll.custom-2.4.5.min.css" type="text/css" />
	<script type="text/javascript" src="inc/jquery.js"></script>
	<script type="text/javascript" src="inc/mobiscroll-2.4.5/js/mobiscroll.custom-2.4.5.min.js"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKkKDJv0w_PNNvXsvn_Kp1-GZnSUbID1s&sensor=true"></script>
	<?php 
		$info = json_decode(file_get_contents("position.json"), true);
		$days = array('Sunday','Monday', 'Tuesday','Wednesday','Thursday','Friday','Saturday');
		$day_str = $days[$info['day']];
	?>
	<script type="text/javascript" src="inc/control.js"></script>
	<script type="text/javascript">
		window.crnt_location = "<?=$info['lat']?>,<?=$info['lon']?>";
		window.crnt_day = daysInWeek_arr["<?=$info['day']?>"];
		window.crnt_hour = <?=$info['hour']?>;
		window.crnt_min = <?=$info['min']?>;
		window.lat = "<?=$info['lat']?>";
		window.lon = "<?=$info['lon']?>";
	</script>
</head>
<body>
	<div id="buttons_container">
  		<div id="streetcleaning">Street Cleaning</div>
  		<select id="dayofweek" name="Day of Week" style="display:none;">
  			<option value="Sunday" <?=($day_str == 'Sunday' ? 'selected' : '')?>>Sunday</option>
  			<option value="Monday" <?=($day_str == 'Monday' ? 'selected' : '')?>>Monday</option>
  			<option value="Tuesday" <?=($day_str == 'Tuesday' ? 'selected' : '')?>>Tuesday</option>
  			<option value="Wednesday" <?=($day_str == 'Wednesday' ? 'selected' : '')?>>Wednesday</option>
  			<option value="Thursday" <?=($day_str == 'Thursday' ? 'selected' : '')?>>Thursday</option>
  			<option value="Friday" <?=($day_str == 'Friday' ? 'selected' : '')?>>Friday</option>
  			<option value="Saturday" <?=($day_str == 'Saturday' ? 'selected' : '')?>>Saturday</option>
  		</select>
  		<div id="time"></div>
  		<button id="save">Save</button>
  	</div>
	<div id="debug"></div>
  	<div id="canvas-holder"><div id="map-canvas"></div></div>
  </body>
</html>
