<?php 
class User_management extends MY_Controller {
	
	public function __construct() {
		$this->modulename = 'User Management';
		$this->moduleid = 7;
		parent::__construct();
		
		$this->load->model('User_management_model');
	}
	
	public function index() {
		$data = array();
		
		//pagination: 
		$currpage = (int)trim($this->input->get('current'));
		$perpage  = (int)trim($this->input->get('perpage'));
		$data['page']['current'] = (!empty($currpage) && is_numeric($currpage) && $currpage > 0) ? $currpage : 1;
		$data['page']['per'] = (!empty($perpage) && is_numeric($perpage) && $perpage > 0) ? $perpage : 10;
		$offset = ($data['page']['current'] - 1) * $data['page']['per'];
		
		//filters:
		$data['filters']['username'] = trim($this->input->get('username'));
		$data['filters']['fullname'] = trim($this->input->get('fullname'));
		$data['filters']['u_type_id'] = trim($this->input->get('u_type_id'));
		//$data['filters']['is_blocked'] = strtolower(trim($this->input->get('is_blocked')));
		
		$filters = $data['filters'];
		
		$valid_filters = $this->validate_filters($data['filters']);
		
		$data['userlist'] = $this->User_management_model->get_users($data['page']['per'], $offset, $filters);
		$data['page']['total'] = ($valid_filters ? $this->User_management_model->get_users_count($filters) : 0);
		$data['user_types'] = $this->User_management_model->get_user_types();
		
		$this->load->helper('ui');
		$this->render('user_management/index', $data);
	}
	
	public function validate_filters($filters) {
		if (!empty($filters['username']) && !preg_match('/^[A-Za-z0-9\-\_]+$/i', $filters['username'])) return false;
		if (!empty($filters['fullname']) && !preg_match('/^[A-Za-z0-9\s]+$/i', $filters['fullname'])) return false;
		if (!empty($filters['u_type_id']) && is_numeric($filters['u_type_id'])) return false;
		
		return true;
	}

	public function change_details($u_id = null) {
		$u_id = trim($u_id);
		$data = array();
		
		if (empty($u_id)) redirect('user_management');
		
		$user_info = $this->User_management_model->get_userdetails($u_id);
		
		if (empty($user_info)) redirect('user_management'); // user dne

		$data['u_id'] = $u_id;
		$data['user_types'] = $this->User_management_model->get_user_types();
		
		if (!empty($_POST)) {
			$data['userinfo']['fullname'] = trim($this->input->post('fullname'));
			$data['userinfo']['email'] = trim($this->input->post('email'));
			$data['userinfo']['u_type_id'] = trim($this->input->post('u_type_id'));
			$data['userinfo']['username'] = $user_info['username'];
			$data['val_errors'] = $this->_validate_input($data['userinfo']);
			
			if (!empty($data['val_errors'])) {
				$this->errors[] = 'You have one or more errors in your inputs. Please check your fields.';
			} else {
				if ($this->User_management_model->update_userinfo($data['u_id'], $data['userinfo']['fullname'], $data['userinfo']['email'], $data['userinfo']['u_type_id'])) {
					$this->flash_success("You have successfully updated user <strong>{$user_info['username']}</strong>.");
					redirect('user_management');
				} else {
					$this->errors[] = 'The information of this user was not updated. Please check your inputs.';
				}
			}	
		} else {
			$data['userinfo'] = $user_info;
		}	
		
		$this->load->helper('ui');
		$this->render('user_management/change_details-form', $data);
	}
	
	public function change_userpass($u_id) {
		$data = array();
		$u_id = trim($u_id);
		
		if (empty($u_id)) redirect('user_management');
		
		$user_info = $this->User_management_model->get_userdetails($u_id);
		
		if (empty($user_info)) redirect('user_management'); // user dne

		$data['userinfo']['password'] = $this->input->post('password');
		
		$data['val_errors'] = $this->_validate_password($data['userinfo']);
		
		if (!empty($data['val_errors'])) {
			$this->flash_error('You have one or more errors in your inputs. Please check your fields.');
			//$this->flash_formerror('password', $data['val_errors']['password']);
		} else {
			$hash = MD5($data['userinfo']['password']);
			
			if ($this->User_management_model->update_userpassword($u_id, $hash)) {
				$this->flash_success("You have successfully updated the password of user <strong>{$user_info['username']}</strong>.");
				redirect('user_management');
			} else {
				$this->errors[] = 'The information of this user was not updated. Please check your inputs.';
			}
		}

		redirect('user_management/change_details/'.$u_id);
	}
	
	public function adduser() {
		$data['userinfo']['username'] = trim($this->input->post('username'));
		$data['userinfo']['password'] = trim($this->input->post('password'));
		$data['userinfo']['fullname'] = trim($this->input->post('fullname'));
		$data['userinfo']['email'] 	  = trim($this->input->post('email'));
		$data['userinfo']['u_type_id'] = trim($this->input->post('u_type_id'));
		$data['user_types'] = $this->User_management_model->get_user_types();
		
		if (!empty($_POST)) {
			$data['val_errors'] = $this->_validate_newuser(); 
			if (!empty($data['val_errors'])) {
				$this->errors[] = 'You have one or more errors in your inputs. Please check your fields.';
			} else {
				$hash = MD5($data['userinfo']['password']);
				if ($this->User_management_model->add_userinfo($data['userinfo']['username'], $hash, $data['userinfo']['fullname'], $data['userinfo']['email'], $data['userinfo']['u_type_id'])) {
					$this->flash_success('Successfully added new user <strong>'.$data['userinfo']['username'].'</strong>.');
				} else {
					$this->flash_error('Unable to create a new user. Please check your inputs.');
				}
				redirect('user_management');
			}
		}

		$this->load->helper('ui');
		$this->render('user_management/add_user-form', $data);
	}
		
	public function delete_user($u_id) {
		$u_id = trim($u_id);
		
		$userinfo = $this->User_management_model->get_userdetails($u_id);
		
		if (empty($userinfo)) redirect('user_management');
		
		if ($this->User_management_model->delete_user($u_id, $userinfo)) {
			$this->flash_success('Successfully deleted user <strong>'.$userinfo['username'].'</strong>. The user was also logged out of the system.');
		} else {
			$this->flash_error('The user <strong>'.$userinfo['username'].'</strong> was not deleted.');
		}
		redirect('user_management');
	}
	
	private function _validate_input($data) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('','');
		$this->form_validation->set_rules('fullname', 'full name', 'trim|required|max_length[64]|callback_valid_fullname');
		$this->form_validation->set_rules('email', 'email', 'trim|required|max_length[64]|valid_email');
		$this->form_validation->set_rules('u_type_id', 'user type', 'trim|required|callback_valid_user_type');
		
		$errors = array();
		if (!$this->form_validation->run()) {
			$errors['u_id'] = form_error('u_id');	
			$errors['fullname'] = form_error('fullname');	
			$errors['email'] = form_error('email');	
			$errors['u_type_id'] = form_error('u_type_id');	
		}
		
		return $errors;
	}
	
	private function _validate_password($data) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('','');
		$this->form_validation->set_rules('password', 'password', 'trim|required');
		
		$errors = array();
		if (!$this->form_validation->run()) {
			$errors['password'] = form_error('password');	
		}
		
		return $errors;
	}
	
	private function _validate_newuser() {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('','');
		$this->form_validation->set_rules('username', 'user', 'trim|required|max_length[64]|callback_valid_username');
		$this->form_validation->set_rules('fullname', 'full name', 'trim|max_length[64]|callback_valid_fullname');
		$this->form_validation->set_rules('email', 'email', 'trim|max_length[64]|valid_email');
		$this->form_validation->set_rules('u_type_id', 'user type', 'trim|required|callback_valid_user_type');
		
		$errors = array();

		if (!$this->form_validation->run()) {
			$errors['username'] = form_error('username');	
			$errors['password'] = form_error('password');	
			$errors['fullname'] = form_error('fullname');	
			$errors['email'] = form_error('email');	
			$errors['u_type_id'] = form_error('u_type_id');	
		}
		
		return $errors;
	}
	
	public function valid_username($str) {
		if (empty($str)) return true;
		
		if (!preg_match('/^[A-Za-z0-9\_\-]+$/i', $str)) {
			$this->form_validation->set_message('valid_username', 'This field accepts only alphanumeric characters, underscores, and dashes.');
			return false;
		}
		
		if ($this->User_management_model->username_exists($str)) {
			$this->form_validation->set_message('valid_username', 'This %s already exists. Please choose another.');
			return false;
		}
		return true;
	}
	
	public function valid_fullname($name) {
		if (empty($name)) return true;
		
		if (!preg_match('/^[A-Za-z0-9\s]+$/i', $name)) {
			$this->form_validation->set_message('valid_fullname', 'This field accepts only alphanumeric characters and spaces.');
			return false;
		}
		return true;
	}
	
	public function valid_user_type($u_type_id) {
		if (empty($u_type_id)) return true;
		
		if (!$this->User_management_model->is_valid_user_type($u_type_id)) {
			$this->form_validation->set_message('valid_user_type', 'The user type does not exist.');
			return false;
		}
		return true;
	}
	
}

?>