<?php

session_start();

require('classes/scUser.php');

//set up conditional to check if sessions set
$scUserObj = new scUser();
$scUserObj->accessToken();

include('views/scUserHome.view.php');

?>