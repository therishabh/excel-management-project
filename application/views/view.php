<div class="row">
	<div class="col-lg-12 ">
		<!-- heading -->
		<div class="row">
			<div class="col-lg-12 main-heading">
				<div class="row">
					<div class="col-lg-3">
						View File
					</div>
					<div class="col-lg-6" >
						<div class="display-msg">
							
						</div>
						<div id="progress" class="progress progress-success progress-striped">
						    <div class="progress-bar" style="width:0%;"></div>
						</div>
						<div id="percent-progress"></div>
					</div>
					<div class="col-lg-3"></div>
				</div>
			</div>
		</div>
		<!-- // end heading -->

		

		<div class="row files-view">
			<div class="col-lg-12" id="main-content-body" style="overflow-y: scroll;">

				<div class="row">
					<div class="col-lg-11 col-centered" style="margin-top:30px">
						<?php echo form_open_multipart('view','id="search-form"');?>
						<div class="row">
							<div class="col-lg-6">
								<input type="text" class="form-control" value="<?php echo ( isset($search_file_name) ? $search_file_name : "" ) ?>" placeholder="Type File Name.." name="file_name">
							</div>
							<div class="col-lg-3">
								<select name="created_by" class="form-control">
									<option value="">Select User</option>
									<?php
									foreach ($user_details as $users) {
									 	echo '<option value="'.$users['username'].'"';
									 	echo $search_created_by == $users['username'] ? "selected" : "";
									 	echo '>'.$users['name'].'</option>';
									 } 
									 ?>
									
								</select>
								
							</div>
							<div class="col-lg-3">
								<div class="row">
									<div class="col-lg-6">
										<div class="submit-btn search-btn">Search</div>
									</div>
									<div class="col-lg-6">
										<div class="cancel-btn reset-btn">Reset</div>
									</div>
								</div>
								<input type="submit" value="success" class="hidden" name="search_btn">
								<input type="reset" value="success" class="hidden" name="reset_btn">
							</div>
						</div>
						<?php echo form_close(); ?>
						
						
					</div>
				</div>
				<div class="row" style="margin-top:20px;">
					<div class="col-lg-11 col-centered display-files">
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
						//execute if there is any file in database..
				    	//check if $file_details array is empty or not..
				    	if(!empty($file_details[0])) 
						{   
			    		$a = 1;
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
				    	}//end foreach loop..
				    }//end if condition
				    	echo '</tbody>
						</table>';
					if(empty($file_details[0])) 
					{
						?>
						<div class="display-error">
							Search Result Not Found !
						</div>
						<?php
					}
			    	?>
						        
						    
					</div>	
				</div>	
				<div class="row">
					<div class="col-lg-11 col-centered pagination_bar">
						<ul>
							
							<?php
					    	//execute if there is any file in database..
					    	//check if $file_details array is empty or not..
					    	if(!empty($file_details[0])) 
							{   
								if($pagination_num > 9)
								{
									for($a = 1; $a <= 8; $a++)
									{
										if($a == 1)
										{
											echo "<li class='".$a." li-bgclr'>$a</li>";
										}
										else
										{
											echo "<li class='".$a."'>$a</li>";
										}
									}
									echo "<li class='2'>>></li>";
									echo "....";
									echo "<li class='".$pagination_num."'>Last</li>";
								}
								else
								{
									//if there is not only one page..
									if($pagination_num != "1")
									{
										for($a = 1; $a <= $pagination_num; $a++)
										{
											if($a == 1)
											{
												echo "<li class='".$a." li-bgclr'>$a</li>";
											}
											else
											{
												echo "<li class='".$a."'>$a</li>";
											}
										}
										echo "<li class='2'>>></li>";
									}
								}
							}
							?>

						</ul>
					</div>
				</div>

			</div><!-- // end col-lg-12 id="main-content-body" -->
		</div><!-- // end row -->
	</div>
</div><!-- // end row open-file-section -->

<div class="modal fade" id="file-upload-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Upload File</h4>
      </div>
      <div class="modal-body">
        <i class="fa fa-exclamation-triangle"></i>
        <span>
        	Do you want to Overright File ?
        </span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary upload-yes-btn" data-dismiss="modal">Sure</button>
        <button type="button" class="btn btn-default upload-no-btn" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="file-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Delete File</h4>
      </div>
      <div class="modal-body">
        <i class="fa fa-exclamation-triangle"></i>
        <span>
        	Do you want to Delete File ?
        </span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary delete-yes-btn" data-dismiss="modal">Yes</button>
        <button type="button" class="btn btn-default delete-no-btn" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>


<div class="hidden hidden-file-name"></div>
<div class="hidden hidden-id"></div>
<div class="hidden hidden-row-id"></div>
<script type="text/javascript">
jQuery(document).ready(function($) {

	//script for delete any file..
	$("#main-content-body").on('click', '.delete-file', function() {

		var id = $(this).attr("id");
		$(".hidden-row-id").text(id);
		$(this).parent().parent().children().css({
				'background': 'rgb(178, 4, 4)',
				'color':'#fff'
		});
		$(this).parent().parent().children().children().css({
				'color':'#fff'
		});

		$(this).parent().parent().addClass('selected-tr');

		$("#file-delete-modal").modal("show");
	});
	//end script for delete any file...

	//script for click search button..
	$(".search-btn").click(function() {
		$( "input[name='search_btn']" ).click();
	});
	// end script for click on search button..

	//script for click reset button..
	$(".reset-btn").click(function() {
		location.reload(true);
	});
	// end script for click on reset button..

	$("#main-content-body").on('click', '.fa-pencil.edit-name', function() {
		$(".edit-name").removeClass('fa-check-circle');
		$(".edit-name").addClass('fa-pencil');
		$(".selected-row").html($(".hidden-file-name").text());
		$(".selected-row").removeClass('selected-row');

		$(this).addClass('fa-check-circle');
		$(this).removeClass('fa-pencil');
		
		var file_name = $(this).parent().prev().text();
		$(".hidden-file-name").text(file_name);
		$(this).parent().prev().html("<input type='text' class='form-control' id='file_name_textbox' value='"+file_name+"'>");
		$(this).parent().prev().children().focus();
		$(this).parent().prev().addClass('selected-row');
	});

	$("#main-content-body").on('click', '.fa-check-circle.edit-name', function(){
		var $tick_btn = $(this);
		var file_name = $(this).parent().prev().children().val();
		var id = $(this).parent().parent().children().last().children('.delete-file').attr('id');
		if( $(".hidden-file-name").text() == file_name )
		{
			$(".edit-name").removeClass('fa-check-circle');
			$(".edit-name").addClass('fa-pencil');
			$(".selected-row").html($(".hidden-file-name").text());
			$(".selected-row").removeClass('selected-row');
			$(".hidden-file-name").text("");

			$(".display-msg").show();
			$('.display-msg').removeClass('display-error');
			$(".display-msg").addClass('display-success');
			$(".display-msg").text("File name has been successfully updated !");

			setTimeout(function(){	
				$(".display-msg").delay(2000).fadeOut(2000);

			},1600)
		}
		else
		{
			$.ajax({
				url: '<?php echo base_url();?>index.php/view/check_file_name',
				type: 'POST',
				data: {file_name: file_name},
				success: function(result){
					//execute if file name is exist into database.. then show error msg..
					if(result == "exist")
					{
						$tick_btn.parent().prev().addClass('has-error');
						$tick_btn.parent().prev().children().focus();
						$(".display-msg").show();
						$('.display-msg').removeClass('display-success');
						$(".display-msg").addClass('display-error');
						$(".display-msg").text("File name already exist please choose another name !");
					}
					//execute if file name is not exist into database.. then update new file name..
					else{
						$tick_btn.parent().prev().removeClass('has-error');
						$(".edit-name").removeClass('fa-check-circle');
						$(".edit-name").addClass('fa-pencil');
						$(".selected-row").html(file_name);
						$(".selected-row").removeClass('selected-row');
						$(".hidden-file-name").text("");
						$(".display-msg").show();
						$('.display-msg').removeClass('display-error');
						$(".display-msg").addClass('display-success');
						$(".display-msg").text("File name has been successfully updated !");

						setTimeout(function(){	
							$(".display-msg").delay(2000).fadeOut(2000);
						},1600)
						$.ajax({
							url: '<?php echo base_url();?>index.php/view/update_file_name',
							type: 'POST',
							data: {'file_name': file_name, "id" : id},
							success: function(result){
							}
						});
						
					}
				}
			})
		}
	});

	$("#main-content-body").on('mousedown', '#file_name_textbox', function() {
		$(".display-msg").fadeOut(1000);
		$(this).parent().removeClass('has-error');
	});
	$("#main-content-body").on('keypress', '#file_name_textbox', function(e) {
		if(e.which == 13)
		{
			$('.fa-check-circle').click();
		}
	});

	$("#main-content-body").on('click', '.fa-cloud-upload', function() {
		$(this).next().click();
	});

	$("#main-content-body").on('change', '.select-overright-file', function() {
		if($(this).val() != "")
		{
			var select_file = this.files[0];
			var name = select_file.name;
		    size = select_file.size;
		    type = select_file.type;
		    if(size < 10485760)
		    {
				$("#file-upload-modal").modal('show');
				$(this).parent().parent().parent().children().css({
					'background': '#168039',
					'color':'#fff'
				})
				$(this).parent().parent().parent().children().children().css({
					'color':'#fff'
				})
				$(this).parent().parent().parent().addClass('selected-tr');
				$(this).addClass('selected-file');
		    }
		    else
		    {
				$(".display-msg").show();
				$(".display-msg").removeClass('display-success');
				$('.display-msg').addClass('display-error');
				$(".display-msg").text("Please Select less than 10 MB File !");
				setTimeout(function(){	
					$(".display-msg").delay(3000).fadeOut(2000);
				},1600)
		    }

		}
		else
		{
			$(".select-overright-file").removeClass('selected-file');
		}
	});

	$(".upload-no-btn").click(function() {
		$("table tbody tr.selected-tr").children().css({
				'background': '#f9f9f9',
				'color':'#333'
		});
		$("table tbody tr.selected-tr").children().children().css({
				'color':'#333'
		});
		$("table tbody tr.selected-tr").children().children(".fa-file-excel-o").css({
				'color':'#217346'
		});
		$("table tbody tr.selected-tr").children().children(".fa-file-pdf-o").css({
				'color':'#8B0505'
		});
		$("table tbody tr.selected-tr").children().children(".fa-file-word-o").css({
				'color':'#2B579A'
		});
		$("table tbody tr.selected-tr").removeClass('selected-tr');
		$(".select-overright-file").removeClass('selected-file');
		$(".select-overright-file").val("");
	});

	$("body").keyup(function(e) {
		if(e.which == "27")
		{
			$(".upload-no-btn").click();
		}
	});

	$("#file-upload-modal .close").click(function() {
		$(".upload-no-btn").click();
	});

	$("#file-upload-modal").click(function() {
		$(".upload-no-btn").click();
	});

	$("#file-delete-modal .close").click(function() {
		$("table tbody tr.selected-tr").children().css({
				'background': '#f9f9f9',
				'color':'#333'
		});
		$("table tbody tr.selected-tr").children().children().css({
				'color':'#333'
		});
		$("table tbody tr.selected-tr").children().children(".fa-file-excel-o").css({
				'color':'#217346'
		});
		$("table tbody tr.selected-tr").children().children(".fa-file-pdf-o").css({
				'color':'#8B0505'
		});
		$("table tbody tr.selected-tr").children().children(".fa-file-word-o").css({
				'color':'#2B579A'
		});
		$("table tbody tr.selected-tr").removeClass('selected-tr');
	});

	$("#file-delete-modal").click(function() {
		$("table tbody tr.selected-tr").children().css({
				'background': '#f9f9f9',
				'color':'#333'
		});
		$("table tbody tr.selected-tr").children().children().css({
				'color':'#333'
		});
		$("table tbody tr.selected-tr").children().children(".fa-file-excel-o").css({
				'color':'#217346'
		});
		$("table tbody tr.selected-tr").children().children(".fa-file-pdf-o").css({
				'color':'#8B0505'
		});
		$("table tbody tr.selected-tr").children().children(".fa-file-word-o").css({
				'color':'#2B579A'
		});
		$("table tbody tr").removeClass('delete-tr');
		$("table tbody tr.selected-tr").addClass('delete-tr');
		$("table tbody tr.selected-tr").removeClass('selected-tr');
	});

	$("#file-delete-modal .delete-no-btn").click(function() {
		$("table tbody tr.selected-tr").children().css({
				'background': '#f9f9f9',
				'color':'#333'
		});
		$("table tbody tr.selected-tr").children().children().css({
				'color':'#333'
		});
		$("table tbody tr.selected-tr").children().children(".fa-file-excel-o").css({
				'color':'#217346'
		});
		$("table tbody tr.selected-tr").children().children(".fa-file-pdf-o").css({
				'color':'#8B0505'
		});
		$("table tbody tr.selected-tr").children().children(".fa-file-word-o").css({
				'color':'#2B579A'
		});
		
		$("table tbody tr.selected-tr").removeClass('selected-tr');
	});

	$("#file-delete-modal .delete-yes-btn").click(function() {

		var file_name = $( "input[name='file_name']" ).val();
		var created_by = $( "select[name='created_by']" ).val();

		$(".delete-tr").fadeOut(1400);

		setTimeout(function(){	
			$(".delete-tr").remove();
			//reset serial number from due notification table..
			$(".table tbody tr").each(function(id){
				var a = ((parseInt(current_page) - 1) * 5) + (id + 1);
				$(this).children().first().html(a);	
			});
			
		},1500)

		var id = $(".hidden-row-id").text();
		$(".pace-active").show();
		$.ajax({
			url: "<?php echo base_url();?>index.php/view/delete_file",
			data: {"id":id}, //returns all cells' data
			// dataType: 'json',
			type: 'POST',
			async:false, 
			success: function (result) {
				$(".pace-active").fadeOut(2000);
			},
			error: function () {
			  	console.log("error");
			}
		});

		var last_row_id = $(".display-files .table tr:last-child td:last-child").children('.delete-file').attr('id');
		$.ajax({
			url: "<?php echo base_url();?>index.php/view/fetch_next_file",
			data: {"last_row_id":last_row_id,"file_name":file_name,"created_by":created_by},
			type: 'POST',
			success: function (result) {
				$(".display-files .table tbody tr:last-child").after(result);
			},
			error: function () {
			  	console.log("error");
			}
		});

		$(".display-msg").show();
		$(".display-msg").addClass('display-success');
		$(".display-msg").text("File Has Been Successfully Deleted !");
			

		var current_page = $(".pagination_bar ul li.li-bgclr").text();

		setTimeout(function(){	
			//reset serial number from due notification table..
			$(".table tbody tr").each(function(id){
				var a = ((parseInt(current_page) - 1) * 5) + (id + 1);
				$(this).children().first().html(a);	
			});
			$(".display-msg").delay(2000).fadeOut(2000);

		},1600)

	});

	//****************************//
	//********** upload file with ajax ****************//
	//****************************//
	$('#progress').hide();
	var options = { 
		target:   '.display-msg',   // target element(s) to be updated with server response 
		beforeSubmit:  beforeSubmit,  // pre-submit callback 
		success:       afterSuccess,  // post-submit callback 
		uploadProgress: OnProgress, //upload progress callback 
		resetForm: true  // reset the form after successful submit 
	}; 

	$(".upload-yes-btn").click(function() {
		$('.selected-tr .overright-file-form').ajaxSubmit(options);
		$('.selected-tr').addClass('change-tr');
		$(".hidden-id").text( $('.selected-tr .overright-file-form input[name="row_id"]').val() ); 
			
		$(".upload-no-btn").click();
		return false;
		// always return false to prevent standard browser submit and page navigation 
	});



	//function after succesful file upload (when server response)
	function afterSuccess()
	{
		$('#progress').delay( 1000 ).fadeOut(); //hide progress bar
		$("#percent-progress").delay( 1000 ).fadeOut();
		setTimeout(function(){	
			$(".display-msg").show();
			$('.display-msg').removeClass('display-error');
			$(".display-msg").addClass('display-success');
			$(".display-msg").text("File name has been successfully updated !");
		},1500)

		setTimeout(function(){	
			$(".display-msg").delay(2000).fadeOut(2000);
		},2000)

		var id = $(".hidden-id").text();
		var $tr = $(".change-tr");
		$.ajax({
			url: '<?php echo base_url();?>index.php/view/new_file_detail',
			type: 'POST',
			data: { "id" : id},
			success: function(result){
				console.log(result);
				$tr.html(result);
				
				var current_page = $(".pagination_bar ul li.li-bgclr").text();
				
				//reset serial number from due notification table..
				$(".table tbody tr").each(function(id){
					var a = ((parseInt(current_page) - 1) * 5) + (id + 1);
					$(this).children().first().html(a);	
				});
				$tr.removeClass('change-tr');
			}
		});


	}


	//function to check file size before uploading.
	function beforeSubmit(){
	    //check whether browser fully supports all File API
	   if (window.File && window.FileReader && window.FileList && window.Blob)
		{
			
		}
		else
		{
			//Output error to older unsupported browsers that doesn't support HTML5 File API
			$("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
			return false;
		}
	}


	//progress bar function
	function OnProgress(event, position, total, percentComplete)
	{
	    //Progress bar
		$('#progress').show();
	    $('#progress .progress-bar').width(percentComplete + '%') //update progressbar percent complete
	    $('#percent-progress').html(percentComplete + '%'); //update status text
	}


	

<?php
for($a = 1; $a <= $pagination_num; $a++)
{
?>

	$(".pagination_bar").on('click', '.<?php echo $a;?>', function(event) {
		var file_name = $( "input[name='file_name']" ).val();
		var created_by = $( "select[name='created_by']" ).val();
		var starting_index = "<?php echo $a; ?>";
		$(".pace-active").show();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>index.php/view/pagination",
			data: { starting_index:starting_index, file_name : file_name, created_by: created_by}
		}).success(function(result){
			$('.display-files').html(result);
		});


		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>index.php/view/pagination",
			data: { index:starting_index, file_name : file_name, created_by: created_by }
		}).success(function(result){
			$('.pagination_bar ul').html(result);
			$(".pace-active").fadeOut(1000);
		});
	});

<?php
}
?>

});
</script>