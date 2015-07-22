<?php

session_start();

require('classes/scUser.php');

//set up conditional to check if sessions set
//if ($_SESSION['scUserName']){
	$scUserObj = new scUser();
	$scUserObj->accessToken();
	//print_r($_SESSION['scUserName']);
	//echo "shat";
	
/*}else{
	$scUserObj = new scUser();
	$scUserObj->loginToSc();
	echo "mat";
}
*/

include('views/scUserHome.view.php');

?>