<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct()
	{
		# code...
		parent :: __construct();
	}
	public function index()
	{
		$this->load->model("main_model");
		if($this->input->post('search_btn'))
		{
			$name = $_POST['search-name'];
			$username = $_POST['search-username'];
			$role = $_POST['search-role'];
			$data['user_details'] = $this->main_model->searchuser($name,$username,$role);
			$data['name_search'] = $name;
			$data['username_search'] = $username;
			$data['role_search'] = $role;
			$data['page'] = 'user';
			$this->load->view('template',$data);
		}
		else
		{

			$data['user_details'] = $this->main_model->fetchalldatadesc("user")[0];
			$data['role_search'] = "";
			$data['page'] = 'user';
			$this->load->view('template',$data);
		}
	}

	//function for check file name into database..
	// check if selected file name is exist or not into database..
	function check_user_name()
	{
		$user_name = $_POST['username'];
		$this->load->model('main_model');
		//check if username is exist into database where it's status is true..
		$user = $this->main_model->fetchbyfield('username',$user_name,'user');
		if($user)
		{
			echo "exist";
		}
		else
		{
			echo "notexist";
		}
	}
	function adduser()
	{
		$insert_data['name'] = $_POST['name'];
		$insert_data['username'] = $_POST['username'];
		$insert_data['password'] = $_POST['password'];
		$insert_data['role'] = $_POST['role'];
		$insert_data['permission'] = $_POST['permission'];
		$this->load->model('main_model');
		$query = $this->main_model->insertdata($insert_data,'user');
		echo $query;
	}

	function delete_user()
	{
		$id = $_POST['id'];
		$update_data['status'] = "0";
		$this->load->model('main_model');
		$query = $this->main_model->updatedata($update_data,$id,'user');
	}

	
}