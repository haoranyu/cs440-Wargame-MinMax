<?php
	header('Content-Type: text/html');
	
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
width:20px;
color:#bbb;
display:block;
float:left;
}
.u0{
width:20px;
color:blue;
display:block;
float:left;
}
.u1{
width:20px;
color:red;
display:block;
float:left;
}
</style>