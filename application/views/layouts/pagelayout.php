<!DOCTYPE html >
<html>
	<head>
		<meta charset="utf-8"> 
		<title>NMP Monitoring Tool</title>

		<link rel="stylesheet" href="<?php echo base_url('css/bootstrap.css') ?>" />

		<link rel="stylesheet" href="<?php echo base_url('css/pagelayout.css') ?>" />
		<link rel="stylesheet" href="<?php echo base_url('css/pagelayout.css') ?>" />
		<link rel="stylesheet" href="<?php echo base_url('css/jquery-ui.css') ?>" />
		<link rel="stylesheet" href="<?php echo base_url('css/theme.css') ?>" />
		<link rel="stylesheet" href="<?php echo base_url('css/font-awesome-4.3.0/css/font-awesome.min.css') ?>" />
		
		<style>
			.error li, .warning li {
				padding-left: 0.5em;
			}

			#webtool_content {
			<?php if (!$print_navbar): ?>
				/*margin-top: 5em;*/
			<?php else: ?>
				/*margin-top:8em;
				margin-left: 1%;
				margin-right: 1%;*/
				/*left: 16%;
				position: absolute;
				width: 83%;*/
				margin-left: 16%;
				width: 83%;
				padding-top: 10px;
				top: 5em;
			<?php endif; ?>
			}

			<?php if ($print_navbar): ?>
			#webtool_navbar {
				color: #2F4F4F;
				width: 15%;
				background-color: #E0EEEE;
				margin-top: 58px;
				box-shadow: 0px 2px 10px 0px rgba(0, 0, 0, 0.26);
				font-size: normal;
				position: fixed;
				/*overflow-y: scroll; removes vertical scroll from left panel*/ 
				overflow-x: scroll; /*put horizontal scroll on left panel*/
				height: 100%;
				font-size: smaller;
			}  

			#webtool_navbar a {
				font-size: 12px;
				padding-left: 25px;
				padding-top: 12px;
				padding-bottom: 12px;
				padding-right: 10px;
				text-decoration: none;
				color: inherit;
				display: block;	
			}

			#webtool_navbar a:hover {
				background-color: rgba(0, 0, 0, 0.15);
				font-weight: bold;
			}

			#webtool_navbar a.selected {
				background-color: rgba(0, 0, 0, 0.15);
				border: none;
				font-weight: bold;
			}
			/*
			#webtool_usernav {
			    position: absolute;
			    right: 0;
			    top: 0;	
			}*/
			.webtool_navbar_header {
				padding: 10px;
			}

			.block_tpm_24hour {
				display: none;
				background-color: white;
			}

			#modulename {
				margin-left: 1em;
			}

			#alarms_count{
				padding: 5px 5px 5px 5px;
				background-color: darkslategrey;
				color: #ffffff;
				font-weight: bold;
				margin-left: -10px;
				border-radius: 100px;
				position: absolute;
				margin-top: -15px;
				font-size: 10px;
			}

			#alarms_content{
				max-height: 100px;
				min-width: 800px;
				padding: -5px -5px -5px -5px;
				background-color: #E0EEEE;
				color: #2F4F4F;
				font-weight: bold;
				border-radius: 3px;
				position: relative;
				font-size: 12px;
			}
			<?php endif ?>

			<?php if (!empty($this->load->css)): ?>
			<?php echo $this->load->css ?>
			<?php endif?>
		</style>

		<script src="<?php echo base_url('js/bootstrap.js') ?>" type="text/javascript"></script>

		<script src="<?php echo base_url('js/jquery-1.11.1.min.js') ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('js/jquery-ui.min.js') ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('js/jquery.tablesorter.min.js') ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('js/pagelayout.js') ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('js/jquery-ui-timepicker-addon.js') ?>" type="text/javascript"></script>

		<script src="<?php echo base_url('js/tableExport.js') ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('js/tableExportHelper.js') ?>" type="text/javascript"></script>
		<!--<script src="<?php //echo base_url('js/datePickerHelper.js') ?>" type="text/javascript"></script>-->

		<!--Calendar Javascript-->
		<script type="text/javascript">
			$(document).ready(function() {
				$('.export_as_csv_button').addClass('btn').addClass('btn-secondary').text(' Export as CSV').prepend($("<i class='fa fa-download'></i>"));
				$('.filter_button').addClass('btn').addClass('btn-default').addClass('btn-sm').text(' Filter').prepend('<i class="fa fa-search"></i>');
				//$('.tab_button').addClass('btn').addClass('btn-primary').addClass('btn-md');

				if ($('.allow_export').is(':checked'))
					$('.export_as_csv_button').attr('disabled',false);
				else
					$('.export_as_csv_button').attr('disabled',true);

				/*
				if ($('.allow_filter').is(':checked'))
					$('.filter_button').attr('disabled',false);
				else
					$('.filter_button').attr('disabled',true);
				*/
				
				$('.datepicker').datepicker({
					dateFormat: 'yy-mm-dd',
					changeMonth: true,
			        changeYear: true,
			        yearRange: '-50:+50',
			        showOn: "both",
			        buttonImage: "<?php echo base_url('/images/calendar-green.gif'); ?>"
				});
				/*
				$('.datetimepicker').datetimepicker({
					showOn: "both",
					buttonImage: "<?php //echo base_url('/images/calendar.gif') ?>",
					timeFormat: "hh:mm tt"
				});
				*/

			<?php if (!empty($this->load->js)): ?>
			<?php echo $this->load->js ?>
			<?php endif ?>
			});

			//run on page load - check alarms count
			updateAlarms();

			//update alarms count
			function updateAlarmsCount(type, msg){
				$('#alarms_count').html(msg);
			}

			//update alarms content - on click
			function updateAlarmsContent(type, msg){
				$('#alarms_content').html(msg);
			}
			 
			//get data from url - every <x> no of second
			function updateAlarms(){
				$.ajax({
					type: "GET",
					url: "http://192.168.0.68/nmp-emt/index.php/Alarms/get_alarms_count",
					 
					async: true,
					cache: false,
					timeout:120000,
					 
					success: function(data){
						updateAlarmsCount("new", data);
						setTimeout(updateAlarms, 30000);
					},
					error: function(XMLHttpRequest, textStatus, errorThrown){
						updateAlarmsCount();//handler error
						setTimeout(updateAlarms, 60000);
					}
				});
			}

			$(function() {
				//initialize alarms content
				$("#alarms_content").dialog({
					autoOpen : false, 
					modal : true, 
					show : "blind", 
					hide : "blind",
					buttons : {
						OK : {
							class : 'btn btn-default btn-md',
							text : 'OK',
							click : function(){
								$("#alarms_content").dialog("close");
							}
						}
					}
				});$("#alarms_content").dialog({
					autoOpen : false, modal : true, show : "blind", hide : "blind"
				});

				//update then open alarms content
				$("#alarms_link").click(function() {

					//update alarms content first before to open
					$.ajax({
					type: "GET",
					url: "http://192.168.0.68/nmp-emt/index.php/Alarms/get_latest_alarms",
						async: true,
						cache: false,
						timeout:50000,
							 
						success: function(data){
						updateAlarmsContent("new", data);
						},
						error: function(XMLHttpRequest, textStatus, errorThrown){
						updateAlarmsContent("error", textStatus + " (" + errorThrown + ")");
						}
					});

					$.ajax({
						type: "GET",
						url: "http://192.168.0.68/nmp-emt/index.php/Alarms/set_alarms_count_to_zero",
						 
						async: true,
						cache: false,
						timeout:120000,
						 
						success: function(data){
							updateAlarmsCount("new", 0);
						},
						error: function(XMLHttpRequest, textStatus, errorThrown){
							updateAlarmsCount();
						}
					});

					//open alarms content
					$("#alarms_content").dialog("open");
					return false;
				});
			});

			//load alarms content from url <X>
            $(document).ready(function(){
                $('#alarms_content').load('http://192.168.0.21/nmp-emt/index.php/Alarms/get_latest_alarms');
        	});
		</script>

	</head>

	<body>		
		<div id="webtool_body">
		
			<!--Header-->
			<div id="webtool_header">
				<span style="font-family: Helvetica; font-size: 1.7em;"><i class="fa fa-desktop"></i> Notification Management Platform - Monitoring Tool</span>
			</div>
			
			<!--if print nav_bar-->
			<?php if ($print_navbar): ?>

				<div id="webtool_navbar">
					<!--Get current link by getting URI segment(1), thats the Controller name-->
					<?php $currlink = $this->uri->segment(1); ?>
					
					<!--if not empty modules-->
					<?php if (!empty($modules)): ?>
						<h3 class="webtool_navbar_header"><i class="fa fa-align-justify"></i> Modules</h3> 
						<!--print all available modules-->
						<ul>
						<?php foreach ($modules as $url=>$name): ?>
							<!--highlight selected modules-->
							<a href="<?php echo site_url($url) ?>" <?php if ($url == $currlink) echo 'class="selected"'?>><?php echo $name ?></a>
						<?php endforeach ?>
						</ul>
						<br/>
					<?php endif ?>

					<!--echo username, groupname-->
					<h3 class="webtool_navbar_header"><i class="fa fa-user"></i> <?php echo $this->session->userdata('username').' ('.$this->session->userdata('u_type_id').')'?></h3>
					<?php /* <div id="webtool_usernav">*/?>
					<!-- My account settings -->
					<!--<a href="<?php echo site_url('my_account') ?>" <?php if ('my_account' == $currlink) echo 'class="selected"'?>>My Account</a>-->
				
					<!--alarms count icon-->
					<a href="#" id="alarms_link" style="margin-top: 25px; margin-left: -25px;"><h3 class="webtool_navbar_header" style="margin-top: -10px; margin-bottom: -10px;"><i class="fa fa-bell"></i><span id="alarms_count"></span>&nbspalarms</h3></a>     

					<!--alarms content-->
					<div id="alarms_content" title="Latest Alarms">
					</div>

				</div>

				<!-- logout hyperlink -->
				<a id="webtool_logout" href="<?php echo site_url('login/logout') ?>">Log Out</a>
			
			<?php endif ?>

			<!--current content-->
			<div id="webtool_content">
				<h4 id="modulename" style="font-weight: bold"><?php echo $modulename ?></h4>

				<!--if print_msgbanners-->
				<?php if ($print_msgbanners): ?>
					<!--if not empty errors echo-->
					<?php if (!empty($errors)): ?>
						<br/>
						<ul class="error">
						<?php foreach ($errors as $error): ?>
							<li><i class="fa fa-warning"></i>  <?php echo $error ?></li>
						<?php endforeach ?>
						</ul>
					<?php endif ?>

					<!--if not empty successes echo-->
					<?php if (!empty($successes)): ?>
						<br/>
						<ul class="success">
						<?php foreach ($successes as $success): ?>
							<li><i class="fa fa-check-circle-o"></i>  <?php echo $success ?></li>
						<?php endforeach ?>
						</ul>
					<?php endif ?>
				<?php endif ?>

				<br/>

				<!--echo current module content, from MY_Coontroller-->
				<?php echo $content ?>
			</div>
		</div>

	</body>


</html>
