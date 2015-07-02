<?php
if(isset($_POST['day']) && isset($_POST['hour']) &&  isset($_POST['min']) && isset($_POST['lat']) && isset($_POST['lon'])) {
	$day = strip_tags($_POST['day']);
	$hour= strip_tags($_POST['hour']);
	$min = strip_tags($_POST['min']);
	$lat = strip_tags($_POST['lat']);
	$lon = strip_tags($_POST['lon']);
	if($lat && $lon && $day && $hour && $min) {
		if(is_numeric($lat) && is_numeric($lon) && is_numeric($day) && is_numeric($hour) && is_numeric($min)) {
			$out = array("day"=>$day,"hour"=>$hour,"min"=>$min,"lat"=>$lat,"lon"=>$lon);
			file_put_contents("position.json", json_encode($out));
			return true;
		} else { echo " ** Error ** Posted data is not in the correct format..."; return false; }
	} else { echo " ** Error ** Missing Data - Required: Lat/Lon, Day, Hour, Minute..."; return false; }
} else { echo " ** Error ** Post data missing..."; return false; }
?>
