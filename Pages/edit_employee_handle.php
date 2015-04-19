<?php

include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/PDO_Query.php';
$q = new PDO_Query;
session_start();

if (is_null($_POST))
{
    $_SESSION["error"] = "Sorry, something went awfully wrong. Please try again";
    header("Location: edit_employee.php"); 
    exit;
}

if ($_POST['employee_type'] == "administrator")
{
    $is_admin = true;
    $is_manager = false;
}
elseif ($_POST['employee_type'] == "manager")
{
    $is_admin = false;
    $is_manager = true;
}
else
{
    $is_admin = false;
    $is_manager = false;
}

$employee_id = $_POST['employee_id'];
$employee_name = $_POST['employee_name'];
$username = $_POST['username'];
$email_address = $_POST['email_address'];
$hours_day = $_POST['hours_day'];
$manager_name = $_POST['manager_name'];
$hours_this = $_POST['hours_this'];
$hours_next = $_POST['hours_next'];
$year_this = date("Y");
$year_next = $year_this + 1;

$manager_id = $q->fetchIDandStatusfromName($manager_name); //for validation of manager!

 if ($manager_id['employee_id'] == false)
 {
    $_SESSION['error'] = "Please enter a correct employee name as your manager!";
    header("Location: edit_employee.php");
    exit;
 }
  elseif ($manager_id['is_manager'] == false)
 {
    $_SESSION['error'] = "The employee entered doesn't have manager priviliges - Please set these up before adding employees";
    header("Location: edit_employee.php");
    exit;
 }

$update = $q->updateEmployeeDetails($employee_id, $username, $email_address, $is_manager, $is_admin, $hours_day, $manager_name);

if ($_POST['hours_this_existing'] != $hours_this)
{
    $update1 = $q->updateHolidayDetails($employee_id, $hours_this, $year_this);
    if ($update1 == false)
    {
        print_r("Error something failed 63 eeh");
        exit;
    }
}
if ($_POST['hours_next_existing'] != $hours_next)
{
    $update2 = $q->updateHolidayDetails($employee_id, $hours_next, $year_next);
     if ($update2 == false)
    {
        print_r("Error something failed 72 eeh");
        exit;
    }
}



header("Location: edit_employee.php"); //should be set in the query...