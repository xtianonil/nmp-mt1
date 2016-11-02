<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Dashboard extends MY_Controller{

		public function __construct()
	    {
	    	$this->modulename = 'Performance Dashboard';
			$this->moduleid = 1;

	        parent::__construct();
	        $this->load->model('Dashboard_model');
	    }

		public function index()
		{
			$_POST['dashboard_date_input'] = $this->get_date_yesterday();
			$this->display_dashboard_view();		
		}//end of public function index

		public function display_dashboard_view()
		{
			//$data['dashboard_date_list']		= $this->Dashboard_model->get_dashboard_date_list();
			$data['dashboard_component_list']	= $this->Dashboard_model->get_dashboard_component_list();
			$data['dashboard_column_list']		= $this->Dashboard_model->get_dashboard_column_list();

			$data['subscriptions_brands_list']	= $this->Dashboard_model->get_subscriptions_brands_list();
			$data['subscriptions_columns_list']	= $this->Dashboard_model->get_subscriptions_columns_list();

			$data['server_resource_util_servers_list'] 	= $this->Dashboard_model->get_server_resource_util_servers_list();
			$data['server_resource_util_columns_list']	= $this->Dashboard_model->get_server_resource_util_columns_list();

			$data['selected_dashboard_date'] 	= $this->input->post('dashboard_date_input');

			$data['no_results']							= $this->Dashboard_model->is_empty($data['selected_dashboard_date']);

			if (is_array($data['dashboard_component_list']))
			{
				foreach ($data['dashboard_component_list'] as $component)
				{
					if (is_array($data['dashboard_column_list']))
					{
						foreach ($data['dashboard_column_list'] as $column)
						{
							$data['rows'][$component->component_name][$column->column_name] = $this->Dashboard_model->get_dashboard_view($data['selected_dashboard_date'],$column->column_id,$component->component_id);
						}
					}
				}
			}
			if (is_array($data['subscriptions_brands_list']))
			{
				foreach ($data['subscriptions_brands_list'] as $brand)
				{
					if (is_array($data['subscriptions_columns_list']))
					{
						foreach ($data['subscriptions_columns_list'] as $column)
						{
							$data['rows'][$brand->brand_name][$column->column_name] = $this->Dashboard_model->get_subscriptions($data['selected_dashboard_date'],$brand->brand_id,$column->column_id);
						}
					}
				}
			}
			if (is_array($data['server_resource_util_servers_list']))
			{
				foreach ($data['server_resource_util_servers_list'] as $server)
				{
					if (is_array($data['server_resource_util_columns_list']))
					{
						foreach ($data['server_resource_util_columns_list'] as $column)
						{
							$data['rows'][$server->server_name][$column->column_name] = $this->Dashboard_model->get_server_resource_util($data['selected_dashboard_date'],$server->server_id,$column->column_id);
						}
					}
				}
			}

			$this->render('dashboard/dashboard', $data);
		}

		public function add_dashboard_record()
		{
			$data['dashboard_component_list']	= $this->Dashboard_model->get_dashboard_component_list();
			$data['dashboard_column_list']		= $this->Dashboard_model->get_dashboard_column_list();

			$data['subscriptions_brands_list']	= $this->Dashboard_model->get_subscriptions_brands_list();
			$data['subscriptions_columns_list']	= $this->Dashboard_model->get_subscriptions_columns_list();

			$data['server_resource_util_servers_list'] 	= $this->Dashboard_model->get_server_resource_util_servers_list();
			$data['server_resource_util_columns_list']	= $this->Dashboard_model->get_server_resource_util_columns_list();

			$data['selected_dashboard_date'] 	= $this->input->post('dashboard_date_input');

			$data['no_results']							= $this->Dashboard_model->is_empty($data['selected_dashboard_date']);

			if (is_array($data['dashboard_component_list']))
			{
				foreach ($data['dashboard_component_list'] as $component)
				{
					if (is_array($data['dashboard_column_list']))
					{
						foreach ($data['dashboard_column_list'] as $column)
						{
							$data['rows'][$component->component_name][$column->column_name] = $this->Dashboard_model->get_dashboard_view($data['selected_dashboard_date'],$column->column_id,$component->component_id);
						}
					}
				}
			}
			if (is_array($data['subscriptions_brands_list']))
			{
				foreach ($data['subscriptions_brands_list'] as $brand)
				{
					if (is_array($data['subscriptions_columns_list']))
					{
						foreach ($data['subscriptions_columns_list'] as $column)
						{
							$data['rows'][$brand->brand_name][$column->column_name] = $this->Dashboard_model->get_subscriptions($data['selected_dashboard_date'],$brand->brand_id,$column->column_id);
						}
					}
				}
			}
			if (is_array($data['server_resource_util_servers_list']))
			{
				foreach ($data['server_resource_util_servers_list'] as $server)
				{
					if (is_array($data['server_resource_util_columns_list']))
					{
						foreach ($data['server_resource_util_columns_list'] as $column)
						{
							$data['rows'][$server->server_name][$column->column_name] = $this->Dashboard_model->get_server_resource_util($data['selected_dashboard_date'],$server->server_id,$column->column_id);
						}
					}
				}
			}

			$this->render('dashboard/add_dashboard_record', $data);
		}
	}//end of Dashboard_controller