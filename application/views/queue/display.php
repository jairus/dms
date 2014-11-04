<?php
//echo $office;
//print_r($stationvals);
$stationvals = $commands['stationvals'];
?>
<html>
<head>
<script language="javascript" src="<?php echo site_url("media/js/jquery-1.7.2.min.js"); ?>"></script>
<script>
function blink(elem){
	elem.hide();
	setTimeout(function(){ elem.show() }, 300);
	setTimeout(function(){ elem.hide() }, 600);
	setTimeout(function(){ elem.show() }, 900);
	setTimeout(function(){ elem.hide() }, 1200);
	setTimeout(function(){ elem.show() }, 1500);
}
function fetchQueue(){
	jQuery.ajax({
		type: "POST",
		url: "<?php echo site_url("queue/stations_ajax/".$office); ?>/"
	})
	.done(function( msg ) {
		commands = JSON.parse(msg);
		stationvals = commands['stationvals'];
		codes = commands['commands'];
		lastnum = commands['lastnum'];
		for(x in stationvals){
			index = x;
			val = stationvals[index];
			if(jQuery("#queue"+index).html()!=val){
				jQuery("#queue"+index).html(val);
			}
		}
		if(jQuery(".now_num").html()!=lastnum){
			jQuery(".now_num").html(lastnum);
			elem = jQuery(".now_container");
			blink(elem);
		}
		for(x in codes){
			index = x;
			val = codes[index];
			val_md5 = codes[index+"_md5"];
			//alert(val);
			if(index=="video"){
				if(jQuery("#video_temp").val()!=val_md5){
					jQuery("#video_temp").val(val_md5)
					jQuery("#video").html(val)
					//alert("here");
				}
			}
			else if(index=="marquee"){
				
				if(jQuery("#marquee_temp").val()!=val_md5){
					jQuery("#marquee_temp").val(val_md5)
					jQuery("#marquee").html(val)
				}
			}
		}
		
		setTimeout(function(){
			fetchQueue();
		}, 1000)
	});
}
</script>
<style>
*{
	font-family: Verdana;
}
.maintable{
	width:100%;
	height:100%;
}
.numbers_container{
	vertical-align:top;
	background: orange;
}
.numbers{
	width:100%;
	
}

.numbers .queue_num{
	font-size:53px;
	color:white;
	background:black;
	width:10%;
	border-bottom: 1px solid black;
	padding:5px;
}

.numbers .queue{
	font-size:53px;
	color:white;
	width:90%;	
	border-bottom: 1px solid black;
	padding:5px;
	text-align:center;
}

.now_serving{
	font-size:53px;
	color:white;
	width:100%;	
	border-bottom: 1px solid black;
	padding:5px;
	text-align:center;
	background: red;
}
.now_serving td{
	height:95px;
}
.now{
	font-size:23px;
}


body{
	margin:0px;
}
#marquee{
	height:60px;
	padding:5px;
	background:gray;
}
#marquee marquee{
	font-size:53px;
}
</style>
</head>
<body>
	<table class="maintable" cellpadding=0 cellspacing=0>
	<tr>
	<td class="numbers_container" style="width:25%">
		<table class="now_serving" cellpadding=0 cellspacing=0>
			<tr>
				<td>
					<div class="now_container">
						<div class="now">Now Serving</div>
						<div class="now_num">0</div>
					</div>
				</td>
			</tr>
		</table>
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
			<script>
			jQuery(".now_num").html("<?php echo $value; ?>");
			</script>
			</table>
			<?php
		}
		?>
	</td>
	<td style="width:75%; background:black">
		<textarea id="video_temp" style="display:none"></textarea>
		<textarea id="marquee_temp" style="display:none"></textarea>
		<table style="height:100%; width:100%" cellpadding=0 cellspacing=0 border=0>
			<tr>
			<td id="video"></td>
			</tr>
			<tr>
			<td id="marquee" style="color:white;"><marquee>Hello World</marquee></td>
			</tr>
		</table>
	</td>
	</tr>
	</table>
<script>
fetchQueue();
</script>
</body>
</html>