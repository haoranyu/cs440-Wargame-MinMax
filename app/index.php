<?php
/*
	COPYRIGHT CS440 Group @ UIUC 
	By Haoran Yu, Le Wang and Tao Feng
*/
?>
<?php if(!isset($_GET['query'])){?>
<?php
include("config.php");
include("load_map.php");
include("function.php");
?>
<html>
<head>
<meta charset="utf-8">
<base target="_blank">
<title>War Game</title>
<link rel="stylesheet" type="text/css" href="main.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>
<div class="header">War Game</div>
<div class="selection">
	Map <select id="mapselect">
		<option value="Keren" <?php if($_GET['map'] == "Keren")echo 'selected="selected"';?>>Keren</option>
		<option value="Narvik"<?php if($_GET['map'] == "Narvik")echo 'selected="selected"';?>>Narvik</option>
		<option value="Sevastopol"<?php if($_GET['map'] == "Sevastopol")echo 'selected="selected"';?>>Sevastopol</option>
		<option value="Smolensk"<?php if($_GET['map'] == "Smolensk")echo 'selected="selected"';?>>Smolensk</option>
		<option value="Westerplatte"<?php if($_GET['map'] == "Westerplatte")echo 'selected="selected"';?>>Westerplatte</option>
		<option value="Random"<?php if($_GET['map'] == "Random")echo 'selected="selected"';?>>Random</option>
	</select>
	 - <select id="firstselect">
		<option value="Me" <?php if($first_step == "Me")echo 'selected="selected"';?>>Player</option>
		<option value="Computer"<?php if($first_step == "Computer")echo 'selected="selected"';?>>Computer</option>
	</select> first
<div>
<div class="main" id="main">
	<?php drawMap($map)?>
</div>
<div class="panel">
	<span><?php echo $first_step == "Me"?"Player":"Computer";?>: </span>
	<span id="blue">0</span>
	- 
	<span><?php echo $first_step == "Me"?"Computer":"Player";?>: </span><span id="green">0</span>
<div>
<div class="footer">&copy; CS440 Group @ UIUC <br/>By Haoran Yu, Le Wang and Tao Feng</div>
<script>
var curr_map = <?php echo json_encode($map)?>;
var curr_step = 1;
var lock = 0;
$(document).on("change", "#mapselect", function(){
	window.location.href="?map="+$(this).val();
});
$(document).on("change", "#firstselect", function(){
	window.location.href="?map=<?php echo $_GET['map'];?>&first="+$(this).val();
});
<?php if($first_step!="Me"){?>
if(lock == 0){
	lock = 1;
	$(this).removeClass("u-1");
	$(this).addClass("u1-1");
	$.ajax({
		url: './?query=1',
		type: 'POST',
		data:{ map: curr_map, coor:"x,x", step: curr_step, last:$('.u-1').size()},
		dataType: 'json',
		timeout: 60000,
		error: function(){
			alert("Timeout...Try again please...");
		},
		success: function(data){
			$("#main").html(data[0]);
			curr_map = data[1];
			lock = 0;
			$("#blue").html(data[2]);
			$("#green").html(data[3]);
		}
	});
}
else{
	alert("Please wait before your next move");
}
<?php }?>
$(document).on("click",".u-1",function(){
	if($('.u-1').size()>=1){
		if(lock == 0){
			lock = 1;
			$(this).removeClass("u-1");
			$(this).addClass("u1-1");
			$.ajax({
				url: './?query=1',
				type: 'POST',
				data:{ map: curr_map, coor:$(this).attr("coor"), step: curr_step, last:$('.u-1').size()},
				dataType: 'json',
				timeout: 60000,
				error: function(){
					alert("Timeout...Try again please...");
				},
				success: function(data){
					$("#main").html(data[0]);
					curr_map = data[1];
					curr_step++;
					lock = 0;
					$("#blue").html(data[2]);
					$("#green").html(data[3]);
					if($('.u-1').size() == 0){
						alert("Game Finished");
					}
				}
			});
		}
		else{
			alert("Please wait before your next move");
		}
	}
	else{
		alert("Game Finished");
	}
});
</script>
</body>
</html>
<?php }else{
include("function.php");
include("function_algorithm.php");

$map = $_POST['map'];
$coor = $_POST['coor'];
$i = $_POST['step'];
$last = $_POST['last'];
if($coor != "x,x"){
$ret = excuteStep($map, array($coor));
$map = $ret[0];
}
if($last > 0){
$map = flipView($map);
$decision = getDecision_AB($map, getDepth($i));
$coor = $decision[0];
$ret = excuteStep($map, array($coor));
$map = $ret[0];
$map = flipView($map);
}
$retmap = drawMap($map,0);
$score = getScore($map);
echo json_encode(array($retmap,$map,$score[0],$score[1]));
}?>