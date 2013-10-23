<?php

function drawMap($map){
	echo "<div style=\"margin-bottom:10px\">";
	foreach($map as $r){
		foreach($r as $c){
			echo '<span class="u'.$c['color'].'">'.$c['value']."</span> ";
		}
		echo "<br/>";
	}
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

?>