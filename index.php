<?php
include("config.php");
include("load_map.php");
include("function.php");
function shouldBlitzing($map_x, $r, $c, $user_x){
	if($r - 1 >= 0 && $map_x[$r - 1][$c]["color"] == $user_x) return true;
	if($c - 1 >= 0 && $map_x[$r][$c - 1]["color"] == $user_x) return true;
	if($r + 1 < 6 && $map_x[$r+1][$c]["color"] == $user_x) return true;
	if($c + 1 < 6 && $map_x[$r][$c+1]["color"] == $user_x) return true;
	return false;
}
function takeStep($map_x, $r, $c, $user_x = 1){
    if($map_x[$r][$c]["color"] == 0 || $map_x[$r][$c]["color"] == 1){
		return false;// cannot drop color here
	}
	else{
		if($user_x == 1){
			$map_x[$r][$c]["color"] = 1;
			if(shouldBlitzing($map_x, $r, $c, $user_x)){
				if($r - 1 >= 0 && $map_x[$r - 1][$c]["color"] == 0) $map_x[$r-1][$c]["color"] = 1;
				if($c - 1 >= 0 && $map_x[$r][$c - 1]["color"] == 0) $map_x[$r][$c-1]["color"] = 1;
				if($r + 1 < 6 && $map_x[$r+1][$c]["color"] == 0) $map_x[$r+1][$c]["color"] = 1;
				if($c + 1 < 6 && $map_x[$r][$c+1]["color"] == 0) $map_x[$r][$c+1]["color"] = 1;
			}
		}
		else{
			$map_x[$r][$c]["color"] = 0;
			if(shouldBlitzing($map_x, $r, $c, $user_x)){
				if($r - 1 >= 0 && $map_x[$r - 1][$c]["color"] == 1) $map_x[$r-1][$c]["color"] = 0;
				if($c - 1 >= 0 && $map_x[$r][$c - 1]["color"] == 1) $map_x[$r][$c-1]["color"] = 0;
				if($r + 1 < 6 && $map_x[$r+1][$c]["color"] == 1) $map_x[$r+1][$c]["color"] = 0;
				if($c + 1 < 6 && $map_x[$r][$c+1]["color"] == 1) $map_x[$r][$c+1]["color"] = 0;
			}
		}
	}
	return $map_x;
}
function getScore($map_x){
	$score = array(0, 0);
	foreach($map_x as $r){
		foreach($r as $c){
			if($c['color'] == 1) $score[1] += $c['value'];
			elseif($c['color'] == 0) $score[0] += $c['value'];
		}
	}
	return $score;
}
function evalueDepth($map_x){
	return 2;
}
function buildTree_MinMax($map_x){
	$tree = buildTree_MinMax_helper($map_x, array(), array(), 0, 0);
	$tree = array("next" => "x,x", "value"=> -1, "user" => 1,"subtree" =>$tree);
	$tree["value"] = getMinMax($tree["subtree"],1,1);
	$tree["next"] = $tree["value"][1];
	$tree["value"] = $tree["value"][0];
	return $tree;
}
function reinitMap($map_x){
	for($r = 0; $r < 6; $r ++){
		for($c = 0; $c < 6; $c ++){
			$map_x[$r][$c]['color'] = -1;
		}
	}
	return $map_x;
}
function getDecision_MinMax($map_x){
	$result = buildTree_MinMax($map_x);
	$result = explode(",", $result['next']);
	return $result;
}
function getMinMax($subtree,$max,$root = 0){
	$coor = "";
	if($max == 1) {
		$retval = 0;
	}
	else{
		$retval = 999999;
	}
	foreach($subtree as $node){
		if($max == 1){
			if($node["value"] > $retval) {
				$retval = $node["value"];
				$coor = $node["coor"];
			}
		}
		else{
			if($node["value"] < $retval) {
				$retval = $node["value"];
				$coor = $node["coor"];
			}
		}
	}
	if($root == 0){	
		return $retval;
	}
	else{
		return array($retval,$coor);
	}
}
function buildTree_MinMax_helper($map_x, $tree, $ancestor ,$level = 0, $user = 1){
	//print_r($tree);
	if(evalueDepth($map_x) == $level) return -1;
	else{
		$subtree = array();
		for($r = 0; $r < 6; $r ++){
			for($c = 0; $c < 6; $c ++){
				if($map_x[$r][$c]["color"] == -1){
					if(!in_array($r.",".$c, $ancestor)){
						$node = array("coor" => $r.",".$c, "value"=> -1, "user" => $user,"subtree" => buildTree_MinMax_helper($map_x, array(), array_merge($ancestor, array($r.",".$c)), $level+1, not($user)));
						
						if($node["subtree"] == -1){ // if it the leaf
							$user_in = 0;
							$ancestor_in = array_merge($ancestor, array($r.",".$c));
							//print_r($ancestor_in);
							//0,0之外的所有的第三层都没用5,X作为祖先，很奇怪
							$map_x_in = reinitMap($map_x);
							foreach($ancestor_in as $a){
								$coor = explode(",",$a);
								$map_x_in = takeStep($map_x_in, $coor[0], $coor[1], not($user_in));
							}
							$node["value"] = getScore($map_x_in);
							$node["value"] = $node["value"][$node["user"]];
						}
						else{
							$node["value"] = getMinMax($node["subtree"], $user);
						}
						array_push($subtree, $node);
					}
				}
			}
		}
	}
	return $subtree;
}
function flipView($map_x){
	foreach($map_x as &$r){
		foreach($r as &$c){
			if($c['color'] == 1) $c['color'] = 0;
			elseif($c['color'] == 0) $c['color'] = 1;
		}
	}
	return $map_x;
}
print_r(buildTree_MinMax($map));
//play it !
/*
$coor = getDecision_MinMax($map);
$map = takeStep($map, $coor[0], $coor[1], 1);
drawMap($map);

$map = flipView($map);
$coor = getDecision_MinMax($map);
$map = takeStep($map, $coor[0], $coor[1], 1);

$map = flipView($map);

$coor = getDecision_MinMax($map);
$map = takeStep($map, $coor[0], $coor[1], 1);
drawMap($map);
$map = flipView($map);

$coor = getDecision_MinMax($map);
$map = takeStep($map, $coor[0], $coor[1], 1);

$map = flipView($map);

$coor = getDecision_MinMax($map);
$map = takeStep($map, $coor[0], $coor[1], 1);
drawMap($map);
$map = flipView($map);

$coor = getDecision_MinMax($map);
$map = takeStep($map, $coor[0], $coor[1], 1);
$map = flipView($map);

$coor = getDecision_MinMax($map);

$map = takeStep($map, $coor[0], $coor[1], 1);
$map = flipView($map);
*/


?>
