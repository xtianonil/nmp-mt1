<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Dashboard_model extends CI_Model
	{
		public function __construct()
	    {
	        parent::__construct();
	    }

	    function get_dashboard_date_list()
		{
			$sql = "SELECT DISTINCT dashboard_date FROM dashboard ORDER BY dashboard_date";
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
		}
	    function get_dashboard_component_list()
	    {
	    	$sql = "SELECT * FROM dashboard_component";
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
	    }
	    function get_dashboard_column_list()
	    {
	    	$sql = "SELECT * FROM dashboard_column";
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
	    }
	    function get_dashboard_view($dashboard_date, $column_id, $component_id)
		{
			$sql = "SELECT * FROM dashboard,dashboard_column,dashboard_component 
			WHERE dashboard.column_id=dashboard_column.column_id AND 
				  dashboard.component_id=dashboard_component.component_id AND 
				  dashboard.dashboard_date='" . $dashboard_date . "' AND 
				  dashboard.column_id=" . $column_id . " AND 
				  dashboard.component_id=" . $component_id;
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
		}

	    function get_subscriptions_brands_list()
	    {
	    	$sql = "SELECT * FROM pnp_subscriptions_brands";
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
	    }
	    function get_subscriptions_columns_list()
	    {
	    	$sql = "SELECT * FROM pnp_subscriptions_columns";
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
	    }
	    function get_subscriptions($date, $brand_id, $column_id)
	    {
	    	$sql = "SELECT * FROM pnp_subscriptions,pnp_subscriptions_brands,pnp_subscriptions_columns WHERE 
	    			pnp_subscriptions.brand_id=pnp_subscriptions_brands.brand_id AND 
	    			pnp_subscriptions.column_id=pnp_subscriptions_columns.column_id AND 
	    			subscription_date='" . $date . "' AND 
	    			pnp_subscriptions.brand_id=" . $brand_id . " AND 
	    			pnp_subscriptions.column_id=" . $column_id;
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
	    }

	    function get_server_resource_util_servers_list()
	    {
	    	$sql = "SELECT * FROM server_resource_util_servers";
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
	    }
	    function get_server_resource_util_columns_list()
	    {
	    	$sql = "SELECT * FROM server_resource_util_columns";
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
	    }
	    function get_server_resource_util($date, $server_id, $column_id)
	    {
	    	$sql = "SELECT * FROM server_resource_util,server_resource_util_servers,server_resource_util_columns WHERE 
	    			server_resource_util.server_id=server_resource_util_servers.server_id AND 
	    			server_resource_util.column_id=server_resource_util_columns.column_id AND 
	    			sru_date='" . $date . "' AND 
	    			server_resource_util_servers.server_id=" . $server_id . " AND 
	    			server_resource_util_columns.column_id=" . $column_id;
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
	    }
	    function is_empty($date)
		{
			$sql = "SELECT * FROM dashboard WHERE dashboard_date='" . $date . "'";
			$q = $this->db->query($sql);

			return !($q -> num_rows() > 0);
		}
	}
?>