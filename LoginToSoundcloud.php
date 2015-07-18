<?php
session_start();

include('classes/scUser.php');
//add conditional to check if already logged in

$scUserObj = new scUser();
$scUserObj->loginToSc();

?>