<div class="row">
	<div class="col-lg-12 ">
		<!-- heading -->
		<div class="row">
			<div class="col-lg-12 main-heading">
				<div class="row">
					<div class="col-lg-10">Manage User</div>
					<div class="col-lg-2">
						<div class="submit-btn add-btn" style="background:#3c8dbc;font-size:14px;">Add User</div>
					</div>
				</div>
				
			</div>
		</div>
		<!-- // end heading -->
		<div class="row">
			<div class="col-lg-12" id="main-content-body">

				<div class="row">
					<?php echo form_open_multipart('user','id="search-form"');?>
					<div class="col-lg-10 col-centered user-search-div">
						<div class="row">
							<div class="col-lg-9">
								<div class="row">
									<div class="col-lg-4">
										<input type="text" class='form-control search-user' value="<?php echo ( isset($name_search) ? $name_search : "" ) ?>" name="search-name" placeholder="Type Name.." >
									</div>
									<div class="col-lg-4">
										<input type="text" class='form-control search-user' value="<?php echo ( isset($username_search) ? $username_search : "" ) ?>" name="search-username" placeholder="Type Username.." >
									</div>
									<div class="col-lg-4">
										<select name="search-role" class="form-control">
											<option value="">Role</option>
											<option value="User" <?php echo ( ($role_search == "User") ? "selected" : "" )  ?>>User</option>
											<option value="Admin" <?php echo ( ($role_search == "Admin") ? "selected" : "" )  ?>>Admin</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="row">
									<div class="col-lg-6">
										<input type="submit" class="hidden" name="search_btn" value="success">
										<div class="submit-btn search-btn">Search</div>
									</div>
									<div class="col-lg-6">
										<div class="cancel-btn reset-btn">Reset</div>
									</div>
								</div>
							</div>
						</div>
					</div><!-- // end user-search-div -->
					<?php echo form_close(); ?>	
				</div>


				<table class="table user-table">
				    <thead>
				        <tr>
				            <th>S.No</th>
				            <th>Name</th>
				            <th>Usename</th>
				            <th>Password</th>
				            <th>Role</th>
				            <th>Permission</th>
				            <th>Operation</th>
				        </tr>
				    </thead>
				    <tbody>
				    		<tr class="add-div" style="display:none;">
				    			<td style="padding-top:17px;">*</td>
				    			<td><input type="text" class="form-control user_name"></td>
				    			<td>
				    				<input type="text" class="form-control username">
				    				<div class="file-error"></div>
				    			</td>
				    			<td style="position:relative">
									<input type="text" class="form-control password">
				    			</td>
				    			<td>
					    			<select name="role" id="role" class="form-control">
					    				<option value="User">User</option>
					    				<option value="Admin">Admin</option>
					    			</select>
				    			</td>
				    			<td>
				    				<select name="permission" id="permission" class="form-control">
					    				<option value="Read Only">Read Only</option>
					    				<option value="Read and Modify">Read and Modify</option>
					    			</select>
				    			</td>
				    			<td><div class="submit-btn add-user-btn" style="font-size:14px;padding:6px;">Add</div></td>
				    		</tr>
				    	<?php
				    	//check if there is any user in database..
				    	if(!empty($user_details[0]))
				    	{
				    		$a = 1;
				    		foreach($user_details as $user)
				    		{
					    		echo "<tr>";
					    		echo "<td>$a</td>";
					    		echo "<td>".$user['name']."</td>";
					    		echo "<td>".$user['username']."</td>";
					    		echo "<td>
					    			<span class='password-hide'>**********</span>
					    			<span class='password-show'>".$user['password']."</span>
					    			<i class='fa fa-eye pull-right'></i>
					    			</td>";
					    		echo "<td>".ucfirst($user['role'])."</td>";
					    		echo "<td>".ucfirst($user['permission'])."</td>";
					    		echo "<td>
							    		<i class='fa fa-pencil edit-btn'></i>";
							    		if($user['id'] != "1")
							    		{
							    			echo "<i class='fa fa-times delete-btn' data-toggle='modal' data-target='#user-delete-modal' id='".$user['id']."'></i>";
							    		}
							    		else
							    		{
							    			echo "<i id='".$user['id']."'></i>";
							    		}
							    echo "</td>";
					    		echo "</tr>";
				    			$a++;
				    		}
				    	} 
				    	?>
				    	
				    </tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- Small modal -->


<div class="modal fade" id="user-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Delete User</h4>
      </div>
      <div class="modal-body">
        <i class="fa fa-exclamation-triangle"></i>
        <span>
        	Do you want to delete User ?
        </span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary yes-btn" data-dismiss="modal">Yes</button>
        <button type="button" class="btn btn-default no-btn" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>





<div id="hidden-name"></div>
<div id="hidden-password"></div>
<div id="hidden-role"></div>
<div id="hidden-permission"></div>
<div class="hidden-user-id hidden"></div>
<div class="flag_username hidden">0</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$("#main-content-body").on('mousedown', '.fa-eye', function() {
		$(this).parent().children('.password-show').show();
		$(this).parent().children('.password-hide').hide();
	});

	$("#main-content-body").on('mouseup', '.fa-eye', function() {
		$(this).parent().children('.password-hide').show();
		$(this).parent().children('.password-show').hide();
	});

	$("#main-content-body").on('mouseleave', '.fa-eye', function() {
		$(this).parent().children('.password-hide').show();
		$(this).parent().children('.password-show').hide();
	});

	//script for click search button..
	$(".search-btn").click(function() {
		$(' input[name="search_btn"]').click();
	});
	// end script for click search button..

	//script for click reset button..
	$(".reset-btn").click(function() {
		location.reload(true);
	});
	// end script for click on reset button..

	$("#role").change(function(){
		var role = $(this).val();
		if(role == "Admin")
		{
			$("#permission").html('<option value="All">All</option>');
		}
		else if(role == "User")
		{
			$("#permission").html('<option value="Read Only">Read Only</option><option value="Read and Modify">Read and Modify</option>');
		}
	});

	$(".add-user-btn").click(function() {
		var user_name = $(".user_name").val();
		var username = $(".username").val();
		var password = $(".password").val();
		var role = $("#role").val();
		var permission = $("#permission").val();
		if(user_name == "")
		{
			var flag_user_name = 0;
			$(".user_name").parent().addClass('has-error');
			$(".user_name").focus();
		}
		else
		{
			var flag_user_name = 1;
		}

		if(username == "")
		{
			var flag_username = 0;
			$(".username").parent().addClass('has-error');
			if(user_name != ""){
				$(".username").focus();
			}

		}
		else
		{
			if($(".flag_username").text() == "0")
			{
				$(".username").parent().addClass('has-error');
				$(".file-error").show();
				$(".file-error").text("username already exist !");
				if(user_name != ""){
					$(".username").focus();
				}
			}
			var flag_username = $(".flag_username").text();
		}

		if(password == "")
		{
			var flag_password = 0;
			$(".password").parent().addClass('has-error');
			if(user_name != ""){
				if(username != ""){
				$(".password").focus();
				}
			}
		}
		else
		{
			var flag_password = 1;
		}
		console.log(flag_user_name+"********"+flag_username+"**********"+flag_password);
		if(flag_user_name == "1" && flag_username == "1" && flag_password == "1")
		{
			$.ajax({
				url: '<?php echo base_url();?>index.php/user/adduser',
				type: 'POST',
				data: {name:user_name,username:username,password:password,role:role,permission:permission},
				success: function(result){
					$(".user-table tbody tr:nth-child(2) td").last().children('.delete-btn').attr('id',result);
				}//end success
			})
			
			var tr = '<tr><td>1</td><td>'+user_name+'</td><td>'+username+'</td><td>'
					+'<span class="password-hide">**********</span>'
					+'<span class="password-show">'+password+'</span>'
					+'<i class="fa fa-eye pull-right"></i>'
					+'</td><td>'+role+'</td><td>'+permission+'</td><td>'
					+'<i class="fa fa-pencil edit-btn"></i><i class="fa fa-times delete-btn"></i>'
					+'</td></tr>'
			$(".user-table tbody tr:first-child").after(tr);

			//reset serial number from due notification table..
			$(".user-table tbody tr:not(:first-child)").each(function(id){
				$(this).children().first().html(id+1);	
			});

			$(".user_name").val("");
			$(".username").val("");
			$(".password").val("");
			$(".user-table tbody tr:nth-child(2)").css('background', 'rgb(32, 161, 74)');
			$(".user-table tbody tr:nth-child(2)").animate({
		    	backgroundColor:"#f9f9f9"
		  	},2000);
		}

	});
	
	$(".form-control").keypress(function() {
		$(this).parent().removeClass('has-error');
		$(".file-error").slideUp();
	});
	
	$(".username").focusout(function() {
		if ($(this).val() != "") 
		{
			var flag_username = $(".flag_username").text();
			if(flag_username == '0')
			{
				$(".username").parent().addClass('has-error');
				$(".file-error").show();
				$(".file-error").text("username already exist !");
			}
			else
			{
				$(".username").parent().removeClass('has-error');
				$(".file-error").slideUp();
			}
		}
	});

	$(".username").keyup(function() {
		var username = $(this).val();
		$.ajax({
			url: '<?php echo base_url();?>index.php/user/check_user_name',
			type: 'POST',
			data: {username: username},
			success: function(result){
				//if file is not exist into database..
				if(result == "exist")
				{
					$(".flag_username").text("0");
				}
				// if file is exist into database..
				else
				{
					$(".flag_username").text("1");
				}
			}//end success
		});	
	});

	// $(".file-error").delay(2000).slideUp();
	$(".add-btn").click(function() {
		$('#main-content-body').animate({scrollTop:0}, 'slow');
		$(".add-div td").css('background', 'rgb(32, 161, 74)');
		$(".add-div").fadeIn(1000);
		$(".add-div td").animate({
	    	backgroundColor:"#f9f9f9"
	  	},2000);
	});

	$(".no-btn").click(function() {
		$("table tbody tr.selected-tr").children().css({
				'background': '#f9f9f9',
				'color':'#333'
		});
		$("table tbody tr.selected-tr").removeClass('selected-tr')
	});

	$("#user-delete-modal .close").click(function() {
		$(".no-btn").click();
	});

	$("body").keyup(function(e) {
		if(e.which == "27")
		{
			$(".no-btn").click();
		}
	});

	$(".yes-btn").click(function() {
		var id = $(".hidden-user-id").text();
		$("table tbody tr.selected-tr").fadeOut(1400);
		setTimeout(function(){
			$("table tbody tr.selected-tr").remove();

			//reset serial number from due notification table..
			$(".user-table tbody tr:not(:first-child)").each(function(id){
				$(this).children().first().html(id+1);	
			});

		},1400);

		$.ajax({
			url: "<?php echo base_url();?>index.php/user/delete_user",
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
	});


	$("#main-content-body").on('click', '.delete-btn', function() {
		var id = $(this).attr("id");
		$(".hidden-user-id").text(id);
		$(this).parent().parent().children().css({
				'background': 'rgb(178, 4, 4)',
				'color':'#fff'
		})
		$(this).parent().parent().addClass('selected-tr');

	});

	$("#main-content-body").on('click', '.edit-btn', function() {
		// var id = $(this).next().attr("id");
		// $(".hidden-user-id").text(id);

		// var name = $(this).parent().parent().children().eq(1).text();
		// var password = $(this).parent().parent().children().eq(3).children('.password-show').text();
		// var role = $(this).parent().parent().children().eq(4).text();
		// var permission = $(this).parent().parent().children().eq(5).text();

		// $("table tbody tr.select-for-edit").children().eq(1).text($("#hidden-name").text());
		// $("table tbody tr.select-for-edit").children().eq(3).html("<span class='password-hide'>**********</span><span class='password-show'>"+$("#hidden-password").text()+"</span><i class='fa fa-eye pull-right'></i>");
		// $(".fa-eye").hide();
		// $(".fa-eye").show();
		
		// $("table tbody tr.select-for-edit").removeClass('select-for-edit');

		// $(this).parent().parent().addClass('select-for-edit');
		// $(this).parent().parent().children().eq(1).html('<input type="text" class="form-control update_name" value="'+name+'">')
		// $(this).parent().parent().children().eq(3).html('<input type="text" class="form-control update_name" value="'+password+'">')

		// $("#hidden-name").text(name);
		// $("#hidden-password").text(password);
		// $("#hidden-role").text(role);
		// $("#hidden-permission").text(permission);

	});

});
</script> 