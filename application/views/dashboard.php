

<p>
  <button class="maximize">Maximize HOT table</button>
</p>

<div class="excel-div">
	<div id="example1" style="width: 400px; height: 200px; overflow: auto" class="handsontable"></div>
	<div id="hello"></div>
</div>

<p>
  <button name="dump" data-dump="#example1" title="Prints current data source to Firebug/Chrome Dev Tools">Dump
    data to console
  </button>
</p>
<script type="text/javascript">
$(document).ready(function () {

	function createBigData() {
	var rows = []
	  , i
	  , j;

	for (i = 0; i < 1000; i++) {
	  var row = [];
	  for (j = 0; j < 6; j++) {
	    row.push(Handsontable.helper.spreadsheetColumnLabel(j) + i);
	  }
	  rows.push(row);
	}

	return rows;
	}

	var maxed = false
	, resizeTimeout
	, availableWidth
	, availableHeight
	, $window = $(window)
	, $example1 = $('#example1');

	var calculateSize = function () {
	if(maxed) {
	  var offset = $example1.offset();
	  availableWidth = $window.width() - offset.left + $window.scrollLeft();
	  availableHeight = $window.height() - offset.top + $window.scrollTop();
	  $example1.width(availableWidth).height(availableHeight);
	}
	};
	$window.on('resize', calculateSize);

	$example1.handsontable({
	data: createBigData(),
	colWidths: [55, 80, 80, 80, 80, 80, 80], //can also be a number or a function
	rowHeaders: true,
	colHeaders: true,
	minSpareRows: 1,
	stretchH: 'all',
	contextMenu: true
	});

	$('.maximize').on('click', function () {
	maxed = !maxed;
	if(maxed) {
	  calculateSize();
	}
	else {
	  $example1.width(400).height(200);
	}
	$example1.handsontable('render');
	});


	function bindDumpButton() {
	  $('body').on('click', 'button[name=dump]', function () {
	    var dump = $(this).data('dump');
	    var $container = $(dump);
	    console.log('data of ' + dump, $container.handsontable('getData'));
	  });
	}
	bindDumpButton();

});
</script>