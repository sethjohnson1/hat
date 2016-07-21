<?
//for testing, kills all the old ones
$killpid=exec('pidof fbi');
exec ('sudo kill '.$killpid);
exec ('sleep 1');
//$cmd="sudo fbi -T 2 -noverbose /var/www/html/hat/1.JPG";
//exec($cmd . " > /dev/null &");
//pause for a moment otherwise pidof returns blank
exec("sleep 1");
$pid=exec('pidof fbi');
echo $pid;
//first get all the images
$image=array();
foreach (glob('hat/*.JPG') as $key=>$filename) $image[$key]=$filename;
//if it's lowercase...
if (empty($image)) foreach (glob('hat/*.jpg') as $key=>$filename) $image[$key]=$filename;
//encode for later decoding, I am reversing for my example to make it proper
$jsonimg=json_encode(array_reverse($image));
?>
<head>
<style>
img.hat{
	height:10%;
	width:10%;
}
</style>
<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
<script src="js/jquery-ui/jquery-ui.min.js"></script>
<!-- some kind soul made this script and jQuery UI slider now works on mobile -->
<script src="js/jquery-ui/jquery.ui.touch-punch.min.js"></script>

<script>
  $( function() {
    // setup slider
    $( "#slider" ).slider({
		value: 0,
		orientation: "horizontal",
		range: "max",
		min: -1,
		<?//normally take one off the end, but now we leave padding on either side (which is why min is -1) so that the looping of the slider works properly?>
		max: <?=count($image)?>,
		animate: true,
		change: function(event, ui) {
		if ($('input[name=img]').val()) $("#rotateForm").submit();
        },
		slide: function( event, ui ) {
			//console.log($('input[name=img]').val());
			if($(this).slider('option','max') === ui.value) {
				$(this).slider('option','value',$(this).slider('option','min'));
				return false;
			}
			if($(this).slider('option','min') === ui.value) {
				$(this).slider('option','value',$(this).slider('option','max'));
				return false;
			}
			//the first submission is empty, so there is an IF statement
			if ($('input[name=img]').val()) $("#rotateForm").submit();
			$( "#imgname" ).val( ui.value );
		},
    });
  });
  
  $(document).ready(function() {
	  var jsonImg=$.parseJSON('<?=$jsonimg?>');
    // process the form
    $('form').submit(function(event) {
		//the first value is empty, so there is an IF statement
		imgname=jsonImg[$('input[name=img]').val()];
		// get the form data
		// there are many ways to get this data using jQuery (you can use the class or id also)
		var formData = {
			'img': imgname,
			'pids': $('input[name=pids]').val()
		};

		// process the form
		$.ajax({
			type        : 'POST',
			url         : 'process.php',
			data        : formData,
			dataType    : 'json',
						encode          : true
		})
			.done(function(data) {
				$( "#pids" ).val( data['pids']);
				//console.log(data); 

			});

		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();		
    });
	
});
</script>
</head>
<form id="rotateForm">
<div id="slider"  style="width:260px; margin:15px;"></div>
<div id="sliderValue"></div>
<input name="img" type="hidden" id="imgname" /><br />
<button type="submit" class="btn btn-success" style="display:none">Submit</button>

</form>
<p><b>Images:</b></p>
<pre>
<?
print_r($image);
?>
</pre>