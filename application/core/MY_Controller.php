<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	private $_usermodules = array();//glenn//not using
	private $_session_timeout = 30; //timeout after 30 minutes//glenn//not using
	
	//print_navbar, print_msgbanners value is true by default
	protected $print_navbar = true;
	protected $print_msgbanners = true;
	
	//declare errors, successes as an array variable
	protected $errors = array();
	protected $successes = array();

	//declare modulename, moduleid as null variable
	protected $modulename = null;
	protected $moduleid = null;
	
	//declare _modules as an array variable, will put modules here later. format: 'url'=>'human_readable_text'
	private $_modules = array(); 
	
	//declare css as null variable
	protected $css = null;//glenn//not using
	
	public function __construct() {
		parent::__construct();//to invoke CI_Controller functions
		
		//Required to Load
		$this->load->model('Login_model');
		
		//get current controller
		/*
		Remember:
		uri->segment(1) is the Controller name
		uri->segment(2) is the function name
		uri->segment(3) is argument
		*/
		$currpage = $this->uri->segment(1);

		//Check if user is currently logged in 
		if ($this->Login_model->is_logged_in()) {
			
			//get modules by user type id,
			//_modules is an array of url and names
			$this->_modules = $this->Login_model->get_usermodules($this->session->userdata('u_type_id'));
				
			//Check if user has access to current module
			if (!empty($this->moduleid) && !$this->Login_model->is_module_accessible($this->session->userdata('u_type_id'), $this->moduleid)) {//echo $this->db->last_query(); die;
				$this->flash_error('You have no permission to access the '.$this->modulename.' module. Contact your administrator if you think this is incorrect.'); //echo 'redirect'; die;
				redirect('home');
			}

			//if the user accesses the login page but currently logged in redirect to home
			$currsegment = trim($this->uri->segment(1));
			$url2 = $this->uri->segment(2);
			if (empty($currsegment)) redirect('home');
			else if ($currsegment == 'login' && empty($url2))  redirect ('home');
			
		} else { 
			//get userdata user id from session
			$dirtysession = $this->session->userdata('u_id');
			
			//user not logged in but has userdata on session - confirmed
			if (!empty($dirtysession)) $this->Login_model->destroy_session();

			if ($currpage == 'login') {
				return;
			} else {
				if (!empty($currpage)) $this->flash_error('You must be logged in to access this page.');
					redirect('login');
			}
		}
	}
	
	public function render($file = '', $data = array()) {
		//flash_success, flash_error variable as userdata param successes, errors
		$flash_success = $this->session->userdata('successes'); 
		$flash_error = $this->session->userdata('errors');
		
		//unset userdata param successes/errors
		$this->session->unset_userdata('successes');
		$this->session->unset_userdata('errors');
		
		//will use later to <module> to load
		//put errors + flash_errors into data[errors]
		//put successes + flash_successes into data[successes]
		// print_r($flash_error); die;
		$data['errors'] = array_merge($this->errors, (empty($flash_error) ? array() : $flash_error));
		$data['successes'] = array_merge($this->successes, (empty($flash_success) ? array() : $flash_success));
		
		//content is current <module> to load with data
		$content = $this->load->view($file, $data, true);
		
		//load pagelayout with an array of data
		// $content = null;
		$this->load->view('layouts/pagelayout',array('content'=>$content, 
													 'print_navbar'=>$this->print_navbar, 
													 'print_msgbanners'=>$this->print_msgbanners, 
													 'modules'=>$this->_modules, 
													 'modulename'=>$this->modulename)
		);
		
	}

	public function flash_success($msg) {
		$messages = $this->session->userdata('successes');
		$messages[] = $msg;
		$this->session->set_userdata('successes', $messages);	
	}
	
	
	public function flash_error($msg, $i=0) {
		$messages = $this->session->userdata('errors'); //var_dump($messages);
		$messages[] = $msg; //print_r($msg); print_r($messages);
		$this->session->set_userdata('errors', $messages);	//if ($i==2) { die;}
	}

	public function get_date_yesterday()
	{
		date_default_timezone_set('Asia/Manila');
		return date('Y-m-d',strtotime("yesterday"));	//get date yesterday
	}
}	
?>