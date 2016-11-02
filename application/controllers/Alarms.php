<?php

class Alarms extends MY_Controller {
	
	public function __construct() {
		$this->modulename = 'Alarms';

		//to invoke CI_Controller functions
		parent::__construct();
		
		//Required to Load
		$this->load->model('Alarms_model');
	}

	public function index(){
		if(!empty($_POST)){
			$data['filters']['alarms_stats_date'] = trim($this->input->post('alarms_stats_date'));
			$from_date = $data['filters']['alarms_stats_date'];
		}else{
			$data['filters']['alarms_stats_date'] = '2016-10-24';//temp//glenn
			$from_date = $data['filters']['alarms_stats_date'];
		}

		//---------- date format
		date_default_timezone_set('Asia/Manila');//set timezone

		$to_date = date('Y-m-d', strtotime($from_date . " +1 days"));

		$data['alarms_stats_of_date'] = $this->Alarms_model->get_alarms_stats_of_date($from_date, $to_date);

		$this->render('alarms/index', $data);		
	}
	
	public function get_alarms_count(){
		$data['alarms'] = $this->Alarms_model->get_alarms_count();
		$alarms = $data['alarms'];

		echo count($alarms);
	}

	public function get_latest_alarms(){
		$data['latest_alarms'] = $this->Alarms_model->get_latest_alarms();

		$this->load->view('alarms/latest_alarms', $data);
	}

	public function set_alarms_count_to_zero(){
		$this->Alarms_model->set_alarms_count_to_zero();
	}


}

?>