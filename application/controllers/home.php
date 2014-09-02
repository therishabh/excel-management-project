<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct()
	{
		# code...
		parent :: __construct();
	}
	public function index()
	{
		// check login if session is isset or not
		//execute if session is set that means user is already login.
		if( $this->session->userdata('colombia_username') )
		{
			redirect('view');
		}
		//execute when session is not set and click on login button
		else if( $this->input->post('login_btn') )
		{
			$this->load->library('form_validation');
			$this->load->model('main_model');

			$this->form_validation->set_rules('username',"Username",'required|trim');
			$this->form_validation->set_rules('password','Password','required');
			if( $this->form_validation->run() == FALSE )
			{
				$data['error'] = "Please Enter Username and Password !";
				$this->load->view('login',$data);
			}
			else
			{
				//get username and paassword from form..
				$username = $this->input->post('username');
				$password = $this->input->post('password');

				//check login authontication..
				$check_login = $this->main_model->check_login($username,$password,'user');

				//check if check_login is true..
				if($check_login)
				{
					$this->session->set_userdata('colombia_username',$username);
					redirect('view');
				}
				else
				{
					$data['error'] = "Enter Correct Username and Password !";
					$this->load->view('login',$data);
				}

			}
		}
		else
		{
			$data['title'] = "LearnEx Login";
			$this->load->view('login',$data);			
		}
	}
}
