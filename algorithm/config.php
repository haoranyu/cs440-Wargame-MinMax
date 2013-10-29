<?php
/*
	COPYRIGHT CS440 Group @ UIUC 
	By Haoran Yu, Le Wang and Tao Feng
*/
set_time_limit(20000);
	header('Content-Type: text/html');
	define ("SIZE", "6");
	session_start();
	$_SESSION['level-1'] = 'x';
	$_SESSION['level0'] = 'x';
	$_SESSION['level1'] = 'x';
	$_SESSION['level2'] = 'x';
	$_SESSION['level3'] = 'x';
	$_SESSION['level4'] = 'x';
	$_SESSION['level5'] = 'x';
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
.x-map{
width:200px;
height:200px;
border:1px solid #ddd;
-moz-border-radius: 15px;
-webkit-border-radius: 15px;   
border-radius:15px; 
padding:10px;
}
.x-block{
border:1px solid #fff;
-moz-border-radius: 15px;
-webkit-border-radius: 15px;   
border-radius:15px; 
width:30px;
height:30px;
text-align:center;
line-height:30px;
float:left;
display:block;
}
.u-1{
background:#eee
}
.u0{
background:green;
color:#fff;
}
.u1{
background:blue;
color:#fff;
}
.o-1{
color:#999;
}
.o0{
color:green;
}
.o1{
color:blue;
}
</style>