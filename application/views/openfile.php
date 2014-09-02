<div class="row open-file-section">
	<div class="col-lg-12 ">
		<!-- heading -->
		<div class="row">
			<div class="col-lg-12 main-heading">
				Open File
			</div>
		</div>
		<!-- // end heading -->

		<div class="row">
			<div class="col-lg-12" id="main-content-body">
				
			
				<div class="row">
					<div class="col-lg-11 col-centered">
						<div class="row">
							<div class="col-lg-4">
								<div class="text-label">
									File Name:
								</div>
								<div class="text">
									<?php echo $file_name; ?>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="text-label">
									Created By:
								</div>
								<div class="text">
									<?php
									$created_user = $this->main_model->fetch_user_name($created_by);
									echo $created_user['name']; 
									?>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="text-label">
									Date
								</div>
								<div class="text">
									<?php echo $date; ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<div id="excel-div" style="width: 100%; height: 300px; overflow: auto" class="handsontable"></div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-lg-12">

						<!-- <div class="column-section">
							<ul class="column-ul">
						            
						    </ul>
						</div>
						<div class="operator-section">
							<button id="add-btn">+</button>
							<button id="sub-btn">-</button>
							<button id="div-btn">/</button>
							<button id="mul-btn">*</button>
							<button id="equal-btn">=</button>
						</div>
						<div class="display-msg"></div>
						<button id="done">Done</button> -->
					</div>
				</div>
				
			</div><!-- // end col-lg-12 id="main-content-body" -->
		</div><!-- // end row -->
	</div>
</div><!-- // end row open-file-section -->

<div class="result hidden"></div>
<div class="hidden temp">0</div>
<div class="hidden-table hidden"></div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	var $container = $("#excel-div");
	var $parent = $container.parent();//body..
	var data = <?php echo $file_content ?>
	$container.handsontable({
		data : data,
		startRows: 11,
		startCols: 11,
		//apply header in row and columns..
		rowHeaders: true,
		colHeaders: true,

		//Column & row move
		manualColumnMove: true,
		manualRowMove: true,

		//add one extra row and columns
		minSpareRows: 1,
		minSpareCols: 1,

		stretchH: 'all',

		contextMenu: true,
		afterChange: function (change, source) {
			$(".hidden-table").html($(".wtHolder.ht_master .wtSpreader").html());                 
		}
	});
	$container.handsontable('render'); //refresh the grid to display the new value
	var handsontable = $container.data('handsontable');



$(".ht_clone_top table.htCore thead tr th").css('cursor','pointer');
$(".wtHolder.ht_master table.htCore thead tr th").each(function() {
    var columns_name = $(this).children().children().text();
    if( $(this).index() != "0" )
    {
    	$(".column-section .column-ul").append("<li id='"+$(this).index()+"'>"+columns_name+"</li>");
    }
});


//add inactive class into operators..
$(".operator-section button").addClass('inactive');

//script execute when click on operator buttons..
$(".operator-section button").click(function(){
	//execute if click equal to operator..
	if($(this).attr('id') == "equal-btn")
	{
	    
	}

	if($(".temp").text() == 0)
    {
    	alert("Please First Select any Columns ! ");
    }
    else{
    	$(".temp").text("0");
		var operator_value = $(this).text();
		$(".result").append(operator_value);
		$(".display-msg").append(operator_value);
		$(".column-ul li").removeClass('inactive');
		$(".operator-section button").addClass('inactive');
    }
});

//script execute when click on columns buttons..
$(".column-section").on('click', '.column-ul li', function() {
	if($(".temp").text() == "1")
	{
		alert("Please Select any Operator ! ")
	}
	else
	{
		$(".temp").text("1");
		var column_value = $(this).text();
		$(".result").append($(this).attr('id'));
		$(".display-msg").append(column_value);
		$(".column-ul li").addClass('inactive');
		$(".operator-section button").removeClass('inactive');

	}
});


$("button#done").click(function() {
	var columns = $(".column-index").text();
	var operator = $(".select-operator").text();
	var result = $(".result").text();

	var num_of_columns = columns.length; 
	var num_of_operator = operator.length;
	var result_length = result.length;
	var abc = parseInt(result_length) - 2;
	var a = 0;
	var result_column_num = parseInt(result[parseInt(result_length) - 1]) - 1;
	$(".hidden-table table.htCore tbody tr").each(function(){

		// console.log($(':nth-child(2)', this).text());
		var equ = "";
		for(var x = 0; x < (result_length-2); x++)
		{
			if(x%2 == 0)
			{
				aa = parseInt(result[x]) + 1;
				// console.log(aa);
				equ +=  $(':nth-child('+aa+')', this).text();
			}
			else
			{
				equ += result[x];
			}
		}
		
		// check if result of equ is integer then execute this script..
		// find the result of equ using eval function and assign into final_result variable..
		// if($.isNumeric(parseInt(equ)))
		console.log(equ);
		if(isNaN(equ))
		{
			console.log("true");
			var final_result = "";
		}
		//execute if result of equl is not integer then assign final_result variable blank value..
		else
		{
			console.log("false");
			var final_result = eval(equ);
		}
		
		console.log(final_result);

		//insert data into table at result columns..
		data[a][result_column_num] = final_result;
		$container.handsontable('render'); //refresh the grid to display the new value

		//increment of a..
		a++;
	})


	//add a columns in table
	// $container.handsontable('alter', 'insert_col', parseInt(result_column_num)+1);

	$(".hidden-table").html($(".wtHolder.ht_master .wtSpreader").html());
	$(".temp").text("0");
	$(".display-msg").text("");
	$(".result").text("");

});

$("#hello").click(function(){
	
	// $(".result").text("");
});


});
</script>