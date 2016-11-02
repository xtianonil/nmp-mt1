<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class MY_Model extends CI_Model {
	
	public function get_row() {
		$result = array();
		foreach ($this->db->get()->row_array() as $key=>$val) {
			$result[strtolower($key)] = $val;
		}
		return $result;
	}

	public function get_rows() {
		$result = array();
		// echo $this->db->get(); echo $this->db->last_query(); die;
		foreach ($this->db->get()->result_array() as $i=>$row) {
			foreach ($row as $key=>$val) {
				$result[$i][strtolower($key)] = $val;
			}
		}
		return $result;
	}

	
	public function get_user_ip(){
	    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip=$_SERVER['HTTP_CLIENT_IP'];
	    }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){//to check ip is pass from proxy
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	    }else{
			$ip=$_SERVER['REMOTE_ADDR'];
	    }
	    return $ip;
	}

}

?>
