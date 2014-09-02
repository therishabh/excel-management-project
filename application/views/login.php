<!DOCTYPE html>
<html>
<head>
	<title>Colombia Mining</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<!-- user favicon -->
	<link rel="shortcut icon" type="image/png" href="<?php echo base_url(); ?>img/favicon.ico"/>
	<!-- // end user favicon -->
</head>
<body class="login-screen">
<div class="container-fluid ">
	<div class="row">
		<div class="col-lg-5 col-centered login">
			<div class="login-heading">
				Sign In
			</div>
			<?php echo form_open();?>
			<div class="login-div">
				<input type="text" class="form-control" name="username" placeholder="Type Your Username..." autocomplete="off" required>
				<input type="password" class="form-control" name="password" placeholder="Type Your Password..." required>
				<div class="login-error">
					<div>
						<?php 
						if(isset($error))
						{
							echo $error;
						}
						?>
					</div>
				</div>
			</div>
			<div class="login-btn-div">
				<div class="row">
					<div class="col-lg-8 col-centered">
						<input type="submit" value="Sign me in" name="login_btn">
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>	
		</div>
	</div>
</div>
</body>
</html>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$(".login-error div").delay(5000).fadeOut(1000);
});
</script>