<?php

class Servers_stats extends MY_Controller {
	
	public function __construct() {
		$this->modulename = 'Servers Stats';
		$this->moduleid = 4;

		//to invoke CI_Controller functions
		parent::__construct();
		
		//Required to Load
		$this->load->model('Servers_stats_model');
		$this->load->helper('ui');
	}

	public function index(){
		$this->table_view();
	}
	
	public function table_view() {
		$data = array();
		
		//---------- get filters
		if(!empty($_POST)){
			//latest filters
			//$data['filters']['date'] = trim($this->input->post('date'));
			$data['filters']['servers_stats_date'] = trim($this->input->post('servers_stats_date'));
			$servers_stats_date = $data['filters']['servers_stats_date'];

			//$data['filters']['server_type'] = trim($this->input->post('server_type'));
			//$server_type = $data['filters']['server_type'];

			$data['filters']['server_name'] = trim($this->input->post('server_name'));
			$server_name = $data['filters']['server_name'];
		}else{
			//Default filters
			$data['filters']['servers_stats_date'] = '2016-10-11';//temp//glenn//must be yesterday date
			$servers_stats_date = $data['filters']['servers_stats_date'];

			//$data['filters']['server_type'] = 'ETL'
			//$server_type = $data['filters']['server_type'];

			$data['filters']['server_name'] = 'ETL1';
			$server_name = $data['filters']['server_name'];
		}

		//---------- required records
		//get servers of type
		$data['server_names'] = $this->Servers_stats_model->get_servers();//for servers dropdown
		
		//$data['server_types'] = $this->Servers_stats_model->get_server_types_temp();//for server types dropdown

		//---------- date format
		date_default_timezone_set('Asia/Manila');//set timezone

		$to_date = $servers_stats_date;//selected date
		$formatted_to_date = str_replace('-', '/', $to_date);
		$to_date = date('Y-m-d',strtotime($formatted_to_date . "+1 days"));

		//---------- database queries
		$data['servers_stats_of_date'] = $this->Servers_stats_model->get_servers_stats_of_date($servers_stats_date, $to_date, $server_name);
		
		//---------- render page with data
		$this->render('servers_stats/table_view', $data);
		
	}

	public function graph_view() {
		$data = array();
		
		//---------- get filters
		if(!empty($_POST)){
			//latest filters
			//$data['filters']['date'] = trim($this->input->post('date'));
			$data['filters']['servers_stats_date'] = trim($this->input->post('servers_stats_date'));
			$servers_stats_date = $data['filters']['servers_stats_date'];

			//$data['filters']['server_type'] = trim($this->input->post('server_type'));
			//$server_type = $data['filters']['server_type'];

			$data['filters']['server_name'] = trim($this->input->post('server_name'));
			$server_name = $data['filters']['server_name'];

			$data['filters']['util'] = trim($this->input->post('util'));
			$util = $data['filters']['util'];
		}else{
			//Default filters
			$data['filters']['servers_stats_date'] = '2016-10-11';//temp//glenn//must be yesterday date
			$servers_stats_date = $data['filters']['servers_stats_date'];

			//$data['filters']['server_type'] = 'ETL'
			//$server_type = $data['filters']['server_type'];

			$data['filters']['server_name'] = 'ETL1';
			$server_name = $data['filters']['server_name'];
			
			$data['filters']['util'] = 'idle';
			$util = $data['filters']['util'];
		}
		
		//---------- date format
		date_default_timezone_set('Asia/Manila');//set timezone

		$to_date = $servers_stats_date;//selected date
		$formatted_to_date = str_replace('-', '/', $to_date);
		$to_date = date('Y-m-d',strtotime($formatted_to_date . "+1 days"));

		//---------- required records
		//get servers of type
		$data['server_names'] = $this->Servers_stats_model->get_servers();//for servers dropdown
		
		//$data['server_types'] = $this->Servers_stats_model->get_server_types_temp();//for server types dropdown

		///---------- database queries
		$data['servers_stats_of_date'] = $this->Servers_stats_model->get_servers_stats_of_date($servers_stats_date, $to_date, $server_name);
		
		//---------- render page with data
		$this->render('servers_stats/graph_view', $data);
		
	}

	public function get_graphdata(){
		
		//---------- get filters
		if(!empty($_POST)){
			//latest filters
			//$data['filters']['date'] = trim($this->input->post('date'));
			$data['filters']['servers_stats_datetime'] = trim($this->input->post('servers_stats_date'));
			$current_datetime = $data['filters']['servers_stats_datetime'];

			/*$data['filters']['server_type'] = trim($this->input->post('server_type'));
			$server_type = $data['filters']['server_type'];*/

			$data['filters']['server_name'] = trim($this->input->post('server_name'));
			$server_name = $data['filters']['server_name'];

			$data['filters']['util'] = trim($this->input->post('util'));
			$util = $data['filters']['util'];
		}else{
			//Default filters
			$data['filters']['servers_stats_datetime'] = '2016-10-11';//temp//glenn//must be yesterday date
			$current_datetime = $data['filters']['servers_stats_datetime'];

			/*$data['filters']['server_type'] = 'ETL';
			$server_type = $data['filters']['server_type'];*/

			$data['filters']['server_name'] = 'ETL2';
			$server_name = $data['filters']['server_name'];

			$data['filters']['util'] = 'mem_used';
			$util = $data['filters']['util'];
		}
		
		//---------- date format
		date_default_timezone_set('Asia/Manila');//set timezone

		$to_date = $current_datetime;//selected date
		$formatted_to_date = str_replace('-', '/', $to_date);
		$to_date = date('Y-m-d',strtotime($formatted_to_date . "+1 days"));

		//---------- required records
		//get servers of type
		$data['server_names'] = $this->Servers_stats_model->get_servers();//for servers dropdown
		
		//$data['server_types'] = $this->Servers_stats_model->get_server_types_temp();//for server types dropdown

		//get server stats of date
		$data['servers_stats_of_date'] = $this->Servers_stats_model->get_servers_stats_of_date($current_datetime, $to_date, $server_name);
		$servers_stats_of_date = $data['servers_stats_of_date'];

		//initialize idle, mem used, buff used, buff free, swap used
		//<x> no. of durations loop
		for ($a = 1; $a <= 24; $a++) {
			//<x> no. of server/s loop
			$count_s = 1;
			//foreach ($servers as $row_s) {
				$data_s_v[$a][$count_s][3] = 0;
				$data_s_v[$a][$count_s][4] = 0;
				$data_s_v[$a][$count_s][5] = 0;
				$data_s_v[$a][$count_s][6] = 0;
				$data_s_v[$a][$count_s][7] = 0;
				
			//	$count_s++;
			//}
		}

		//<x> no. of durations loop - to know which servers to deposit handler stats
		for($b = 1; $b <= 24; $b++){	
			//echo '<br>' . $current_datetime . '<br>';

			//<x> no. of servers stats rec
			$count_ssod = 1;
			foreach ($servers_stats_of_date as $row_ssod) {
				$servers_stats_datetime = $row_ssod->servers_stats_datetime;
			    $server_name = $row_ssod->server_name;
			    $idle = $row_ssod->idle;
			    $mem_used = $row_ssod->mem_used;
			    $buff_used = $row_ssod->buff_used;
			    $buff_free = $row_ssod->buff_free;
			    $swap_used = $row_ssod->swap_used;
			    //put to 2D array
			    $data_ssod[$count_ssod][1] = $servers_stats_datetime;
			    $data_ssod[$count_ssod][2] = $server_name;
			    $data_ssod[$count_ssod][3] = $idle;
			    $data_ssod[$count_ssod][4] = $mem_used;
			    $data_ssod[$count_ssod][5] = $buff_used;
			    $data_ssod[$count_ssod][6] = $buff_free;
			    $data_ssod[$count_ssod][7] = $swap_used;

				//if current datetime is equal to servers stats logtime
				if($current_datetime == $data_ssod[$count_ssod][1]){
				$temp[] = array('v' => $current_datetime);
					//<x> no. of server/s loop - to know which servers to deposit handler stats
					$count_s = 1;
					//foreach ($servers as $row_s) {
						//$server_name = $row_s->server_name;
						//put to 2D array
						//$data_s[$count_s][2] = $server_name;
						//if servers server name is equal to servers stats of date server name
						//if($data_s[$count_s][2] == $data_ssod[$count_ssod][2]){
							//echo $row_s->server_name;
		
							//what to deposit?
							switch ($util) {
								case 'idle':
									$data_s_v[$b][$count_s][3] = $data_ssod[$count_ssod][3];//deposit success + fail
									//echo ' ' . $data_s_v[$b][$count_s][3] . ' ';
									break;
								case 'memused':
									$data_s_v[$b][$count_s][4] = $data_ssod[$count_ssod][4];//deposit success only
									//echo ' ' . $data_s_v[$b][$count_s][4] . ' ';
									break;
								case 'buffused':
									$data_s_v[$b][$count_s][5] = $data_ssod[$count_ssod][5];//deposit fail only
									//echo ' ' . $data_s_v[$b][$count_s][5] . ' ';
									break;
								case 'buffree':
									$data_s_v[$b][$count_s][6] = $data_ssod[$count_ssod][6];//deposit eventsinqueue only
									//echo ' ' . $data_s_v[$b][$count_s][6] . ' ';
									break;
								case 'swapused':
									$data_s_v[$b][$count_s][7] = $data_ssod[$count_ssod][7];//deposit eventsinqueue only
									//echo ' ' . $data_s_v[$b][$count_s][7] . ' ';
									break;
								default:
									$data_s_v[$b][$count_s][3] = $data_ssod[$count_ssod][3];//deposit success + fail
									//echo ' ' . $data_s_v[$b][$count_s][3] . ' ';
									break;
							}
						//}

					//	$count_s++;
					//}//end - <x> no. of server/s loop

				}//end - //if current datetime is equal to servers stats logtime
				
				$count_ssod++;
			}//end - <x> no. of servers stats rec

			date_default_timezone_set('Asia/Manila');//set timezone

			//update current datetime
			$strtotime_current_datetime = strtotime($current_datetime);//current datetime - 1 minute
			$added_current_datetime = $strtotime_current_datetime+(60*+60);
			$current_datetime = date("Y-m-d H:i:s", $added_current_datetime);//glenn	
		
		}//end - <x> no. of durations loop

		//initialize server names
		//<x> no. of server/s loop
		/*$count_s = 1;
		foreach ($servers as $row_s) {
			$server_name = $row_s->server_name;

			$data_s[$count_s][1] = $server_name;

			$count_s++;
		}*/
		
		//<x> no. of servers loop
		//for ($d = 1; $d <= count($servers); $d++) { 
		//	if($d == 1){
				$cols[] = array('label' => 'datetime', 'type' => 'string');
		//	}
			
			$cols[] = array('label' => $server_name, 'type' => 'number');
		//}

		//populate table cols
		$table['cols'] = $cols;

		$current_datetime = $data['filters']['servers_stats_datetime'];//original current datetime

		//<x> no. of durations loop
		for ($e = 1; $e <= 24; $e++) {
			$temp = array();

			date_default_timezone_set('Asia/Manila');//set timezone
			$current_datetime_Hi = strtotime($current_datetime);
			$current_datetime_Hi = date("H:i", $current_datetime_Hi);

			$temp[] = array('v' => $current_datetime_Hi);
			//<x> no. of server/s loop
			//for ($f = 1; $f <= count($servers); $f++) {
				//what to deposit?
			$f = 1;
				switch ($util) {
					case 'idle':
						$idle = $data_s_v[$e][$f][3];
						$temp[] = array('v' => $idle);
						break;
					case 'memused':
						$memused = $data_s_v[$e][$f][4];
						$temp[] = array('v' => $memused);
						break;
					case 'buffused':
						$buffused = $data_s_v[$e][$f][5];
						$temp[] = array('v' => $buffused);
						break;
					case 'bufffree':
						$bufffree= $data_s_v[$e][$f][6];
						$temp[] = array('v' => $bufffree);
						break;
					case 'swapused':
						$swapused = $data_s_v[$e][$f][7];
						$temp[] = array('v' => $swapused);
						break;
					default:
						$idle = $data_s_v[$e][$f][3];
						$temp[] = array('v' => $idle);
						break;
				}

			//}

			if($e != 1) $rows[] = array('c' => $temp);

			//update current datetime
			$strtotime_current_datetime = strtotime($current_datetime);//current datetime - 1 minute
			$added_current_datetime = $strtotime_current_datetime+(60*+60);
			$current_datetime = date("Y-m-d H:i:s", $added_current_datetime);//glenn
		}

		// populate table rows
		$table['rows'] = $rows;

		// encode the table as JSON
		$jsonTable = json_encode($table);

		// set up header; first two prevent IE from caching queries
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');

		// return the JSON data
		echo $jsonTable;


	}//end - function get_graphdata_temp


}

?>