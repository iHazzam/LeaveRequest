<?php


//print_r($_POST);

$hours_requested = $_POST['hours_requested'];
$request_id = $_POST['request_id'];
$confirmation_status = $_POST['confirmation_status'];

session_start();

$year = $_POST['year'];
//remove from requests
//notify manager
include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/PDO_Query.php'; //gets here we need the database!
$q = new PDO_Query();

if ($confirmation_status === "DENIED")
{
    $_SESSION['error'] = "Denied requests are kept in the database for posterity. You don't need to delete these!.";
    header("Location: dashboard.php");
    exit;
}

$delete = $q->deleteFromRequests_id($request_id);

if ($delete === false)
{
    $_SESSION['error'] = "Request cancellation failed. Please try again or contact an administrator";
    header("Location: dashboard.php");
    exit;
}
else //delete happened
{
   //we now know employee_id

        $employee_id = $delete;
        $alter = $q->alterHolidays2($confirmation_status, $hours_requested, $employee_id, $year);
       
        print_r($alter);
        if ($alter === true)
        {
            $_SESSION['message'] = "Your request has been deleted.";
            header("Location: dashboard.php");
        }
        else
        {
            $_SESSION['error'] = "Something has broken. Please ask an administrator to get it resolved.";
            header("Location: dashboard.php");
        }
        
  
}

//
//if approved, take off hours allocated, 