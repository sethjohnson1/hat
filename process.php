<?php
// process.php

$errors         = array();      // array to hold validation errors
$data           = array();      // array to pass back data

// validate the variables ======================================================
    // if any of these variables don't exist, add an error to our $errors array
/*
    if (empty($_POST['name']))
        $errors['name'] = 'Name is required.';

    if (empty($_POST['email']))
        $errors['email'] = 'Email is required.';

    if (empty($_POST['superheroAlias']))
        $errors['superheroAlias'] = 'Superhero alias is required.';
*/
// return a response ===========================================================

    // if there are any errors in our errors array, return a success boolean of false
    if ( ! empty($errors)) {

        // if there are items in our errors array, return those errors
        $data['success'] = false;
        $data['errors']  = $errors;
    } else {

        // if there are no errors process our form, then return a message

        // DO ALL YOUR FORM PROCESSING HERE
        $data['success'] = true;
		$data['img']=$_POST['img'];
		
		//first take note of all existing PIDs
		$killpid=exec('pidof fbi');
		//now launch the new image, using verbose output for testing
		//$cmd="sudo fbi -T 2 -noverbose /var/www/html/".$_POST['img'];
		$cmd="sudo fbi -T 2 /var/www/html/".$_POST['img'];
		exec($cmd . " > /dev/null &");
		//exec("sleep 1");
		//$data['pids']=exec('pidof fbi');
		//now kill the old ones
		exec('sudo kill -9 '.$killpid);
    }

    // return all our data to an AJAX call
    echo json_encode($data);
	
	?>