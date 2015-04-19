<?php

 include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/PDO_Query.php';

 $q = new PDO_Query();
 session_start();

 $employee_name = $_POST['employee_name'];
 $username = $_POST['username'];
 $rand = (base64_encode(md5(microtime().rand())));
 $password = substr($rand, 0, 8);
 //change to this password in db
 $manager_name = $_POST['manager_name'];
 
 $manager_id = $q->fetchIDfromName($manager_name);
 
 if ($manager_id == false)
 {
    $_SESSION['error'] = "Please enter a correct employee name as your manager!";
    header("Location: new_employee.php");
    exit;
 }
 
 $employee_type = $_POST['employee_type'];
 $email_address = $_POST['email_address'];
 
 if ($employee_type == "manager")
 {
     $is_manager = true;
 }
 else
 {
     $is_manager = false;
 }
 //employee role
 if ($employee_type == "administrator")
 {
     $is_admin = true;
 }
 else
 {
     $is_admin = false;
 }
 

 $hours_day = $_POST['hours_day'];
 $creation_date = date("Y-m-d");
 
 //employee name, email address and username need to be unique
 
 $unique = $q->isInfoUnique($username, $email_address);
 
if ($unique == true)//comes through as 1 or 0 cause column, limit one)
{
    $_SESSION['error'] = "Sorry, please enter unique values for employee name, username and email address!";
    header("Location: new_employee.php");
    exit;
}

  $query = $q->insertEmployee($employee_name, $username, $password, $email_address, $is_manager, $is_admin, $hours_day, $creation_date, $manager_id);

  
  $total_hours_this = $_POST['total_hours_this'];
  $total_hours_next = $_POST['total_hours_next'];
  
  $year = date("Y");
  $year_next = $year + 1;
  $employee_id = $query;
  

  $query2 = $q->CreateHoliday($employee_id, $year, $total_hours_this);
  $query3 = $q->CreateHoliday($employee_id, $year_next, $total_hours_next);
  print_r($query2);
  print_r("<br>");
  print_r($query3);


  
 if ($query == true)
 {
        $to = $email_address; //email employee telling him details
        $subject = "Your new TRC Dashboard account";
        $txt = "Hi, $employee_name. \r\n Your new TRC dashboard account details: \r\n Username: $username  \r\n Password: $password \r\n   \r\n Please now try and log in, to change your password and request leave! \r\n".
        $headers = "From: dashboard@thereachcentre.com";
        $mail = mail($to,$subject,$txt,$headers); //not actually done, will do later... 
        
        
        $manager_details = $q->getEmployeeDetails($manager_name);
    //keep manager up to date as well
        $to = $manager_details["email_address"];
        $subject = "New employee on the TRC dashboard";
        $txt = "Hi, $manager_name. \r\n This is a quick email to notify you that $employee_name has been registered as your employee on the TRC dashboard. You will be able to log in and see his/her leave requests at any time. \r\n".
        $headers = "From: dashboard@thereachcentre.com";
        $mail = mail($to,$subject,$txt,$headers); 
     
      $_SESSION['message'] = "Thankyou! The employee should have recieved an email with a new password! Please feel free to navigate away from this page, or create another new employee record.";
      header("Location: new_employee.php");
      exit;
 }
 else
 {
      $_SESSION['error'] = "Sorry, something was wrong with your request - please try again or contact an administrator.";
      header("Location: new_employee.php"); 
      exit;
      
 }
         