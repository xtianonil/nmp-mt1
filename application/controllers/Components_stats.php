<?php
ini_set('max_execution_time', 240); //Maximum execution time of 30 seconds extended to 4 minutes
ini_set('memory_limit','2048M');

class Components_stats extends MY_Controller {
	
	public function __construct() {
		$this->modulename = 'Components Stats';
		$this->moduleid = 1;

		//to invoke CI_Controller functions
		parent::__construct();
		
		//Required to Load
		$this->load->model('Components_stats_model');
	}

	public function index(){
		$this->summary_view();
	}
	
	public function server_view() {
		$data = array();
		
		//---------- get filters
		if(!empty($_POST)){
			//latest filters
			//remove server filters
			//$data['filters']['server'] = trim($this->input->post('server'));
			//$server = $data['filters']['server'];

			$data['filters']['component'] = trim($this->input->post('component'));
			$component = $data['filters']['component'];

			$data['filters']['category'] = trim($this->input->post('category'));

			$data['filters']['duration'] = trim($this->input->post('duration'));
			$duration = $data['filters']['duration'] - 1;
			if($duration > 120) $duration = 120;//limit duration
		}else{
			//default filters
			//remove server filters
			//$data['filters']['server'] = 'AllServers';
			//$server = $data['filters']['server'];

			$data['filters']['component'] = 'AllComponents';
			$component = $data['filters']['component'];

			$data['filters']['category'] = 'tpm';

			$data['filters']['duration'] = 5;
			$duration = $data['filters']['duration'] - 1;
			if($duration > 120) $duration = 120;//limit duration
		}
	
		//---------- required records
		//get servers
		$data['servers'] = $this->Components_stats_model->get_servers();//will use later to server view - header, temp server values (tpm/success/fail/eventsinqueue)
		//get components
		$data['components'] = $this->Components_stats_model->get_components();//will use later to server view - components dropdown

		//get components - distinguished by table - will use later to limit querying
		$handlerstats_components = $this->Components_stats_model->get_components('handlerstats');
		$notifstats_components = $this->Components_stats_model->get_components('notifstats');
		$smsgatewaystats_components = $this->Components_stats_model->get_components('smsgatewaystats');
		
		//handler stats component names - avoid undefined offset
		if(!empty($handlerstats_components)){
			foreach ($handlerstats_components as $row_hsc) {
				$handlerstats_component_names[] = $row_hsc->component_name;
			}
		}

		//notif stats component names - avoid undefined offset
		if(!empty($notifstats_components)){
			foreach ($notifstats_components as $row_nsc) {
				$notifstats_component_names[] = $row_nsc->component_name;
			}
		}

		//smsgateway stats component names - avoid undefined offset
		if(!empty($smsgatewaystats_components)){
			foreach ($smsgatewaystats_components as $row_ssc) {
				$smsgatewaystats_component_names[] = $row_ssc->component_name;
			}
		}

		//---------- date format
		date_default_timezone_set('Asia/Manila');//set timezone

		$now_datetime = '2016-10-17 08:00:00.0';//now datetime//temp//glenn
		$data['now_datetime'] = $now_datetime;
		
		$strtotime_now_datetime = strtotime($now_datetime);//get <from datetime> by <today datetime> - <minutes>
		$subtracted_now_datetime = $strtotime_now_datetime+(60*-$duration);
		$data['from_datetime'] = date("Y-m-d H:i:s", $subtracted_now_datetime);
		$from_datetime = $data['from_datetime'];
		
		//---------- database queries - limit queries from gfxd
		//if selected component is handler stats component
		if((in_array($component, $handlerstats_component_names)) || $component == 'AllComponents'){
			//get handler stats
			$data['handler_stats'] = $this->Components_stats_model->get_handler_stats($server = 'AllServers', $component, $from_datetime, $now_datetime);
		}
		//if selected component is notif stats component
		if((in_array($component, $notifstats_component_names)) || $component == 'AllComponents'){
			//get notif stats
			$data['notif_stats'] = $this->Components_stats_model->get_notif_stats($server = 'AllServers', $from_datetime, $now_datetime);
		}
		//if selected component is smsgateway stats component
		if((in_array($component, $smsgatewaystats_component_names)) || $component == 'AllComponents'){
			//get smsgateway stats
			$data['smsgateway_stats'] = $this->Components_stats_model->get_smsgateway_stats($server = 'AllServers', $from_datetime, $now_datetime);
		}

		//---------- render page with data
		$this->render('components_stats/server_view', $data);
	}

	public function component_view() {
		$data = array();
		
		//---------- get filters
		if(!empty($_POST)){
			//latest filters
			$data['filters']['server'] = trim($this->input->post('server'));
			$server = $data['filters']['server'];

			//remove component filters
			//$data['filters']['component'] = trim($this->input->post('component'));
			//$component = $data['filters']['component'];

			$data['filters']['category'] = trim($this->input->post('category'));

			$data['filters']['duration'] = trim($this->input->post('duration'));
			$duration = $data['filters']['duration'] - 1;
			if($duration > 120) $duration = 120;
		}else{
			//default filters
			$data['filters']['server'] = 'AllServers';
			$server = $data['filters']['server'];

			//remove component filters
			//$data['filters']['component'] = 'AllComponents';
			//$component = $data['filters']['component'];

			$data['filters']['category'] = 'tpm';

			$data['filters']['duration'] = 5;
			$duration = $data['filters']['duration'] - 1;
		}
	
		//---------- required records
		//get servers
		$data['servers'] = $this->Components_stats_model->get_servers();//will use later to component view - servers dropdown
		//get components
		$data['components'] = $this->Components_stats_model->get_components();//will use later to component view - header, temp component values (tpm/success/fail/eventsinqueue)
		
		//---------- date format
		date_default_timezone_set('Asia/Manila');//set timezone

		$now_datetime = '2016-10-17 08:00:00.0';//now datetime//temp//glenn
		$data['now_datetime'] = $now_datetime;
		
		$strtotime_now_datetime = strtotime($now_datetime);//get from datetime by today datetime - minutes
		$subtracted_now_datetime = $strtotime_now_datetime+(60*-$duration);
		$data['from_datetime'] = date("Y-m-d H:i:s", $subtracted_now_datetime);
		$from_datetime = $data['from_datetime'];
		
		//---------- database queries - limit queries from gfxd
		//get handler stats
		$data['handler_stats'] = $this->Components_stats_model->get_handler_stats($server, $component = 'AllComponents',  $from_datetime, $now_datetime);
		//get notif stats
		$data['notif_stats'] = $this->Components_stats_model->get_notif_stats($server, $from_datetime, $now_datetime);
		//get smsgateway stats
		$data['smsgateway_stats'] = $this->Components_stats_model->get_smsgateway_stats($server, $from_datetime, $now_datetime);

		//---------- render page with data
		$this->render('components_stats/component_view', $data);
	}

	public function summary_view() {
		$data = array();
		
		//---------- get filters
		if(!empty($_POST)){
			//latest filters
			//remove server filters
			//$data['filters']['server'] = trim($this->input->post('server'));
			//$server = $data['filters']['server'];

			$data['filters']['component'] = trim($this->input->post('component'));
			$component = $data['filters']['component'];

			$data['filters']['category'] = trim($this->input->post('category'));

			$data['filters']['duration'] = trim($this->input->post('duration'));
			$duration = $data['filters']['duration'] - 1;
			if($duration > 120) $duration = 120;//limit duration
		}else{
			//default filters
			//remove server filters
			//$data['filters']['server'] = 'AllServers';
			//$server = $data['filters']['server'];

			$data['filters']['component'] = 'AllComponents';
			$component = $data['filters']['component'];

			$data['filters']['category'] = 'tpm';

			$data['filters']['duration'] = 5;
			$duration = $data['filters']['duration'] - 1;
			if($duration > 120) $duration = 120;//limit duration
		}
	
		//---------- required records
		//get servers
		$data['servers'] = $this->Components_stats_model->get_servers();//will use later to summary view - header, lookup - which server to deposit?
		//get components
		$data['components'] = $this->Components_stats_model->get_components();//will use later to summary view - lookup - which component to deposit?

		//get components - distinguished by table - will use later to limit querying
		$handlerstats_components = $this->Components_stats_model->get_components('handlerstats');
		$data['handlerstats_components'] = $handlerstats_components;

		$notifstats_components = $this->Components_stats_model->get_components('notifstats');
		$data['notifstats_components'] = $notifstats_components;

		$smsgatewaystats_components = $this->Components_stats_model->get_components('smsgatewaystats');
		$data['smsgatewaystats_components'] = $smsgatewaystats_components;
		
		//handler stats component names - avoid undefined offset
		if(!empty($handlerstats_components)){
			foreach ($handlerstats_components as $row_hsc) {
				$handlerstats_component_names[] = $row_hsc->component_name;
			}
		}

		//notif stats component names - avoid undefined offset
		if(!empty($notifstats_components)){
			foreach ($notifstats_components as $row_nsc) {
				$notifstats_component_names[] = $row_nsc->component_name;
			}
		}

		//smsgateway stats component names - avoid undefined offset
		if(!empty($smsgatewaystats_components)){
			foreach ($smsgatewaystats_components as $row_ssc) {
				$smsgatewaystats_component_names[] = $row_ssc->component_name;
			}
		}

		//---------- date format
		date_default_timezone_set('Asia/Manila');//set timezone

		$now_datetime = '2016-10-17 08:00:00.0';//now datetime//temp//glenn
		$data['now_datetime'] = $now_datetime;
		
		$strtotime_now_datetime = strtotime($now_datetime);//get <from datetime> by <today datetime> - <minutes>
		$subtracted_now_datetime = $strtotime_now_datetime+(60*-$duration);
		$data['from_datetime'] = date("Y-m-d H:i:s", $subtracted_now_datetime);
		$from_datetime = $data['from_datetime'];
		
		//---------- database queries - limit queries from gfxd
		//if selected component is handler stats component
		if((in_array($component, $handlerstats_component_names)) || $component == 'AllComponents'){
			//get handler stats
			$data['handler_stats'] = $this->Components_stats_model->get_handler_stats($server = 'AllServers', $component, $from_datetime, $now_datetime);
		}
		//if selected component is notif stats component
		if((in_array($component, $notifstats_component_names)) || $component == 'AllComponents'){
			//get notif stats
			$data['notif_stats'] = $this->Components_stats_model->get_notif_stats($server = 'AllServers', $from_datetime, $now_datetime);
		}
		//if selected component is smsgateway stats component
		if(in_array($component, $smsgatewaystats_component_names)){
			//get smsgateway stats
			$data['smsgateway_stats'] = $this->Components_stats_model->get_smsgateway_stats($server = 'AllServers', $from_datetime, $now_datetime);
		}

		//---------- render page with data
		if($component == 'AllComponents'){
			$this->render('components_stats/summary_view/all_components', $data);
		}else if($component == 'SMSGateway'){
			$data['instances'] = $this->Components_stats_model->get_smsgateway_instance($from_datetime, $now_datetime);

			$this->render('components_stats/summary_view/smsgateway', $data);
		}
		
	}
}

?>