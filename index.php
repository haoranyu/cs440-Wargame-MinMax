<?php
include("config.php");
include("load_map.php");
include("function.php");
function excuteStep($map_x, $steps){
	$map_x = urlencode(json_encode($map_x));
	$steps = urlencode(json_encode($steps));
	$back = objectToArray(json_decode(file_get_contents('http://127.0.0.1/host/cs440/cs440-wargame/takestep.php?map='.$map_x.'&step='.$steps)));
	return $back;
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
						$ancestor_next = array_merge($ancestor, array($map_x[$r][$c]["coor"]));
						$temp_node = array("coor"=>$map_x[$r][$c]["coor"] , "value" => -1, "subtree" => buildTree_MinMax_helper($map_x, $ancestor_next ,$level+1, not($user)));
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
function flipView($map_x){
	foreach($map_x as &$r){
		foreach($r as &$c){
			if($c['color'] == 1) $c['color'] = 0;
			elseif($c['color'] == 0) $c['color'] = 1;
		}
	}
	return $map_x;
}

//play it !

echo "the Map is ".$_GET['map']."<br/>";
echo "Minimax vs. minimax"."<br/>";
$time = time();
$timesum = 0;
$nodesum = array(0,0);
for($i = 0; $i < 18; $i++){
$decision = getDecision_MinMax($map);
$coor = $decision[0];
$node_count = $decision[1];
$map = excuteStep($map, array($coor));
$time_dur = (time() - $time);
$timesum+=$time_dur;
$nodesum[0] +=$node_count;
echo "Blue: paradrop ".decodeCoor($coor).", use: ".$time_dur."s, Node expanded: ".$node_count."<br/>";
$time = time();
$map = flipView($map);
$decision = getDecision_MinMax($map);
$coor = $decision[0];
$node_count = $decision[1];
$map = excuteStep($map, array($coor));
$time_dur = (time() - $time);
$timesum+=$time_dur;
$nodesum[1] +=$node_count;
echo "Green: paradrop ".decodeCoor($coor).", use: ".$time_dur."s, Node expanded: ".$node_count."<br/>";
$time = time();
$map = flipView($map);
}
drawMap($map);
echo "Total number of game tree nodes expanded by Blue:".$nodesum[0]."<br/>";
echo "Total number of game tree nodes expanded by Green:".$nodesum[1]."<br/>";
echo "Average number of nodes expanded / Move:". ($nodesum[0]+$nodesum[1])/36 ."<br/>";
echo "Average amount of time / Move:". $timesum/36 ."s<br/>";
?>
