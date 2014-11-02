<script>
	office = "<?php
		echo $office;
		?>"
	//alert(office);
	function submitQueue(station, increment){
		if(!increment){
			increment = 0;
		}
		increment = uNum(increment);
		queue = uNum(jQuery("#queue"+station).val());
		queue += increment;
		if(queue<0){
			queue = 0;
		}
		if(queue>300){
			queue = 0;
		}
		jQuery("#queue"+station).val(queue);
		
		jQuery.ajax({
			type: "POST",
			url: "<?php echo site_url("queue/submitQueue"); ?>/",
			data: { office: "<?php echo $office; ?>", command: station, value: queue }
		})
		.done(function( msg ) {
			//alert( "Data Saved: " + msg );
		});
  
  
	}
	
</script>

<div class='list'>
<table style="width:50%; margin:auto">
	<tr>
		<td colspan=5 style="text-align:center">
		<?php
		echo strtoupper($office);
		?>
		</td>
	</tr>
	<tr>
		<th width='20%'>Window</th>
		<th width='20%'></th>
		<th width='20%'></th>
		<th width='20%'>Queue</th>
		<th width='20%'></th>
	</tr>
	<?php
	$stationvals = $commands['stationvals'];
	foreach($stationvals as $station=>$values){
		?>
		<tr>
			<td style="text-align:center"><?php echo $station?></td>
			<td style="text-align:center"><input type="button" value="Previous" onclick="submitQueue('<?php echo $station?>', -1)"></td>
			<td style="text-align:center"><input type="button" value="Next" onclick="submitQueue('<?php echo $station?>', 1)"></td>
			<td style="text-align:center"><input type="text" id="queue<?php echo $station?>" value="<?php echo $stationvals[$station]; ?>" style="width:60px"></td>
			<td style="text-align:center"><input type="button" value="Submit"  onclick="submitQueue('<?php echo $station?>')"></td>
		</tr>
		<?php
	}
	?>
</table>
</div>
