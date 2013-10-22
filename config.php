<?php
	header('Content-Type: text/plain');
	
	$map_array = array(
				'Keren',
				'Narvik',
				'Sevastopol',
				'Smolensk',
				'Westerplatte');
				
	if(isset($_GET['map']) && (in_array($_GET['map'], $map_array))){
		$map = file_get_contents('map/'.$_GET['map'].'.txt');
	}
	else{
		exit("no map specified('Keren','Narvik','Sevastopol','Smolensk','Westerplatte')");
	}
?>