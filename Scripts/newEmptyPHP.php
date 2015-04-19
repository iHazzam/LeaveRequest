public function submitRequest_hist($id, $date_start, $date_end, $reason_employee, $date_submitted ){ 
        try{
            $sql2 = "SELECT e.hours_day, h.year, h.hours_holiday, h.total_hours, h.hours_pending FROM employees AS e INNER JOIN holidays AS h ON e.employee_id = h.employee_id WHERE e.employee_id = :_id ORDER BY year ASC";
            $stmt2 = $this->_db->prepare($sql2);
            $stmt2->execute(array(':_id' => $id));
            $hours = $stmt2->fetchAll();
             // work out how many hours they want 
             print_r($hours);
             exit;
            $temp1 = explode("-", $date_start);
            $year_start = $temp1[2];
            $temp2 = explode("-", $date_end);
            $year_end = $temp2[2];
            $date_start_time = new DateTime($date_start);
            $date_end_time  = new DateTime($date_end);
            if ($year_start != $year_end)
            {
                $endofyear = date('Y-12-31');
                $days_requested_this = date_diff($date_start_time, $endofyear);
                $days_requested_next = date_diff($endofyear, $date_end_time);
                $days_requested_this = $days_requested_this->days;
                $days_requested_next = $days_requested_next->days;
                $days_requested_next = $days_requested_next + 1;
                $hours_requested_this = $days_requested_this*$hours["hours_day"];//for year = this
                $hours_requested_next = $days_requested_next*$hours["hours_day"]; //for year = next
                $totalHours = $hours_requested_this + $hours_requested_next + $hours["hours_holiday"] + $hours["hours_pending"];
                $flag = 1;
            }
            else
            {
                $days_requested = date_diff($date_start_time, $date_end_time);//datetime 
                $days_requested = $days_requested->days;//to int
                $days_requested = $days_requested+1;//+1 to include end and start day
                $hours_requested = $days_requested*$hours["hours_day"];//needs changed to be year 
                //something something weekends and bank holidays :/
                //something something days left 
                $totalHours = $hours_requested + $hours["hours_holiday"] + $hours["hours_pending"];
                $flag = 0;
                
            }
     
            if ($totalHours <= $hours["total_hours"])
            {
            
            $sql = "INSERT INTO requests (employee_id,date_start,date_end,reason_employee,date_submitted) "
                                 . "VALUES (:_id, :_date_start, :_date_end, :_reason_employee, :_date_submitted)";
            $stmt = $this->_db->prepare($sql);
    
            $execute = $stmt->execute( array(':_id' => $id,':_date_start' => $date_start,':_date_end' => $date_end,':_reason_employee' => $reason_employee,':_date_submitted' => $date_submitted) ); 
            print_r($execute);
            exit;
                if ($execute == true)
                {
                    if ($flag == 0) //if single year 
                    {
                        $hours_requested = $hours['hours_pending'] + $hours_requested; //set total time pending rather than just this amount of time

                        $sql3 = "UPDATE holidays "
                                . "SET hours_pending=:_hours_requested "
                                . "WHERE employee_id=:_id AND year = :_year ";
                        $stmt3 = $this->_db->prepare($sql3);
                        $execute3 = $stmt3->execute(array(':_hours_requested' => $hours_requested, ':_id' => $id, ':_year' => $year_start));//as they are both the same

                        if ($execute3 == true)
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    //add this to hours pending in holidays column
                    }
                    else //if $flag is 1 - we have 2 years
                    {
                      
                        $hours_requested_this = $hours['hours_pending'] + $hours_requested;
                        $hours_requested_next = 

                        $sql3 = "UPDATE holidays "
                                . "SET hours_pending=:_hours_requested "
                                . "WHERE employee_id=:_id AND year = ";
                        $stmt3 = $this->_db->prepare($sql3);
                        $execute3 = $stmt3->execute(array(':_hours_requested' => $hours_requested, ':_id' => $id));

                        if ($execute3 == true)
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    }
                }
                else
                {
                 return $execute;
                }
            }
            else
            {
                return array("Error" => "Sorry, you have no holiday available!");
            }
        }
        catch(PDOException $e){
                return array('error' => $e->getMessage());
        }   
}
