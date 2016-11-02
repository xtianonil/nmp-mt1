<button class="export_as_csv_button component_view tpm_24hour btn btn-default btn-sm">Export as CSV</button>
<?php echo form_open('TPM_24hour/filter'); ?>
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
				</div><!--end of form filter div-->
				<div class="filter_label">
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
				</div>
					<?php echo form_close(); ?>
				<?php
					if (!$is_empty)	//if there are no results that match filter options
					{
				?>
						<input style='display:none' class='allow_export' type='checkbox' checked='checked' />
						<table class='table table-striped' id='TPM_24hour_table' style="table-layout:fixed; width:100%;">
							<thead>
								<?php
									echo '<td class="table_custom_header" width="4%" align="right">Hours</td>';
									if (is_array($component_list))
									{	//iterates tdrough list of and print tcservers as column headers
										foreach ($component_list as $column_header)
										{
											echo '<td class="table_custom_header" align="right" width="16%">' . $column_header->component_name . '</td>';
										}
									}
								?>
							</thead>
							<tbody>
								<?php
									for ($i=0; $i<=23; $i++)
									{	//24 hours in a day represent each row
										if ($i <= 9)
											$hour = '0' . $i . ':00';
										else
											$hour = $i . ':00';
										$hours[] = $hour;
									}
									foreach ($hours as $hour)
									{	//a row represents an hour in a day
										echo '<tr>';					//so print a new row every hour
											echo '<td width="4%">' . $hour . '</td>';	//print corresponding hour in leftmost column
											if (is_array($component_list))
											{
												foreach ($component_list as $component)
												{	//iterate through list of components--this makes number of columns 
													echo '<td width="16%" align="right">';	
														if (is_array($rows[$hour][$component->component_name]))
														{	//check if data exists
															foreach ($rows[$hour][$component->component_name] as $row){
																if ($row->component_name === $component->component_name && $row->tpm_hour === $hour)
																{	//put data in its corresponding place in table matrix
																	echo $row->transactions;
																}
															}
														}
														else
														{	//else, print '-' to show a data cell has no data
															echo '-';
														}
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