<?php
/*
	COPYRIGHT CS440 Group @ UIUC 
	By Haoran Yu, Le Wang and Tao Feng
*/
define ("SIZE", "6");
include("function.php");
function shouldBlitzing($map_x, $r, $c, $user){
	if($r - 1 >= 0 && $map_x[$r - 1][$c]["color"] == $user) return true;
	if($c - 1 >= 0 && $map_x[$r][$c - 1]["color"] == $user) return true;
	if($r + 1 < SIZE && $map_x[$r+1][$c]["color"] == $user) return true;
	if($c + 1 < SIZE && $map_x[$r][$c+1]["color"] == $user) return true;
	return false;
}
function takeStep($map_x, $r, $c, $user){
	$con_flag = 0;
    if($map_x[$r][$c]["color"] == 0 || $map_x[$r][$c]["color"] == 1){
		return false;// cannot drop color here
	}
	else{
		$map_x[$r][$c]["color"] = $user;
		if(shouldBlitzing($map_x, $r, $c, $user)){
			if($r - 1 >= 0 && $map_x[$r - 1][$c]["color"] == not($user)){
				$map_x[$r-1][$c]["color"] = $user;
				$con_flag = 1;
			}
			if($c - 1 >= 0 && $map_x[$r][$c - 1]["color"] == not($user)) {
				$map_x[$r][$c-1]["color"] = $user;
				$con_flag = 1;
			}
			if($r + 1 < SIZE && $map_x[$r+1][$c]["color"] == not($user)) {
				$map_x[$r+1][$c]["color"] = $user;
				$con_flag = 1;
			}
			if($c + 1 < SIZE && $map_x[$r][$c+1]["color"] == not($user)) {
				$map_x[$r][$c+1]["color"] = $user;
				$con_flag = 1;
			}
		}
	}
	return array($map_x, $con_flag);
}
function takeSteps($map_x, $steps){
	$user = 1;
	$first_step = 1;
	$con_flag = 0;
	foreach($steps as $a){
		$coor = explode(",",$a);
		$ret = takeStep($map_x, $coor[0], $coor[1], $user);
		$map_x = $ret[0];
		if($ret[1] == 1 && $first_step == 1){
			$con_flag = 1;
		}
		$user = not($user);
		$first_step = 0;
	}
	return array($map_x, $con_flag);
}
$map = objectToArray(json_decode(urldecode($_GET['map'])));
$steps = objectToArray(json_decode(urldecode($_GET['step'])));
echo json_encode(takeSteps($map, $steps));
?>