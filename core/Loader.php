<?php
function load_dir_files($directory) {
	if(is_dir($directory)) {
		$scan = scandir($directory);
		unset($scan[0], $scan[1]); //unset . and ..
		foreach($scan as $file) {
			if(is_dir($directory."/".$file)) {
				load_dir_files($directory."/".$file);
			} else {
				if(strpos($file, '.php') !== false) {
					require_once($directory."/".$file);
				}
			}
		}
	}
}

function span_count($time){
	$time = array_map('intval', explode(':', $time));
	if($time['0']>0){
		if($time[0]>24){
			$time = intval($time[0]/24); 
            $interval = $time>1 ? ' days ago' : ' day ago';
		}elseif($time[0]>730){
			$time = intval($time[0]/730); 
            $interval = $time>1 ? ' month ago' : ' months ago';
		}elseif($time[0]>8760){
			$time = intval($time[0]/8760); 
            $interval = $time>1 ? ' year ago' : ' years ago';
		}else{
        	$time = intval($time[0]); 
            $interval = $time>1 ? ' hours ago' : ' hour ago';
        }
	}elseif($time[1]>0){
		$time = $time[1]; 
		$interval = $time>1 ? ' minutes ago' : ' minute ago';
	}else{
		$time = $time[2];
		$interval = ' seconds ago';
	}

	return $time.$interval;
}

function config($conf){
	if($conf = 'all'){ $env = $_ENV;
		}else{ $env = $_ENV[strtoupper($conf)]; 
	}
	return $env;
}

function account($data){
	if(!empty($_SESSION)){
		return $_SESSION[$data];
	}else{
		return 'none';
	}
}

function role(){
	if(!empty($_SESSION)){
		switch($_SESSION['user_role']){
			case 1: return 'Administrator';	break;
			case 2: return 'Teacher';		break;
			default: return 'Student'; 
		}
	}else{
		return 'none';
	}
}

function viewer(){
	return $_SESSION['user_role'];
}

require_once 'Configuration.php';
require_once 'Notices.php';
load_dir_files('controls');
?>