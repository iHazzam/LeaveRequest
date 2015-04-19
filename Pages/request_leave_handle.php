<?php

$date1 = $_POST["dateone"];
$date2 = $_POST["datetwo"];
$texta = trim($_POST["textarea"]);
$hours = $_POST["hours"];
$unpaid = $_POST["unpaid"];




if ($unpaid == "on")
{
    $hours = "unpaid"; //set to unpaid
    
    
}
if ($hours == 0)
{ //should have taken out all unpaid leave by now, so 0 requested is an error   
    $_SESSION['error'] = "Please enter a value for how much leave you require. If you require unpaid leave, please make sure to check the box.";
        header("Location: requestLeave.php");
}
session_start();

if (((empty($date1))||(empty($date2))))
{
   
  $_SESSION['error'] = "Please enter values for start date and end date.";
        header("Location: requestLeave.php");
}
else //we have both dates
{
  //date validation
  
    $date_now = date("Y-m-d");
   
    if (($date1 <= $date_now) || ($date2 < $date1) ) //if dates are before or today (can't book) or if end date is before start date
    {
        
        $_SESSION['error'] = "Please enter valid dates: End date must come after start date, and neither date can be today or earlier ($date_now)";
        header("Location: requestLeave.php");
    }
    else
    {
        
        if (empty($texta)) //if optional field not set
        {
            $texta = "n/a";
        }
        include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/PDO_Query.php'; //gets here we need the database!
        $q = new PDO_Query();

        $id = $_SESSION["employee_id"];
        //look up db, see if the person has any other pending or confirmed requests for any of these dates
        $requests = $q->returnDatesOfExistingRequests($id);
        $flag = 1;
       
        foreach($requests as $r)
        {   //if either end of date is in any part of range already taken

            if ((($date1 >= $r['date_start']) && ($date1 <= $r['date_end'])) || (($date2 >= $r['date_start']) && ($date2 <= $r['date_end'])))
            { 
                  $_SESSION['error'] = "Sorry, you already have pending or approved leave for one of the dates requested. Please try again";
                  header("Location: requestLeave.php");
                  exit;
                  
            }
        }
       
        
       
   
        //if it gets here, OK to save to requests table in DB and return a good message for once! (cause of either not in, or will have exited on 52)
        $submit = $q -> submitRequest($id, $date1, $date2, $texta, $date_now, $hours);
             
        
        
        
        if ($submit == true)
        {
             
             $to = $q -> getManagerEmail($id);
             
             $subject = "New request awaiting approve in the TRC dashboard";
             $txt = "Hi! You have a new request made by one of your employees in the TRC dashboard. Please log in to see all pending requests.";
             $headers = "From: dashboard@thereachcentre.com";
             $mail = mail($to,$subject,$txt,$headers); //not actually done, will do later... 
             if ($mail == true)
             {
                  $_SESSION['message'] = "Thankyou! Your leave request was recieved successfully. You will be able to track the status of it on the Dashboard. Please feel free to make more requests, or navigate away from the page.";
                   header("Location: requestLeave.php");
             }
        }
        else
        {
            $_SESSION['error'] = "Error: Something went wrong with the SQL request";
            header("Location: requestLeave.php");
        }
    }
 
}


