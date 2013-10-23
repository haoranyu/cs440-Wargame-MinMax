<?php
	header('Content-Type: text/html');
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
	else{
		exit("no map specified('Keren','Narvik','Sevastopol','Smolensk','Westerplatte')");
	}
?>

<style>
.u-1{
width:30px;
color:#bbb;
display:block;
float:left;
}
.u0{
width:30px;
color:green;
display:block;
float:left;
}
.u1{
width:30px;
color:blue;
display:block;
float:left;
}
</style>