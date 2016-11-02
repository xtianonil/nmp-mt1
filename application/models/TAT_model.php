<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class TAT_model extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
		}

		function get_server_view($selected_extract_date, $selected_component, $tcserver, $time_interval)
		{
			$sql = "SELECT * FROM tat_extract WHERE extract_date='" . $selected_extract_date . "' AND component_name LIKE '" . $selected_component ."' AND tcserver_name LIKE '". $tcserver ."' AND time_interval LIKE '" . $time_interval . "'";
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
		}//end of get_server_vie
		function get_component_view($selected_extract_date, $selected_tcserver, $component, $time_interval)
		{
			$sql = "SELECT * FROM tat_extract WHERE extract_date='" . $selected_extract_date . "' AND tcserver_name LIKE '" . $selected_tcserver ."' AND component_name LIKE '". $component ."' AND time_interval LIKE '" . $time_interval . "'";
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
		function get_summary_view($selected_extract_date, $tcserver, $component, $time_interval)
		{
			$sql = "SELECT * FROM tat_extract WHERE extract_date='" . $selected_extract_date . "' AND tcserver_name LIKE '" . $tcserver ."' AND component_name LIKE '". $component ."' AND time_interval LIKE '" . $time_interval . "'";
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

		function get_extract_date_list()
		{
			$sql = "SELECT DISTINCT extract_date FROM tat_extract ORDER BY extract_date";
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
		} //end of get_extract_date_dropdown

		function get_component_list()
		{
			$sql = "SELECT DISTINCT component_name FROM tat_extract";
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
		}//end of get_component_list

		function get_tcserver_list()
		{
			$sql = "SELECT DISTINCT tcserver_name FROM tat_extract ORDER BY tcserver_name";
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
		}//end of get_tcserver_list
		function is_empty($date)
		{
			$sql = "SELECT * FROM tat_extract WHERE extract_date='" . $date . "'";
			$q = $this->db->query($sql);

			return !($q -> num_rows() > 0);
		}
	}//end of class TAT_model
?>