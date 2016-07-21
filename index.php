<?
//for testing, first kills all open ones and then opens one
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

<script>
  $( function() {
    // setup slider
    $( "#slider" ).slider({
      value: 0,
      orientation: "horizontal",
      range: "max",
	  min: 0,
	  max: <?=count($image)?>,
      animate: true,
	  change: function(event, ui) {
		 //$("#rotateForm").submit();
        },
		slide: function( event, ui ) {
            //$("#sliderValue").text(ui.value);
			$("#rotateForm").submit();
			$( "#imgname" ).val( ui.value );
        },
    });
  });
  
  $(document).ready(function() {
	console.log('ready');
    // process the form
    $('form').submit(function(event) {

        // get the form data
        // there are many ways to get this data using jQuery (you can use the class or id also)
        var formData = {
            'img': $('input[name=img]').val()
        };

        // process the form
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'process.php', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
                        encode          : true
        })
            // using the done promise callback
            .done(function(data) {

                // log data to the console so we can see
                console.log(data); 

                // here we will handle errors and validation messages
            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });

});
</script>
</head>
<form id="rotateForm" action="process.php" method="POST">
<div id="slider"  style="width:260px; margin:15px;"></div>
<div id="sliderValue"></div>
<input name="img" type="text" id="imgname" />
<button type="submit" class="btn btn-success">Submit <span class="fa fa-arrow-right"></span></button>

</form>
<?
foreach ($image as $filename):?>
<img class="hat" src="<?=$filename?>" />
<?
endforeach;
?>
<pre>
<?
print_r($image);
?>
</pre>