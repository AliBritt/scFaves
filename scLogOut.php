<?php

	include('classes/scUser.php');
	
	$scUserObj = new scUser();
	$scUserObj->scLogOut();
	
	include('views/scLoggedOut.view.php')

?>