<?php

session_start();

require('classes/scUser.php');
//conditional good enough?
	if($_GET['code']){
			
		$scUserObj = new scUser();
		$scUserObj->getAccessToken();
		//sort base url 
		header('location:http://127.0.0.1/scFaves/scUserHome.php');
		
	}else{
		header('location:http://127.0.0.1/scFaves/LoginToSoundcloud.php');
	}
	
?>