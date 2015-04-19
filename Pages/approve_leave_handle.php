<?php

//queries to edit requests to add approved 
  include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/PDO_Query.php';
  $q = new PDO_Query;   
  
  //fill variables from post from approveLeave.php
  $confirmation_status = $_POST['confirmation_status'];
  $reason_manager = $_POST['reason_manager'];
  $approved_by = $_POST['approved_by'];
  $request_id = $_POST['request_id'];
  $hours_requested = $_POST['hours_requested'];
  $employee_id = $_POST['employee_id'];
  $year = $_POST['start_year'];
  $insert = $q->approveRequest($confirmation_status, $reason_manager, $approved_by, $request_id); //updates conf status and adds reason manager (or N/A) and name of approver to correct record
  
  session_start();
  if ($insert === true)
  {
       $alterHolidays = $q->alterHolidays($confirmation_status, $hours_requested, $employee_id, $year);
       if ($alterHolidays === true)
       { //success message passed to session, the jump to approve page again
            $_SESSION['message'] = "Thanks, Request successfully updated. The employee will be able to see this on his Dashboard.";
            header("Location: approveLeave.php");
            exit;
       }
  }
  else
  {     //success message passed to session, the jump to approve page again
      $_SESSION['error'] = "Sorry, something went wrong with the approval process. Please try again";
      header("Location: approveLeave.php");
      exit;
  }