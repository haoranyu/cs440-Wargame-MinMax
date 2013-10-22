<?php
$map = str_replace("\r","",$map);
$map = str_replace("\n\n","",$map);
$map = trim($map);
$map = explode("\n",$map);
foreach($map as &$line){
	$line = explode("\t", $line);
	foreach($line as &$col){
		$col = array(
				"value" => $col, 
				"color" => -1 //-1 is empty, 1 is me, 0 is opponent
				);
	}
}

$cood = array("H" => 6, "W" => 6);
?>