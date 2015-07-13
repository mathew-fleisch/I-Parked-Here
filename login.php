<?php
session_start();
header("Content-type: application/json");
$salt_length	= 20;
$base		= 10000;
$min_pin	= 9999;
$random_salt	= substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $salt_length);
if(isset($_POST['logout'])) {
	$_SESSION = array();
	session_destroy();
	echo json_encode(array("error"=>0,"message"=>"User successfully logged out."));
}
if(isset($_POST['login-register'])) {
	$regex_email = "^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$"; 
	if(isset($_POST['email'])) { 
		$email = strtolower(strip_tags(urldecode(strip_tags($_POST['email']))));
		if(!preg_match("/".$regex_email."/", $email)) { $email = null; }
	} else { $email = null; } 
	$pin = 0;
	if(isset($_POST['pin'])) { $pin = (int)strip_tags($_POST['pin']); } 

	if(strlen($email) && $pin > $min_pin) { 
		$right_now	= date("Ymd-His");
		$users_file	= "users/users.json";
		$users_raw	= file_get_contents($users_file);
		$users		= json_decode($users_raw, true);
		if(preg_match("/\"$email\"/", $users_raw)) { 
			//Email found... Check pin
			//echo json_encode(array("error"=>0, "message"=>$users_raw));
			$tmp = array();
			foreach($users as $user) { 
				if(preg_match("/".$email."/", $user['email'])) {
					if($user['pin'] == hash("sha256", $user['salt'].$pin)) {
						echo json_encode(array("error"=>0,"message"=>"Email found... pin matched!"));
						$_SESSION['email']	= $email;
						$_SESSION['pin']	= $pin;
						$_SESSION['user_since']	= $user['created'];
						$_SESSION['log_count']	= $user['log-count'];
						$_SESSION['id']		= $user['id'];
						$_SESSION['salt']	= $user['salt'];

						$_SESSION['crnt_time']	= $right_now;
						$user_record = array("id"=>$user['id'],"created"=>$user['created'],"log-count"=>((int)$user['log-count']+1), "email"=>$email,"salt"=>$random_salt,"pin"=>hash("sha256", $random_salt.$pin));
						array_push($tmp, $user_record);
					} else { 
						echo json_encode(array("error"=>1,"message"=>"Incorect Pin..."));
						return false;
					}
				} else { array_push($tmp, $user); } 
			}
			file_put_contents($users_file, json_encode($tmp));
		} else { 
			//Email NOT found... register user
			$_SESSION['email']	= $email;
			$_SESSION['pin']	= $pin;
			$_SESSION['user_since']	= $right_now;
			$_SESSION['log_count']	= 1;
			$_SESSION['salt']	= $random_salt;
			$_SESSION['crnt_time']	= $right_now;
			if(!strlen($users_raw)) { 
				$_SESSION['id']		= $base;
				$user_record = array("id"=>$base,"created"=>$right_now,"log-count"=>1, "email"=>$email,"salt"=>$random_salt,"pin"=>hash("sha256", $random_salt.$pin));
				file_put_contents($users_file, json_encode(array($user_record)));
			} else { 
				$_SESSION['id']		= ((int)$users[(count($users)-1)]['id']+1);

				$user_record = array("id"=>((int)$users[(count($users)-1)]['id']+1),"created"=>$right_now,"log-count"=>1, "email"=>$email,"salt"=>$random_salt,"pin"=>hash("sha256", $random_salt.$pin));
				array_push($users, $user_record);
				file_put_contents($users_file, json_encode($users));
			}
			echo json_encode(array("error"=>0,"message"=>"Email not found... Register user!"));
		}
	} else {
		if($pin > $min_pin) { 
			echo json_encode(array("error"=>1,"message"=>"Invalid Email... \"$email\""));
			return false;
		} else {
			echo json_encode(array("error"=>1,"message"=>"Pin out of range... \"$pin\" < $min_pin"));
			return false;
		}
	}
} 

?>
