<?php 

class Login extends MY_Controller {

	public function __construct() {
		//to invoke CI_Controller functions
		parent::__construct();

		//Required to Load
		$this->load->model('Login_model');
	}
	
	public function index() {
		//do not print navigation bar, message banner when loading login page
		$this->print_navbar = false;
		$this->print_msgbanners = false;

		//get input from view/login/login-form.php 
		$username = trim($this->input->post('username', true));	
		$password = $this->input->post('password');
		
		if (!empty($_POST)) {
			//Validate username, password
			if (empty($username) || empty($password)) {
				$this->errors[] = 'Please provide username and password.';
			} else if (!preg_match('/^[A-Za-z0-9\-\_]+$/i', $username))  {
				$this->errors[] = 'Invalid username or password.';
			} else {
				//username, password validated but not yet authenticated
				//check if username exist, get all data of specific user
				$data = $this->Login_model->authenticate($username);

				//if username found
				if($data){
					foreach ($data as $row) {//get each user data from array
						$row_u_id = $row['u_id'];
						$row_password = $row['password'];
						$row_u_type_id = $row['u_type_id'];
						$row_fullname = $row['fullname'];
						$row_email = $row['email'];
					}

					//if password matched
					if(MD5($password) == $row_password){
						//set user data
						$this->session->set_userdata(array('u_id' => $row_u_id,
														   'username' => $username,
														   'u_type_id' => $row_u_type_id,
														   'fullname' => $row_fullname,
														   'email' => $row_email
														   )
						);
						//update emt user, log user entrance
						$user_logged = $this->Login_model->log_user_activities($row_u_id, $username, 'emt_users', 'login:success');

						redirect('home');//redirect to home controller
					}else{
						$this->errors[] = 'Account does not exist.';
						
						//log user login failure
						$user_logged = $this->Login_model->log_user_activities($row_u_id, $username, 'emt_users', 'login:fail');
					}

				}else{//else username not found
					$this->errors[] = 'Account does not exist.';
				}

			}
		}

		$this->render('login/login-form');
	}

	public function logout() {
		$this->Login_model->destroy_session();

		redirect('login');
	}


	
}

?>
