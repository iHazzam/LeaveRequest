<html><!-- options for managers to approve and deny leave with modals -->
    <head>
        <title>TRC Dashboard - Approve Leave</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
           <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
           <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
           <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
           <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/tooltip.js"></script>
           <script src="javascript/clock.js"></script>
           <script src="javascript/alert.js"></script>
           <link rel="stylesheet" href="sidebar.css">
           <style>
               body {
                   
                   background-color: #c0c0c0;
               }
               #navbar_login{
                     font-family: Georgia, "Times New Roman", Times, serif;
                    font-size:24px;
	
                text-align: center; 
                font-weight: normal;
                color: #222;
               }
               .popover{
                    width:200px;
                    height:60px;    
                    color: #ff0000;
                }
                
                  ._er{
                      color: #ff0000;
                }
                ._mess{
                      color: #00FF00;
                }
              
           </style>
    </head>
    
    <body onload="startTime()"> <!--jquery clock in top bar-->
         

              
        <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                 <a class="brand" href="index.html"> <img src="jpg_logo.jpg"></a>
            </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
          <li> <h3 class="navbar-text" id = "navbar_login">Logged in as 
           <?php 
          session_start();
          if (!isset($_SESSION['login'])) //redirect if they try to access this without logging in
          {
              header("Location: Login.php");
          }
          echo "<span class='username'>" . $_SESSION["employee_name"] . "  </span>";
          ?></h3> </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li> <h3 id = "navbar_login">  <div id="txt">  </div> </h3></li> <!-- javascript clock -->
        <li><a ></a> </li>
        <li><a class ="btn btn-info" href="http://www.chemtrac.co.uk/">Chemtrac</a></li>
        <li><a ></a> </li>
        <li><a class ="btn btn-info" href="http://www.thereachcentre.com/site/content_home.php">TRC Homepage</a></li>
        <li><a ></a> </li>
        <li><a class ="btn btn-success" href="logout.php">Logout</a></li>   
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Other useful links <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
              <li><a href="requestLeave.php">Request leave</a></li>
            <li class="divider"></li>
            <li><a href="changePassword.php">Change password</a></li>
            <li class="divider"></li>
            <li><a href="#">Contact administrator</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
            
     <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <div class="col-md-1" min-height="150">
                        
                    </div>
                </li>
                <li>
                    <a href="dashboard.php">Dashboard</a>
                </li>
                <li>
                    <a href="requestLeave.php">Request leave</a>
                </li>
                <li>
                    <a href="#">Calendar</a>
                </li>
                <li>
                    <a href="changePassword.php">Change Password</a>
                </li>
                <?php 
                        if ($_SESSION['is_manager'] == true)
                        {
                            $to_approve = $_SESSION['to_approve']; // this will be the number of pending requests for a manager to approve
                              echo "<li><a href='approveLeave.php' id='approve'>Approve Leave <span class='badge badge-important'>$to_approve &nbsp &nbsp &nbsp</span></a></li>";
                        }
                        if ($_SESSION['is_admin'] == true)
                        {
                             echo "<li>";
                                echo "<a href='administrator_tools.php'>View administrator tools</a>";
                             echo "</li>";
                             echo "<li>";
                                echo "<a href='new_employee.php'>New employee</a>";
                             echo "</li>";
                             echo "<li>";
                                echo "<a href='edit_employee.php'>Edit employee</a>";
                             echo "</li>";
                        }
               ?>
            </ul>
        </div>  <!-- /sidebar-wrapper -->     
        
        <div class ="container-fluid">
            <div class ="row">
                <div class ="col-lg-2">
                    
                </div>
                <div class ="col-lg-8"> 
             <?php
                   
             
                                     
                 include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/PDO_Query.php';
                 $q = new PDO_Query;   
                 //make request here 
                 $id =  $_SESSION['employee_id'];
                 $query = $q->getRequestsFromManagerID($id); //contains date_start, date_end, submit_date, confirmation_status, reason_employee + request_ID
                 if (empty($query))
                 {
                     //case: no data found at all
                    echo '<div class="alert">There are no leave requests to approve!</div>'; 
                 }
                else
                {
                   echo "<div class='statustable'>";
                        echo "<div class='panel panel-default'>";
                            echo "<div class='panel-heading'>Awaiting approval</div>";
                                echo "<div class='panel-body'>";
                                   if (array_key_exists("error", $_SESSION))
                                    {
                                        echo "<span class='_er'>" . $_SESSION["error"] . "  </span>";
                                        unset($_SESSION['error']);
                                    }
                                    elseif (array_key_exists("message", $_SESSION))
                                    {
                                        echo "<span class='_mess'>" . $_SESSION["message"] . "  </span>";
                                        unset($_SESSION['message']);
                                        echo"<br>"; 
                                        if (array_key_exists("warning", $_SESSION))
                                        {   
                                            echo "<span class='_warning'>" . $_SESSION["warning"] . " </span>";
                                            unset($_SESSION['warning']);
                                        }

                                    }
                
                                    echo "<table class='table'>";
                                        echo "<thead>";
                                                echo "<tr>";//fill the table with the headers
                                                        echo "<th style='min-width:100px'>";
                                                                echo "Employee name";
                                                        echo "</th>";
                                                        echo "<th style='min-width:100px'>";
                                                                echo "Dates of leave";
                                                        echo "</th>";
                                                        echo "<th>";
                                                                echo "Date submitted";
                                                        echo "</th>";
                                                        echo "<th>";
                                                                echo "Employee reason";
                                                        echo "</th>";
                                                         echo "<th>";
                                                                echo "Approve leave";
                                                        echo "</th>";
                                                         echo "<th>";
                                                                echo "Deny leave";
                                                        echo "</th>";
                                                         
                                                echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";
                                    foreach($query as $data)
                                    { //break down 2d array into array
                                        
                                        if ($data['confirmation_status'] == "PENDING")
                                        {
                                        //allocate variables including date manipulation
                                        $sd = $data["date_start"];
                                        $ed = $data["date_end"];
                                        $temp = DateTime::createFromFormat('Y-m-d', "$sd");
                                        $sd = $temp->format('F j, Y');
                                        $exploded = explode(", ", $sd);
                                        $start_year = $exploded[1];
                                        $temp = DateTime::createFromFormat('Y-m-d', "$ed");
                                        $ed = $temp->format('F j, Y');
                                        $date = $sd . " - " . $ed;
                                        
                                        
                                        
                                        $employee_name = $data['employee_name'];
                                        $employee_reason = $data['reason_employee'];
                                        $submit_date = $data['date_submitted'];
                                        $request_id = $data['request_id'];
                                        $hours_requested  = $data['hours_requested'];
                                        $employee_id = $data['employee_id'];
                                        if ($data['reason_employee'] == "")
                                        {
                                            $reason_employee = "N/A";
                                        }
                                        else
                                        {
                                            $employee_reason = $data['reason_employee'];
                                        }
                                        //fill table with values
                                        echo "<tr>";
                                            echo '<td>';
                                                echo "$employee_name";
                                            echo '</td>';
                                            echo '<td>';
                                                echo "$date";
                                            echo '</td>';
                                            echo '<td>';
                                                echo "$submit_date";
                                            echo '</td>';
                                            echo '<td>';
                                                echo "$employee_reason ";
                                            echo '</td>';
                                            echo '<td>';
                                                echo "<button class='btn btn-success' id='modal_submit' data-toggle='modal' data-target='#myModal1$request_id'>"; //approve button to launch modal
                                                 echo "Approve";
                                                echo "</button>";
                                            echo '</td>';
                                            echo '<td>';
                                                echo "<button class='btn btn-danger' id='modal_submit' data-toggle='modal' data-target='#myModal2$request_id'>"; //deny button to launch individual modal
                                                 echo "Deny";
                                                echo "</button>";
                                            echo '</td>';
                                        echo '</tr>';
                                  
                                   
                                    ?><!-- bootstrap modal with php variable echos inside form. Options to continue or go back after checking the entered values --> 
                                    <div class="modal fade" id="myModal1<?php echo $request_id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                           <form class="requestLeave" id="form1" role="form" action="approve_leave_handle.php" method="post" >
                                         <div class="modal-dialog">
                                           <div class="modal-content">
                                             <div class="modal-header">
                                               <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                               <h4 class="modal-title" id="myModalLabel">Approve</h4>
                                             </div>
                                             <div class="modal-body" id="dates_">
                                                 You have chosen to approve leave for <?php echo $employee_name; ?> on the date/dates of  <?php echo $date ?>.   
                                                 <br>
                                                If this is what you want to submit , please press confirm - otherwise, press back and re-enter your information
                                                
                                                <!-- hidden variables in order to post the values over to the approve_leave_handle.php --> 
                                                <input type ="hidden" name="approved_by" value =" <?php echo $_SESSION["employee_name"]; ?>" > <!-- as logged in, this will be who approved request+ --> 
                                                <input type ="hidden" name="reason_manager" value ="N/A" > 
                                                <input type ="hidden" name="confirmation_status" value = "APPROVED"> <!-- hard coded approved (can only be approved/denied) --> 
                                                <input type ="hidden" name="request_id" value = "<?php echo $request_id ?>"> 
                                                <input type ="hidden" name="hours_requested" value = "<?php echo $hours_requested ?>"> 
                                                <input type ="hidden" name="employee_id" value = "<?php echo $employee_id ?>"> 
                                                <input type ="hidden" name="start_year" value = "<?php echo $start_year ?>"> 
                                             </div>
                                             <div class="modal-footer">
                                                 <!--Submit and back buttons (target - Approve_leave_handle.php--> 
                                               <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                                               <button type="submit" class="btn btn-primary" id="confirmButton">Confirm</button>
                                             </div>
                                           </div>
                                         </div>
                                        </form>
                                       </div> <!--Deny modal - same as above with slight differences - a textbox to submit manager reason (optional) --> 
                                       <div class="modal fade" id="myModal2<?php echo $request_id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <form class="requestLeave" id="form1" role="form" action="approve_leave_handle.php" method="post" >
                                         <div class="modal-dialog">
                                           <div class="modal-content">
                                             <div class="modal-header">
                                               <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                               <h4 class="modal-title" id="myModalLabel">Deny</h4>
                                             </div>
                                             <div class="modal-body" id="dates_">
                                                 You have chosen to deny leave for <?php echo $employee_name; ?> on the date/dates of  <?php echo $date ?>.   
                                                 <br>
                                                 Please enter a reason for this denial in the box below - the employee will see this reason when they log into Dashboard next.
                                                 <br>
                                                 
                                                 <input type='text' name ="reason_manager" default="Please enter reason here" > <!--Reason manager on POST--> 
                                                 
                                                 <br>
                                                If this is what you want to submit , please press confirm - otherwise, press back and re-enter your information
                                                
                                                <input type ="hidden" name="approved_by" value =" <?php  echo $_SESSION["employee_name"]; ?>" >
                                                <input type ="hidden" name="confirmation_status" value = "DENIED"> 
                                                <input type ="hidden" name="request_id" value = "<?php echo $request_id ?>"> 
                                                <input type ="hidden" name="hours_requested" value = "<?php echo $hours_requested ?>"> 
                                                <input type ="hidden" name="employee_id" value = "<?php echo $employee_id ?>"> 
                                                <input type ="hidden" name="start_year" value = "<?php echo $start_year ?>"> 
                                             </div>
                                             <div class="modal-footer">
                                                 
                                               <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                                               <button type="submit" class="btn btn-primary" id="confirmButton">Confirm</button>
                                             </div>
                                           </div>
                                         </div>
                                        </form>
                                       </div>
                                        <?php 
                                        }
                                    }
                                        
                                       ?>
                                      </tbody>
                                    </table> 
                              
                             </div>
                        </div>
                 </div> 
        <?php  } ?>
              </div> 
           </div> 
       </div>  
    </body>

</html>       