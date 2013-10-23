<?php

include("function.php");
function shouldBlitzing($map_x, $r, $c, $user){
	if($r - 1 >= 0 && $map_x[$r - 1][$c]["color"] == $user) return true;
	if($c - 1 >= 0 && $map_x[$r][$c - 1]["color"] == $user) return true;
	if($r + 1 < 6 && $map_x[$r+1][$c]["color"] == $user) return true;
	if($c + 1 < 6 && $map_x[$r][$c+1]["color"] == $user) return true;
	return false;
}
function takeStep($map_x, $r, $c, $user){
    if($map_x[$r][$c]["color"] == 0 || $map_x[$r][$c]["color"] == 1){
		return false;// cannot drop color here
	}
	else{
		$map_x[$r][$c]["color"] = $user;
		if(shouldBlitzing($map_x, $r, $c, $user)){
			if($r - 1 >= 0 && $map_x[$r - 1][$c]["color"] == not($user)) $map_x[$r-1][$c]["color"] = $user;
			if($c - 1 >= 0 && $map_x[$r][$c - 1]["color"] == not($user)) $map_x[$r][$c-1]["color"] = $user;
			if($r + 1 < 6 && $map_x[$r+1][$c]["color"] == not($user)) $map_x[$r+1][$c]["color"] = $user;
			if($c + 1 < 6 && $map_x[$r][$c+1]["color"] == not($user)) $map_x[$r][$c+1]["color"] = $user;
		}
	}
	return $map_x;
}
function takeSteps($map_x, $steps){
	$user = 1;
	foreach($steps as $a){
		$coor = explode(",",$a);
		$map_x = takeStep($map_x, $coor[0], $coor[1], $user);
		$user = not($user);
	}
	return $map_x;
}
$map = objectToArray(json_decode(urldecode($_GET['map'])));
$steps = objectToArray(json_decode(urldecode($_GET['step'])));
echo json_encode(takeSteps($map, $steps));
?>