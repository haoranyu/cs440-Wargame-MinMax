<?php
/*
	COPYRIGHT CS440 Group @ UIUC 
	By Haoran Yu, Le Wang and Tao Feng
*/
function curl_file_get_contents($durl){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $durl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);

$output = curl_exec($ch);

curl_close($ch);
   return $output;
 }
function drawMap($map){
	echo "<div class=\"x-map\">";
	foreach($map as $r){
		foreach($r as $c){
			echo '<span class="x-block o'.$c['color'].'">'.$c['value']."|</span> ";
		}
		echo "<br/>";
	}
	echo '<div style="float:none"></div>';
	echo "</div>";
}
function not($u){
	if($u == 1) return 0;
	return 1;
}
function objectToArray($object){ 
    $result = array(); 
    $object = is_object($object) ? get_object_vars($object) : $object; 
    foreach ($object as $key => $val) { 
        $val = (is_object($val) || is_array($val)) ? objectToArray($val) : $val; 
        $result[$key] = $val; 
	}
    return $result; 
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
function decodeCoor($coor){
$C = array("A","B","C","D","E","F");
$R = array("1","2","3","4","5","6");
$coor = explode(",",$coor);
return $R[$coor[0]].$C[$coor[1]];
}
function excuteStep($map_x, $steps){
	$map_x = urlencode(json_encode($map_x));
	$steps = urlencode(json_encode($steps));
	$url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	$url = explode("?", $url);
	$url = $url[0];
	$back = curl_file_get_contents($url.'takestep.php?map='.$map_x.'&step='.$steps);
	//exit('http://127.0.0.1/host/cs440/cs440-wargame/takestep.php?map='.$map_x.'&step='.$steps);
	while($back == false){
		$back = curl_file_get_contents($url.'takestep.php?map='.$map_x.'&step='.$steps);
	}
	$back = objectToArray(json_decode($back));
	return $back;
}

?>