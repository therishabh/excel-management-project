<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offlinecreate extends CI_Controller {

	function __construct()
	{
		# code...
		parent :: __construct();
	}

	public function index()
	{
		$this->load->model("main_model");
		$username = $this->session->userdata('colombia_username');
		$user_detail = $this->main_model->fetchbyfield('username',$username,'user');
		if($this->input->post('submit_btn'))
		{
			//check if any file is selected..
			if( !empty($_FILES['file']['name']) )
			{
				//get file extension..
				$extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
				$encrypted_filename = md5(time().uniqid());

				//check if fileis excel file then convert it into json format and save it..
				if($extension == "xls" || $extension == "xlsx")
				{
					//************* excel read **************
					//script for read excel file...

					$this->load->library('excel');
										
					$file_name = $_FILES['file']['tmp_name'];

					//echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
					try
					{
						$objPHPExcel = PHPExcel_IOFactory::load($file_name);
					}
					catch(Exception $e)
					{
						die('Error loading file "'.pathinfo($file_name,PATHINFO_BASENAME).'": '.$e->getMessage());
					}

					//$exceldata is a multidimention array which have all data of excel file..
					$exceldata = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					
					//************* // end excel read **************


					//************ save excel file into directory ****************
					//set the path where the files uploaded will be copied. NOTE if using linux, set the folder to permission 777
					$config['upload_path'] = './file-uploads/';
					//set logo default name..
					$config['file_name'] = $encrypted_filename;
					
					//load the upload library
					$this->load->library('upload', $config);

					$config['allowed_types'] = 'doc|docx|pdf|xls|xlsx';

			   		$this->upload->set_allowed_types('*');
			    
					$this->upload->initialize($config);

					//if not successful, set the error message
					if ($this->upload->do_upload('file')) 
					{
						$logo_data = $this->upload->data();
					}

					$insert_data['file_name'] = $encrypted_filename;
					//************ // end save excel file into directory ****************

					//************* create a content variable for json file *************

					end($exceldata);
					$last_row = key($exceldata);
					end($exceldata[$last_row]);
					$last_column = key($exceldata[$last_row]);


					$content = '[';
					foreach($exceldata as $row_index => $rows)
					{
						$content .= '[';

						$columns = "";
						foreach($rows as $column_index => $value)
						{
							if($column_index == $last_column)
							{
								$columns .= $column_index;
								$content .= '"'.$value.'"';
							}
							else
							{
								$columns .= $column_index."/";
								$content .= '"'.$value.'",';
							}
						}

						if($row_index == $last_row)
						{
							$content .= ']';
						}
						else
						{
							$content .= '],';
						}
					}
					$content .= ' ]';
					//************* // end create a content variable for json file *************

					//*********** Create Json File ***************
					$json_filename = $encrypted_filename.".json";
					$myfile = fopen("json-uploads/".$json_filename, "w");
					//insert data into json file..
					fwrite($myfile, $content);
					//add a new line at end of the file..
					$newline = "\n";
					fwrite($myfile, $newline);
					//close json file..
					fclose($myfile);
					//*********** // end Create Json File ***************


					//************* save data into database *************
					$insert_data['file_name'] = $this->input->post('file_name');//entered name by user..
					$insert_data['encrypt_name'] = $encrypted_filename;//encrypted file name..
					$insert_data['file_format'] = $extension;//file format..
					$insert_data['columns'] = $columns;//file format..
					$insert_data['created_by'] = $user_detail['username'];
					$insert_data['created_as'] = "offline";
					date_default_timezone_set('Asia/Calcutta');
					$insert_data['create_time'] = date("y-m-d H:i:s");
					$insert_data['modify_time'] = date("y-m-d H:i:s");

					$query = $this->main_model->insertdata($insert_data,'excel_file');
					// if data is successfully submited into course table into database..
		            if($query)
		            {
						//redirect to same page 
						//because of post-request-get rule..
						//there is for Duplicate form submissions avoid..
						//start session for display message..
						$this->session->set_userdata('upload_success',"success");
						header("Location: " . $_SERVER['REQUEST_URI']);
		            }
					//************* // end save data into database *************


				}
				//execute if file is not excel file..
				// that means as save it as it is..
				else
				{
					//************ save excel file into directory ****************
					//set the path where the files uploaded will be copied. NOTE if using linux, set the folder to permission 777
					$config['upload_path'] = './file-uploads/';
					//set logo default name..
					$config['file_name'] = $encrypted_filename;
					
					//load the upload library
					$this->load->library('upload', $config);

					$config['allowed_types'] = 'doc|docx|pdf|xls|xlsx';

			   		$this->upload->set_allowed_types('*');
			    
					$this->upload->initialize($config);

					//if not successful, set the error message
					if ($this->upload->do_upload('file')) 
					{
						$logo_data = $this->upload->data();
					}

					$insert_data['file_name'] = $encrypted_filename;
					//************ // end save excel file into directory ****************

					//************* save data into database *************
					$columns = "";
					$insert_data['file_name'] = $this->input->post('file_name');//entered name by user..
					$insert_data['encrypt_name'] = $encrypted_filename;//encrypted file name..
					$insert_data['file_format'] = $extension;//file format..
					$insert_data['columns'] = $columns;//file format..
					$insert_data['created_by'] = $user_detail['username'];
					$insert_data['created_as'] = "offline";
					date_default_timezone_set('Asia/Calcutta');
					$insert_data['create_time'] = date("y-m-d H:i:s");
					$insert_data['modify_time'] = date("y-m-d H:i:s");

					$query = $this->main_model->insertdata($insert_data,'excel_file');
					// if data is successfully submited into course table into database..
		            if($query)
		            {
						//redirect to same page 
						//because of post-request-get rule..
						//there is for Duplicate form submissions avoid..
						//start session for display message..
						$this->session->set_userdata('upload_success',"success");
						header("Location: " . $_SERVER['REQUEST_URI']);
		            }
					//************* // end save data into database *************
				}
			}
			//execute if there is no any file will be selected.
			else
			{
				$this->session->set_userdata('upload_error',"success");
				header("Location: " . $_SERVER['REQUEST_URI']);
			}

				
		}
		else
		{
			$data['page'] = 'offline-create';
			$this->load->view('template',$data);
		}
		
	}

	//function for check file name into database..
	// check if selected file name is exist or not into database..
	function check_file_name()
	{
		$file_name = $_POST['file_name'];
		$this->load->model('main_model');
		//check if filename is exist into database where it's status is true..
		$file = $this->main_model->fetchbyfield('file_name',$file_name,'excel_file');
		if($file)
		{
			echo "exist";
		}
		else
		{
			echo "notexist";
		}
	}
}
