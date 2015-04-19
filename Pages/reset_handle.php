<?php
include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/PDO_Query.php';
include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/Header.php';

session_start();
if ( empty($_POST['username']) || (empty($_POST['email']) ) )
{
 
    $_SESSION["error"] = "Please enter values for both username and email address, and try again.";
    header("Location: reset_password.php");
}
$username = $_POST['username'];
$email = $_POST['email'];

$q = new PDO_Query();


$login = $q -> validateEmail($username, $email);

if ($login === false)
{
    
    $_SESSION["error"] = "Sorry, email address and username don't match. Please try again, or contact an administrator.";
    header("Location: reset_password.php");
}
else
{
   $name = $login['employee_name'];
    //generate random password
    $rand = (base64_encode(md5(microtime().rand()))); 
    $password = substr($rand, 0, 8);
    //change to this password in db
    $update = $q ->changePassword($password, $login);
    //print_r($update);
  //send email to employee telling them this has been done
    if ($update == true)
    {
        $to = $email; //send email with new password to employee
        $subject = "TRC dashboard password reset";
        $txt = "Hi, $name. \r\n Your new TRC dashboard password: $password. \r\n Please now try and log in! \r\n".
        $headers = "From: dashboard@thereachcentre.com";
        $mail = mail($to,$subject,$txt,$headers); 
        //$mail = true;
    }
    else
    {
         
         $_SESSION["error"] = "Sorry, password update failed. Please contact an administrator.";
         header("Location: reset_password.php");
    }
    
  
    //redirect to login on confirm of alert?
    if ($mail == true)
    {
             
       $_SESSION = array("message" => "Thanks. Your new password has been e-mailed to you, you will be able to change it in the terminal");
       header("Location: Login.php");
   
    }
    
    
}
   
     
    
    
    
    
 
