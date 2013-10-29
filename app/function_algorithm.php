<?php
/*
	COPYRIGHT CS440 Group @ UIUC 
	By Haoran Yu, Le Wang and Tao Feng
*/
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
function getDecision_AB($map_x, $maxdepth){
	$result = buildTree_AB($map_x, $maxdepth);
	return array($result['next'],$result['count']);
}
function getMinMax($subtree,$max,$root = 0){
	if(empty($subtree)){
		if($max == 1) {
			return 99999;
		}
		else{
			return 0;
		}
	}
	
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

function buildTree_AB($map_x, $maxdepth){
	$tree = buildTree_AB_helper($map_x, array(), 0, 0, 0, 999999, $maxdepth);
	$tree = array("next" => "x,x", "value"=> -1, "max" => 1,"subtree" =>$tree[1]);
	$tree["value"] = getMinMax($tree["subtree"],1,1);
	$tree["next"] = $tree["value"][1];
	$tree["value"] = $tree["value"][0];
	$tree["count"] = 0;
	foreach($tree["subtree"] as $node){
		$tree["count"] += $node['count'];
	}
	return $tree;
}
function buildTree_AB_helper($map_x, $ancestor ,$level = 0, $user, $alpha, $beta, $maxdepth){
	if($level >= $maxdepth){
		$ret = excuteStep($map_x, $ancestor);
		$map_x = $ret[0];
		$socre = getScore($map_x);
		return array($socre[1], $socre[1]);
	}
	if($user == 1){
		$subtree = array();
		for($r = 0; $r < 6; $r++){
			for($c = 0; $c < 6; $c++){
				if($map_x[$r][$c]["color"] == -1){
					if(!in_array($map_x[$r][$c]["coor"],$ancestor)){
						$ancestor_next = array_merge($ancestor, array($map_x[$r][$c]["coor"]));
						$val = buildTree_AB_helper($map_x, $ancestor_next ,$level+1, 0, $alpha, $beta, $maxdepth);
						$node = array("coor"=>$map_x[$r][$c]["coor"] ,/*"max" => $user, "alpha"=>$alpha,"beta"=>array("origin"=>$beta,"changto"=>min($beta,$val[0])),*/ "value" => 0, "subtree" => $val[1]);

						$edge_counter = 1;
						while(is_array($node["subtree"]) && empty($node["subtree"])){
							$val = buildTree_AB_helper($map_x, $ancestor_next ,$level+1+$edge_counter, 0, $alpha, $beta, $maxdepth);
							$node = array("coor"=>$map_x[$r][$c]["coor"] ,/*"max" => $user, "alpha"=>$alpha,"beta"=>array("origin"=>$beta,"changto"=>min($beta,$val[0])),*/ "value" => 0, "subtree" => $val[1]);
							$edge_counter ++;
						}
						if(is_array($node["subtree"])){
							$node["value"] = getMinMax($node["subtree"], $user);
							$node["count"] = 0;
							foreach($node["subtree"] as $n){
								$node["count"] += $n['count'];
							}
						}
						else{
							$node["value"] = $node["subtree"];
							unset($node["subtree"]);
							$node["count"] = 1;
						}
						
						$beta = min($beta,$val[0]);
						array_push($subtree, $node);
						if($beta <= $alpha)
							return array($beta, $subtree);
					}
				}
			}
		}
		return array($beta, $subtree);
	}
	else{
		$subtree = array();
		for($r = 0; $r < 6; $r++){
			for($c = 0; $c < 6; $c++){
				if($map_x[$r][$c]["color"] == -1){
					if(!in_array($map_x[$r][$c]["coor"],$ancestor)){
						$ancestor_next = array_merge($ancestor, array($map_x[$r][$c]["coor"]));
						$val = buildTree_AB_helper($map_x, $ancestor_next ,$level+1, 1, $alpha, $beta, $maxdepth);
						$node = array("coor"=>$map_x[$r][$c]["coor"] ,/*"max" => $user, "alpha"=>$alpha,"beta"=>array("origin"=>$beta,"changto"=>min($beta,$val[0])),*/ "value" => 0, "subtree" => $val[1]);

						$edge_counter = 1;
						while(is_array($node["subtree"]) && empty($node["subtree"])){
							$val = buildTree_AB_helper($map_x, $ancestor_next ,$level+1+$edge_counter, 0, $alpha, $beta, $maxdepth);
							$node = array("coor"=>$map_x[$r][$c]["coor"] ,/*"max" => $user, "alpha"=>$alpha,"beta"=>array("origin"=>$beta,"changto"=>min($beta,$val[0])),*/ "value" => 0, "subtree" => $val[1]);
							$edge_counter ++;
						}
						if(is_array($node["subtree"])){
							$node["value"] = getMinMax($node["subtree"], $user);
							$node["count"] = 0;
							foreach($node["subtree"] as $n){
								$node["count"] += $n['count'];
							}
						}
						else{
							$node["value"] = $node["subtree"];
							unset($node["subtree"]);
							$node["count"] = 1;
						}
						$alpha = max($alpha,$val[0]);
						array_push($subtree, $node);
						if($beta <= $alpha)
							return array($alpha, $subtree);
					}
				}
			}
		}
		return array($alpha, $subtree);
	}
}

function getDepth($turn){
	if($turn < 3){
		return 1;
	}
	else if($turn < 10){
		return 2;
	}
	else if($turn < 12){
		return 3;
	}
	else if($turn < 14){
		return 4;
	}
	else if($turn < 16){
		return 5;
	}
	else{
		return 2;
	}
}
?>