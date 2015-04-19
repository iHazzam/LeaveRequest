<?php
print_r($_POST);
$id = $_POST['employee_id'];
$is_manager = $_POST['is_manager'];
include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/PDO_Query.php';
$q = new PDO_Query;

//delete from employees table
$delete1 = $q->deleteFromEmployees($id);
//delete from managers table - If manager -  Email person who made delete request with details of who was managed by the employee
$delete2 = $q->deleteFromManagers($id);
$delete3 = true;
 session_start();
if ($is_manager == true)
{
    $i = 0;
    $employees_updated = $q->updateManagerToNull($id); //returns employee id's that have been updated
    foreach ($employees_updated as $employee)
    {
        
        $updated_list[$i] = $employee['employee_name'];
        $i++;
        
    }
    if (is_null($employees_updated))
    {
        $delete3 = false;
    }
    else
    {
    $updated_string = implode(",", $updated_list);
    $to = $_SESSION['email_address'];
    $subject = "Employees need to be updated";
    $txt = "Hi. \r\n You have deleted an employee from Dashboard, resulting in employees that need a new manager on the system. \r\n These employees are: $updated_string";
    $headers = "From: dashboard@thereachcentre.com";
    $mail = mail($to,$subject,$txt,$headers); //not actually done, will do later... 
    }
}

$delete4 = $q->deleteFromHolidays($id);
//delete from holidays table
$delete5 = $q->deleteFromRequests($id);
//delete from requests table 

//var_dump($delete1);
//print_r("<br>");
//var_dump($delete2);
//print_r("<br>");
//var_dump($delete3); //set by default, if fails, will become null
//print_r("<br>");
//var_dump($delete4);
//print_r("<br>");
//var_dump($delete5);

if (($delete1 == true) && ($delete2 == true) && ($delete3 == true) && ($delete4 == true) && ($delete5 == true))
{
    $_SESSION['message'] = "The employee has been deleted. Please feel free to navigate away from this page";
}
else
{
    $_SESSION['error'] = "There has been an error, and something might not have been deleted correctly. Please try again or contact an administrator";
}


header("Location: edit_employee.php"); 