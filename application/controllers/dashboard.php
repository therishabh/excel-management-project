<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
	{
		# code...
		parent :: __construct();
	}
	public function index()
	{
		$this->load->model('main_model');
		$data['page'] = 'dashboard';
		$this->load->view('template',$data);
	}
}