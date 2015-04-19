<?php

//if password is correct in database
include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/PDO_Query.php';
session_start();
$id = $_SESSION['employee_id'];
$q = new PDO_Query();
$unlock = $q -> isPasswordCorrect($_POST['old_pass'], $id);
if ($unlock == true)
{
    if ($_POST['new_pass'] !== $_POST['new_pass_confirm'])
    {
        $_SESSION['error'] = "Sorry, the two entered passwords do not match. Please try again, or contact an administrator.";
        header("Location: changePassword.php");
    }
     else
    {
        print_r("comes here");
         $pass =  $q->changePassword($_POST['new_pass'], $id);
         print_r($pass);
         
         $_SESSION['message'] = "Thanks! Your new password has been set!";
         header("Location: changePassword.php");
    }
}
else
{
   $_SESSION['error'] = "Sorry, current entered password is incorrect - Please try again, or contact an administrator.";
   $current = $q->getPassword($id);
   print_r($current);
  
   header("Location: changePassword.php");
}