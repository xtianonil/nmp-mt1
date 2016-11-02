<button class="export_as_csv_button summary_view tpm_24hour btn btn-default btn-sm">Export as CSV</button>
<?php echo form_open('TPM_24hour/filter'); ?>
<table width="1000">
	<tr>
		<td>
			<button type='submit' class='btn-custom1_default' name="button_route" value="server_view">Server View</button>
			<button type='submit' class='btn-custom1_default' name="button_route" value="component_view">Component View</button>
			<button type='submit' class='btn-custom1_selected' name="button_route" value="summary_view">Summary View</button>
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
							
							<td>
								<button type='submit' class='filter_button' name="button_route" value="filter_from_summary_view">Filter</button>
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
					</table>
				</div>
					<?php echo form_close(); ?>
				<?php
					if (!$is_empty)	//if there are no results that match filter options
					{
				?>
						<input style='display:none' class='allow_export' type='checkbox' checked='checked' />
						<table class='table table-striped table-bordered' id='TPM_24hour_table'>
							
							<tbody>
								<tr>
									<td class="table_custom_header"></td>
									<?php
										if (is_array($tcserver_list))
										{
											foreach ($tcserver_list as $tcserver)
											{
									?>
												<td class="table_custom_header" colspan="6"><?php echo $tcserver->tcserver_name; ?></td>
									<?php
											}
										}
									?>
								</tr>
								<tr>
									<td class="table_custom_sub_header">Hours</td>
									<?php
										if (is_array($tcserver_list))
										{
											foreach ($tcserver_list as $tcserver)
											{
												if (is_array($component_list))
												{
													foreach ($component_list as $component)
													{
														echo '<td class="table_custom_sub_header">' . $component->component_name . '</td>';
													}
												}
											}
										}
									?>
								</tr>
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
									{
										echo '<tr>';
										echo '<td>' . $hour . '</td>';
										if (is_array($tcserver_list))
										{
											foreach ($tcserver_list as $tcserver)
											{
												if (is_array($component_list))
												{
													foreach ($component_list as $component)
													{
														if (is_array($rows[$hour][$tcserver->tcserver_name][$component->component_name]))
														{	//check if data exists
															foreach ($rows[$hour][$tcserver->tcserver_name][$component->component_name] as $row)
															{
																if ($row->tcserver_name === $tcserver->tcserver_name && $row->component_name === $component->component_name && $row->tpm_hour === $hour)
																{	//put data in its corresponding place in table matrix
																	echo '<td>' . $row->transactions . '</td>';
																}
															}
														}
														else
														{	//else, print '-' to show a data cell has no data
															echo '<td>-</td>';
														}
														
													}
												}
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

