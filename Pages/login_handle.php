<?php

 include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/PDO_Query.php';
 print_r($_POST);

if ( empty($_POST['username']) || (empty($_POST['password']) ) )
{
    session_start();
    $_SESSION["error"] = "Please enter values for both username and password, and try again.";
    header("Location: Login.php");
    exit;
    
}

$username = $_POST["username"];
$password = $_POST["password"];

$q = new PDO_Query();
$login = $q -> validateLogin($username, $password);

if ($login === false)
{
    session_start();
    $_SESSION["error"] = "Sorry, incorrect username or password. Please try again, or reset password.";
    header("Location: Login.php");
}
else
{//logged in
    session_start();      
    
    
    
    $_SESSION = $login;
    $_SESSION['login'] = true;
    
   
    header("Location: dashboard.php");
}