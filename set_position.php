<?php
if(isset($_POST['day']) && isset($_POST['hour']) &&  isset($_POST['min']) && isset($_POST['lat']) && isset($_POST['lon'])) {
	$day = strip_tags($_POST['day']);
	$hour= strip_tags($_POST['hour']);
	$min = strip_tags($_POST['min']);
	$lat = strip_tags($_POST['lat']);
	$lon = strip_tags($_POST['lon']);
	if($lat && $lon && $day && $hour && $min) {
		if(is_numeric($lat) && is_numeric($lon) && is_numeric($day) && is_numeric($hour) && is_numeric($min)) {
			$put = fopen("crnt_position.txt", "w");
			fwrite($put, $day.",".$hour.",".$min.",".$lat.",".$lon);
			fclose($put);
			echo $day.",".$hour.",".$min.",".$lat.",".$lon;
			return true;
		} else { echo "error1"; return false; }
	} else { echo "error2"; return false; }
} else { echo "error3"; return false; }
?>