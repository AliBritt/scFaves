<?php

session_start();

require('classes/scUser.php');

//set up conditional to check if sessions set
if ($_SESSION['scUserToken']){
	
		$scUserObj = new scUser();
		$scUserObj->scSetAccessToken();

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
				
				$scUserObj->scSearch();	
					
				}
		
		
	}else{
		header('location:http://127.0.0.1/scFaves/LoginToSoundcloud.php');
	}
	


include('views/scUserSearch.view.php');


?>