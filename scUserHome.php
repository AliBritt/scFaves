<?php

session_start();

require('classes/scUser.php');

//set up conditional to check if sessions set
	if ($_SESSION['scUserToken']){
		
		$scUserObj = new scUser();
		$scUserObj->scSetAccessToken();
		
		//print_r($_SESSION['scUserToken']);
		//echo "shat";
		
	}else{
		header('location:http://127.0.0.1/scFaves/LoginToSoundcloud.php');
	}


include('views/scUserHome.view.php');

?>