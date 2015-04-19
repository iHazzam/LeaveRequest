<?php

session_start();
$_SESSION['employee_name_temp'] = $_POST['employee_name']; //set 2 session variables, (user input and a flag to say this happened) and go back! 
$_SESSION['individual_flag'] = 1;

header("Location: administrator_tools.php");
exit;