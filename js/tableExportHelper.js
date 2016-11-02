$(function(){
	$('.export_as_csv_button').click(function(){
		if ($('.allow_export').is(":checked"))
		{
			if ($(this).hasClass('tpm_24hour'))
			{
				var fname;
				if ($(this).hasClass('server_view'))
					fname = 'TPM24hour_' + $('#extract_date').val() + '_' + $('#component_list').val();
				else if ($(this).hasClass('component_view'))
					fname = 'TPM24hour_' + $('#extract_date').val() + '_' + $('#tcserver_list').val();
				else if ($(this).hasClass('summary_view'))
					fname = 'TPM24hour_' + $('#extract_date').val();

				$('#TPM_24hour_table').tableExport(
			    {
			      	type:'csv',
			      	fileName: fname
			    });
			}
			else if ($(this).hasClass('tat'))
			{
				var fname;
				if ($(this).hasClass('server_view'))
					fname = 'TAT_' + $('#extract_date').val() + '_' + $('#component_list').val();
				else if ($(this).hasClass('component_view'))
					fname = 'TAT_' + $('#extract_date').val() + '_' + $('#tcserver_list').val();

				$('#tatextract_table').tableExport(
			    {
			      	type:'csv',
			      	fileName: fname
			    });
			}
			else if ($(this).hasClass('dashboard'))
			{
				var fname = 'NMP_Dashboard_' + $('#dashboard_date').val();

				$('#dashboard_table').tableExport(
			    {
			      	type:'csv',
			      	fileName: fname
			    });
			}
			else if ($(this).hasClass('components_stats'))
			{
				var fname;
				if ($(this).hasClass('server_view'))
					fname = 'Components_Stats';
				else if ($(this).hasClass('component_view'))
					fname = 'Components_Stats';

				$('#components_stats_table').tableExport(
			    {
			      	type:'csv',
			      	fileName: fname
			    });
			}
			else if ($(this).hasClass('servers_stats'))
			{
				var fname;
				if ($(this).hasClass('table_view'))
					fname = 'Servers_stats';

				$('#servers_stats_table').tableExport(
			    {
			      	type:'csv',
			      	fileName: fname
			    });
			}
		}
	});//end of .export_as_csv_button click
});//end of $(function)