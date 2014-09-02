<div class="row">
	<div class="col-lg-12 ">
		<!-- heading -->
		<div class="row">
			<div class="col-lg-12 main-heading">
				View File
			</div>
		</div>
		<!-- // end heading -->

		

		<div class="row files-view">
			<div class="col-lg-12" id="main-content-body">

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
						
						    	<?php 
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
							    		echo "<td><i class='fa fa-pencil edit-name'></i></td>";
							    		echo "<td>".$created_user['name']."<br><span>".$created_date."</span></td>";
							            echo "<td>";
							            	if($file['update_status'] == "1")
							            	{
							            		echo date('jS M, Y g:i A',strtotime($file['modify_time']));
							            	}
							            	else
							            	{
							            		echo date('jS M, Y g:i A',strtotime($file['create_time']));
							            	}
							            echo "</td>";
							            echo "<td>";
							            	if($file['file_format'] == "xls" || $file['file_format'] == "xlsx")
								    		{
								    			echo '<a href="'.base_url().'view/openfile/'.$file['encrypt_name'].'"><i class="fa fa-folder-open-o open-file"  title="Open File"></i></a>';
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
								    			// echo "<img src='".base_url()."img/delete.png' alt='Delete Image' class='delete-file' id='".$file['id']."' title='Delete File'> ";
								    		}
								    		//excute if there is a word or pdf file (doc,docx,pdf)
								    		elseif($file['file_format'] == "doc" || $file['file_format'] == "docx" || $file['file_format'] == "pdf")
								    		{
								    			$doc_file_name = $file['encrypt_name'].".".$file['file_format'];
								    			// echo "<a href='".base_url()."file-uploads/".$doc_file_name."' target='_blank'><img src='".base_url()."/img/download.png' alt='Download Image' class='download-file' title='Download File'></a> ";
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
									<div class="error-msg">
										There is no any file !
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

<script type="text/javascript">
jQuery(document).ready(function($) {

	//script for delete any file..
	$("#main-content-body").on('click', '.delete-file', function() {
		
		var $delete_btn = $(this);
		var file_name = $( "input[name='file_name']" ).val();
		var created_by = $( "select[name='created_by']" ).val();
		
		var confirm_check = confirm("Do you want to delete this file ?");
		if(confirm_check == true)
		{
			$(this).parent().parent().children().css({
				'background': 'red',
				'color':'#fff'
			}).fadeOut(1400);


			var current_page = $(".pagination_bar ul li.li-bgclr").text();
			setTimeout(function(){
				
				$delete_btn.parent().parent().remove();
				
				//reset serial number from due notification table..
				$(".table tbody tr").each(function(id){
					var a = ((parseInt(current_page) - 1) * 5) + (id + 1);
					$(this).children().first().html(a);	
				});

			},1400)

			var id = $(this).attr("id");

			$.ajax({
				url: "<?php echo base_url();?>index.php/view/delete_file",
				data: {"id":id}, //returns all cells' data
				// dataType: 'json',
				type: 'POST',
				async:false, 
				success: function (result) {
					$(this).parent().parent().children().fadeOut(1400);	
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

			setTimeout(function(){
				
				//reset serial number from due notification table..
				$(".table tbody tr").each(function(id){
					var a = ((parseInt(current_page) - 1) * 5) + (id + 1);
					$(this).children().first().html(a);	
				});

			},1600)
		}
		else
		{
			//do nothing
		}
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


<?php
for($a = 1; $a <= $pagination_num; $a++)
{
?>

	$(".pagination_bar").on('click', '.<?php echo $a;?>', function(event) {
		var file_name = $( "input[name='file_name']" ).val();
		var created_by = $( "select[name='created_by']" ).val();
		var starting_index = "<?php echo $a; ?>";
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
		});
	});

<?php
}
?>

});
</script>