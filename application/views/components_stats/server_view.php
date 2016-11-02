<?php
echo "<a class='btn btn-custom1 btn-md' href='" . base_url() . "index.php/Components_stats/server_view'>Server View</a>";
echo "<a class='btn btn-default btn-md' href='" . base_url() . "index.php/Components_stats/component_view'>Component View</a>";
echo "<a class='btn btn-default btn-md' href='" . base_url() . "index.php/Components_stats/summary_view'>Summary View</a>";

echo '<button style="float:right" class="export_as_csv_button server_view components_stats btn btn-default">Export as CSV</button>';
?>

<div class="formbox">
	<!--filters-->
	<div class="form_filter">
		<form method="POST" action="<?php echo site_url('components_stats/server_view')?>">
			<span class="filterby">
			Components : 
			<select name="component">
				<option value="AllComponents">All Components</option>
				<?php foreach ($components as $component): ?>
				<option value="<?php echo $component->component_name ?>"<?php if($filters['component'] == $component->component_name) echo 'selected' ?>><?php echo $component->component_name ?></option>
				<?php endforeach ?>
			</select>
			</span>

			<span class="filterby">
			Category : 
			<select name="category">
				<option value="tpm">tpm</option>
				<option value="success"<?php if($filters['category'] == 'success') echo 'selected' ?>>success</option>
				<option value="fail"<?php if($filters['category'] == 'fail') echo 'selected' ?>>fail</option>
				<option value="eventsinqueue"<?php if($filters['category'] == 'eventsinqueue') echo 'selected' ?>>events in queue</option>
			</select>
			</span>

			<span class="filterby">
			Duration : 
			<select name="duration">
				<option value="1"<?php if($filters['duration'] == '1') echo 'selected' ?>>1m</option>
				<option value="3"<?php if($filters['duration'] == '3') echo 'selected' ?>>3m</option>
				<option value="5"<?php if($filters['duration'] == '5') echo 'selected' ?>>5m</option>
				<option value="15"<?php if($filters['duration'] == '15') echo 'selected' ?>>15m</option>
				<option value="30"<?php if($filters['duration'] == '30') echo 'selected' ?>>30m</option>
				<option value="60"<?php if($filters['duration'] == '60') echo 'selected' ?>>1h</option>
				<option value="120"<?php if($filters['duration'] == '120') echo 'selected' ?>>2h</option>
				</select>
			</span>

			<button type="submit" class="filter_button"> Filter</button>
		</form>

		<br>

		<!--current query-->
		<h6><b>Filter Options: </b> <?php echo $filters['component'] . ' -> ' . $filters['category'] . ' -> ' . $filters['duration'] . ' minute/s' . '<br>'; ?></h6>
	</div>

<?php

//handler stats records - avoid undefined offset
if(!empty($handler_stats)){
	//echo 'Handler Stats <br>';
	//handler stats records - put to 2D array
	$count_hs = 1;
	foreach ($handler_stats as $row_hs) {
		//$id = $component_stat->ID;//unneeded
		$logtime_hs = $row_hs->LOGTIME;
		$serverid_hs = $row_hs->SERVERID;
		$handler_hs = $row_hs->HANDLER;
		$success_hs = $row_hs->SUCCESS;
		$fail_hs = $row_hs->FAIL;	
		$eventsinqueue_hs = $row_hs->EVENTSINQUEUE;
		//Put to 2D array
		//$data_cs[$a][1] = $id;//unneedded
		$data_hs[$count_hs][2] = $logtime_hs;
		$data_hs[$count_hs][3] = $serverid_hs;
		$data_hs[$count_hs][4] = $handler_hs;
		$data_hs[$count_hs][5] = $success_hs;	
		$data_hs[$count_hs][6] = $fail_hs;		
		$data_hs[$count_hs][7] = $eventsinqueue_hs;
			
		//echo 'Index: ' . $count_hs . ' ' . $data_hs[$count_hs][2] . ' ' . $data_hs[$count_hs][3] . ' ' . $data_hs[$count_hs][4] . ' ' . $data_hs[$count_hs][5] . ' ' . $data_hs[$count_hs][6] . ' ' . $data_hs[$count_hs][7] .'<br>';
		$count_hs++;
	}
	//echo '<br>';
}else{
	$count_hs = 0;
}

//notif stats records - avoid undefined offset
if(!empty($notif_stats)){
	//echo 'Notif Stats <br>';
	//notif stats records - put to 2D array
	$count_ns = 1;
	foreach ($notif_stats as $row_ns) {
		//$id = $component_stat->ID;//unneeded
		$logtime_ns = $row_ns->LOGTIME;
		$serverid_ns = $row_ns->SERVERID;
		$success_ns = $row_ns->SUCCESS;
		$fail_ns = $row_ns->FAIL;	
		$queue_count_ns = $row_ns->QUEUE_COUNT;
		//Put to 2D array
		//$data_ns[$a][1] = $id;//unneedded
		$data_ns[$count_ns][2] = $logtime_ns;
		$data_ns[$count_ns][3] = $serverid_ns;
		$data_ns[$count_ns][5] = $success_ns;	
		$data_ns[$count_ns][6] = $fail_ns;		
		$data_ns[$count_ns][7] = $queue_count_ns;
			
		//echo 'Index: ' . $count_ns . ' ' . $data_ns[$count_ns][2] . ' ' . $data_ns[$count_ns][3] . ' ' . $data_ns[$count_ns][5] . ' ' . $data_ns[$count_ns][6] . ' ' . $data_ns[$count_ns][7] .'<br>';
		$count_ns++;
	}
	//echo '<br>';
}else{
	$count_ns = 0;
}

//smsgatewaystats records - avoid undefined offset
if(!empty($smsgateway_stats)){
	//echo 'SMSGateway Stats <br>';
	//smsgateway stats records - put to 2D array
	$count_ss = 1;
	foreach ($smsgateway_stats as $row_ss) {
		//$id = $component_stat->ID;//unneeded
		$logtime_ss = $row_ss->LOGTIME;
		$serverid_ss = $row_ss->SERVERID;
		//$instance_ss = $row_ss->INSTANCE;//unneeded
		$success_ss = $row_ss->SUCCESS;
		$fail_ss = $row_ss->FAIL;	
		$queue_count_ss = $row_ss->QUEUE_COUNT;
		//Put to 2D array
		//$data_ns[$a][1] = $id;//unneedded
		$data_ss[$count_ss][2] = $logtime_ss;
		$data_ss[$count_ss][3] = $serverid_ss;
		//$data_ss[$count_ss][4] = $instance_ss;
		$data_ss[$count_ss][5] = $success_ss;	
		$data_ss[$count_ss][6] = $fail_ss;		
		$data_ss[$count_ss][7] = $queue_count_ss;
			
		//echo 'Index: ' . $count_ss . ' ' . $data_ss[$count_ss][2] . ' ' . $data_ss[$count_ss][3] . ' ' . $data_ss[$count_ss][5] . ' ' . $data_ss[$count_ss][6] . ' ' . $data_ss[$count_ss][7] .'<br>';
		$count_ss++;
	}
	//echo '<br>';
}else{
	$count_ss = 0;
}

//servers records - avoid undefined offset
if(!empty($servers)){
	//server name - put to 2D array, init servers tpm, success, fail, eventsinqueue or queue_count to 0
	$count_s = 1;

	foreach($servers as $row_s){
		$server_name = $row_s->server_name;
		//Put to 2D array
		//$data_s[$count_s][1] = $id;//unneeded
		$data_s[$count_s][2] = $server_name;
		$data_s[$count_s][3] = 0;//tpm/success/fail/eventsinqueue or queue_count, will use only if filters server is AllServers
		//$data_s[$count_s][5] = 0;//success, will use only if filters server is not AllServers
		//$data_s[$count_s][6] = 0;//fail, will use only if filters server is not AllServers
		//$data_s[$count_s][7] = 0;//eventsinqueue or queue_count, will use only if filters server is not AllServers

		$count_s++;
	}
}

$duration = $filters['duration'];
$curr_datetime = $now_datetime;
//echo 'hs: ' . $count_hs . '<br>';
//echo 'ns: ' . $count_ns . '<br>';
//echo 'ss: ' . $count_ss . '<br>';
//echo 'max: ' . max($count_hs, $count_ns, $count_ss) . '<br>';

//echo '<br>';
?>

<br>

<!--if filters has no result-->
<?php

if((empty($handler_stats) && empty($notif_stats) && empty($smsgateway_stats))){
	echo '<a style="color:red">No results found.</a>';

	//uncheck checkbox - will use later to disable export as csv
	echo '<input style="display:none" class="allow_export" type="checkbox" />';
}else{
	//check checkbox - will use later to disable export as csv
	echo '<input style="display:none" class="allow_export" type="checkbox" checked="checked" />';
	?>

	<!--component stats table-->
	<table id="components_stats_table" class="table sortable table-striped" style="font-size: medium">
		<thead>
			<!--column header logtime-->
			<td class="table_custom_header">Logtime</td>

			<!--column header of all servers/specific server-->
			<?php
			if(!empty($servers)){
				foreach($servers as $row_s){
					echo '<td class="table_custom_header" align="right">' . $row_s->server_name . '</td>';
				}
			}
			?>
		</thead>
		
		<tbody>
			<?php
			//<x> no. of duration/s loop
			for($i = 1; $i <= $duration; $i++){ 
				//echo 'Index: ' . $i . ' DateTime: ' . $curr_datetime . '<br>';//temp comment
				//<x> no. of maximum rows comparing handler stats, notif stats and smsgateway stats count
				for($j = 1; $j < max($count_hs, $count_ns, $count_ss); $j++){
					
					//avoid array out of bounds
					if($j < $count_hs){
						//if handler stats logtime is equal to current datetime
						if($data_hs[$j][2] == $curr_datetime){
							//echo $j . ' ' . $data_hs[$j][2] . ' ' . $data_hs[$j][3] . ' ' . $data_hs[$j][4] . ' ' . $data_hs[$j][5] . ' ' . $data_hs[$j][6] . ' ' . $data_hs[$j][7] .'<br>';//temp comment	
							//<x> no. of server/s loop - to know which servers to deposit handler stats
							for($k = 1; $k <= count($servers); $k++){
								//if handler stats serverid is equal to servers server name
								if($data_hs[$j][3] == $data_s[$k][2]){
									//what to deposit?
									switch ($filters['category']) {
										case 'tpm':
											$data_s[$k][3] = $data_s[$k][3] + ($data_hs[$j][5] + $data_hs[$j][6]);//deposit success + fail
											break;
										case 'success':
											$data_s[$k][3] = $data_s[$k][3] + $data_hs[$j][5];//deposit success only
											break;
										case 'fail':
											$data_s[$k][3] = $data_s[$k][3] + $data_hs[$j][6];//deposit fail only
											break;
										case 'eventsinqueue':
											$data_s[$k][3] = $data_s[$k][3] + $data_hs[$j][7];//deposit eventsinqueue only
											break;
										default:
											$data_s[$k][3] = $data_s[$k][3] + ($data_hs[$j][5] + $data_hs[$j][6]);//deposit success + fail
											break;
									}
								}
							}					
						}
					}

					//avoid array out of bounds
					if($j < $count_ns){
						//if notif stats logtime is equal to current datetime
						if($data_ns[$j][2] == $curr_datetime){
							//<x> no. of server/s loop - to know which servers to deposit notif stats
							for($l = 1; $l <= count($servers); $l++){
								//if notif stats serverid is equal to servers server name
								if($data_ns[$j][3] == $data_s[$l][2]){
									//what to deposit?
									switch ($filters['category']) {
										case 'tpm':
											$data_s[$l][3] = $data_s[$l][3] + ($data_ns[$j][5] + $data_ns[$j][6]);//deposit success + fail
											break;
										case 'success':
											$data_s[$l][3] = $data_s[$l][3] + $data_ns[$j][5];//deposit success only
											break;
										case 'fail':
											$data_s[$l][3] = $data_s[$l][3] + $data_ns[$j][6];//deposit fail only
											break;
										case 'eventsinqueue':
											$data_s[$l][3] = $data_s[$l][3] + $data_ns[$j][7];//deposit queue_count only
											break;
										default:
											$data_s[$l][3] = $data_s[$l][3] + ($data_ns[$j][5] + $data_ns[$j][6]);//deposit success + fail
										break;
									}
								}
							}
						}
					}	

					//avoid array out of bounds
					if($j < $count_ss){
						//if smsgateway stats logtime is equal to current datetime
						if($data_ss[$j][2] == $curr_datetime){
							//<x> no. of server/s loop - to know which servers to deposit smsgateway stats
							for($m = 1; $m <= count($servers); $m++){
								//if smsgateway stats serverid is equal to servers server name
								if($data_ss[$j][3] == $data_s[$m][2]){
									//what to deposit?
									switch ($filters['category']) {
										case 'tpm':
											$data_s[$m][3] = $data_s[$m][3] + ($data_ss[$j][5] + $data_ss[$j][6]);//deposit success + fail
											break;
										case 'success':
											$data_s[$m][3] = $data_s[$m][3] + $data_ss[$j][5];//deposit success only
											break;
										case 'fail':
											$data_s[$m][3] = $data_s[$m][3] + $data_ss[$j][6];//deposit fail only
											break;
										case 'eventsinqueue':
											$data_s[$m][3] = $data_s[$m][3] + $data_ss[$j][7];//deposit queue_count only
											break;
										default:
											$data_s[$m][3] = $data_s[$m][3] + ($data_ss[$j][5] + $data_ss[$j][6]);//deposit success + fail
										break;
									}
								}
							}
						}
					}

				}

				//format logtime
				$strtotime_curr_datetime = strtotime($curr_datetime);

				//withraw server/s values - Single row only
				echo '<tr>';
				echo '<td>' . date("H:i:s", $strtotime_curr_datetime) . '</td>';

				if(!empty($servers)){
					//echo all server values
					for($n = 1; $n <= count($servers); $n++){
						echo '<td align="right">' /*. $data_s[$n][2] . ' '*/ . $data_s[$n][3] . '</td>';
					}
				}

				echo '</tr>';

				//reset server values
				for($o = 1; $o <= count($servers); $o++){
					$data_s[$o][3] = 0;	
				}
					
				//update current datetime
				$strtotime_now_datetime = strtotime($curr_datetime);//current datetime - 1 minute
				$subtracted_now_datetime = $strtotime_now_datetime+(60*-1);
				$curr_datetime = date("Y-m-d H:i:s.0", $subtracted_now_datetime);//glenn
			
			}//end - <x> no of durations loop

			?>
		</tbody>
	</table><!--END component stats table-->

<?php } ?>

</div>	

<!--
select serverid, handler, avgqueuetime, avgprocesstime, fail, success, eventsinqueue, logtime, evicttag from pnp_handlerstats where logtime <= '2016-02-10 19:47:00.0' and logtime >= '2016-01-10 19:47:00.0' order by logtime desc;
select serverid, fail, success, queue_count, logtime from pnp_notif_stats where logtime >= '2016-09-23 14:15:00.0' order by logtime desc;
-->