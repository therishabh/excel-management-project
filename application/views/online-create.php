<div class="row">
	<div class="col-lg-12 ">

		<!-- heading -->
		<div class="row">
			<div class="col-lg-12 main-heading">
				Create Excel File
			</div>
		</div>
		<!-- // end heading -->
		
		<div class="row online-create">
			<div class="col-lg-12" id="main-content-body">
				
				<div class="row">
					<div class="col-lg-12">
						<div class="msg">
							<div class="success-msg">
								<?php
								if( $this->session->userdata('upload_success') != "" )
								{
									echo "File Has Been Successfully Uploaded.";
								}
								?>
							</div>
							<div class="error-msg">
								
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-2">
						<div class="text-label">File Name : </div>
					</div>
					<div class="col-lg-7">
						<input type="text" class="form-control" id="file-name" name="file_name">
					</div>
					<div class="col-lg-3">
						<div class="file-name-msg">
							
						</div>
					</div>
				</div>
				<div class="row" style="margin-top:25px;">
					<div class="col-lg-12">
						<div id="excel_div" style="width: 100%; height: 300px; overflow: auto" class="handsontable"></div>
					</div>
				</div>
				<div class="row" style="margin-top:25px;">
					<div class="col-lg-6 col-centered">
						<div class="row">
							<div class="col-lg-5">
								<div class="submit-btn">Submit</div>
							</div>
							<div class="col-lg-5 col-lg-offset-2">
								<div class="cancel-btn">Cancel</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>

	</div>
	<!-- end col-lg-12 -->
</div>
<!-- end row -->
<div class="file-name-hidden hidden">0</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
    var $excel_div = $('#excel_div');
	var $parent = $excel_div.parent();//body...
  
	$excel_div.handsontable({	
		startRows: 11,
		startCols: 11,
		colWidths: 88, //can also be a number or a function

		rowHeaders: true,
		colHeaders: true,

		//resize row and columns
		manualColumnResize: true,
  		manualRowResize: true,

		// 	//add one extra row and columns
		minSpareRows: 1,
		minSpareCols: 1,

		stretchH: 'all',
		contextMenu: true,

		//Column & row move
		manualColumnMove: true,
		manualRowMove: true,
		columnSorting:true

	});

	$excel_div.handsontable('render'); //refresh the grid to display the new value
	var handsontable = $excel_div.data('handsontable');

	$("#file-name").focusout(function() {
		if($(this).val() == "")
		{
			$(".file-name-msg").text("Please do not leave blank");
			$(".file-name-msg").addClass('file-error');
			$(".file-name-msg").removeClass('file-success-msg');
			$(this).parent().addClass('has-error');
			$(".file-name-hidden").text("0");
		}
		else
		{
			var file_name = $(this).val();
			$.ajax({
				url: '<?php echo base_url();?>index.php/offlinecreate/check_file_name',
				type: 'POST',
				data: {file_name: file_name},
				success: function(result){
					//if file is not exist into database..
					if(result == "exist")
					{
						$(".file-name-msg").text("Name Already Exist !");
						$(".file-name-msg").addClass('file-error');
						$("#file-name").parent().addClass('has-error');
						$(".file-name-msg").removeClass('file-success-msg');
						$(".file-name-hidden").text("0");
					}
					// if file is exist into database..
					else
					{
						$(".file-name-msg").text("Name Looks Good !");
						$("#file-name").parent().removeClass('has-error');
						$(".file-name-msg").removeClass('file-error');
						$(".file-name-msg").addClass('file-success-msg');
						$(".file-name-hidden").text("1");

					}
				}//end success
			});			
		}

	});


 	$(".submit-btn").click(function() {
 		var file_name = $("#file-name").val();
 		var columns_name = "";
 		var total = $("#excel_div .ht_clone_top table thead th:not(:first)").length;
 		$("#excel_div .ht_clone_top table thead th:not(:first)").each(function(index){
 			if (index === total - 1)
 			{
 				columns_name += $(this).children('div').text();
 			}
 			else
 			{
 				columns_name += $(this).children('div').text()+"/";

 			}
 		});
 		
 		if($(".file-name-hidden").text() == "1")
 		{
			$.ajax({
				url: "<?php echo base_url();?>index.php/onlinecreate/savedata",
				data: {"data":handsontable.getData(), "file_name":file_name,"columns":columns_name}, //returns all cells' data
				// dataType: 'json',
				type: 'POST',
				async:false, 
				success: function (result) {
					console.log(result);
					if(result == "success")
					{
						location.reload(true);
					}
					else
					{
						$(".error-msg").text("Please Do Not Save Blank Excel File !");
						$(".error-msg").delay('2000').fadeOut(1000);
						setTimeout(function(){
							$(".error-msg").text('');
							$(".error-msg").show();
						}, 4000);
					}				  	
				},
				error: function () {
				  	console.log("error");
				}
			});
 		}
 		else
 		{
 			$(".file-name-msg").text("Please do not leave blank");
			$(".file-name-msg").addClass('file-error');
			$(".file-name-msg").removeClass('file-success-msg');
 			$("#file-name").parent().addClass('has-error');
 		}
 	});
 	$(".cancel-btn").click(function() {
 		location.reload(true);
 	});
 	$(".success-msg").delay('5000').fadeOut(1000);
 	
});
</script>
<?php
$this->session->unset_userdata('upload_success');
?>