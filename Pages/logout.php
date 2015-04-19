<?php

session_start();
session_destroy();
//session_start();
//$_SESSION['message'] = "You are now logged out. Thanks for using Dashboard.";

header("Location: login.php");