<?php

class Login_model extends MY_Model {
	public static $emt_users = 'emt_users';
	public static $user_logs = 'user_logs';

	public function authenticate($username) {
		$this->db->select('u_id, username, password, u_type_id, fullname, email, is_blocked, is_root, last_login_datetime, last_login_ip');
		$this->db->from(self::$emt_users);
		$this->db->where('username', $username);
		$query = $this->db->get();
		
		return $query->result_array();
	}

	public function destroy_session() {
		//log user logout
		$u_id = $this->session->userdata('u_id');
		$username = $this->session->userdata('username');

		$user_logged = $this->Login_model->log_user_activities($u_id, $username, 'emt_users', 'logout', 'success');
	
		//unset user data
		$this->session->unset_userdata(array('u_id',
									  		 'username',
									  		 'u_type_id',
									  		 'fullname',
									  		 'email'
								 			  )
		);	

   		session_destroy();//destroy session
	}

	//u_id only used to update emt_users, the rest of the paramater are for user activities logging
	public function log_user_activities($u_id, $username, $tablename, $operation){
		date_default_timezone_set('Asia/Manila');
		
		//update MYSQL TABLE: emt_users only on login success
		if($operation == 'login' && $success_fail == 'success'){
			$this->db->set('last_login_datetime', (string)date('Y-m-d H:i:s'));
			$this->db->set('last_login_ip', $this->get_user_ip());
			$this->db->where('u_id', $u_id);
			$this->db->update(self::$emt_users);
		}

		//prepare user data to log
		$data = array(
			'u_log_id' => '',
			'username' => $username,
			'ip' => $this->get_user_ip(),
			'tablename' => $tablename,
			'operation' => $operation,
			'operation_datetime' => (string)date('Y-m-d H:i:s')
		);

		//insert to TABLE:user_logs
		$this->db->insert(self::$user_logs, $data);
	}

	public function is_logged_in() {
		//get u_id param from userdata
		$u_id = $this->session->userdata('u_id'); 
		
		//if empty u_id return false
		if (empty($u_id)) return false;
		
		//else return true
		return true;
	}

	public function get_usermodules($u_type_id) {
		$modules = array();
		
		$this->db->select('module_id');
		$this->db->distinct();
		$this->db->from('module_functions');
		
		$this->db->join('user_type_permissions', 'user_type_permissions.module_function_id = module_functions.id');
		$this->db->where('u_type_id', $u_type_id);
		
		//get module_id's
		$modids = $this->get_rows(); 
		
		//declare ids as an array variable, will put module_id's here later
		$ids = array();
		
		//put module_id's to ids[]
		foreach ($modids as $id) {
			$ids[] = (int)$id['module_id'];
		}
		
		//if not empty ids[], get url, name from MYSQL Table: modules by module_id
		if (!empty($ids)) {
			$this->db->select('name, url'); 
			$this->db->from('modules'); 
			$this->db->where_in('id', $ids); 
			$this->db->order_by('seqno'); 
			
			foreach ($this->get_rows() as $module) {
				$modules[$module['url']] = $module['name'];	
			}
		}

		return $modules;
	}

	public function is_module_accessible($u_type_id, $moduleid) {
		$this->db->select('1 as accessible');
		$this->db->from('user_type_permissions');
		$this->db->join('module_functions', 'user_type_permissions.module_function_id = module_functions.id');
		$this->db->where('u_type_id', $u_type_id);
		$this->db->where('module_id', $moduleid);
		$row = $this->get_row();
		return (!empty($row['accessible']));
	}
	
}

?>
