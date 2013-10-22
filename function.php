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
?>