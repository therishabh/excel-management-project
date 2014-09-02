<div class="row">
	<div class="col-lg-12 ">
		<!-- heading -->
		<div class="row">
			<div class="col-lg-12 main-heading">
				Upload Excel File
			</div>
		</div>
		<!-- // end heading -->
	
		<div class="row" >
			<div class="col-lg-12" id="main-content-body">
				<div class="main-content">
					<?php echo form_open_multipart('offlinecreate','id="upload-form"');?>
					<div class="row offline-create">
						<div class="col-lg-8 col-centered">
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
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-3">
									<div class="text-label">File Name : </div>
								</div>
								<div class="col-lg-6">
									<input type="text" class="form-control" id="file-name" name="file_name">
								</div>
								<div class="col-lg-3">
									<div class="file-name-msg">
										
									</div>
								</div>
							</div>

							<div class="row" style="margin-top:25px;">
								<div class="col-lg-3">
									<div class="text-label">Upload Excel File : </div>
								</div>
								<div class="col-lg-6">
									<div class="upload upload-excel-file">Upload Excel (.xls) File</div>
									<input type="file" class="hidden" name="file" id="file-upload" accept=".xls,.xlsx,.pdf,.doc,.docx">
								</div>
								<div class="col-lg-3">
									<div class="file-msg file-success-msg">
										
									</div>
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
									<input type="submit" class="hidden" id="form-submit-btn" value="success" name="submit_btn">
								</div>
									
							</div>
						</div>
					</div>
					<?php echo form_close(); ?>

				</div>
			</div>
		</div>
	</div>
</div>
<div class="file-name-hidden hidden">0</div>
<div class="file-hidden hidden">0</div>


<script type="text/javascript">
jQuery(document).ready(function($) {
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
	
	$("#file-name").focusin(function() {
		$(".file-name-msg").text("");
		$(this).parent().removeClass('has-error');
	});

	//execute if file-upload is change..
	$("#file-upload").change(function() {
		if($(this).val() == "")
		{
			$(".file-msg").text("Please select a file.");
			$(".file-msg").removeClass("file-success-msg");
			$(".file-msg").addClass('file-error');
			$(".file-hidden").text('0');
		}
		else
		{
			// get the file name, possibly with path (depends on browser)
	        var filename = $("#file-upload").val();
	        $(".file-msg").text("1 File Selected ");
			$(".file-msg").addClass("file-success-msg");
			$(".file-msg").removeClass('file-error');
			$(".file-hidden").text('1');

	        // Use a regular expression to trim everything before final dot
	  		// var extension = filename.replace(/^.*\./, '');
			// if(extension == "xls")
			// {
			// 	$(".file-msg").text("1 File Selected ");
			// 	$(".file-msg").addClass("file-success-msg");
			// 	$(".file-msg").removeClass('file-error');
			// 	$(".file-hidden").text('1');
			// }
			// else
			// {
			// 	$(".file-msg").text("Please choose correct file.");
			// 	$(".file-msg").removeClass("file-success-msg");
			// 	$(".file-msg").addClass('file-error');
			// 	$(".file-hidden").text('0');

			// }

	        console.log(extension);
	        
		}
	});

	$(".submit-btn").click(function() {
		if($(".file-name-hidden").text() == "0")
		{
			$("#file-name").parent().addClass('has-error');
		}
		else
		{
			$("#file-name").parent().removeClass('has-error');
		}
		if($(".file-hidden").text() == "0")
		{
			$(".file-msg").text("Please select a file.");
			$(".file-msg").removeClass("file-success-msg");
			$(".file-msg").addClass('file-error');
		}

		if($(".file-name-hidden").text() == "1" && $(".file-hidden").text() == "1")
		{
			$("#form-submit-btn").click();
		}
	});
	$(".cancel-btn").click(function() {
		$("#file-name").val("");
		$("#file-upload").val("");
		$(".file-name-msg").text("");
		$(".file-msg").text("");
		$("#file-name").parent().removeClass('has-error');
	});
	$(".success-msg").delay('5000').fadeOut(1000);
});
</script>

<?php
$this->session->unset_userdata('upload_success');
?>