<?php

//fetch variables from admin tools form (administrator_tools.php)
$hours_requested = $_POST['hours_requested'];
$request_id = $_POST['request_id'];
$confirmation_status = $_POST['confirmation_status'];

session_start();


$_SESSION['individual_flag'] = 0;
include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/PDO_Query.php'; //gets here we need the database!
$q = new PDO_Query();
//if the post tells us that either leave has been approved or denied by the admin
if (array_key_exists('approve', $_POST)||(array_key_exists('deny', $_POST)))
{
  
  
  $reason_manager = "Request handled by Admin";//set all variables required to update the request
  $approved_by = $_SESSION['employee_name'];
  
  $query = $q->getInfoFromRequestID($request_id);
  if ($query == false)
  {
      $_SESSION['error'] = "Sorry, something went wrong with the approval process. Please try again";
      header("Location: administrator_tools.php");
      exit;
  }
  if (array_key_exists("approve", $_POST)) //decide which and set variable
  {
      $confirmation_status_new = "APPROVED";
  }
  elseif (array_key_exists("deny", $_POST))
  {
      $confirmation_status_new = "DENIED";
  }
  
  $employee_id = $query['employee_id'];
  $temp = explode("-",$query['date_start']); //get the date out of a multi-part date format
 
  $year = $temp[0];
  $insert = $q->approveRequest($confirmation_status_new, $reason_manager, $approved_by, $request_id);
  //actually an update request rather than insert

  if ($insert === true)
  {
       $alterHolidays = $q->alterHolidays($confirmation_status_new, $hours_requested, $employee_id, $year);
      //if all requests complete drop through and set message - return to adminstrator_tools
       if ($alterHolidays === true)
       {
            
            $_SESSION['message'] = "Thanks, Request successfully updated. The employee will be able to see this on his Dashboard.";
            header("Location: administrator_tools.php");
            exit;
       }
  }
  else
  {
      $_SESSION['error'] = "Sorry, something went wrong with the approval process. Please try again";
      header("Location: adminsitrator_tools.php");
  }
}
else //this means the employee pressed "cancel" request
{
    $year = $_POST['year'];
    //remove from requests
    //notify manager
   
    if ($confirmation_status === "DENIED") //decision made to not allow denied requests to be cancelled
    {
        $_SESSION['error'] = "Denied requests are kept in the database for posterity. You don't need to delete these!.";
        header("Location: administrator_tools.php");
        exit;
    }

    $delete = $q->deleteFromRequests_id($request_id); //if it passes, delete the request 

    if ($delete === false)
    {
        $_SESSION['error'] = "Request cancellation failed. Please try again or contact an administrator";
        header("Location: administrator_tools.php");
        exit;
    }
    else //delete happened
    {
       //we now know employee_id    

            $employee_id = $delete;
            $alter = $q->alterHolidays2($confirmation_status, $hours_requested, $employee_id, $year); //update the holiday table to show holiday has been restored to available (cancelled)


            if ($alter == true)
            {
                $_SESSION['message'] = "Your request has been deleted.";
                header("Location: administrator_tools.php");
            }
            else
            {
                $_SESSION['error'] = "Something has broken. Contact Tech Support!"; //should never get here
                header("Location: administrator_tools.php");
            }


    }
}
