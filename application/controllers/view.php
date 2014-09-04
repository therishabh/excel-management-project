<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View extends CI_Controller {

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
			$file_name = $this->input->post('file_name');
			$created_by = $this->input->post('created_by');
			$date = $this->input->post('date');

			$query_total_files = $this->main_model->fetchallfiles($file_name,$created_by);
			$total_num_of_files = $query_total_files[1];

			$display_file = 5;
			$pagination_num = ceil($total_num_of_files / $display_file);
			$data['pagination_num'] = $pagination_num;

			$data['file_details'] = $this->main_model->fetch_file_with_limit($file_name,$created_by,$display_file)[0];
			$data['page'] = 'view';
			$data['search_file_name'] = $file_name;
			$data['search_created_by'] = $created_by;
			$data['search_date'] = $date;
			$data['user_details'] = $this->main_model->fetchalldatadesc("user")[0];

			$username = $this->session->userdata('colombia_username');
			$data['login_user_detail'] = $this->main_model->fetchbyfield('username',$username,'user');

			$this->load->view('template',$data);
		}
		else
		{

			$query_total_files = $this->main_model->fetchallfiles();
			$total_num_of_files = $query_total_files[1];

			$display_file = 5;
			$pagination_num = ceil($total_num_of_files / $display_file);
			$data['pagination_num'] = $pagination_num;

			$data['user_details'] = $this->main_model->fetchalldatadesc("user")[0];
			$data['search_created_by'] = "";
			$data['file_details'] = $this->main_model->fetch_file_with_limit('','',$display_file)[0];

			$username = $this->session->userdata('colombia_username');
			$data['login_user_detail'] = $this->main_model->fetchbyfield('username',$username,'user');

			$data['page'] = 'view';
			$this->load->view('template',$data);
		}
	}

	function download_excel($encrypt_file_name)
	{
		//load main_model model..
		$this->load->model("main_model");
		$file_details = $this->main_model->fetchbyfield('encrypt_name',$encrypt_file_name,'excel_file');

		$columns =  explode('/',$file_details['columns']);
		$json_file_name = $file_details['encrypt_name'].".json";
		$file_content = file_get_contents('json-uploads/'.$json_file_name);
		// $data = file_get_contents ('./cob_details.json');
	    $json_array = json_decode($file_content, TRUE);

	    end($json_array);
		$last_row = key($json_array);

		//load our new PHPExcel library
		$this->load->library('excel');
		//activate worksheet number 1

		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('asia/calcutta');

		if (PHP_SAPI == 'cli')
			die('This example should only be run from a Web Browser');

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("CodeBibber");
		$objPHPExcel->getProperties()->setLastModifiedBy("CodeBibber");
		//$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document")
									 // ->setSubject("Office 2007 XLSX Test Document")
									 // ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 // ->setKeywords("office 2007 openxml php")
									 // ->setCategory("Test result file");


		// Add some data
		$a = 1;
	   	for($i = 0; $i <= $last_row; $i++)
	   	{
	   		for($j = 0; $j < count($columns); $j++)
	   		{
	   			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columns[$j].$a, $json_array[$i][$j]);
	   		}
	   		$a++;
	   	}
		// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Aa1', 'Hello');
	


		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Rishabh');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$encrypt_file_name.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}

	function delete_file()
	{
		$id = $_POST['id'];
		$data['status'] = "0";
		$this->load->model("main_model");
		$query = $this->main_model->updatedata($data,$id,'excel_file');
		if($query)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function fetch_next_file()
	{
		$last_row_id = $_POST['last_row_id'];
		$file_name = $_POST['file_name'];
		$created_by = $_POST['created_by'];
		$this->load->model("main_model");
		$file_details = $this->main_model->fetchallfiles($file_name,$created_by)[0];
		$username = $this->session->userdata('colombia_username');
		$login_user_detail = $this->main_model->fetchbyfield('username',$username,'user');
		
		$a = 0;
		foreach ($file_details as $file) {
			if($a == 1)
			{
				$created_date = date('jS M, Y (g:i A)',strtotime($file['create_time']));
				$created_user = $this->main_model->fetch_user_name($file['created_by']);
				echo "<tr>";
	    		echo "<td>$a</td>";
	    		echo "<td>";
	    		if($file['file_format'] == "xls" || $file['file_format'] == "xlsx")
	    		{
	    			echo '<i class="fa fa-file-excel-o" title="Excel File" style="color:#217346;"></i>';
	    			// echo "<img src='".base_url()."img/excel.png' alt=''>";
	    		}
	    		elseif($file['file_format'] == "doc" || $file['file_format'] == "docx")
	    		{
	    			echo '<i class="fa fa-file-word-o" title="Word File"  style="color:#2B579A;"></i>';
	    			// echo "<img src='".base_url()."img/word.png' alt=''>";
	    		}
	    		elseif($file['file_format'] == "pdf")
	    		{
	    			echo '<i class="fa fa-file-pdf-o" title="PDF File" style="color:#8B0505;"></i>';
	    			// echo "<img src='".base_url()."img/pdf.png' alt=''>";
	    		}
	    		echo "</td>";
	    		echo "<td title='".$file['file_name']."'>".$file['file_name']."</td>";
			    if(strtolower($login_user_detail['permission']) != "read only")
			    {
	    			echo "<td><i class='fa fa-pencil edit-name'></i></td>";
			    }
			    else
			    {
			    	echo "<td></td>";
			    }
	    		echo "<td>".$created_user['name']."<br><span>".$created_date."</span></td>";
	            echo "<td>";
	            echo date('jS M, Y g:i A',strtotime($file['modify_time']));
	            echo "</td>";
	            echo "<td>";
	            	
	            	if($file['file_format'] == "xls" || $file['file_format'] == "xlsx")
		    		{
		    			echo '<a href="'.base_url().'view/openfile/'.$file['encrypt_name'].'"><i class="fa fa-folder-open-o open-file"  title="Open File"></i></a>';
		    			
			            if(strtolower($login_user_detail['permission']) != "read only")
		    			{
			    			echo form_open_multipart('view/overright_file','class="overright-file-form"');
			            	echo '<i class="fa fa-cloud-upload upload-file" style="cursor:pointer" title="Overright File"></i>';
			            	echo '<input type="file"  name="file" class="hidden select-overright-file" accept=".xls,.xlsx,.pdf,.doc,.docx">';
			            	echo '<input type="hidden"  name="row_id" value="'.$file['id'].'">';
			            	echo form_close();
		    			}
		    			
		    			//if file is created offline..
		    			if($file['created_as'] == "offline")
		    			{
		    				//if file is one time modified..
			    			if($file['update_status'] == "1")
			    			{
			    				//download excel file after converting it in excel..
			    				//using php-excel-create for create excel file and download it..
		    					echo "<a href='".base_url()."view/download_excel/".$file['encrypt_name']."' target='_blank'><i class='fa fa-cloud-download download-file' title='Download File'></i></a>";
			    			}
			    			//excute if file is not modifiled once..
			    			//then direct download excel file..
			    			else
			    			{
			    				//download direct excel file..
			    				$excel_file_name = $file['encrypt_name'].".".$file['file_format'];
		    					echo "<a href='".base_url()."file-uploads/".$excel_file_name."' target='_blank'><i class='fa fa-cloud-download download-file' title='Download File'></i></a>";
			    			}
		    			}
		    			//if file is created online..
		    			elseif($file['created_as'] == "online")
		    			{
		    				//download excel file after converting it from json usin php-excel-create..
		    				echo "<a href='".base_url()."view/download_excel/".$file['encrypt_name']."' target='_blank'><i class='fa fa-cloud-download download-file' title='Download File'></i></a>";
		    			}
		    			echo '<i class="fa fa-trash-o delete-file" id="'.$file['id'].'" title="Delete File"></i>';
		    		}
		    		//excute if there is a word or pdf file (doc,docx,pdf)
		    		elseif($file['file_format'] == "doc" || $file['file_format'] == "docx" || $file['file_format'] == "pdf")
		    		{
		    			$doc_file_name = $file['encrypt_name'].".".$file['file_format'];
		    			
			            if(strtolower($login_user_detail['permission']) != "read only")
		    			{
			    			echo form_open_multipart('view/overright_file','class="overright-file-form"');
			            	echo '<i class="fa fa-cloud-upload upload-file" style="cursor:pointer" title="Overright File"></i>';
			            	echo '<input type="file"  name="file" class="hidden select-overright-file" accept=".xls,.xlsx,.pdf,.doc,.docx">';
			            	echo '<input type="hidden"  name="row_id" value="'.$file['id'].'">';
			            	echo form_close();
		    			}

		    			echo "<a href='".base_url()."file-uploads/".$doc_file_name."' target='_blank'><i class='fa fa-cloud-download download-file' title='Download File'></i></a>";
		    			echo '<i class="fa fa-trash-o delete-file" id="'.$file['id'].'" title="Delete File"></i>';
		    		}
	            echo "</td>";
	    		echo "</tr>";
	    		//further assign $a to 0;
				$a = "0";
			}
			//if file id is equal to last_row_id..
			if($file['id'] == $last_row_id)
			{
				//assign $a value to 1..
				$a = 1;
			}
		}
	}

	function openfile($filecode)
	{
		$this->load->model("main_model");
		//check if filename is exist into database where it's status is true..
		$file_check_query = $this->main_model->fetchbyfield('encrypt_name',$filecode,'excel_file');
		if($file_check_query)
		{
			//get json file content..
			$json_file_name = $filecode.".json";
			$file_content = file_get_contents('json-uploads/'.$json_file_name);
			//send json file content into view mode.
			$data['file_content'] = $file_content;
			$data['file_name'] = $file_check_query['file_name'];
			$data['created_by'] = $file_check_query['created_by'];
			

			if($file_check_query['modify_time'] != "0000-00-00 00:00:00")
        	{
        		$data['date'] =  date('jS M, Y g:i A',strtotime($file_check_query['modify_time']));
        	}
        	else
        	{
        		$data['date'] =  date('jS M, Y g:i A',strtotime($file_check_query['create_time']));
        	}

			$data['page'] = 'openfile';
			$this->load->view('template',$data);
		}
		else
		{
			 redirect('view');
		}
	}

	function pagination()
	{
		$this->load->model("main_model");
		$display_file = 5;
		if(isset($_POST['starting_index']))
		{
			$x = $_POST['starting_index'];
			$starting_index = $_POST['starting_index'] - 1;
			$starting_index = $starting_index * $display_file;
			$file_name = $_POST['file_name'];
			$created_by = $_POST['created_by'];
			$file_details = $this->main_model->fetch_file_limit($file_name,$created_by,$starting_index,$display_file)[0];
			$username = $this->session->userdata('colombia_username');
			$login_user_detail = $this->main_model->fetchbyfield('username',$username,'user');
			//execute if there is any file in database..
	    	//check if $file_details array is empty or not..
	    	if(!empty($file_details[0])) 
			{   
			?>
			<table class="table">
			    <thead>
			        <tr>
			            <th>S.No</th>
			            <th></th>
			            <th>File Name</th>
			            <th></th>
			            <th>Created By</th>
			            <th>Date</th>
			            <th>Operation</th>
			        </tr>
			    </thead>
			    <tbody>
			    <?php
			    if($x == "1")
			    {
			    	$a = $x;
			    }
			    else
			    {
    				$a = (($x - 1) * $display_file) + 1;
			    }
		    	foreach ($file_details as $file) {
		    		$created_date = date('jS M, Y (g:i A)',strtotime($file['create_time']));
		    		$created_user = $this->main_model->fetch_user_name($file['created_by']);
		    		echo "<tr>";
		    		echo "<td>$a</td>";
		    		echo "<td>";
		    		if($file['file_format'] == "xls" || $file['file_format'] == "xlsx")
		    		{
		    			echo '<i class="fa fa-file-excel-o" title="Excel File" style="color:#217346;"></i>';
		    			// echo "<img src='".base_url()."img/excel.png' alt=''>";
		    		}
		    		elseif($file['file_format'] == "doc" || $file['file_format'] == "docx")
		    		{
		    			echo '<i class="fa fa-file-word-o" title="Word File"  style="color:#2B579A;"></i>';
		    			// echo "<img src='".base_url()."img/word.png' alt=''>";
		    		}
		    		elseif($file['file_format'] == "pdf")
		    		{
		    			echo '<i class="fa fa-file-pdf-o" title="PDF File" style="color:#8B0505;"></i>';
		    			// echo "<img src='".base_url()."img/pdf.png' alt=''>";
		    		}
		    		echo "</td>";
		    		echo "<td title='".$file['file_name']."'>".$file['file_name']."</td>";
		    		if(strtolower($login_user_detail['permission']) != "read only")
				    {
		    			echo "<td><i class='fa fa-pencil edit-name'></i></td>";
				    }
				    else
				    {
				    	echo "<td></td>";
				    }
		    		echo "<td>".$created_user['name']."<br><span>".$created_date."</span></td>";
		            echo "<td>";
		            echo date('jS M, Y g:i A',strtotime($file['modify_time']));
		            echo "</td>";
		            echo "<td>";
		            	if($file['file_format'] == "xls" || $file['file_format'] == "xlsx")
			    		{
			    			echo '<a href="'.base_url().'view/openfile/'.$file['encrypt_name'].'"><i class="fa fa-folder-open-o open-file"  title="Open File"></i></a>';
			    			
			    			if(strtolower($login_user_detail['permission']) != "read only")
			    			{
				    			echo form_open_multipart('view/overright_file','class="overright-file-form"');
				            	echo '<i class="fa fa-cloud-upload upload-file" style="cursor:pointer" title="Overright File"></i>';
				            	echo '<input type="file"  name="file" class="hidden select-overright-file" accept=".xls,.xlsx,.pdf,.doc,.docx">';
				            	echo '<input type="hidden"  name="row_id" value="'.$file['id'].'">';
				            	echo form_close();
			    			}
			    			//if file is created offline..
			    			if($file['created_as'] == "offline")
			    			{
			    				//if file is one time modified..
				    			if($file['update_status'] == "1")
				    			{
				    				//download excel file after converting it in excel..
				    				//using php-excel-create for create excel file and download it..
			    					echo "<a href='".base_url()."view/download_excel/".$file['encrypt_name']."' target='_blank'><i class='fa fa-cloud-download download-file' title='Download File'></i></a>";
				    			}
				    			//excute if file is not modifiled once..
				    			//then direct download excel file..
				    			else
				    			{
				    				//download direct excel file..
				    				$excel_file_name = $file['encrypt_name'].".".$file['file_format'];
			    					echo "<a href='".base_url()."file-uploads/".$excel_file_name."' target='_blank'><i class='fa fa-cloud-download download-file' title='Download File'></i></a>";
				    			}
			    			}
			    			//if file is created online..
			    			elseif($file['created_as'] == "online")
			    			{
			    				//download excel file after converting it from json usin php-excel-create..
			    				echo "<a href='".base_url()."view/download_excel/".$file['encrypt_name']."' target='_blank'><i class='fa fa-cloud-download download-file' title='Download File'></i></a>";
			    			}
			    			echo '<i class="fa fa-trash-o delete-file" id="'.$file['id'].'" title="Delete File"></i>';
			    		}
			    		//excute if there is a word or pdf file (doc,docx,pdf)
			    		elseif($file['file_format'] == "doc" || $file['file_format'] == "docx" || $file['file_format'] == "pdf")
			    		{
			    			$doc_file_name = $file['encrypt_name'].".".$file['file_format'];
			    			
			    			if(strtolower($login_user_detail['permission']) != "read only")
			    			{
				    			echo form_open_multipart('view/overright_file','class="overright-file-form"');
				            	echo '<i class="fa fa-cloud-upload upload-file" style="cursor:pointer" title="Overright File"></i>';
				            	echo '<input type="file"  name="file" class="hidden select-overright-file" accept=".xls,.xlsx,.pdf,.doc,.docx">';
				            	echo '<input type="hidden"  name="row_id" value="'.$file['id'].'">';
				            	echo form_close();
			    			}

			    			echo "<a href='".base_url()."file-uploads/".$doc_file_name."' target='_blank'><i class='fa fa-cloud-download download-file' title='Download File'></i></a>";
			    			echo '<i class="fa fa-trash-o delete-file" id="'.$file['id'].'" title="Delete File"></i>';
			    		}
		            echo "</td>";
		    		echo "</tr>";
		    		$a++;
		    	}
		    	echo '</tbody>
				</table>';
			   
			}
			//execute if $file_details array is empty
			else
			{
				?>
				<div class="display-error">
					There is no any file !
				</div>
				<?php
			}
		}
		if(isset($_POST['index']))
		{
			$file_name = $_POST['file_name'];
			$created_by = $_POST['created_by'];
			$query_total_files = $this->main_model->fetchallfiles($file_name,$created_by);
			$total_num_of_files = $query_total_files[1];

			$pagination_num = ceil($total_num_of_files / $display_file);
			

			$starting_index = $_POST['index'];
			$previous = $starting_index - 1;
			if($starting_index != $pagination_num)
			{
				$next = $starting_index + 1;
			}
			elseif($starting_index == $pagination_num)
			{
				$next = $starting_index;
			}

			if($starting_index == 1 || $starting_index == 2 || $starting_index == 3 || $starting_index == 4)
			{
				if($pagination_num > 8)
				{
					if($starting_index != 1)
					{
						echo "<li class='".$previous."' ><<</li>";	
					}
					
					for($a = 1; $a <= 8; $a++)
					{
						if($a == $starting_index)
						{
							echo "<li class='".$a." li-bgclr'>$a</li>";
						}
						else
						{
							echo "<li class='".$a."'>$a</li>";
						}
						
					}
					echo "<li class='".$next."'>>></li>";
					echo "....";
					echo "<li class='".$pagination_num."'>Last</li>";
							

				}
				else
				{

					if($starting_index != 1)
					{
						echo "<li class='".$previous."' ><<</li>";	
					}
					for($a = 1; $a <= $pagination_num; $a++)
					{
						if($a == $starting_index)
						{
							echo "<li class='".$a." li-bgclr'>$a</li>";
						}
						else
						{
							echo "<li class='".$a."'>$a</li>";
						}
					}
					if($starting_index != $pagination_num)
					{
					echo "<li class='".$next."'>>></li>";	
					}	

				}	
			}
			elseif($starting_index == $pagination_num || $starting_index == ($pagination_num - 1) 
				|| $starting_index == ($pagination_num - 2) || $starting_index == ($pagination_num - 3) )
			{
				if($pagination_num > 8)
				{
					echo "<li class='1'>First</li>";
					echo "...";
					echo "<li class='".$previous."'><<</li>";
					for($a = $pagination_num - 7 ; $a <= $pagination_num; $a++)
					{
						if($a == $starting_index)
						{
							echo "<li class='".$a." li-bgclr'>$a</li>";
						}
						else
						{
							echo "<li class='".$a."'>$a</li>";
						}
					}
					if($starting_index != $pagination_num)
					{
						echo "<li class='".$next."'>>></li>";
					}
					
				}
				else
				{
					echo "<li class='".$previous."'><<</li>";
					for($a = 1; $a <= $pagination_num; $a++)
					{
						if($a == $starting_index)
						{
							echo "<li class='".$a." li-bgclr'>$a</li>";
						}
						else
						{
							echo "<li class='".$a."'>$a</li>";
						}
					}
					if($starting_index != $pagination_num)
					{
						echo "<li class='".$next."'>>></li>";
					}
					

				}	
			}
			//execute when click on center pagination number.
			else
			{
				if($pagination_num > 8)
				{
					$starting = $starting_index - 3;
					$ending = $starting_index + 3;

					echo "<li class='1'>First</li>";
					echo "...";
					echo "<li class='".$previous."'><<</li>";
					for($a = $starting; $a <= $ending; $a++)
					{
						if($a == $starting_index)
						{
							echo "<li class='".$a." li-bgclr'>$a</li>";
						}
						else
						{
							echo "<li class='".$a."'>$a</li>";
						}
						
					}
					echo "<li class='".$next."'>>></li>";
					echo "...";
					echo "<li class='".$pagination_num."'>Last</li>";
					
				}
				else
				{
					echo "<li class='".$previous."'><<</li>";
					for($a = 1; $a <= $pagination_num; $a++)
					{
						if($a == $starting_index)
						{
							echo "<li class='".$a." li-bgclr'>$a</li>";
						}
						else
						{
							echo "<li class='".$a."'>$a</li>";
						}
					}
					echo "<li class='".$next."'>>></li>";
				}
			}
		}
	}

	function check_file_name()
	{
		$file_name = $_POST['file_name'];
		$this->load->model('main_model');
		$query = $this->main_model->fetchbyfield('file_name',$file_name,"excel_file");
		if($query)
		{
			echo "exist";
		}
		else
		{
			echo "not exist";
		}
	}

	function update_file_name()
	{
		$data['file_name'] = $_POST['file_name'];
		$id = $_POST['id'];
		$this->load->model('main_model');
		$query = $this->main_model->updatedata($data,$id,'excel_file');
	}

	function overright_file()
	{
		$this->load->model("main_model");
		$username = $this->session->userdata('colombia_username');
		$user_detail = $this->main_model->fetchbyfield('username',$username,'user');
		$id = $this->input->post('row_id');
		
		// check if any file is selected..
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
				$update_data['encrypt_name'] = $encrypted_filename;//encrypted file name..
				$update_data['file_format'] = $extension;//file format..
				$update_data['columns'] = $columns;//file format..
				$update_data['created_by'] = $user_detail['username'];
				$update_data['created_as'] = "offline";
				date_default_timezone_set('Asia/Calcutta');
				$insert_data['modify_time'] = date("y-m-d H:i:s");

				$query = $this->main_model->updatedata($update_data,$id,'excel_file');
				// if data is successfully submited into course table into database..
	            if($query)
	            {
					
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

				//************ // end save excel file into directory ****************

				//************* save data into database *************
				$columns = "";
				$update_data['encrypt_name'] = $encrypted_filename;//encrypted file name..
				$update_data['file_format'] = $extension;//file format..
				$update_data['columns'] = $columns;//file format..
				$update_data['created_by'] = $user_detail['username'];
				$update_data['created_as'] = "offline";
				date_default_timezone_set('Asia/Calcutta');
				$update_data['modify_time'] = date("y-m-d H:i:s");

				$query = $this->main_model->updatedata($update_data,$id,'excel_file');
				
				//************* // end save data into database *************
			}
		}//end if condition
	}

	function new_file_detail()
	{
		$this->load->model("main_model");
		$username = $this->session->userdata('colombia_username');
		$login_user_detail = $this->main_model->fetchbyfield('username',$username,'user');
		$id = $_POST['id'];
		$file = $this->main_model->fetchbyid($id,'excel_file');
		$created_date = date('jS M, Y (g:i A)',strtotime($file['create_time']));
		$created_user = $this->main_model->fetch_user_name($file['created_by']);

		echo "<td></td>";
		echo "<td>";
		if($file['file_format'] == "xls" || $file['file_format'] == "xlsx")
		{
			echo '<i class="fa fa-file-excel-o" title="Excel File" style="color:#217346;"></i>';
			// echo "<img src='".base_url()."img/excel.png' alt=''>";
		}
		elseif($file['file_format'] == "doc" || $file['file_format'] == "docx")
		{
			echo '<i class="fa fa-file-word-o" title="Word File"  style="color:#2B579A;"></i>';
			// echo "<img src='".base_url()."img/word.png' alt=''>";
		}
		elseif($file['file_format'] == "pdf")
		{
			echo '<i class="fa fa-file-pdf-o" title="PDF File" style="color:#8B0505;"></i>';
			// echo "<img src='".base_url()."img/pdf.png' alt=''>";
		}
		echo "</td>";
		echo "<td title='".$file['file_name']."'>".$file['file_name']."</td>";
		if(strtolower($login_user_detail['permission']) != "read only")
	    {
			echo "<td><i class='fa fa-pencil edit-name'></i></td>";
	    }
	    else
	    {
	    	echo "<td></td>";
	    }
		echo "<td>".$created_user['name']."<br><span>".$created_date."</span></td>";
        echo "<td>";
        echo date('jS M, Y g:i A',strtotime($file['modify_time']));
        echo "</td>";
        echo "<td>";
        	if($file['file_format'] == "xls" || $file['file_format'] == "xlsx")
    		{
    			echo '<a href="'.base_url().'view/openfile/'.$file['encrypt_name'].'"><i class="fa fa-folder-open-o open-file"  title="Open File"></i></a>';
    			
    			if(strtolower($login_user_detail['permission']) != "read only")
    			{
	    			echo form_open_multipart('view/overright_file','class="overright-file-form"');
	            	echo '<i class="fa fa-cloud-upload upload-file" style="cursor:pointer" title="Overright File"></i>';
	            	echo '<input type="file"  name="file" class="hidden select-overright-file" accept=".xls,.xlsx,.pdf,.doc,.docx">';
	            	echo '<input type="hidden"  name="row_id" value="'.$file['id'].'">';
	            	echo form_close();
    			}
    			//if file is created offline..
    			if($file['created_as'] == "offline")
    			{
    				//if file is one time modified..
	    			if($file['update_status'] == "1")
	    			{
	    				//download excel file after converting it in excel..
	    				//using php-excel-create for create excel file and download it..
    					echo "<a href='".base_url()."view/download_excel/".$file['encrypt_name']."' target='_blank'><i class='fa fa-cloud-download download-file' title='Download File'></i></a>";
	    			}
	    			//excute if file is not modifiled once..
	    			//then direct download excel file..
	    			else
	    			{
	    				//download direct excel file..
	    				$excel_file_name = $file['encrypt_name'].".".$file['file_format'];
    					echo "<a href='".base_url()."file-uploads/".$excel_file_name."' target='_blank'><i class='fa fa-cloud-download download-file' title='Download File'></i></a>";
	    			}
    			}
    			//if file is created online..
    			elseif($file['created_as'] == "online")
    			{
    				//download excel file after converting it from json usin php-excel-create..
    				echo "<a href='".base_url()."view/download_excel/".$file['encrypt_name']."' target='_blank'><i class='fa fa-cloud-download download-file' title='Download File'></i></a>";
    			}
    			echo '<i class="fa fa-trash-o delete-file" id="'.$file['id'].'" title="Delete File"></i>';
    		}
    		//excute if there is a word or pdf file (doc,docx,pdf)
    		elseif($file['file_format'] == "doc" || $file['file_format'] == "docx" || $file['file_format'] == "pdf")
    		{
    			$doc_file_name = $file['encrypt_name'].".".$file['file_format'];
    			
    			if(strtolower($login_user_detail['permission']) != "read only")
    			{
	    			echo form_open_multipart('view/overright_file','class="overright-file-form"');
	            	echo '<i class="fa fa-cloud-upload upload-file" style="cursor:pointer" title="Overright File"></i>';
	            	echo '<input type="file"  name="file" class="hidden select-overright-file" accept=".xls,.xlsx,.pdf,.doc,.docx">';
	            	echo '<input type="hidden"  name="row_id" value="'.$file['id'].'">';
	            	echo form_close();
    			}

    			echo "<a href='".base_url()."file-uploads/".$doc_file_name."' target='_blank'><i class='fa fa-cloud-download download-file' title='Download File'></i></a>";
    			echo '<i class="fa fa-trash-o delete-file" id="'.$file['id'].'" title="Delete File"></i>';
    		}
        echo "</td>";
	}
}
?>