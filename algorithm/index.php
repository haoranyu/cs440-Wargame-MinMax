<?php
include("config.php");
include("load_map.php");
include("function.php");
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
function buildTree_MinMax($map_x){
	$tree = buildTree_MinMax_helper($map_x, array(), 0, 0);
	$tree = array("next" => "x,x", "value"=> -1, "user" => 1,"subtree" =>$tree);
	$tree["value"] = getMinMax($tree["subtree"],1,1);
	$tree["next"] = $tree["value"][1];
	$tree["value"] = $tree["value"][0];
	$tree["count"] = 0;
	foreach($tree["subtree"] as $node){
		$tree["count"] += $node['count'];
	}
	return $tree;
}
function buildTree_MinMax_helper($map_x, $ancestor ,$level = 0, $user){
	if($level == 3){
		$ret = excuteStep($map_x, $ancestor);
		$map_x = $ret[0];
		$socre = getScore($map_x);
		return $socre[1];
	}
	else{
		$subtree = array();
		for($r = 0; $r < 6; $r++){
			for($c = 0; $c < 6; $c++){
				if($map_x[$r][$c]["color"] == -1){
					if(!in_array($map_x[$r][$c]["coor"],$ancestor)){
						$ancestor_next = array_merge($ancestor, array($map_x[$r][$c]["coor"]));
						$temp_node = array("coor"=>$map_x[$r][$c]["coor"] , "value" => -1, "subtree" => buildTree_MinMax_helper($map_x, $ancestor_next ,$level+1, not($user)));
						
						$edge_counter = 1;
						while(is_array($temp_node["subtree"]) && empty($temp_node["subtree"])){
							$temp_node = array("coor"=>$map_x[$r][$c]["coor"] , "value" => -1, "subtree" => buildTree_MinMax_helper($map_x, $ancestor_next ,$level+1+$edge_counter, not($user)));
							$edge_counter ++;
						}
						
						if(is_array($temp_node["subtree"])){
							$temp_node["value"] = getMinMax($temp_node["subtree"], $user);
							$temp_node["count"] = 0;
							foreach($temp_node["subtree"] as $node){
								$temp_node["count"] += $node['count'];
							}
						}
						else{
							$temp_node["value"] = $temp_node["subtree"];
							$temp_node["subtree"] = NULL;
							$temp_node["count"] = 1;
						}
						array_push($subtree, $temp_node);
					}
				}
			}
		}
		return $subtree;
	}
}
function getDecision_MinMax($map_x){
	$result = buildTree_MinMax($map_x);
	return array($result['next'],$result['count']);
}
function getDecision_AB($map_x){
	$result = buildTree_AB($map_x);
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
		if($node["value"] != "x"){
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
	}
	if($root == 0){	
		return $retval;
	}
	else{
		return array($retval,$coor);
	}
}

function buildTree_AB($map_x){
	$tree = buildTree_AB_helper($map_x, array(), 0, 0, 0, 999999);
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
/*function buildTree_AB_helper($map_x, $ancestor ,$level = 0, $user){
	//echo "level".$level."=".($_SESSION['level'.($level)])."\n";
	//$_SESSION['level'.($level)] = 'x';
	if($level == 3){
		$map_x = excuteStep($map_x, $ancestor);
		$socre = getScore($map_x);
		return $socre[1];
	}
	else{
		$subtree = array();
		for($r = 0; $r < 6; $r++){
			for($c = 0; $c < 6; $c++){
				if($map_x[$r][$c]["color"] == -1){
					if(!in_array($map_x[$r][$c]["coor"],$ancestor)){
						if($level == 1)
						echo $_SESSION['level'.($level)]."\n";
						$ancestor_next = array_merge($ancestor, array($map_x[$r][$c]["coor"]));
						$val = buildTree_AB_helper($map_x, $ancestor_next ,$level+1, not($user));
						
						if(is_array($val)){
							$value = getMinMax($val, $user);
							if($value != "x"){
								if($_SESSION['level'.($level+1)] == 'x'){
									$_SESSION['level'.($level+1)] = $value;
								}
								else if(!$user && $_SESSION['level'.($level+1)] < $value){
									$_SESSION['level'.($level+1)] = $value;
								}
								else if($user && $_SESSION['level'.($level+1)] > $value){
									$_SESSION['level'.($level+1)] = $value;
								}
							}
							if($_SESSION['level'.($level)] != "x"){
								if($user && $val <= $_SESSION['level'.($level)]){
									return $subtree;
								}
								else if(!$user && $val >= $_SESSION['level'.($level)]){
									return $subtree;
								}
							}
							$node = array("coor"=>$map_x[$r][$c]["coor"] , "value" => $value, "subtree" => $val);
						}
						else{
							if($_SESSION['level'.($level)] != "x"){
								if($user && $val <= $_SESSION['level'.($level)]){
									return $subtree;
								}
								else if(!$user && $val >= $_SESSION['level'.($level)]){
									return $subtree;
								}
							}
							$node = array("coor"=>$map_x[$r][$c]["coor"] , "value" => $val);
						}
						array_push($subtree, $node);
					}
				}
			}
		}
		$_SESSION['level'.$level+1] = "x";
		return $subtree;
	}
}
*/
function buildTree_AB_helper($map_x, $ancestor ,$level = 0, $user, $alpha, $beta){
	if($level == 2){
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
						$val = buildTree_AB_helper($map_x, $ancestor_next ,$level+1, 0, $alpha, $beta);
						$node = array("coor"=>$map_x[$r][$c]["coor"] ,/*"max" => $user, "alpha"=>$alpha,"beta"=>array("origin"=>$beta,"changto"=>min($beta,$val[0])),*/ "value" => 0, "subtree" => $val[1]);

						$edge_counter = 1;
						while(is_array($node["subtree"]) && empty($node["subtree"])){
							$val = buildTree_AB_helper($map_x, $ancestor_next ,$level+1+$edge_counter, 0, $alpha, $beta);
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
						$val = buildTree_AB_helper($map_x, $ancestor_next ,$level+1, 1, $alpha, $beta);
						$node = array("coor"=>$map_x[$r][$c]["coor"] ,/*"max" => $user, "alpha"=>$alpha,"beta"=>array("origin"=>$beta,"changto"=>min($beta,$val[0])),*/ "value" => 0, "subtree" => $val[1]);

						$edge_counter = 1;
						while(is_array($node["subtree"]) && empty($node["subtree"])){
							$val = buildTree_AB_helper($map_x, $ancestor_next ,$level+1+$edge_counter, 0, $alpha, $beta);
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



//play it !
echo "the Map is ".$_GET['map']."<br/>";
echo "MinMax vs. MinMax(Level 1)"."<br/>";
$time = time();
$timesum = 0;
$nodesum = array(0,0);
for($i = 0; $i < 1; $i++){
$decision = getDecision_AB($map);
$coor = $decision[0];
$node_count = $decision[1];
$ret = excuteStep($map, array($coor));
$map = $ret[0];
$time_dur = (time() - $time);
$timesum+=$time_dur;
$nodesum[0] +=$node_count;
echo "Blue: ".($ret[1] == 0 ?"Commando Para Drop":" Drop(Death Blitz)")." in ".decodeCoor($coor).", use: ".$time_dur."s, Node expanded: ".$node_count."<br/>";
$time = time();
$map = flipView($map);
$decision = getDecision_AB($map);
$coor = $decision[0];
$node_count = $decision[1];
$ret = excuteStep($map, array($coor));
$map = $ret[0];
$time_dur = (time() - $time);
$timesum+=$time_dur;
$nodesum[1] +=$node_count;
echo "Green: ".($ret[1] == 0 ?"Commando Para Drop":" Drop(Death Blitz)")." in ".decodeCoor($coor).", use: ".$time_dur."s, Node expanded: ".$node_count."<br/>";
$time = time();
$map = flipView($map);
}
drawMap($map);
echo "Total number of game tree nodes expanded by Blue:".$nodesum[0]."<br/>";
echo "Total number of game tree nodes expanded by Green:".$nodesum[1]."<br/>";
echo "Average number of nodes expanded / Move:". ($nodesum[0]+$nodesum[1])/36 ."<br/>";
echo "Average amount of time / Move:". $timesum/36 ."s<br/>";

?>
