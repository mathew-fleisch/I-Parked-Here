<?php
session_start();
header("Content-type: application/json");
if(isset($_SESSION['id'])) { 
	$user_id = (int)strip_tags($_SESSION['id']);
	if(isset($_POST['set-position'])) { 
		if(isset($_POST['day']) && isset($_POST['hour']) &&  isset($_POST['min']) && isset($_POST['lat']) && isset($_POST['lon'])) {
			$day = strip_tags($_POST['day']);
			$hour= strip_tags($_POST['hour']);
			$min = strip_tags($_POST['min']);
			$lat = strip_tags($_POST['lat']);
			$lon = strip_tags($_POST['lon']);
			if($lat && $lon && $day && $hour && $min) {
				if(is_numeric($lat) && is_numeric($lon) && is_numeric($day) && is_numeric($hour) && is_numeric($min)) {
					$out = array("day"=>$day,"hour"=>$hour,"min"=>$min,"lat"=>$lat,"lon"=>$lon);
					file_put_contents("users/position-$user_id.json", json_encode($out));
					echo json_encode(array("error"=>0,"message"=>"Car position set...", "parameters"=>$out));
				} else { echo json_encode(array("error"=>1,"message"=>" ** Error ** Posted data is not in the correct format..."));  }
			} else { echo json_encode(array("error"=>1,"message"=>" ** Error ** Missing Data - Required: Lat/Lon, Day, Hour, Minute..."));  }
		} else { echo json_encode(array("error"=>1,"message"=>" ** Error ** POST Required: Lat/Lon, Day, Hour, Minute...")); }
	} elseif(isset($_POST['get-position'])) {
		if(file_exists("users/position-$user_id.json")) { 
			echo file_get_contents("users/position-$user_id.json");
		} else { echo json_encode(array("error"=>1, "message"=>"Position not saved...")); } 
	} else { echo json_encode(array("error"=>1,"message"=>" ** Error ** Post data missing...")); }
} else { echo json_encode(array("error"=>1,"message"=>"User not logged in properly... (no user id found)")); }
?>
