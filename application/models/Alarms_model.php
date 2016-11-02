<?php

class Alarms_model extends MY_Model{
	public static $alarms = 'alarms';

	//FUNCTION: Get alarms count
	//MYSQL TABLE: servers
	public function get_alarms_count(){
		$this->db->from(self::$alarms);
		
		$this->db->where('is_read', 1);
		
		$query = $this->db->get();

		return $query->result();
	}

	//FUNCTION: Get latest alarms <x>
	//MYSQL TABLE: alarms
	public function get_latest_alarms(){
		$this->db->select('alarm_id, alarm_description, server, component, start_datetime, end_datetime, is_read');
		$this->db->from(self::$alarms);
		
		//$this->db->where('is_read', 1);
		$this->db->order_by('start_datetime', 'desc');
		$this->db->limit(5);

		$query = $this->db->get();

		return $query->result();		
	}

	//FUNCTION: Get alarms of date <x>
	//MYSQL TABLE: alarms
	public function get_alarms_stats_of_date($from_date, $to_date) {
		$this->db->select('alarm_id, alarm_description, server, component, start_datetime, end_datetime, is_read');
		$this->db->from(self::$alarms);
		
		$this->db->where('start_datetime >=', $from_date);//filter date
		$this->db->where('start_datetime <=', $to_date);//filter date
		
		$query = $this->db->get();

		return $query->result();
	}

	public function set_alarms_count_to_zero(){
		$this->db->update('alarms', array('is_read' => 0));
	}

}

?>

