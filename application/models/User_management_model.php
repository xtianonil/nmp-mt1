<?php
class User_management_model extends MY_Model {
	
	public function __construct() {
		parent::__construct();	
	}
	
	public function get_users($limit = 10, $offset = 0, $filters = array()) {
		$this->db->select('u_id, username, fullname, email, u_type_id');
		$this->db->from('emt_users');
		$this->db->order_by('username');
		$this->db->limit($limit, $offset);
		
		//filters
		if ($filters['username']!=='') $this->db->like('UPPER(username)', strtoupper($filters['username']));
		if ($filters['fullname']!=='') $this->db->like('UPPER(fullname)', strtoupper($filters['fullname']));
		if (!empty($filters['u_type_id'])) $this->db->where('u_type_id', (string)$filters['u_type_id']);
		
		return $this->get_rows();
	}
	
	public function get_users_count($filters = array()) {
		$this->db->select('count(*) as count');
		$this->db->from('emt_users');
		$this->db->join('user_types', 'emt_users.u_type_id = user_types.u_type_id');
		
		//filters
		if ($filters['username']!=='') $this->db->like('UPPER(username)', strtoupper($filters['username']));
		if ($filters['fullname']!=='') $this->db->like('UPPER(fullname)', strtoupper($filters['fullname']));
		if (!empty($filters['u_type_id'])) $this->db->where('u_type_id', (string)$filters['u_type_id']);
		//if ($filters['is_blocked']!=='') $this->db->where('is_blocked', (int)$filters['is_blocked']);
		
		$count = $this->get_row();
		return $count['count'];
	}
	
	public function get_userdetails($u_id) {
		$result = array();
		$this->db->select('username, fullname, email, u_type_id');
		$this->db->from('emt_users'); 
		$this->db->where('u_id', $u_id); 
		return $this->get_row();
	}
	
	public function get_user_types() {
		$result = array();
		$this->db->select('u_type_id, u_type_name');
		$this->db->from('user_types');
		$this->db->order_by('u_type_name'); 
		foreach ($this->get_rows() as $row) {
			$result[$row['u_type_id']] = $row['u_type_name'];	
		}
		return $result;
	}
	
	public function is_valid_user($u_id) {
		$this->db->select('1')->from('emt_users')->where('u_id', $u_id);
		return ($this->db->get()->num_rows() == 1); 
	}
	
	public function is_valid_user_type($u_type_id) {
		$this->db->select('1')->from('user_types')->where('u_type_id', $u_type_id);
		return (count($this->db->get()->result()) == 1); 
	}
	
	public function update_userinfo($u_id, $fullname, $email, $u_type_id) {
		$data = array('fullname'=>$fullname, 'email'=>$email, 'u_type_id'=>$u_type_id);
		$this->db->where('u_id', $u_id);
		return $this->db->update('emt_users', $data);
	}
	
	public function update_userpassword($u_id, $password) {
		$continue = true;
		
		$this->db->where('u_id', $u_id);
		$continue = $this->db->update('emt_users', array('password'=>$password));
		
		return $continue;
	}
	
	public function username_exists($username) {
		$this->db->select('1')->from('emt_users')->where('username', $username);
		return (count($this->db->get()->result()) == 1); 
	}
	
	public function add_userinfo($username, $password, $fullname, $email, $u_type_id) {
		$continue = true;
		
		$continue = $this->db->insert('emt_users', array('username'=>$username,
														'password'=>$password,
														'fullname'=>$fullname,
														'email'=>$email,
														'u_type_id'=>$u_type_id,
													)
			   						);
		
		return $continue;
	}
	
	public function delete_user($u_id) {
		$this->db->where('u_id', $u_id);
		$continue = $this->db->delete('emt_users');
			
		return $continue;
	}
}

?>