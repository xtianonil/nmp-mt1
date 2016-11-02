<div>
	<form method="get" action="<?php echo site_url('component_stats/index')?>">
		<span class="filterby">
		Server
		<select name="serverid">
			<option value="AllServers">AllServers</option>
			<option value="Optimus2">Optimus2</option>
			<option value="TCSERVER01">TCSERVER01</option>
			<option value="TC1">TC1</option>
		</select>
		</span>			

		<span class="filterby">
		Component
		<select name="component">
			<option value="AllComponents">AllComponents</option>
			<option value="ADCHandler">ADCHandler</option>
			<option value="WalletHandler">WalletHandler</option>
			<option value="ServiceEventHandler">ServiceEventHandler</option>
			<option value="NotificationSystem">NotificationSystem</option>
			<option value="SMSGateway">SMSGateway</option>
		</select>
		</span>	

		<span class="filterby">
		Category 
		<select name="category">
			<option value="tpm">tpm</option>
			<option value="success">success</option>
			<option value="fail">fail</option>
			<option value="eventsinqueue">eventsinqueue</option>
		</select>
		</span>

		<span class="filterby">
		Duration 
		<select name="duration">
			<option value="1">1m</option>
			<option value="3">3m</option>
			<option value="5">5m</option>
			<option value="15">15m</option>
			<option value="30">30m</option>
			<option value="60">1h</option>
			<option value="120">2h</option>
			</select>
		</span>

		<button type="submit" class="primary"><i class="fa fa-search"></i> Filter</button>
	</form>
</div>

<?php if($filters['serverid'] == 'AllServers') { ?>
	<table>
		<thead>
		<?php
			//current filters
			echo '<b>Query: </b>' . $filters['serverid'] . ' -> ' .  $filters['component'] . ' -> ' . $filters['category'] . ' -> ' . $filters['duration'] . ' minute/s' . '<br>';

			//column header logtime
			echo '<th>Logtime</th>';
			//echo column header of all servers serverid
			foreach($servers as $row_s){
				//$id = $row_s->id;//unneeded
				$serverid = $row_s['serverid'];

				//Put to 2D array
				echo '<th>' . $serverid . '</th>';
			}
		?>
		</thead>

		<tbody>

			<?php
				//queried records from GFXD TABLE: PNP_HANDLERSTATS as component stats
				//preparing component stats
				$a = 1;
				
				foreach ($handler_stats as $row_cs) {
					//$id = $component_stat->ID;//unneeded
					$logtime = $row_cs->LOGTIME;
					$serverid = $row_cs->SERVERID;
					$handler = $row_cs->HANDLER;
					$success = $row_cs->SUCCESS;
					$fail = $row_cs->FAIL;	
					$eventsinqueue = $row_cs->EVENTSINQUEUE;

					//Put to 2D array
					//$data_cs[$a][1] = $id;//unneedded
					$data_cs[$a][2] = $logtime;
					$data_cs[$a][3] = $serverid;
					$data_cs[$a][4] = $handler;
					$data_cs[$a][5] = $success;	
					$data_cs[$a][6] = $fail;		
					$data_cs[$a][7] = $eventsinqueue;

					//echo $a . ' ' . $data_cs[$a][2] . ' ' . $data_cs[$a][3] . ' ' . $data_cs[$a][4] . ' ' . $data_cs[$a][5] . ' ' . $data_cs[$a][6] . ' ' . $data_cs[$a][7] .'<br>';
					$a++;
				}

				//queried records from MYSQL TABLE: servers as servers
				//preparing servers
				$b = 1;

				foreach($servers as $row_s){
					//$id = $row_s->id;//unneeded
					$serverid = $row_s['serverid'];

					//Put to 2D array
					//$data_s[$b][1] = $id;//unneeded
					$data_s[$b][2] = $serverid;

					$b++;
				}

				//Component Stats are ready... //Servers are ready...

				//initialize all servers values to 0
				for($d = 1; $d <= count($servers); $d++){
					$data_s_v[$d][1] = 0;
				}

				$count_component_stats = count($handler_stats);//count queried component stats
				$curr_logtime = $now_datetime;
				

				echo 'Record/s on pnp handler stats: ' . $count_component_stats . '<br>';
				echo '<b>From: </b>' . $from_datetime . ' -> ' . '<b>To: </b>' . $curr_logtime . '<br>';

				//algorithm on how servers values will be echoed
				for($i = 1; $i <= $count_component_stats; $i++){//single row of component stats will undergo onto this loop
					//choose server to deposit
					for($j = 1; $j <= count($servers); $j++){
						//if serverid(from component stats) is equal to serverid(from servers) then deposit
						if($data_cs[$i][3] == $data_s[$j][2]){					
							//filters category
							//choose which value/s to deposit
							if($filters['category'] == 'tpm'){
								$data_s_v[$j][1] = $data_s_v[$j][1] + ($data_cs[$i][5] + $data_cs[$i][6]);//deposit success + fail
							}elseif($filters['category'] == 'success'){
								$data_s_v[$j][1] = $data_s_v[$j][1] + $data_cs[$i][5];//deposit success
							}elseif($filters['category'] == 'fail'){
								$data_s_v[$j][1] = $data_s_v[$j][1] + $data_cs[$i][6];//deposit fail
							}elseif($filters['category'] == 'eventsinqueue'){
								$data_s_v[$j][1] = $data_s_v[$j][1] + $data_cs[$i][7];//deposit eventsinqueue
							}
						}
					}

					//when to withraw?
					if($i != $count_component_stats){//avoid array out of bounds
						// print server values when next logtime is different
						if($curr_logtime != $data_cs[$i+1][2]){
							echo '<tr>';//echo single row

							//echo logtime
							echo '<td align="center">'. $data_cs[$i][2] . '</td>';
							//echo all servers total values
							for($k = 1; $k <= count($servers); $k++){
								echo '<td align="center">' . $data_s[$k][2] . ': ' . $data_s_v[$k][1] . ' ' . '</td>';
							}

							echo '</tr>';			

							//reset all servers values
							for($l = 1; $l <= count($servers); $l++){
								$data_s_v[$l][1] = 0;
							}	

							//update current logtime
							$curr_logtime = $data_cs[$i+1][2];
						}

					}elseif($i == $count_component_stats){//print last batch of servers total values
						echo '<tr>';//echo single row

						//echo logtime
						echo '<td align="center">'. $data_cs[$i][2] .'</td>';
						//echo all servers total values
						for($m = 1; $m <= count($servers); $m++){
							echo '<td align="center">' . $data_s[$m][2] . ' :' . $data_s_v[$m][1] . ' ' . '</td>';
						}

						echo '</tr>';			

						//reset all servers values
						for($n = 1; $n <= count($servers); $n++){
							$data_s_v[$n][1] = 0;
						}

					}	
				}

			?>

			</tr>
		</tbody>
	</table>	

<?php }else{ ?>
	
	<table>
		<thead>
		<?php
			//current filters
			echo '<b>Query: </b>' . $filters['serverid'] . ' -> ' .  $filters['component'] . ' -> ' . $filters['category'] . ' -> ' . $filters['duration'] . ' minute/s' . '<br>';
			
			//column header logtime
			echo '<th>Logtime</th>';
			//echo column header of all specific server serverid
			echo '<th>' . $filters['serverid'] . '</th>';
		?>
		</thead>

		<tbody>

			<?php
				//queried records from GFXD TABLE: PNP_HANDLERSTATS as component stats
				//preparing component stats
				$a = 1;
				
				foreach ($handler_stats as $row_cs) {
					//$id = $component_stat->ID;//unneeded
					$logtime = $row_cs->LOGTIME;
					$serverid = $row_cs->SERVERID;
					$handler = $row_cs->HANDLER;
					$success = $row_cs->SUCCESS;
					$fail = $row_cs->FAIL;	
					$eventsinqueue = $row_cs->EVENTSINQUEUE;

					//Put to 2D array
					//$data_cs[$a][1] = $id;//unneedded
					$data_cs[$a][2] = $logtime;
					$data_cs[$a][3] = $serverid;
					$data_cs[$a][4] = $handler;
					$data_cs[$a][5] = $success;	
					$data_cs[$a][6] = $fail;		
					$data_cs[$a][7] = $eventsinqueue;

					//echo $a . ' ' . $data_cs[$a][2] . ' ' . $data_cs[$a][3] . ' ' . $data_cs[$a][4] . ' ' . $data_cs[$a][5] . ' ' . $data_cs[$a][6] . ' ' . $data_cs[$a][7] .'<br>';
					$a++;
				}

				//queried records from MYSQL TABLE: servers as servers
				//preparing servers
				$b = 1;

				$count_component_stats = count($handler_stats);//count queried component stats
				$curr_logtime = $now_datetime;
				
				echo 'Record/s on pnp handler stats: ' . $count_component_stats . '<br>';
				echo '<b>From: </b>' . $from_datetime . ' -> ' . '<b>To: </b>' . $curr_logtime . '<br>';

				//initialize specific server value to 0
				$data_s_v = 0;
				//algorithm on how servers values will be echoed
				for($i = 1; $i <= $count_component_stats; $i++){//single row of component stats will undergo onto this loop				
					//filters category
					//choose which value/s to deposit
					if($filters['category'] == 'tpm'){
						$data_s_v = $data_s_v + ($data_cs[$i][5] + $data_cs[$i][6]);//deposit success + fail
					}elseif($filters['category'] == 'success'){
						$data_s_v = $data_s_v + $data_cs[$i][5];//deposit success
					}elseif($filters['category'] == 'fail'){
						$data_s_v = $data_s_v + $data_cs[$i][6];//deposit fail
					}elseif($filters['category'] == 'eventsinqueue'){
						$data_s_v = $data_s_v + $data_cs[$i][7];//deposit eventsinqueue
					}

					//when to withraw?
					if($i != $count_component_stats){//avoid array out of bounds
						// print server values when next logtime is different
						if($curr_logtime != $data_cs[$i+1][2]){
							echo '<tr>';//echo single row

							//echo logtime
							echo '<td align="center">'. $data_cs[$i][2] . '</td>';
							//echo specific server total values
							echo '<td align="center">' . $filters['serverid'] . ': ' . $data_s_v . ' ' . '</td>';

							echo '</tr>';			

							//reset specific servers values
							$data_s_v = 0;

							//update current logtime
							$curr_logtime = $data_cs[$i+1][2];
						}

					}elseif($i == $count_component_stats){//print last batch of servers total values
						echo '<tr>';//echo single row

						//echo logtime
						echo '<td align="center">'. $data_cs[$i][2] . '</td>';
						//echo specific server total values
						echo '<td align="center">' . $filters['serverid'] . ': ' . $data_s_v . ' ' . '</td>';

						echo '</tr>';			

						//reset specific servers values
						$data_s_v = 0;
					}	
				}

			?>

			</tr>
		</tbody>

	</table>
<?php } ?>