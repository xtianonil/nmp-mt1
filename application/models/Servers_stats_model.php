<?php

class Servers_stats_model extends MY_Model{
	public static $servers = 'servers';
	public static $servers_stats = 'servers_stats';

	//FUNCTION: Get servers name <x>
	//MYSQL TABLE: servers
	public function get_servers(){
		$this->db->select('server_type, server_name');
		$this->db->from(self::$servers);
	
		$this->db->order_by('seqno');

		$query = $this->db->get();

		return $query->result();
	}

	//FUNCTION: Get servers_stats of date <x>
	//MYSQL TABLE: servers stats
	public function get_servers_stats_of_date($servers_stats_date, $to_date, $server_name) {

		$this->db->select('servers_stats_datetime, server_name, idle, mem_used, buff_used, buff_free, swap_used');
		$this->db->from(self::$servers_stats);
		$this->db->where('servers_stats_datetime >', $servers_stats_date);//filter date
		$this->db->where('servers_stats_datetime <', $to_date);//filter date
		$this->db->where('server_name', $server_name);//filter name
		
		$query = $this->db->get();

		return $query->result();
	}



	//select serverid,handler,success,fail,logtime,eventsinqueue from pnp_handlerstats where logtime  > '2016-09-30 09:30:00' order by logtime;
	//select serverid,handler,success,fail,logtime,eventsinqueue from pnp_handlerstats where logtime  > '2016-09-30 09:31:00' and varchar(handler) in ('ADCHandlerBuddy') order by logtime;
}

?>

