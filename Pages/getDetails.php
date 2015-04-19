<?php
include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/PDO_Query.php';
$q = new PDO_Query;
$name = $_POST['employee_name'];
session_start();
$_SESSION["employee_name_query"] = $name;
$details = $q->getEmployeeDetails($name);

if ($details == FALSE)
{
    
    //$_SESSION["error"] = "Sorry, employee name not found, please try again"; //comment this out to debug
    header("Location: edit_employee.php"); 
    exit;
}


$_SESSION['details'] = $details;
$_SESSION['disabled'] = "false";

 $_SESSION["message"] =  header("Location: edit_employee.php"); 
 exit;
 
 