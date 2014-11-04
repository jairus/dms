<?php
//echo $office;
//print_r($stationvals);
$stationvals = $commands['stationvals'];
?>
<html>
<head>
<script language="javascript" src="<?php echo site_url("media/js/jquery-1.7.2.min.js"); ?>"></script>
<script>
function blink(id, val){
	setTimeout(function(){ jQuery(id).html(" "); }, 1000);
	setTimeout(function(){ jQuery(id).html(val); }, 2000);
	setTimeout(function(){ jQuery(id).html(" "); }, 3000);
	setTimeout(function(){ jQuery(id).html(val); }, 4000);
	setTimeout(function(){ jQuery(id).html(" "); }, 5000);
	setTimeout(function(){ jQuery(id).html(val); }, 6000);
	
}
function fetchQueue(){
	jQuery.ajax({
		type: "POST",
		url: "<?php echo site_url("queue/stations_ajax/".$office); ?>/"
	})
	.done(function( msg ) {
		commands = JSON.parse(msg);
		stationvals = commands['stationvals'];
		for(x in stationvals){
			index = x;
			val = stationvals[index];
			if(jQuery("#queue"+index).html()!=val){
				jQuery("#queue"+index).html(val);
				//blink("#queue"+index, val);
			}
			if(x=="command"){
				eval(val);
			}
		}
		setTimeout(function(){
			fetchQueue();
		}, 1000)
	});
}
</script>
<style>
.maintable{
	width:100%;
	height:100%;
}
.numbers_container{
	width:20%;
	vertical-align:top;
	background: orange;
}
.numbers{
	width:100%;
	
}

.numbers .queue_num{
	font-size:90px;
	color:white;
	background:black;
	width:10%;
	border-bottom: 1px solid black;
	padding:10px;
}

.numbers .queue{
	font-size:90px;
	color:white;
	widyh:90%;	
	border-bottom: 1px solid black;
	padding:10px;
	text-align:center;
}

body{
	margin:0px;
}
</style>
</head>
<body>
	<table class="maintable">
	<tr>
	<td class="numbers_container">
	<?php
	if(is_array($stationvals)){
		?>
		<table class="numbers" cellpadding=0 cellspacing=0>
		<?php
		foreach($stationvals as $key=>$value){
			if($key!="video"&&$key!="command"){
				$num = str_replace("station_", "", $key);
				//$value = substr("000".$value, -3);
				echo "<tr>";
				echo "<td id='queue_num".$key."' class='queue_num'>".$num."</td>";
				echo "<td id='queue".$key."' class='queue'>".$value."</td>";
				echo "</tr>";
			}
		}
		?>
		</table>
		<?php
	}
	?>
	</td>
	<td style="width:75%">
	</td>
	</tr>
	</table>
<script>
fetchQueue();
</script>
</body>
</html>