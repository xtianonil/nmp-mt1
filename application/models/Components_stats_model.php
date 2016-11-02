<?php

class Components_stats_model extends MY_Model{
	public static $servers = 'servers';
	public static $components = 'components';
	public static $pnp_handlerstats = 'PNP_HANDLERSTATS';
	public static $pnp_notif_stats = 'PNP_NOTIF_STATS';
	public static $pnp_smsgateway_stats = 'PNP_SMSGATEWAY_STATS';

	
	//FUNCTION: Get Handler Stats by filters
	//GFXD TABLE: PNP_HANDLERSTATS
	public function get_handler_stats($server, $component, $from_datetime, $now_datetime) {
		$GFXD_DB = $this->load->database('gfxd', true);

		//select columns
		$GFXD_DB->select('LOGTIME, SERVERID, HANDLER, SUCCESS, FAIL, EVENTSINQUEUE');
		
		//from
		$GFXD_DB->from(self::$pnp_handlerstats);
		
		//where clause
		$GFXD_DB->where("LOGTIME >= '" . $from_datetime . "'");//today date, time - duration
		$GFXD_DB->where("LOGTIME <= '" . $now_datetime . "'");//today date, time
		if($component != 'AllComponents') $GFXD_DB->where("varchar(handler) in ('" . $component . "')");//query handler
		if($server != 'AllServers') $GFXD_DB->where("varchar(serverid) in ('" . $server . "')");//query serverid

		//other
		$GFXD_DB->order_by('LOGTIME', 'DESC');
		
		$query = $GFXD_DB->get();

		return $query->result();
	}

	//FUNCTION: Get SMSGateway Stats by filters
	//GFXD TABLE: PNP_SMSGATEWAY_STATS
	public function get_notif_stats($server, $from_datetime, $now_datetime){
		$GFXD_DB = $this->load->database('gfxd', true);

		//select columns
		$GFXD_DB->select("LOGTIME, SERVERID, SUCCESS, FAIL, QUEUE_COUNT");

		//from
		$GFXD_DB->from(self::$pnp_notif_stats);

		//where clause
		$GFXD_DB->where("LOGTIME >= '" . $from_datetime . "'");//today date, time - duration
		$GFXD_DB->where("LOGTIME <= '" . $now_datetime . "'");//today date, time
		if($server != 'AllServers') $GFXD_DB->where("varchar(serverid) in ('" . $server . "')");//query serverid
		
		//other
		$GFXD_DB->order_by('LOGTIME', 'DESC');
		
		$query = $GFXD_DB->get();

		return $query->result();
	}

	//FUNCTION: Get SMSGateway Stats by filters
	//GFXD TABLE: PNP_SMSGATEWAY_STATS
	public function get_smsgateway_stats($server, $from_datetime, $now_datetime){
		$GFXD_DB = $this->load->database('gfxd', true);

		//select columns
		$GFXD_DB->select("LOGTIME, SERVERID, INSTANCE, SUCCESS, FAIL, QUEUE_COUNT");

		//from
		$GFXD_DB->from(self::$pnp_smsgateway_stats);

		//where clause
		$GFXD_DB->where("LOGTIME >= '" . $from_datetime . "'");//today date, time - duration
		$GFXD_DB->where("LOGTIME <= '" . $now_datetime . "'");//today date, time
		if($server != 'AllServers') $GFXD_DB->where("varchar(serverid) in ('" . $server . "')");//query serverid

		//other
		$GFXD_DB->order_by('LOGTIME', 'DESC');
		$GFXD_DB->order_by('SERVERID', 'ASC');
		
		$query = $GFXD_DB->get();

		return $query->result();
	}

	public function get_servers(){
		$this->db->select('server_name');
		$this->db->from(self::$servers);
		$this->db->where('server_type', 'TC');

		$this->db->order_by('seqno');

		$query = $this->db->get();

		return $query->result();
	}

	public function get_components($component_table = null){
		$this->db->select('component_name');
		$this->db->from(self::$components);

		if($component_table) $this->db->where('component_table', $component_table);

		$this->db->order_by('seqno');
		
		$query = $this->db->get();

		return $query->result();
	}

	public function get_smsgateway_instance($from_datetime, $now_datetime){
		$GFXD_DB = $this->load->database('gfxd', true);

		//select columns
		$GFXD_DB->select("SERVERID, INSTANCE");

		//from
		$GFXD_DB->from(self::$pnp_smsgateway_stats);

		//where clause
		$GFXD_DB->where("LOGTIME >= '" . $from_datetime . "'");//current minute
		$GFXD_DB->where("LOGTIME <= '" . $now_datetime . "'");//current minute
		
		$query = $GFXD_DB->get();

		return $query->result();	
	}

	//select serverid,handler,success,fail,logtime,eventsinqueue from pnp_handlerstats where logtime  > '2016-04-11 00:00:00' and varchar(handler) in ('ADCHandler') order by logtime;
}

?>

