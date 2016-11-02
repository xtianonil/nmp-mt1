<?php
echo "<a class='btn btn-default btn-md' href='" . base_url() . "index.php/Components_stats/server_view'>Server View</a>";
echo "<a class='btn btn-default btn-md' href='" . base_url() . "index.php/Components_stats/component_view'>Component View</a>";
echo "<a class='btn btn-custom1 btn-md' href='" . base_url() . "index.php/Components_stats/summary_view/smsgateway'>Summary View</a>";

echo '<button style="float:right" class="export_as_csv_button summary_view components_stats btn btn-default">Export as CSV</button>';
?>

<div class="formbox">
	<!--filters-->
	<div class="form_filter">
		<form method="POST" action="<?php echo site_url('components_stats/summary_view')?>">
			<span class="filterby">
			Components : 
			<select name="component">
				<option value="AllComponents"<?php if($filters['component'] == 'AllComponents') echo 'selected' ?>>All Components</option>
				<option value="SMSGateway"<?php if($filters['component'] == 'SMSGateway') echo 'selected' ?>>SMS Gateway</option>
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

	//smsgatewaystats records - avoid undefined offset
	if(!empty($smsgateway_stats)){
		//echo 'SMSGateway Stats <br>';
		//smsgateway stats records - put to 2D array
		$count_ss = 1;
		foreach ($smsgateway_stats as $row_ss) {
			//$id = $component_stat->ID;//unneeded
			$logtime_ss = $row_ss->LOGTIME;
			$serverid_ss = $row_ss->SERVERID;
			$instance_ss = $row_ss->INSTANCE;//unneeded
			$success_ss = $row_ss->SUCCESS;
			$fail_ss = $row_ss->FAIL;	
			$queue_count_ss = $row_ss->QUEUE_COUNT;
			//Put to 2D array
			//$data_ns[$a][1] = $id;//unneedded
			$data_ss[$count_ss][2] = $logtime_ss;
			$data_ss[$count_ss][3] = $serverid_ss;
			$data_ss[$count_ss][4] = $instance_ss;
			$data_ss[$count_ss][5] = $success_ss;	
			$data_ss[$count_ss][6] = $fail_ss;		
			$data_ss[$count_ss][7] = $queue_count_ss;
				
			//echo 'Index: ' . $count_ss . ' ' . $data_ss[$count_ss][2] . ' ' . $data_ss[$count_ss][3] . ' ' . $data_ss[$count_ss][4] . ' ' . $data_ss[$count_ss][5] . ' ' . $data_ss[$count_ss][6] . ' ' . $data_ss[$count_ss][7] .'<br>';
			$count_ss++;
		}
		//echo '<br>';
	}else{
		$count_ss = 0;
	}

	$duration = $filters['duration'];
	$curr_datetime = $now_datetime;

	//servers records - avoid undefined offset
	if(!empty($servers)){
		$count_s = 1;

		//<x> no. of servers loop
		foreach($servers as $row_s){
			$server_name = $row_s->server_name;
			//Put to 2D array
			//$data_s[$count_s][1] = $id;//unneeded
			$data_s[$count_s][2] = $server_name;
			
			$count_s++;
		}
	}

	//<x> no. of duration/s loop - init servers instances tpm/success/fail/eventsinqueue
	for($i = 1; $i <= $duration; $i++){ 
		
		if(!empty($servers)){
			//<x> no. of server/s loop
			$count_s = 1;
			foreach($servers as $row_s){
				$server_name = $row_s->server_name;

				if(!empty($instances)){
					//get server specific instances - by comparing instances serverid to servers servername
					foreach ($instances as $row_s) {
						if($row_s->SERVERID == $server_name){
							$instance[] = $row_s->INSTANCE;
						}
					}
				}

				//distinct instances of specific serverid
				$server_specific_instances = array_unique($instance);
				//sort
				sort($server_specific_instances);
				
				//<x> no. of instances of specific server loop - init instance value to 0
				$count_i = 1;
				foreach ($server_specific_instances as $row_ssi) {
					$data_ssi[$count_s][$count_i][0] = $server_name;
					$data_ssi[$count_s][$count_i][1] = $row_ssi;
					$data_ssi[$count_s][$count_i][5] = 0;
				
					//echo $count_i . ' ' . $data_ssi[$count_s][$count_i][0] . ' ' . $data_ssi[$count_s][$count_i][1] . ' ' . $data_ssi[$count_s][$count_i][5] . ' ';
					//echo '<br>';
				$count_i++;
				}

				//reset instance - will use by next server
				$instance = null;

			$count_s++;
			}
		}


	}//end - <x> no. of duration/s loop

	$instance = null;
	$server_specific_instances= null;

	?>

	<br>

	<!--if filters has no result-->
	<?php

	if(empty($smsgateway_stats)){
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
			<tr>
				<!--column header logtime-->
				<td class="table_custom_header">Logtime</td>

				<!--column header of all servers id's-->
				<?php
					//servers - avoid undefined offset
					if(!empty($servers)){
						//<x> no. of servers loop
						for($count_s = 1; $count_s <= count($servers); $count_s++) {
							//echo serverid within td
							echo '<td class="table_custom_header">' . $data_s[$count_s][2] . '</td>';

							if(!empty($instances)){
								//get server specific instances - how many header instances to echo?
								foreach ($instances as $row_i) {
									if($row_i->SERVERID == $data_s[$count_s][2]){
										$instance[] = $row_i->INSTANCE;
									}
								}
							}


							//distinct instances of specific serverid
							$server_specific_instances = array_unique($instance);

							//echo empty td
							for($count_i = 1; $count_i < count($server_specific_instances); $count_i++){
								echo '<td class="table_custom_header"></td>';
							}

							//reset instance
							$instance = null;	
							$server_specific_instances = null;
						}
					}
				?>
			</tr>
			<tr>
				<!--column header logtime - empty td-->
				<td class=""></td>

				<!--column header of all servers server-->
				<?php
					//servers - avoid undefined offset
					if(!empty($servers)){
						//<x> no. of servers loop
						for($count_s = 1; $count_s <= count($servers); $count_s++) {
							
							if(!empty($instances)){
								//get server specific instances - how many header instances to echo?
								foreach ($instances as $row_i) {
									if($row_i->SERVERID == $data_s[$count_s][2]){
										$instance[] = $row_i->INSTANCE;
									}
								}
							}


							//distinct instances of specific serverid
							$server_specific_instances = array_unique($instance);

							//echo server specific smsgateway instances
							for($count_i = 1; $count_i <= count($server_specific_instances); $count_i++){
								echo '<td>' . $data_ssi[$count_s][$count_i][1] . '</td>';
							}

							//reset instance
							$instance = null;	
							$server_specific_instances = null;
						}
					}
				?>
			</tr>
		</thead>

		<tbody>

		<?php
		//<x> no. of duration/s loop
		for($i = 1; $i <= $duration; $i++){ 
			//echo 'Index: ' . $i . ' DateTime: ' . $curr_datetime . '<br>';//temp comment
			//<x> no. of smsgateway stats loop
			for($j = 1; $j < $count_ss; $j++){
							
				//avoid array out of bounds
				if($j < $count_ss){
					//if smsgateway stats logtime is equal to current datetime
					if($data_ss[$j][2] == $curr_datetime){
						//echo $j . ' ' . $data_ss[$j][2] . ' ' . $data_ss[$j][3] . ' ' . $data_ss[$j][4] . ' ' . $data_ss[$j][5] . ' ' . $data_ss[$j][6] . ' ' . $data_ss[$j][7] .'<br>';//temp comment	
						//<x> no. of server/s loop - to know which servers to deposit notif stats
						for($k = 1; $k <= count($servers); $k++){
							//if smsgateway stats serverid is equal to servers server name
							if($data_ss[$j][3] == $data_s[$k][2]){
								//echo $curr_datetime . ' ' . $data_ss[$j][3] . ' ' . $data_ss[$j][4] . '<br>';

								//get server specific instances - by comparing instances serverid to servers servername
								if(!empty($instances)){
									foreach ($instances as $row_i) {
										if($row_i->SERVERID == $data_s[$k][2]){
											$instance[] = $row_i->INSTANCE;
										}
									}
								}

								//distinct instances of specific serverid
								$server_specific_instances = array_unique($instance);
								//sort
								sort($server_specific_instances);	
								//servername
								//echo $data_ss[$j][3] . '<br>';

								//<x> no. of instances of specific server loop - which instance to deposit?
								$count_i = 1;
								foreach ($server_specific_instances as $row_ssi) {
									//if smsgateway stats instance is equal to server instance
									if($data_ss[$j][4] == $row_ssi){
										//what to deposit?
										switch ($filters['category']) {
											case 'tpm':
												$data_ssi[$k][$count_i][5] = $data_ssi[$k][$count_i][5] + ($data_ss[$j][5] + $data_ss[$j][6]);//deposit success + fail
												break;
											case 'success':
												$data_ssi[$k][$count_i][5] = $data_ssi[$k][$count_i][5] + $data_ss[$j][5];//deposit success only
												break;
											case 'fail':
												$data_ssi[$k][$count_i][5] = $data_ssi[$k][$count_i][5] + $data_ss[$j][6];//deposit fail only
												break;
											case 'eventsinqueue':
												$data_ssi[$k][$count_i][5] = $data_ssi[$k][$count_i][5] + $data_ss[$j][7];//deposit eventsinqueue only
												break;
											default:
												$data_ssi[$k][$count_i][5] = $data_ssi[$k][$count_i][5] + ($data_ss[$j][5] + $data_ss[$j][6]);//deposit success + fail
											break;
										}
									}
								
								//echo $count_i . ' ' . $data_ssi[$k][$count_i][0] . ' ' . $data_ssi[$k][$count_i][1] . ' ' . $data_ssi[$k][$count_i][5] . ' ';
								//echo '<br>';
								$count_i++;
								}	
								//echo '<br>';

								//reset instance - will use by next server
								$instance = null;					
					
							}
						}
						
								
					}
				}

			}

			//format logtime
			$strtotime_curr_datetime = strtotime($curr_datetime);

			//withraw server/s instances values - Single row only
			echo '<tr>';
			echo '<td>' . date("H:i:s", $strtotime_curr_datetime) . '</td>';

			//servers - avoid undefined offset
			if(!empty($servers)){
				//<x> no. of servers loop
				for($count_s = 1; $count_s <= count($servers); $count_s++) {
					//get server specific instances - by comparing instances serverid to servers servername - how many instances to echo?
					if(!empty($instances)){
						foreach ($instances as $row_i) {
							if($row_i->SERVERID == $data_s[$count_s][2]){
								$instance[] = $row_i->INSTANCE;
							}
						}
					}

					//distinct instances of specific serverid
					$server_specific_instances = array_unique($instance);

					//withraw specific instances values
					for($count_i = 1; $count_i <= count($server_specific_instances); $count_i++){
						echo '<td>' /*. $data_ssi[$count_s][$count_i][1] . ' '*/ . $data_ssi[$count_s][$count_i][5] . '</td>';
					}

					//reset specific instances values to 0
					for($count_i = 1; $count_i <= count($server_specific_instances); $count_i++){
						$data_ssi[$count_s][$count_i][5] = 0;
					}

					//reset instance - will use by next server
					$instance = null;	
					$server_specific_instances = null;
				}
			}
			
			echo '</tr>';
							
			//update current datetime
			$strtotime_now_datetime = strtotime($curr_datetime);//current datetime - 1 minute
			$subtracted_now_datetime = $strtotime_now_datetime+(60*-1);
			$curr_datetime = date("Y-m-d H:i:s.0", $subtracted_now_datetime);//glenn
					
		}//end - <x> no of durations loop

		?>

		</tbody>
	</table>

	<?php } ?>

</div>
