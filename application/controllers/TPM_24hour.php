<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class TPM_24hour extends MY_Controller{

		public function __construct()
	    {
	    	$this->modulename = '24-hour TPM';
			$this->moduleid = 3;

	        parent::__construct();
	        $this->load->model('TPM_24hour_model');

	    }

	    public function index()
	    {
	        $_POST['button_route'] = 'server_view';				//default button_route is filter
	        //$_POST['extract_date'] = '2016-09-20';
	        $_POST['extract_date'] = $this->get_date_yesterday();			//change to date yesterday
	        $_POST['component_list'] = 'ServiceEventHandler';	//defaukt selection
	        $_POST['tcserver_list'] = 'TCSERVER01';
	        $this->filter();
	    }

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
				$this->display_server_view();
			}
			else if ($this->input->post('button_route') == 'component_view')
			{
				$_POST['tcserver_list'] = 'TCSERVER01';	//defaukt selection
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

			//$data['extract_date_list']	= $this->TPM_24hour_model->get_extract_date_list();
			$data['component_list']		= $this->TPM_24hour_model->get_component_list();
			$data['tcserver_list']		= $this->TPM_24hour_model->get_tcserver_list();

			$data['selected_extract_date'] 	= $this->input->post('extract_date');
			$data['selected_component']		= $this->input->post('component_list');

			$data['is_empty'] = $this->TPM_24hour_model->is_empty($data['selected_extract_date']);
			
			if (is_array($data['tcserver_list']))
			{
				foreach ($data['tcserver_list'] as $tcserver)
				{
					for ($i=0; $i<=23; $i++)
					{
						if ($i <= 9)
							$hour = '0' . $i . ':00';
						else
							$hour = $i . ':00';
						$data['rows'][$hour][$tcserver->tcserver_name] = $this->TPM_24hour_model->get_server_view($data['selected_extract_date'], $data['selected_component'], $tcserver->tcserver_name, $hour);
					}//end of for
				}//end of foreach
			}//end of if
			$this->render('tpm_24hour/server_view', $data);
		}	//end of display server_view

		public function display_component_view()
		{
			//$data['extract_date_list']	= $this->TPM_24hour_model->get_extract_date_list();
			$data['component_list']		= $this->TPM_24hour_model->get_component_list();
			$data['tcserver_list']		= $this->TPM_24hour_model->get_tcserver_list();

			$data['selected_extract_date'] 	= $this->input->post('extract_date');
			$data['selected_tcserver']		= $this->input->post('tcserver_list');
		
			$data['is_empty'] = $this->TPM_24hour_model->is_empty($data['selected_extract_date']);
			
			if (is_array($data['component_list']))
			{
				foreach ($data['component_list'] as $component)
				{
					//$components[] = $component->component_name;
					for ($i=0; $i<=23; $i++)
					{
						if ($i <= 9)
							$hour = '0' . $i . ':00';
						else
							$hour = $i . ':00';
						$data['rows'][$hour][$component->component_name] = $this->TPM_24hour_model->get_component_view($data['selected_extract_date'], $data['selected_tcserver'], $component->component_name, $hour);
					}//end of for
				}//end of foreach
			}
			$this->render('tpm_24hour/component_view', $data);
		}	//end of display component_view

		public function display_summary_view()
		{
			$data['component_list']		= $this->TPM_24hour_model->get_component_list();
			$data['tcserver_list']		= $this->TPM_24hour_model->get_tcserver_list();

			$data['selected_extract_date'] 	= $this->input->post('extract_date');
			$data['selected_tcserver']		= $this->input->post('tcserver_list');
		
			$data['is_empty'] = $this->TPM_24hour_model->is_empty($data['selected_extract_date']);
			
			if (is_array($data['tcserver_list']))
			{
				foreach ($data['tcserver_list'] as $tcserver)
				{
					if (is_array($data['component_list']))
					{
						foreach ($data['component_list'] as $component)
						{
							//$components[] = $component->component_name;
							for ($i=0; $i<=23; $i++)
							{
								if ($i <= 9)
									$hour = '0' . $i . ':00';
								else
									$hour = $i . ':00';
								$data['rows'][$hour][$tcserver->tcserver_name][$component->component_name] = $this->TPM_24hour_model->get_summary_view($data['selected_extract_date'], $tcserver->tcserver_name, $component->component_name, $hour);
							}//end of for
						}//end of foreach
					}
				}
			}
			
			$this->render('tpm_24hour/summary_view', $data);
		}

	}//end of TPM_24hour controller
?>