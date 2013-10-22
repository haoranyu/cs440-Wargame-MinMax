<?php
include("config.php");
include("load_map.php");
include("function.php");
function shouldBlitzing($map, $r, $c, $user){
	if($r - 1 >= 0 && $map[$r - 1][$c]["color"] == $user) return true;
	if($c - 1 >= 0 && $map[$r][$c - 1]["color"] == $user) return true;
	if($r + 1 < 6 && $map[$r+1][$c]["color"] == $user) return true;
	if($c + 1 < 6 && $map[$r][$c+1]["color"] == $user) return true;
	return false;
}
function takeStep($map, $r, $c, $user = 1){
	if($map[$r][$c]["color"] == 0 || $map[$r][$c]["color"] == 1){
		return false;// cannot drop color here
	}
	else{
		if($user == 1){
			$map[$r][$c]["color"] = 1;
			if(shouldBlitzing($map, $r, $c, $user)){
				if($r - 1 >= 0 && $map[$r - 1][$c]["color"] == 0) $map[$r-1][$c]["color"] = 1;
				if($c - 1 >= 0 && $map[$r][$c - 1]["color"] == 0) $map[$r][$c-1]["color"] = 1;
				if($r + 1 < 6 && $map[$r+1][$c]["color"] == 0) $map[$r+1][$c]["color"] = 1;
				if($c + 1 < 6 && $map[$r][$c+1]["color"] == 0) $map[$r][$c+1]["color"] = 1;
			}
		}
		else{
			$map[$r][$c]["color"] = 0;
			if(shouldBlitzing($map, $r, $c, $user)){
				if($r - 1 >= 0 && $map[$r - 1][$c]["color"] == 1) $map[$r-1][$c]["color"] = 0;
				if($c - 1 >= 0 && $map[$r][$c - 1]["color"] == 1) $map[$r][$c-1]["color"] = 0;
				if($r + 1 < 6 && $map[$r+1][$c]["color"] == 1) $map[$r+1][$c]["color"] = 0;
				if($c + 1 < 6 && $map[$r][$c+1]["color"] == 1) $map[$r][$c+1]["color"] = 0;
			}
		}
	}
	return $map;
}
function getScore($map){
	$score = array(0, 0);
	foreach($map as $r){
		foreach($r as $c){
			if($c['color'] == 1) $score[1] += $c['value'];
			elseif($c['color'] == 0) $score[0] += $c['value'];
		}
	}
	return $score;
}
function evalueDepth($map){
	return 3;
}
function buildTree($map){
	$tree = buildTree_helper($map, array(), array(), 0);
	return $tree;
}
function buildTree_helper($map, $tree, $ancestor ,$level = 0){
	if(evalueDepth($map) == $level) return -1;
	else{
		$subtree = array();
		for($r = 0; $r < 6; $r ++){
			for($c = 0; $c < 6; $c ++){
				if(!in_array($r.",".$c, $ancestor)){
					array_push($subtree,array("r"=>$r, "c"=>$c, "value"=> -1, "subtree" => buildTree_helper($map, $tree, array_merge($ancestor, array($r.",".$c)), $level+1)));
				}
			}
		}
	}
	return $subtree;
}

print_r(buildTree($map));
drawMap($map);
print_r(getScore($map));
?>
