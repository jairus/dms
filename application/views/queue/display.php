<?php
//echo $office;
//print_r($stationvals);
$stationvals = $commands['stationvals'];
?>
<html>
<head>
<script language="javascript" src="<?php echo site_url("media/js/jquery-1.7.2.min.js"); ?>"></script>
<script>
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
fetchQueue();
</script>
<style>
.maintable{
	width:100%;
}
</style>
</head>
<body>
	<table class="maintable">
	<tr>
	<td style="width:20%;" class="numbers_container">
	<?php
	if(is_array($stationvals)){
		?>
		<table style="width:100%; height:100%" class="numbers">
		<?php
		foreach($stationvals as $key=>$value){
			if($key!="video"&&$key!="command"){
				echo "<tr>";
				echo "<td id='queue".$key."'>".$value."</td>";
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
</body>
</html>