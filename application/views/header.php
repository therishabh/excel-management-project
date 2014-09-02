<?php
date_default_timezone_set('Asia/Calcutta');
$current_url = current_url();
$page_name = uri_string();

//
if($this->session->userdata('colombia_username'))
{
	$this->load->model('main_model');
	$username = $this->session->userdata('colombia_username');
	$user_detail = $this->main_model->fetchbyfield('username',$username,'user');
					
	if($user_detail['status'] != 1)
	{
		$this->session->sess_destroy('colombia_username');
		redirect('home', 'refresh');
	}
	else
	{
		if($user_detail['role'] == "User")
		{
			if($user_detail['permission'] == "Read Only")
			{
				if($page_name == "offlinecreate")
				{
					redirect('view');
				}
				if($page_name == "onlinecreate")
				{
					redirect('view');
				}
				if($page_name == "user")
				{
					redirect('view');
				}
			}	
			else
			{
				if($page_name == "user")
				{
					redirect('view');
				}
			}		
		}
	}
}
else
{
	 redirect('/home/', 'refresh');
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Colombia Minning</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css">
	
	<!-- use font-awesome -->
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<!-- // end use font-awesome -->

	
	<!-- add basic jquery -->
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
	<!-- // end add basic jquery -->
	
	<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap.js"></script>

	<!-- javascript and css for handsontable... -->
	<script data-jsfiddle="common" src="<?php echo base_url(); ?>js/jquery.handsontable.full.js"></script>
	<link data-jsfiddle="common" rel="stylesheet" media="screen" href="<?php echo base_url(); ?>css/jquery.handsontable.full.css">
	<!-- // end javascript and css for handsontable... -->
	

	<!-- use favicon -->
	<link rel="shortcut icon" type="image/png" href="<?php echo base_url(); ?>img/favicon.ico"/>
	<!-- // end use favicon -->
</head>
<body>

<div class="container-fluid">
	<header class='header'>
	<div class="row">
		<div class="col-lg-2 logo">
			<a href="<?php echo base_url(); ?>">
				COLOMBIA
			</a>
		</div>
		<div class="col-lg-10 top-nav">
			<div class="row">
				<div class="col-lg-1">
					<img src="<?php echo base_url(); ?>img/toggle.png" alt="" class="toggle-img slide-left">
				</div>
				<div class="col-lg-3 col-lg-offset-8">
					<div class="user">
						<i class="fa fa-user"></i>
						<?php echo $user_detail['name']; ?>
						<i class="fa fa-caret-down"></i>
					</div>
					<div class="user-detail-div">
						<div style="position:reltive;">
							<div class="top-arrow"><i class="fa fa-caret-up"></i></div>
						</div>
						<a href="#" class="profile-btn">Setting</a>
					
						<a href="<?php echo base_url(); ?>logout" class="logout-btn">Sign out</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	</header>
	<div id="left-nav">
		<div class="row user-detail">
			<div class="col-lg-4 avatar-img">
				<img src="<?php echo base_url(); ?>img/avatar.png" alt="" >
			</div>
			<div class="col-lg-8">
				<div class="user-name">
					<?php
					$name_array = explode(" ",$user_detail['name']);
					echo "Hello, ".$name_array[0];
					?>
				</div>
				
			</div>
		</div>
		
		<div class="row" id="left-menu">
			<div class="col-lg-12">
				<ul>
					<li <?php echo $page_name == "view" ? "class='active'" : "" ?>>
						<a href="<?php echo base_url(); ?>view">
							<i class="fa fa-file-text"></i>View
						</a>
					</li>

					<?php
					if($user_detail['permission'] != "Read Only")
					{
					?>
					<li <?php echo $page_name == "onlinecreate" || $page_name == "offlinecreate" ? "class='active'" : "" ?>>
						<a href="#">
							<i class="fa fa-pencil-square-o"></i></i>Create<i class="fa pull-right fa-angle-left"></i>
						</a>
						<ul>
							<a href="<?php echo base_url(); ?>onlinecreate">
								<li><i class="fa fa-angle-double-right"></i> Online Create</li>
							</a>

							<a href="<?php echo base_url(); ?>offlinecreate">
								<li><i class="fa fa-angle-double-right"></i> Offline Upload</li>
							</a>

						</ul>
					</li>
					<?php
					}
					?>

					<?php
					if($user_detail['role'] == "Admin")
					{
					?>
						<li <?php echo $page_name == "user" ? "class='active'" : "" ?>>
							<a href="<?php echo base_url(); ?>user">
								<i class="fa fa-users"></i>User Mangament
							</a>
						</li>
					<?php
					} 
					?>

					<li <?php echo $page_name == "analysis" ? "class='active'" : "" ?>>
						<a href="#">
							<i class="fa fa-tachometer"></i>Dashboard
						</a>
					</li>
					
				</ul>
			</div>
		</div>
	</div>
	
	<div id="main-body">
		

