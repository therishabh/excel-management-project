<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Onlinecreate extends CI_Controller {

	function __construct()
	{
		# code...
		parent :: __construct();
	}
	public function index()
	{
		$this->load->model('main_model');
		$data['page'] = 'online-create';
		$this->load->view('template',$data);
	}

	function savedata()
	{
		$this->load->model('main_model');
		$username = $this->session->userdata('colombia_username');
		$user_detail = $this->main_model->fetchbyfield('username',$username,'user');
		//assign a status for check if there is excel file empty or not..
		//if excel file is empty then file will not save and show an error to user..
		$status = 0;
		$content = '[';
		for($a = 0; $a < count($_POST['data']); $a++)
		{
			$abc = $a + 1;
			$content .= '[';
			for($x = 0 ; $x < count($_POST['data'][$a]); $x++)
			{
				$xyz = $x + 1;
				if($x == count($_POST['data'][$a]) - 1 )
				{
					//if there is any row is filled then update $status valuee
					if( !empty($_POST['data'][$a][$x]) )
					{
						$status = 1;
					}
					$content .= '"'.$_POST['data'][$a][$x].'"';
				}
				else
				{
					//if there is any row is filled then update $status valuee
					if( !empty($_POST['data'][$a][$x]) )
					{
						$status = 1;
					}
					$content .= '"'.$_POST['data'][$a][$x].'",';
				}
			}

			if($a == count($_POST['data']) - 1 )
			{
				$content .= ']';
			}
			else
			{
				$content .= '],';
			}
		}

		$content .= "]";

		if($status == '1')
		{
			//create a json file..
			$file_name = md5(time().uniqid());
			$json_filename = $file_name.".json";

			$myfile = fopen("json-uploads/".$json_filename, "w");
			//insert data into json file..
			fwrite($myfile, $content);
			//add a new line at end of the file..
			$newline = "\n";
			fwrite($myfile, $newline);
			//close json file..
			fclose($myfile);

			$insert_data['encrypt_name'] = $file_name;//encrypted file name..
			$insert_data['file_name'] = $this->input->post('file_name');//entered name by user..
			$insert_data['file_format'] = "xls";
			$insert_data['created_as'] = "online";
			$insert_data['columns'] = $this->input->post('columns');
			date_default_timezone_set('Asia/Calcutta');
			$insert_data['created_by'] = $user_detail['username'];
			$insert_data['create_time'] = date("y-m-d H:i:s");
			$insert_data['modify_time'] = date("y-m-d H:i:s");

			$query = $this->main_model->insertdata($insert_data,'excel_file');
			$this->session->set_userdata('upload_success',"success");
			echo "success";
		}
		else
		{
			echo "error";
		}
	}
}
