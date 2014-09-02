</div><!-- end main-body -->
</div>
<!-- end container-fluid -->

</body>
</html>

<script type="text/javascript">
jQuery(document).ready(function($) {
	$('body').on('click', '.toggle-img.slide-left', function() {
		$(this).removeClass('slide-left');
		$(this).addClass('slide-right');
		$("#left-nav").addClass('slide-left');
		$("#main-body").addClass('slide-left');
		var rotate = "rotate(90deg)";
        var trans = "all 0.4s ease-out";
		$(".toggle-img").css({
                "-webkit-transform": rotate,
                "-moz-transform": rotate,
                "-o-transform": rotate,
                "msTransform": rotate,
                "transform": rotate,
                "-webkit-transition": trans,
                "-moz-transition": trans,
                "-o-transition": trans,
                "transition": trans
            });
	});


	$('body').on('click', '.toggle-img.slide-right', function() {

		$(this).removeClass('slide-right');
		$(this).addClass('slide-left');
		$("#left-nav").removeClass('slide-left');
		$("#main-body").removeClass('slide-left');
		var rotate = "rotate(0deg)";
        var trans = "all 0.4s ease-out";
		$(".toggle-img").css({
                "-webkit-transform": rotate,
                "-moz-transform": rotate,
                "-o-transform": rotate,
                "msTransform": rotate,
                "transform": rotate,
                "-webkit-transition": trans,
                "-moz-transition": trans,
                "-o-transition": trans,
                "transition": trans
            });

    });

	
	$(".user").click(function() {
		$('.user-detail-div').toggle();
	});

	
	$(document).click( function (event) {
	    var className = event.target.className;
	    if(className == "user-detail-div" || className == "user" || className == "profile-btn" || className == "fa fa-caret-down" || className == "fa fa-user" ||  className == "sign-out"){
	        return false;
	    }
	    else
	    {    	
	    	$(".user-detail-div").slideUp();
	    }

	});

	var window_height = $( window ).height();
	var main_content_height = parseInt(window_height) - 111;
	$(".main-content").css({
		'height': main_content_height+'px'
	});

	$("#main-content-body").css({
		'height': main_content_height+'px'
	});

	$(".upload-excel-file").click(function() {
		$("#file-upload").click();
	});

	$("body #left-nav #left-menu ul li").click(function() {
		$(this).children('ul').slideToggle();
		var $right_arrow = $(this).find('.pull-right');
		if($right_arrow.hasClass('fa-angle-left'))
		{
			$right_arrow.removeClass('fa-angle-left');
			$right_arrow.addClass('fa-angle-down');
		}
		else
		{
			$right_arrow.addClass('fa-angle-left');
			$right_arrow.removeClass('fa-angle-down');
		}
	});
	
});
</script>