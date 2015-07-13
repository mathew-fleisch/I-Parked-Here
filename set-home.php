<?php
session_start();
header("Content-type: application/json");
if(isset($_POST['set-home'])) { 
	if(isset($_SESSION['id'])) { 
		$user_id = (int)strip_tags($_SESSION['id']);
		if(isset($_POST['address']) && isset($_POST['lat']) && isset($_POST['lon'])) { 
			if(is_numeric($_POST['lat']) && is_numeric($_POST['lon'])) { 
				$_SESSION['home_lat'] = $_POST['lat'];
				$_SESSION['home_lon'] = $_POST['lon'];
				file_put_contents("users/home-$user_id.json", json_encode(array("address"=>strip_tags(urldecode(strip_tags($_POST['address']))),"lat"=>$_POST['lat'], "lon"=>$_POST['lon'])));
				echo json_encode(array("error"=>0,"message"=>"User home saved!"));
			} else { echo json_encode(array("error"=>1,"message"=>"Latitude or Longitude not in correct format...")); } 
		} else { echo json_encode(array("error"=>1,"message"=>"Latitude or Longitude missing... Could not save home.")); } 
	} else { echo json_encode(array("error"=>1,"message"=>"User not logged in properly... (no user id found)")); }
}
if(isset($_POST['get-home'])) { 
	if(isset($_SESSION['id'])) { 
		$user_id = (int)strip_tags($_SESSION['id']);
		$user_filename = "users/home-$user_id.json";
		if(file_exists($user_filename)) { 
			$user_file = json_decode(file_get_contents($user_filename), true);
			if(is_numeric($user_file['lat']) && is_numeric($user_file['lat'])) { 
				$_SESSION['home_lat'] = $user_file['lat'];
				$_SESSION['home_lon'] = $user_file['lon'];
				echo json_encode(array("error"=>0, "message"=>"Home has been previously set...", "lat"=>$user_file['lat'], "lon"=>$user_file['lon']));;
			} else { echo json_encode(array("error"=>1,"message"=>"Latitude or Longitude saved in user file are not coordinates...")); } 
		} else { echo json_encode(array("error"=>2,"message"=>"User home file not found.")); } 
	} else { echo json_encode(array("error"=>1,"message"=>"User not logged in properly... (no user id found)")); }
}
if(isset($_POST['clear-home'])) { 
	if(isset($_SESSION['id'])) { 
		$user_id = (int)strip_tags($_SESSION['id']);
		$user_filename = "users/home-$user_id.json";
		if(file_exists($user_filename)) { 
			unlink($user_filename);
			if(file_exists($user_filename)) { 
				echo json_encode(array("error"=>1,"message"=>"File could NOT be deleted..."));
			} else { echo json_encode(array("error"=>0,"message"=>"Home was cleared..")); }
		} else { echo json_encode(array("error"=>2,"message"=>"User home file not found.")); } 
	} else { echo json_encode(array("error"=>1,"message"=>"User not logged in properly... (no user id found)")); }
}
?>
