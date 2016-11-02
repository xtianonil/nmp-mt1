<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class TAT extends MY_Controller{

		public function __construct()
		{
			$this->modulename = 'TAT';
			$this->moduleid = 4;

			parent::__construct();
			$this->load->model('TAT_model');
		}

		public function index()
		{
			//$_POST['extract_date_list'] = '2016-08-15';	//change to date yesterday
			//$_POST['component_list'] = 'ServiceEventHandler';
			//$this->display_server_view();

			$_POST['button_route'] = 'server_view';				//default button_route is filter
	        $_POST['extract_date'] = $this->get_date_yesterday();			//change to date yesterday

	        //$_POST['extract_date'] = '2016-09-20';				//change to date yesterday
	        $_POST['component_list'] = 'ServiceEventHandler';	//defaukt selection
	        //$_POST['tcserver_list'] = 'TCSERVER01';
	        $this->filter();
		}//end of function index

		public function filter()
		{
			//$this->get_date_yesterday();
			//$data['selected_extract_date'] = '2016-08-15';	//change to date yesterday

			if ($this->input->post('button_route') == 'filter_from_server_view')
				$this->display_server_view();
			else if ($this->input->post('button_route') == 'filter_from_component_view')
				$this->display_component_view();
			else if ($this->input->post('button_route') == 'filter_from_summary_view')
				$this->display_summary_view();

			else if ($this->input->post('button_route') == 'server_view')
			{
				$_POST['component_list'] = 'ServiceEventHandler';	//defaukt selection
				//$data['selected_component']		= $this->input->post('component_list');
				$this->display_server_view();
			}
			else if ($this->input->post('button_route') == 'component_view')
			{
				$_POST['tcserver_list'] = 'TCSERVER01';	//defaukt selection
				//$data['selected_tcserver']		= $this->input->post('tcserver_list');
				$this->display_component_view();
			}
			else if ($this->input->post('button_route') == 'summary_view')
			{
				//$_POST['tcserver_list'] = 'TCSERVER01';	//defaukt selection
				$this->display_summary_view();
			}

		}//end of public function index

		public function display_server_view()
		{
			//$data['extract_date_list']	= $this->TAT_model->get_extract_date_list();
			$data['component_list']		= $this->TAT_model->get_component_list();
			$data['tcserver_list']		= $this->TAT_model->get_tcserver_list();

			$data['selected_extract_date'] 	= $this->input->post('extract_date');
			$data['selected_component']		= $this->input->post('component_list');

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

			$data['is_empty'] = $this->TAT_model->is_empty($data['selected_extract_date']);
			foreach ($time as $time_interval)
			{
				if (is_array($data['tcserver_list']))
				{
					foreach ($data['tcserver_list'] as $tcserver)
					{
						$data['rows'][$time_interval][$tcserver->tcserver_name] = $this->TAT_model->get_server_view($data['selected_extract_date'], $data['selected_component'], $tcserver->tcserver_name, $time_interval);
						//$data['rows'][$time_interval][$tcserver->tcserver_name]['percentage'] = $this->TAT_model->get_server_view($data['selected_extract_date'], $data['selected_component'], $tcserver->tcserver_name, $time_interval,'percentage');
					}//end of foreach tcserver_list
				}
			}//end of foreach time

			$this->render('tat/server_view', $data);
		}//end of display_server_view

		public function display_component_view()
		{
			//$data['extract_date_list']	= $this->TAT_model->get_extract_date_list();
			$data['component_list']		= $this->TAT_model->get_component_list();
			$data['tcserver_list']		= $this->TAT_model->get_tcserver_list();

			$data['selected_extract_date'] 	= $this->input->post('extract_date');
			$data['selected_tcserver']		= $this->input->post('tcserver_list');

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

			$data['is_empty'] = $this->TAT_model->is_empty($data['selected_extract_date']);
			foreach ($time as $time_interval)
			{
				if (is_array($data['component_list']))
				{
					foreach ($data['component_list'] as $component)
					{
						$data['rows'][$time_interval][$component->component_name] = $this->TAT_model->get_component_view($data['selected_extract_date'], $data['selected_tcserver'], $component->component_name, $time_interval);
						//$data['rows'][$time_interval][$component->component_name]['percentage'] = $this->TAT_model->get_component_view($data['selected_extract_date'], $data['selected_tcserver'], $component->component_name, $time_interval,'percentage');
					}//end of foreach tcserver_list
				}
			}//end of foreach time

			$this->render('tat/component_view', $data);
		}//end of display_component_view

		public function display_summary_view()
		{
			$data['component_list']		= $this->TAT_model->get_component_list();
			$data['tcserver_list']		= $this->TAT_model->get_tcserver_list();

			$data['selected_extract_date'] 	= $this->input->post('extract_date');
			//$data['selected_tcserver']		= $this->input->post('tcserver_list');
		
			$data['is_empty'] = $this->TAT_model->is_empty($data['selected_extract_date']);
			
			if (is_array($data['tcserver_list']))
			{
				foreach ($data['tcserver_list'] as $tcserver)
				{
					if (is_array($data['component_list']))
					{
						foreach ($data['component_list'] as $component)
						{
							//$components[] = $component->component_name;
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
								$data['rows'][$time_interval][$tcserver->tcserver_name][$component->component_name] = $this->TAT_model->get_summary_view($data['selected_extract_date'], $tcserver->tcserver_name, $component->component_name, $time_interval);
							}//end of foreach
						}//end of foreach
					}
				}
			}
			
			$this->render('tat/summary_view', $data);
		}
	}//end of class TAT_controller
?>