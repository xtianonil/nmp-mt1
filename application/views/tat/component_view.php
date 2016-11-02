<button class="export_as_csv_button component_view tat btn btn-default btn-sm">Export as CSV</button>
<?php echo form_open('TAT/filter'); ?>
<table>
	<tr>
		<td>
			<button type='submit' class='btn-custom1_default' name="button_route" value="server_view">Server View</button>
			<button type='submit' class='btn-custom1_selected' name="button_route" value="component_view">Component View</button>
			<button type='submit' class='btn-custom1_default' name="button_route" value="summary_view">Summary View</button>
		</td>
	</tr>
	<tr>
		<td>
			<div class="formbox-inline">
				<div class="form_filter">
					<table>
						<tr>
							<td class="filter-group1">
								Extract Date:
							</td>
							<td class="filter-group2">
								<?php 
									$extract_date = array(
										'type' 			=> 'text',
										'name' 			=> 'extract_date',	//necessary for it to be called by this->input->pos
										'id' 			=> 'extract_date',	//necessary for it to be called by tableEport.js
										'class'			=> 'datepicker',	//styledatepicker class
										'placeholder'	=> '-select date-',
										'readonly'		=> true,			//disallow editting of input
										'value'			=> $selected_extract_date
									);
									echo form_input($extract_date); 
								?>
							</td>
							<td class="filter-group1">
								TC Server:
							</td>
							<td class="filter-group2">
								<select name="tcserver_list" id="tcserver_list" class="custom_dropdown_2em">
									<?php
										if (is_array($tcserver_list))
										{
											foreach ($tcserver_list as $tcserver)
											{
												if ($selected_tcserver === $tcserver->tcserver_name)	//when form is submitted, remember which option the user has selected
													echo '<option value=' . $tcserver->tcserver_name . ' selected>' . $tcserver->tcserver_name . '</option>';
												else
													echo '<option value=' . $tcserver->tcserver_name . '>' . $tcserver->tcserver_name . '</option>';
											}
										}
									?>
								</select>
							</td>
							<td>
								<button type='submit' class='filter_button' name="button_route" value="filter_from_component_view">Filter</button>
							</td>
						</tr>
					</table>
				</div>
				<table>
					<tr>
						<td colspan="2">
							Filter options
						</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td class="filter-group1" style="font-weight: bold;">
							Date:
						</td>
						<td class="filter-group2">
							<?php echo $selected_extract_date; ?>
						</td>
					</tr>
					<tr>
						<td></td>
						<td class="filter-group1" style="font-weight: bold;">
							TC Server:
						</td>
						<td class="filter-group2">
							<?php echo $selected_tcserver; ?>
						</td>
					</tr>
				</table>
				
				<?php echo form_close(); ?>
				<?php
					if (!$is_empty)	//if there are no results that match filter options
					{
				?>
						<input style='display:none' class='allow_export' type='checkbox' checked='checked' />
						<table class='table table-striped table-bordered' id='tat_table'>
							<tbody>
								<tr>
									<?php
										echo '<td class="table_custom_header"></td>';
										if (is_array($component_list))
											foreach ($component_list as $column_header)
												echo '<td colspan="2" class="table_custom_header">' . $column_header->component_name . '</td>';
									?>
								</tr>
								<tr>
									<?php
										echo '<td class="table_custom_sub_header">Time_Interval</td>';
										if (is_array($component_list))
											foreach ($component_list as $column_header){
												//echo '<td>';
												echo '<td class="table_custom_sub_header">processed</td>';
												echo '<td class="table_custom_sub_header">percentage</td>';
												//echo '</td>';
											}
									?>
								</tr>
								<?php
									$time = array('< 1m', 
										'> 1m - 2m', 
										'> 2m - 3m',
										'> 3m - 5m',
										'> 5m - 10m',
										'> 10m - 15m',
										'> 15m - 20m',
										'> 20m - 30m',
										'> 30m',
										'TOTAL');
									foreach ($time as $time_interval)
									{
										echo '<tr>';
											echo '<td align="center">' . $time_interval . '</td>';
											if (is_array($component_list))
											{
												foreach ($component_list as $component)
												{
													echo '<td align="right">';
													if (is_array($rows[$time_interval][$component->component_name]))
													{
														foreach ($rows[$time_interval][$component->component_name] as $row)
														{
															if ($row->time_interval === $time_interval && $row->component_name === $component->component_name)
															{
																echo $row->turnaround_processed . '</td>';
																echo '<td align="right">';
																echo $row->turnaround_percentage . '%';
															}
														}
													}
													else
														echo '-';
													echo '</td>';
												}
											}
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
				<?php
					}	
					else
					{	//if no results are found, do not show export as csv button
				?>
						<input style='display:none' class='allow_export' type='checkbox' />
						<br><a style='color:red'>No results found.</a>
				<?php
						echo '';
					}
				?>
			</div>
		</td>
	</tr>
</table>