<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class TPM_24hour_model extends CI_Model
	{
		public function __construct()
	    {
	        parent::__construct();
	    }

		function get_server_view($selected_extract_date, $selected_component,$tcserver, $hour)
		{
			$sql = "SELECT * FROM tpm_24hour WHERE extract_date='" . $selected_extract_date . "' AND component_name LIKE '" . $selected_component ."' AND tcserver_name LIKE '". $tcserver ."' AND tpm_hour LIKE '" . $hour . "' ";
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

		function get_component_view($selected_extract_date, $selected_tcserver,$component, $hour)
		{
			$sql = "SELECT * FROM tpm_24hour WHERE extract_date='" . $selected_extract_date . "' AND tcserver_name LIKE '" . $selected_tcserver ."' AND component_name LIKE '" . $component ."' AND tpm_hour LIKE '". $hour ."' ";
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

		function get_summary_view($selected_extract_date, $tcserver,$component, $hour)
		{
			$sql = "SELECT * FROM tpm_24hour WHERE extract_date='" . $selected_extract_date . "' AND tcserver_name LIKE '" . $tcserver ."' AND component_name LIKE '" . $component ."' AND tpm_hour LIKE '". $hour ."' ";
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

		function get_extract_date_list()
		{
			$sql = "SELECT DISTINCT extract_date FROM tpm_24hour ORDER BY extract_date";
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
			$sql = "SELECT DISTINCT component_name FROM tpm_24hour";
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
			$sql = "SELECT DISTINCT tcserver_name FROM tpm_24hour ORDER BY tcserver_name";
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

		function get_hour_list()
		{
			$sql = "SELECT DISTINCT tpm_hour FROM tpm_24hour ORDER BY tpm_hour";
			$q = $this->db->query($sql);

			if ($q -> num_rows() > 0)
			{
				foreach ($q -> result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
		}//end of get_hours_list

		function is_empty($date)
		{
			$sql = "SELECT * FROM tpm_24hour WHERE extract_date='" . $date . "'";
			$q = $this->db->query($sql);

			return !($q -> num_rows() > 0);
		}
	}//end of classTPM_24hour model
?>