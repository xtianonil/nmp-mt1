New dashboard entry
<div class='formbox'>
<div class='form_filter'>
<!--<button style="float:right" class="export_as_csv_button dashboard"><i class="fa fa-download"></i> Export as CSV</button>-->
<button style="float:right" class="export_as_csv_button dashboard"></button>
<?php

	echo form_open('Dashboard_controller/add_dashboard_record');

	echo form_label('Extract Date:&nbsp;');

	$dashboard_date_input = array(
		'type' 	=> 'text',
		'name' 	=> 'dashboard_date_input',
		'id' 	=> 'dashboard_date',
		'class'	=> 'datepicker',
		'value'	=> $selected_dashboard_date,
		'readonly' => true
	);
	echo form_input($dashboard_date_input);


	//echo form_submit(array('id' => 'submit', 'class' => 'btn btn-default fa fa-search', 'value' => 'Filter'));
	echo '<button type="submit" class="filter_button"> Filter</button>';
	echo form_close(); 
?>
</div>
<?php
/*
	if(!$no_results)
	{
		echo '<input style="display:none" class="allow_export" type="checkbox" checked="checked" />';
		*/
?>
<table class='table' id="dashboard_table" width="50%">
<?php
	echo '<thead>';
		if (is_array($dashboard_column_list))
		{
			echo '<td class="table_custom_header" width="20%">Components</td>';
			foreach ($dashboard_column_list as $column)
			{
				echo '<td align="right" class="table_custom_header">';
				echo $column->column_name;
				echo '</td>';
			}
		}
	echo '</thead>';

	echo '<tbody>';
		if (is_array($dashboard_component_list))
		{
			foreach ($dashboard_component_list as $component)
			{
				echo '<tr>';
				echo '<td>' . $component->component_name . '</td>';
				if (is_array($dashboard_column_list))
				{
					foreach ($dashboard_column_list as $column)
					{
						echo '<td align="right">';
						if (is_array($rows[$component->component_name][$column->column_name]))
						{
							foreach ($rows[$component->component_name][$column->column_name] as $row)
							{
								if ($row->column_name === $column->column_name && $row->component_name === $component->component_name)
									//echo $row->dashboard_value;
									echo '<input type="text" value="' . $row->dashboard_value .' "/>';
							}
						}
						else
							echo '<input type="text" value="' . '-' .' "/>';
						echo '</td>';
					}
					echo '</tr>';
				}
			}
		}

		if (is_array($subscriptions_columns_list))
		{
			echo '<td class="table_custom_header">PNP Subscriptions</td>';
			foreach ($subscriptions_columns_list as $column)
			{
				echo '<td align="right" class="table_custom_header">';
				echo $column->column_name;
				echo '</td>';
			}
		}
		if (is_array($subscriptions_brands_list))
		{
			foreach ($subscriptions_brands_list as $brand)
			{
				echo '<tr>';
				echo '<td>' . $brand->brand_name . '</td>';
				if (is_array($subscriptions_columns_list))
				{
					foreach ($subscriptions_columns_list as $column)
					{
						echo '<td align="right">';
						if (is_array($rows[$brand->brand_name][$column->column_name]))
						{
							foreach ($rows[$brand->brand_name][$column->column_name] as $row)
							{
								if ($row->column_name === $column->column_name && $row->brand_name === $brand->brand_name)
									echo $row->subscription_value;
							}
						}
						else
							echo '-';
						echo '</td>';
					}
					echo '</tr>';
				}
			}
		}

		if (is_array($server_resource_util_columns_list))
		{
			echo '<td class="table_custom_header">Server Resource Utilization</td>';
			foreach ($server_resource_util_columns_list as $column)
			{
				echo '<td align="right" class="table_custom_header">';
				echo $column->column_name;
				echo '</td>';
			}
		}
		if (is_array($server_resource_util_servers_list))
		{
			foreach ($server_resource_util_servers_list as $server)
			{
				echo '<tr>';
				echo '<td>' . $server->server_name . '</td>';
				if (is_array($server_resource_util_columns_list))
				{
					foreach ($server_resource_util_columns_list as $column)
					{
						echo '<td align="right">';
						if (is_array($rows[$server->server_name][$column->column_name]))
						{
							foreach ($rows[$server->server_name][$column->column_name] as $row)
							{
								if ($row->column_name === $column->column_name && $row->server_name === $server->server_name)
									echo $row->sru_value;
							}
						}
						else
							echo '-';
						echo '</td>';
					}
					echo '</tr>';
				}
			}
		}

	echo '</tbody>';
?>
</table>
<?php
/*
	}
	else
	{
		echo '<input style="display:none" class="allow_export" type="checkbox" />';
		echo '<br><a style="color:red">No results found.</a>';
	}
	*/
?>
</div>