<?php
$map = str_replace("\r","",$map);
$map = str_replace("\n\n","",$map);
$map = trim($map);
$map = explode("\n",$map);
foreach($map as &$line){
	$line = explode("\t", $line);
	foreach($line as &$col){
		$col = array("value" => $col, "color" => 0);
	}
}

$cood = array("H" => 6, "W" => 6);
?>