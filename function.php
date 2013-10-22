<?php
function drawMap($map){
	echo "<div>";
	foreach($map as $r){
		foreach($r as $c){
			echo '<span class="u'.$c['color'].'">'.$c['value']."</span> ";
		}
		echo "<br/>";
	}
	echo "</div>";
}
?>