<?php
/*
	COPYRIGHT CS440 Group @ UIUC 
	By Haoran Yu, Le Wang and Tao Feng
*/
	header('Content-Type: text/html');
	session_start();
	$_SESSION['level-1'] = 'x';
	$_SESSION['level0'] = 'x';
	$_SESSION['level1'] = 'x';
	$_SESSION['level2'] = 'x';
	$_SESSION['level3'] = 'x';
	$_SESSION['level4'] = 'x';
	$_SESSION['level5'] = 'x';
	set_time_limit(20000);
	$map_array = array(
				'Keren',
				'Narvik',
				'Sevastopol',
				'Smolensk',
				'Westerplatte');

	if(isset($_GET['map']) && (in_array($_GET['map'], $map_array))){
		$map = file_get_contents('map/'.$_GET['map'].'.txt');
	}
	elseif(isset($_GET['map'])){
		$map = "";
		for($j = 0; $j < 6; $j++){
			for($i = 0; $i < 6; $i++){
				$map = $map.(rand()%100);
				if($i!=5){
					$map = $map."\t";
				}
			}
			$map = $map."\n";
		}
	}
	else{
		exit("no map specified('Keren','Narvik','Sevastopol','Smolensk','Westerplatte')");
	}
	if(isset($_GET['first'])){
		$first_step = $_GET['first'];
	}
	else{
		$first_step = "Me";
	}
?>