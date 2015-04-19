<?php

include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/PDO_Query.php';
$q = new PDO_Query;

//initialise variables for query
$id = $_POST['employee_id'];
$total_hours = $_POST['total_hours'];
$create_leave = $_POST['create_leave'];
$year_next = date("Y") +1;
session_start();

if ($create_leave == true) //if there is no leave created at all already 
{
    $query = $q->CreateHoliday($id, $year_next, $total_hours);
    if ($query == true)
    {
        $_SESSION['message'] = "Thanks, the values have been saved to the system. To edit them, please submit the same form again";
          header("Location: edit_employee.php");
    }
    else
    {   
        $_SESSION['error'] = "Sorry, something went wrong with the holiday year change. Please try again!";
          header("Location: edit_employee.php"); 

    }
}
//now there will definately be leave created, so it can be updated below

$query = $q->updateNextYearHolidays($id, $total_hours); //query can update and make new leave
unset($_SESSION['new_leave_id']);
if ($query == true)
{
    $_SESSION['message'] = "Thanks, the values have been saved to the system. To edit them, please submit the same form again";
      header("Location: edit_employee.php");
}
else
{   
    $_SESSION['error'] = "Sorry, something went wrong with the holiday year change. Please try again!";
      header("Location: edit_employee.php"); 
    
}