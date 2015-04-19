<?php

include_once 'PDO_Connection.php';


class PDO_Query extends PDO_Connection{
    
    public function __construct() {
        parent::__construct();
        
    }
    
//checks username and password for login
    public function validateLogin($username, $password){ 

        try{
            $sql = "SELECT employee_id, employee_name, email_address, is_manager, is_admin FROM employees WHERE username = :_username AND password = :_password";
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute( array(':_username' => $username, ':_password' => $password) ) ){
                
                 return $stmt->fetch(); 
             
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
       
        
    }
    //check username goes with email - for reset password
    public function validateEmail($username, $email){ 

        try{
            $sql = "SELECT employee_id, employee_name FROM employees WHERE username = :_username AND email_address = :_email";
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute( array(':_username' => $username, ':_email' => $email) ) ){
                
                 return $stmt->fetch(); 
             
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
       
        
    }
    //update password in database
    public function changePassword($password, $id){ 

        try{
           // var_dump($password);
            $id = $id["employee_id"];
            $sql = "UPDATE employees SET password = :_password WHERE employee_id = :_id";
            $stmt = $this->_db->prepare($sql);
             
            if( $stmt->execute( array(':_password' => $password, ':_id' => $id) ) ){
                
                 return TRUE; 
                 
            }
            else{
                
                return "IT BE BROKEN";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
       
        
    }
    //fetches up to the 5 next requests an employee has
    public function getRequestsFromID($id){ 

        try{
            //$date = date("Y-m-d");
            $sql = "SELECT request_id, date_start, date_end, confirmation_status, date_submitted, reason_manager, hours_requested "
                    . "FROM requests "
                    . "WHERE employee_id = :_id "
                    . "AND date_start >= CURDATE()" //not before today
                    . "ORDER BY date_start ASC LIMIT 5";
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute( array(':_id' => $id) ) ){
                
                 return $stmt->fetchAll(); 
                 
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
       
        
    }
    //simple fetch from this year and next year's holiday allocations
    public function getHolidayInfo($id){ 

        try{
            $year = date("Y");
            $sql = "SELECT h.year, h.hours_holiday, h.total_hours, h.hours_pending, e.hours_day "
                    . "FROM holidays AS h "
                    . "RIGHT JOIN employees AS e "
                    . "ON e.employee_id = h.employee_id "
                    . "WHERE h.employee_id = :_id AND year >= $year ORDER BY year ASC";
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute( array(':_id' => $id) ) ){
                
                 return $stmt->fetchAll(); 
                 
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
       
        
    }
    //validate password for password change
    public function isPasswordCorrect($password, $id){ 

        try{
            $sql = "SELECT COUNT(employee_id) FROM employees WHERE employee_id = :_id AND password = :_password";
            $stmt = $this->_db->prepare($sql);
            
            if( $stmt->execute( array(':_password' => $password, ':_id' => $id) ) ){
              return $stmt -> fetchColumn();
             
            }
            else{
                
                return "IT BE BROKEN";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
       
        
    }
    //fetch current password to compare if user is allowed to continue
    public function getPassword($id){ 

        try{
            $sql = "SELECT password FROM employees WHERE employee_id = :_id";
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute( array(':_id' => $id) ) ){
                
                 return $stmt->fetch(); 
             
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
//get dates of all requests for employee - to prevent duplicate date requests
    public function returnDatesOfExistingRequests($id){ 

        try{
            $sql = "SELECT date_start, date_end FROM requests WHERE employee_id = :_id AND (confirmation_status = 'pending' OR confirmation_status = 'approved') AND 
                (date_start > CURDATE()) " ;
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute( array(':_id' => $id) ) ){
                
                 return $stmt->fetchAll(); 
             
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
//add new request to database
    public function submitRequest($id, $date_start, $date_end, $reason_employee, $date_submitted, $_hours ){ 
        try{
            //initialise variables
            $temp1 = explode("-", $date_start);
           
            $year_start = $temp1[0]; //get year
            
            $temp2 = explode("-", $date_end);
            
            $year_end = $temp2[0];
            $date_start_time = new DateTime($date_start);
            $date_end_time  = new DateTime($date_end);
            
            //branch between 1 year and 2 years
            
            
           //if leave is split over 2 years (i.e dec 2014-jan 2015)
            if ($year_start != $year_end) // 2 years 
            {
                $sql_holInfo = "SELECT e.hours_day, h.hours_holiday, h.total_hours, h.hours_pending FROM employees AS e INNER JOIN holidays AS h ON e.employee_id = h.employee_id WHERE e.employee_id = :_id AND year = :_year";
                //for current year
                $stmt_this = $this->_db->prepare($sql_holInfo);
                $stmt_this->execute(array(':_id' => $id, ':_year' => $year_start));
                $hol_info_this = $stmt_this->fetch(); //used to be called hours (confusion)
                
                //for next year
                $stmt_next = $this->_db->prepare($sql_holInfo);
                $stmt_next->execute(array(':_id' => $id, ':_year' => $year_end));
                $hol_info_next = $stmt_next->fetch(); //used to be called hours (confusion)
            
                //This section for if you ever want to implement the automatic days requested system that we decided was too hard
               
//                $endofyear = new DateTime(date('Y-12-31'));
//                $days_requested_this = date_diff($date_start_time, $endofyear);
//                $days_requested_this = $days_requested_this->days;
//                //maybe needed +1 - test this!
//                
//                $days_requested_next = date_diff($endofyear, $date_end_time);
//                $days_requested_next = $days_requested_next->days;
//                $days_requested_next = $days_requested_next + 1;
//                
//                $hours_requested_this = $days_requested_this*$hol_info_this["hours_day"];//for year = this
//                $hours_requested_next = $days_requested_next*$hol_info_next["hours_day"]; //for year = next
                  
                if ($_hours == "unpaid")
                {
                    $hours_requested = 0;
                    
                }
                else
                {
                    $hours_requested = $_hours;
                }
            
                
                  $usedhours = $hol_info_this["hours_holiday"] + $hol_info_this["hours_pending"];
                  
                  $hours_remaining = ($hol_info_this["total_hours"] - $usedhours); //should never be negative
                  
                  if ($hours_remaining < $hours_requested)
                  {
                      //taking off 2 years...
                      $hours_requested_next = $hours_requested - $hours_remaining;
                      $hours_requested_this = $hours_remaining;
                  }
                  else
                  {
                      $hours_requested_this = $hours_requested;
                      $hours_requested_next = 0;
                  }
                $totalHours_this = $hours_requested_this + $usedhours;
                $totalHours_next = $hours_requested_next + $hol_info_next["hours_holiday"] + $hol_info_next["hours_pending"];
                
            
            
                 if ( ($totalHours_next > $hol_info_next["total_hours"])) // if next year's allocation is full 
                {
                    $_SESSION['error'] = "Sorry, you have already used your full holiday allocation for next year somehow!!";
                    return 0;
                }
                else //they can book holiday
                {
                    if ($_hours == "unpaid")
                    {   
                        $sql = "INSERT INTO requests (employee_id,date_start,date_end,hours_requested,reason_employee,date_submitted, type) " //submit the request
                           . "VALUES (:_id, :_date_start, :_date_end, :_hours_requested, :_reason_employee, :_date_submitted, 'UNPAID')";
                          $stmt = $this->_db->prepare($sql);
                          $execute = $stmt->execute( array(':_id' => $id,':_date_start' => $date_start,':_date_end' => $date_end, ':_hours_requested' => $hours_requested, ':_reason_employee' => $reason_employee,':_date_submitted' => $date_submitted) ); 
                    }
                    else
                    {
                          $sql = "INSERT INTO requests (employee_id,date_start,date_end,hours_requested,reason_employee,date_submitted) " //submit the request
                           . "VALUES (:_id, :_date_start, :_date_end, :_hours_requested, :_reason_employee, :_date_submitted)";
                          $stmt = $this->_db->prepare($sql);
                          $execute = $stmt->execute( array(':_id' => $id,':_date_start' => $date_start,':_date_end' => $date_end, ':_hours_requested' => $hours_requested, ':_reason_employee' => $reason_employee,':_date_submitted' => $date_submitted) ); 
                    
                     
                    }
                 
                    
                    
                    if ($execute == true) //query succeeded - insert the amount of hours pending for both years...
                    {
                        
                        $hours_requested_this = $hol_info_this['hours_pending'] + $hours_requested_this; //set total time pending rather than just this amount of time
                        $hours_requested_next = $hol_info_next['hours_pending'] + $hours_requested_next; //for both
                        $sql_this = "UPDATE holidays "
                                . "SET hours_pending=:_hours_requested "
                                . "WHERE employee_id=:_id AND year = :_year ";
                        $stmt_this = $this->_db->prepare($sql_this);
                        $execute_this = $stmt_this->execute(array(':_hours_requested' => $hours_requested_this, ':_id' => $id, ':_year' => $year_start));
                        
                       if ($hours_requested_next !== 0)
                       {
                            $sql_next = "UPDATE holidays "
                                    . "SET hours_pending=:_hours_requested "
                                    . "WHERE employee_id=:_id AND year = :_year ";
                            $stmt_next = $this->_db->prepare($sql_next);
                            $execute_next = $stmt_next->execute(array(':_hours_requested' => $hours_requested_next, ':_id' => $id, ':_year' => $year_end));
                       }
                       else
                       {
                           $execute_next = true;
                       }
                      
                        if (($execute_this) == true && ($execute_next == true))
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    } 
                    else
                    {
                        return $execute; //query failed - return this!
                    }
                    
                }
            }
               else //if all the leave is set in one year (usual case)
            {
                
               $sql_holInfo = "SELECT e.hours_day, h.hours_holiday, h.total_hours, h.hours_pending FROM employees AS e INNER JOIN holidays AS h ON e.employee_id = h.employee_id WHERE e.employee_id = :_id AND year = :_year";
                //for current year
                $stmt = $this->_db->prepare($sql_holInfo);
                $stmt->execute(array(':_id' => $id, ':_year' => $year_start)); //as only 1 year
                $hol_info = $stmt->fetch(); 
                
                //set more variables
               
                $days_requested = date_diff($date_start_time, $date_end_time); //use imput field for hours 
                $days_requested = $days_requested->days;
                $days_requested = $days_requested + 1; //for last day
                
                $hours_requested= $days_requested*$hol_info["hours_day"];//for year = this
               
                if ($hours_requested !== $_hours)
                {
                    if ($_hours == "unpaid")
                    {
                        $_SESSION['warning'] = "You have requested unpaid leave"; //unpaid leave set - notification
                    }
                    else
                    {
                        $_SESSION['warning'] = "You have requested " . ($_hours). " hours of holiday spread over $days_requested total days.";
                    }
                    
                }
                
                
                
                if ($_hours == "unpaid")
                {
                    $hours_requested = 0;
                }
                else
                {
                    $hours_requested = $_hours;
                }
                
                
                $totalHours = $hours_requested + $hol_info["hours_holiday"] + $hol_info["hours_pending"];
                
                 if ($totalHours > $hol_info["total_hours"]) // if either this or next year's allocation is full
                {
                    $_SESSION['error'] ="Sorry, you have already used your full holiday allocation!";
                    return 0;
                }
                else //they can book holiday
                {
                    if ($_hours == "unpaid")
                    {   
                        $sql = "INSERT INTO requests (employee_id,date_start,date_end,hours_requested,reason_employee,date_submitted, type) " //submit the request
                           . "VALUES (:_id, :_date_start, :_date_end, :_hours_requested, :_reason_employee, :_date_submitted, 'UNPAID')";
                          $stmt = $this->_db->prepare($sql);
                          $execute = $stmt->execute( array(':_id' => $id,':_date_start' => $date_start,':_date_end' => $date_end, ':_hours_requested' => $hours_requested, ':_reason_employee' => $reason_employee,':_date_submitted' => $date_submitted) ); 
                    }
                    else
                    {   
                       
                          $sql = "INSERT INTO requests (employee_id,date_start,date_end,hours_requested,reason_employee,date_submitted) " //submit the request
                           . "VALUES (:_id, :_date_start, :_date_end, :_hours_requested, :_reason_employee, :_date_submitted)";
                          $stmt = $this->_db->prepare($sql);
                          $execute = $stmt->execute( array(':_id' => $id,':_date_start' => $date_start,':_date_end' => $date_end, ':_hours_requested' => $hours_requested, ':_reason_employee' => $reason_employee,':_date_submitted' => $date_submitted) ); 
                    
                    } 
                    
                    if ($execute == true) //query succeeded - insert the amount of hours pending for both years...
                    {
                        $hours_requested = $hol_info['hours_pending'] + $hours_requested; //set total time pending rather than just this amount of time
                        
                        $sql    = "UPDATE holidays "
                                . "SET hours_pending=:_hours_requested "
                                . "WHERE employee_id=:_id AND year = :_year ";
                        $stmt = $this->_db->prepare($sql);
                        $execute = $stmt->execute(array(':_hours_requested' => $hours_requested, ':_id' => $id, ':_year' => $year_start)); //as only 1
                    
                        if ($execute == true)
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    } 
                    else
                    {
                        return $execute; //query failed - return this!
                    }
                    
            }
        }
        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
    //adds new employee to table
    public function insertEmployee($employee_name, $username, $password, $email_address, $is_manager, $is_admin, $hours_day, $creation_date, $manager_id){ 

        try{
           $sql = "INSERT INTO employees (employee_name, username, password, email_address, is_manager, is_admin, hours_day, creation_date) " //submit the request
                           . "VALUES (:_employee_name, :_username, :_password, :_email_address, :_is_manager, :_is_admin, :_hours_day, :_creation_date)";
           $stmt = $this->_db->prepare($sql);
           $execute = $stmt->execute( array(":_employee_name" => $employee_name, ":_username" => $username, ":_password" => $password, ":_email_address" => $email_address, ":_is_manager" => $is_manager, ":_is_admin" => $is_admin, ":_hours_day" => $hours_day, ":_creation_date" => $creation_date) ); 
           if ($execute == true) //set manager details
           {
               $insertedID = $this->_db->lastInsertId();
               $sql2 = "INSERT INTO managers (manager_id, employee_id) VALUES (:_manager_id, $insertedID)"; //updates manager table to add the new employee and his manager
                $stmt = $this->_db->prepare($sql2);
               $execute2 = $stmt->execute( array(":_manager_id" => $manager_id));
               if ($execute2 == true)
               {
                  return $insertedID;
               }
               else
               {
                   return false;
               }
               
           }
           else
           {
               return $execute;
           }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
    //returns employee_id given employee_name
    public function fetchIDfromName($manager_name){ 

        try{
            $sql = "SELECT employee_id FROM employees WHERE employee_name = :_employee_name LIMIT 1";
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute( array(':_employee_name' => $manager_name) ) ){
                
                 return $stmt->fetchColumn(); 
             
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
    //checks no-one else has selected username or email address - Not case sensitive however FIX ME!
    public function isInfoUnique($username, $email_address){ 
        try{
            $sql = "SELECT employee_id FROM employees WHERE (username = :_username OR email_address = :_email_address) LIMIT 1";
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute( array(':_username' => $username,':_email_address' => $email_address) ) ){
                
               
                 return $stmt->fetchColumn();
             
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}

//select important employee details for form population, given employee name
    public function getEmployeeDetails($employee_name){ 

        try{ //fetch major details, including internal id
            $sql = "SELECT employee_id, username, password, email_address, is_manager, is_admin, hours_day FROM employees WHERE employee_name = :_employee_name";
            $stmt = $this->_db->prepare($sql);
            $details = array();
            if( $stmt->execute( array(':_employee_name' => $employee_name) ) ){
                $details = $stmt->fetch();
                if (is_null($details))
                {
                    $_SESSION['error'] ="Error: It would appear employee name did not exist - Please check for errors and try again!";
                    return 0;
                }
                $employee_id = $details['employee_id']; //set id 
                
               $sql2 = "SELECT manager_id FROM managers WHERE employee_id = :_employee_id"; //fetch manager name first to allow "no manager" to be a thing (if manager leaves, set to 0)
               $stmt2 = $this->_db->prepare($sql2);
               if( $stmt2->execute( array(':_employee_id' => $employee_id) ) ){
               
                   $manid = $stmt2->fetchColumn();
                  
                   if ($manid === false)
                    {
                        $_SESSION['error'] ="Error: It would appear that the employee has no manager. You're going to need to alter the database manually...";
                        return 0;
                    }
                    else {
                         if ($manid !== 0)
                         {
                             $sql3 = "SELECT employee_name FROM employees WHERE employee_id = :_employee_id "; //get manager name
                                $stmt3 = $this->_db->prepare($sql3);
                            if( $stmt3->execute( array(':_employee_id' => $manid ) )){
                              $result3 = $stmt3->fetchColumn();
                       
                    
                            if ($result3 !== false)
                              {
                                 $details['manager_name'] = $result3;
                          
                         }
                         else
                         {
                             $details['manager_name'] = "";
                         }
                  
                            
                                   $sql4 = "SELECT year, total_hours FROM holidays WHERE employee_id = :_employee_id "; //get the last few details from a different table
                                   $stmt4 = $this->_db->prepare($sql4);
                                   if( $stmt4->execute( array(':_employee_id' => $employee_id ) )){
                                       $year_this = date("Y");
                                       $year_next = $year_this + 1;
                                    $statementfour = $stmt4->fetchAll();
                                    $i = 0;
                                   foreach($statementfour as $data)
                                     {
                                          if ($data['year'] == $year_this || $data["year"] == $year_next)
                                          {
                                              $details_array[$i] = array($data["year"] => $data["total_hours"]);
                                              $i++;
                                           }
                                     }
                             //  print_r($details_array);
                               
                                      $details['hours_hol_this'] = $details_array[0][$year_this];
                                      $details['hours_hol_next'] = $details_array[1][$year_next];
                               
                                }
                             }
                            return $details;
                       
                       
                        }
                         else{
                            return "error";
                         }   
                    }
                 
               }
               else{
                       return "error";
                   }
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
    //insert new details to employees and managers tables for edit_employee.php
    public function updateEmployeeDetails($employee_id, $username, $email_address, $is_manager, $is_admin, $hours_day, $manager_name){ 

        try{
             $sql = "UPDATE employees "
                     . "SET username = :_username, email_address = :_email_address, is_manager = :_is_manager, is_admin = :_is_admin, hours_day=:_hours_day "
                     . "WHERE employee_id = :_employee_id";
             $stmt = $this->_db->prepare($sql);
            if( $stmt->execute( array(':_username' => $username, ':_email_address' => $email_address, ':_is_manager' => $is_manager,':_is_admin' => $is_admin, ':_hours_day' => $hours_day, ':_employee_id' => $employee_id) ) ){
               
                    $manager_id = $this->fetchIDfromName($manager_name);//fetch manager ID being that we are only given name
                    
                    $sql1 = "UPDATE managers "
                     . "SET manager_id = :_manager_id "
                     . "WHERE employee_id = :_employee_id ";
                     $stmt1 = $this->_db->prepare($sql1);
                    if ( $stmt1->execute( array(':_employee_id' => $employee_id , ':_manager_id' => $manager_id ))){
                    
                        $_SESSION['message'] ="Employee succesfully updated!";
                        return 1;
                    }
                    else
                    {
                        $_SESSION['error'] ="Error: Employee update failed";
                        return 0;
                    }
                }   
                 else
                {
                    return "error";
                }
            }

       
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
    //to validate if an entered employee name to "manager name" field fulfills "is manager" criteria
    public function fetchIDandStatusfromName($manager_name){ 

        try{
            $sql = "SELECT employee_id, is_manager FROM employees WHERE employee_name = :_employee_name LIMIT 1";
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute( array(':_employee_name' => $manager_name) ) ){
                
                 return $stmt->fetch(); 
             
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}

    public function CreateHoliday($employee_id, $year, $total_hours){ 

        try{
            $sql = "INSERT INTO holidays (employee_id, year, total_hours) " //submit the request
                           . "VALUES (:_employee_id, :_year, :_total_hours)";
           $stmt = $this->_db->prepare($sql);
           $execute = $stmt->execute( array(":_employee_id" => $employee_id, ":_year" => $year, ":_total_hours" => $total_hours));
    
            if($execute !== false){
                
                 return true; 
             
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
    //Select all employee names (for dropdown)
    public function fetchEmployeeNames(){ 

        try{
            $sql = "SELECT employee_name FROM employees";
            $stmt = $this->_db->prepare($sql);
            if( $stmt->execute()){
                
                 return $stmt->fetchAll(); 
                 
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
    //update values in holiday table to add or substract leave if total allocated amount changes for a specified year)
    public function updateHolidayDetails($employee_id, $hours, $year){ 

        try{
            $sql = "UPDATE holidays "
                     . "SET total_hours = :_total_hours "
                     . "WHERE employee_id = :_employee_id AND year = :_year";
       
            $stmt = $this->_db->prepare($sql);
            $execute = $stmt->execute(array(":_employee_id" => $employee_id, ":_year" => $year, ":_total_hours" => $hours));
            if( $execute == true){
                
                 return $execute;
                 
            }
            else{
                
                return false;
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
    //remove one employee
    public function deleteFromEmployees($id){ 

        try{
            $sql = "DELETE FROM employees WHERE employee_id = :_employee_id";
            $stmt = $this->_db->prepare($sql);
            if( $stmt->execute(array(":_employee_id" => $id))){
                
                 return true;
                 
            }
            else{
                
                return false;
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
    //remove employee from manager table
    public function deleteFromManagers ($id){ 

        try{
            $sql = "DELETE FROM managers WHERE employee_id = :_employee_id";
            $stmt = $this->_db->prepare($sql);
            if( $stmt->execute(array(":_employee_id" => $id))){
                
                 return true;
                 
            }
            else{
                
                return false;
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
    //if a a manager leaves make his employees have 0 manager
    public function updateManagerToNull($id){ 

        try{
            
            $sql = "SELECT e.employee_name "
                    . "FROM employees AS e "
                    . "LEFT JOIN managers AS m "
                    . "ON e.employee_id = m.employee_id "
                    . "WHERE manager_id = :_manager_id";
            $stmt = $this->_db->prepare($sql);
            if( $stmt->execute(array(":_manager_id" => $id))){
                
                $employees_updated = $stmt->fetchAll();
                 
            }
            
            
            
            
            $sql2 = "UPDATE managers "
                     . "SET manager_id = NULL "
                     . "WHERE manager_id = :_manager_id";
            $stmt2 = $this->_db->prepare($sql2);
            if( $stmt2->execute(array(":_manager_id" => $id))){
                
                 return $employees_updated;
                 
            }
            else{
                
                return false;
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
    //remove an employees holiday (if he is deleted)
    public function deleteFromHolidays ($id){ 

        try{
            $sql = "DELETE FROM holidays WHERE employee_id = :_employee_id";
            $stmt = $this->_db->prepare($sql);
            if( $stmt->execute(array(":_employee_id" => $id))){
                
                 return true;
                 
            }
            else{
                
                return false;
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
    //remove an employee holiday requests if he is deleted
    public function deleteFromRequests ($id){ 

        try{
            $sql = "DELETE FROM requests WHERE employee_id = :_employee_id";
            $stmt = $this->_db->prepare($sql);
            if( $stmt->execute(array(":_employee_id" => $id))){
                
                 return true;
                 
            }
            else{
                
                return false;
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
//count the amount of pending requests a manager has to approve  ( - for notification popover)
public function howManyRequests ($id){ 

        try{
            $sql = "SELECT COUNT(r.request_id) "
                    . "FROM requests AS r "
                    . "LEFT JOIN managers AS m "
                    . "ON r.employee_id = m.employee_id "
                    . "WHERE m.manager_id = :_manager_id "
                    . "AND r.confirmation_status = 'PENDING'";
            $stmt = $this->_db->prepare($sql);
            if( $stmt->execute(array(":_manager_id" => $id))){
                
                 return $stmt->fetchColumn();
            }
            else{ 
                
                return false;
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}

//populate the manager approve request table
 public function getRequestsFromManagerID($id){ 

        try{
            $sql = "SELECT e.employee_id, e.employee_name, r.date_start, r.request_id, r.date_end, r.hours_requested, r.confirmation_status, r.date_submitted, r.reason_employee "
                    . "FROM requests AS r "
                    . "LEFT JOIN managers AS m "
                    . "ON r.employee_id = m.employee_id "
                    . "INNER JOIN employees as e "
                    . "ON e.employee_id = r.employee_id "
                    . "ORDER BY date_start ASC ";
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute( array(':_id' => $id) ) ){
                
                 return $stmt->fetchAll(); 
                 
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
       
        
    }
//update request row with the correct "approved" status
    public function approveRequest($confirmation_status, $reason_manager, $approved_by, $request_id){ 

        try{
            $sql = "UPDATE requests SET confirmation_status = :_confirmation_status, reason_manager = :_reason_manager, approved_by = :_approved_by WHERE request_id = :_request_id";
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute( array(':_confirmation_status' => $confirmation_status,':_reason_manager' => $reason_manager,':_approved_by' => $approved_by,':_request_id' => $request_id) ) ){
                
               return true;
                 
            }
            else{
                
                return false;
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
       
        
    }
//alter the holiday in the correct way
    public function alterHolidays($confirmation_status, $hours_requested, $employee_id, $year){ 
        if  ($confirmation_status == "APPROVED")
        {
                try{
                $sql = "UPDATE holidays "
                         . "SET hours_holiday = hours_holiday + :_hours_requested, hours_pending = hours_pending - :_hours_requested " //if accepted, add to accepted hours, take off pending hours
                         . "WHERE employee_id = :_employee_id "
                         . "AND year = :_year";

                $stmt = $this->_db->prepare($sql);
                $execute = $stmt->execute(array(":_employee_id" => $employee_id, ":_hours_requested" => $hours_requested, ":_year" => $year));
                
                if( $execute == true){

                     return $execute;

                }
                else{

                    return false;
                }

            }
            catch(PDOException $e){
                    return array('error' => $e->getMessage());
            }   
        }
        elseif ($confirmation_status == "DENIED")
        {
            try{
            $sql = "UPDATE holidays "
                     . "SET hours_pending = hours_pending - :_hours_requested " //if declined, just take off pending hours
                     . "WHERE employee_id = :_employee_id "
                     . "AND year = :_year";
            $stmt = $this->_db->prepare($sql);
            $execute = $stmt->execute(array(":_employee_id" => $employee_id, ":_hours_requested" => $hours_requested, ":_year" => $year));
                if( $execute == true){

                     return $execute;

                }
                else{

                    return false;
                }

            }
            catch(PDOException $e){
                    return array('error' => $e->getMessage());
            }   

        }
        else
        {
            return false;
        }
}
//takes request id, deletes row from table
 public function deleteFromRequests_id ($id){ 
     
        try{
            //print_r($id);
            $sql2 = "SELECT employee_id "
                    . "FROM requests "
                    . "WHERE request_id = :_id";
                 $stmt2 = $this->_db->prepare($sql2);   
                 
            if( $stmt2->execute(array(":_id" => $id))){
                $return = $stmt2->fetchColumn();
                
                $sql = "DELETE FROM requests WHERE request_id = :_id";
                $stmt = $this->_db->prepare($sql);
                $execute = $stmt->execute(array(":_id" => $id));
               if ($execute == true)
               {
                   return $return;
               }
               else
               {
                   return false;
               }
                 
            }
            else{
                
                return false;
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}

//alter holiday when admin confirm (for pending and approved) - actually remove 
 public function alterHolidays2($confirmation_status, $hours_requested, $employee_id, $year){ 
     
        if  ($confirmation_status == "PENDING")
        {
         
                try{
                $sql = "UPDATE holidays "
                         . "SET  hours_pending = hours_pending - :_hours_requested "
                         . "WHERE employee_id = :_employee_id "
                         . "AND year = :_year";

                $stmt = $this->_db->prepare($sql);
                $execute = $stmt->execute(array(":_employee_id" => $employee_id, ":_hours_requested" => $hours_requested, ":_year" => $year));
               
               
     
                if( $execute == true){
                    
                     return $execute;

                }
                else{
                    
                    return false;
                }

            }
            catch(PDOException $e){
                    return array('error' => $e->getMessage());
            }   
        }
        elseif ($confirmation_status == "APPROVED")
        {
           
            try{
            $sql = "UPDATE holidays "
                     . "SET hours_holiday = hours_holiday - :_hours_requested "
                     . "WHERE employee_id = :_employee_id "
                     . "AND year = :_year";
            $stmt = $this->_db->prepare($sql);
            $execute = $stmt->execute(array(":_employee_id" => $employee_id, ":_hours_requested" => $hours_requested, ":_year" => $year));
                if( $execute == true){

                     return $execute;

                }
                else{

                    return false;
                }

            }
            catch(PDOException $e){
                    return array('error' => $e->getMessage());
            }   

        }
        else
        {
           
            return false;
        }
}
//fetch the holiday information from the year required
public function getNewHolInfo($id, $year){ 

        try{
            $sql = "SELECT h.total_hours, e.employee_id, e.employee_name, e.hours_day "
                    . "FROM employees AS e "
                    . "RIGHT JOIN holidays AS h "
                    . "ON e.employee_id = h.employee_id "
                    . "WHERE e.employee_id = :_employee_id "
                    . "AND h.year = :_year LIMIT 1";
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute( array(':_employee_id' => $id, ':_year' => $year) ) ){
                
                 return $stmt->fetch(); 
             
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
//alter next year holidays if they edit an existing one
public function updateNextYearHolidays($id, $total_hours){ 
    try{
        $year = date('Y') + 1 ;
                $sql = "UPDATE holidays "
                         . "SET  total_hours = :_total_hours "
                         . "WHERE employee_id = :_employee_id "
                         . "AND year = :_year";

                $stmt = $this->_db->prepare($sql);
                $execute = $stmt->execute(array(":_employee_id" => $id, ":_total_hours" => $total_hours, ":_year" => $year));
               
               
     
                if( $execute == true){
                    
                     return $execute;

                }
                else{
                    
                    return false;
                }

            }
    catch(PDOException $e){
            return array('error' => $e->getMessage());
    }   


}
//fetch all request info for the next 15 requests after today, not caring about employee
public function getRequests(){ 

        try{
            
            $sql = "SELECT e.employee_name, r.request_id, r.date_start, r.date_end,  r.confirmation_status, r.date_submitted, r.reason_manager, r.reason_employee, r.hours_requested, r.approved_by, r.type "
                    . "FROM requests AS r "
                    . "LEFT JOIN employees AS e "
                    . "ON r.employee_id = e.employee_id "
                    . "WHERE date_start >= CURDATE()" //not before today
                    . "ORDER BY date_start ASC LIMIT 15";
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute() ){
                
                 return $stmt->fetchAll(); 
                 
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
       
        
    }
    
//get all requests ever made (and not deleted) for a specific user - for admin tools
public function getHistoricalRequests($username){ 

        try{
            
            $id = $this->fetchIDfromName($username);
            $sql = "SELECT  request_id, date_start, date_end, confirmation_status, date_submitted, reason_manager, reason_employee, hours_requested, approved_by, type "
                    . "FROM requests "
                    . "WHERE employee_id = :_id " 
                    . "ORDER BY date_start ASC ";
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute(array(":_id" => $id)) ){
                
                 return $stmt->fetchAll(); 
                 
            }
            else{
                
                return "nothing found";
            }
            
        } 
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   

}

//find out employee and the dates of the request from the request ID (admin tools)
public function getInfoFromRequestID($request_id){ 

        try{
            
            $sql = "SELECT  employee_id, date_start "
                    . "FROM requests "
                    . "WHERE request_id = :_request_id LIMIT 1";
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute(array(":_request_id" => $request_id)) ){
                
                $return = $stmt->fetch();
                 return $return; 
                 
            }
            else{
                
                return "nothing found";
            }
            
        } 
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   

}

public function getManagerEmail($employee_id){ 

        try{
            //select managers email address given employee id
            $sql = "SELECT e.email_address "
                    . "FROM employees AS e "
                    . "LEFT JOIN managers AS m "
                    . "ON e.employee_id = m.manager_id "
                    . "WHERE m.employee_id = :_employee_id";
            
            $stmt = $this->_db->prepare($sql);
    
            if( $stmt->execute(array(":_employee_id" => $employee_id)) ){
                
                $return = $stmt->fetchColumn();
                 return $return; 
                 
            }
            else{
                
                return "nothing found";
            }
            
        } 
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   

}

public function fetchManagerNames(){ 

        try{
            $sql = "SELECT employee_name FROM employees WHERE is_manager = 1 ";
            $stmt = $this->_db->prepare($sql);
            if( $stmt->execute()){
                
                 return $stmt->fetchAll(); 
                 
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}

public function getEmployeeLeave(){ 
//select all employee leave for the current year
        try{
            $year = date("Y");
            $sql = "SELECT h.hours_holiday, h.hours_pending, h.total_hours, e.employee_name, e.hours_day "
                    . "FROM employees AS e "
                    . "INNER JOIN holidays AS h "
                    . "ON h.employee_id = e.employee_id "
                    . "WHERE year = :_year";
            $stmt = $this->_db->prepare($sql);
            if( $stmt->execute(array(":_year" => $year))){
                
                 return $stmt->fetchAll(); 
                 
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}

public function fetchRequestsMonth(){ 
//select all employee leave for the current year
        try{
//            $date = date("d-m-Y");
//            $month_ago = strtotime( '-1 month', $date );
            
            $sql = "SELECT e.employee_name, e.hours_day, r.date_start, r.date_end, r.approved_by, r.type "
                    . "FROM employees AS e "
                    . "INNER JOIN requests AS r "
                    . "ON r.employee_id = e.employee_id "
                    . "WHERE r.confirmation_status = 'APPROVED' "
                    . "AND ((DATE(date_end) BETWEEN DATE_ADD(NOW(), INTERVAL -1 MONTH) AND NOW()) OR (DATE(date_start) BETWEEN DATE_ADD(NOW(), INTERVAL -1 MONTH) AND NOW()))" ;
            $stmt = $this->_db->prepare($sql);
            if( $stmt->execute(array())){
                
                 return $stmt->fetchAll(); 
                 
            }
            else{
                
                return "nothing found";
            }

        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
}
