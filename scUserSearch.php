<?php

session_start();

require('classes/scUser.php');

//set up conditional to check if sessions set
	$scUserObj = new scUser();
	$scUserObj->accessToken();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
			
			$scUserObj->scSearch();	
				
			}


include('views/scUserSearch.view.php');


?>