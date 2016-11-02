<?php
class Home extends MY_Controller {
	
	public function __construct() {
		//to invoke CI_Controller functions
		parent::__construct();

		//$this->modulename = 'Home';
	}
	
	public function index() {
		//get session user data
		$data['u_id'] = $this->session->userdata('u_id');
		$data['username'] = $this->session->userdata('username');
		$data['u_type_id'] = $this->session->userdata('u_type_id');
		
		//render home
		//$this->render('home/index', $data);

		//default to dashboard module after successful login
		redirect('dashboard');
	}
	
}
?>