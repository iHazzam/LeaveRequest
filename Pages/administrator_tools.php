<html>
    
    <head>
        <title>TRC Dashboard - Admin</title>
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
                 ._warning{
                      color:darkorange;
                }
              
           </style>
    </head>
    
    <body onload="startTime()">
         

              
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
          if (!isset($_SESSION['login']))
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
                        include_once $_SERVER['DOCUMENT_ROOT'] . 'LeaveRequest/Scripts/PDO_Query.php';
                 $q = new PDO_Query;
                 $id = $_SESSION["employee_id"]; //required later on too
                        if ($_SESSION['is_manager'] == true)
                        { //for popover
                            $to_approve = $q->HowManyRequests($id);
                            $_SESSION['to_approve'] = $to_approve;
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
        </div>       

        <div class ="container-fluid">
            <div class ="row">
                <div class ="col-lg-2">
                    
                </div>
                <div class ="col-lg-8">
                    <div class="information">
                        <div class="panel panel-default">
                            <div class="panel-heading">Administrator tools</div>
                            <div class="panel-body">
                                <?php 
                                  
                                    
                                       echo "<div class='col-md-5'>";
                                       if (array_key_exists('individual_flag', $_SESSION))
                                       {
                                            $individual_flag = $_SESSION['individual_flag'];
                                       }
                                       else
                                       {
                                           $individual_flag = 0;
                                       }
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

                                        unset($_SESSION['error']);
                                        
                                       // print_r($_SESSION);
                                       
                                        
                                        $namelist = $q->fetchEmployeeNames(); //get all employee names on database 
                                        if (!array_key_exists('employee_name_temp', $_SESSION))
                                        {
                                            //first load of page in session
                                            $_SESSION['employee_name_temp'] = "";
                                        }
                                        $default = $_SESSION['employee_name_temp'];
                                        //form to allow employee name to be selected from list
                                        echo "<form class = 'detailsform' role='form' action = 'administrator_tools_handle_employee.php' method='post'>";
                                            echo "<div class='row'>";
                                                echo "<div class='col-lg-12'>";
                                                    echo "<h5> Please select the name of an employee to see his/her current requests. To see denied requests, please navigate to the employee's profile </h5>";
                                                    echo "<div class='form-group' >"; 
                                                        echo "<label for='employee_name'>Employee Name</label>";
                                                        echo "<select class='form-control' name ='employee_name' >";
                                                        
                                                        foreach($namelist as $value)
                                                        {
                                                             $name = $value["employee_name"];
                                                            if ($name == $_SESSION['employee_name_temp']) //sets the last selected name to default status
                                                            {
                                                                echo "<option value='$name' selected='selected' >$name</option>";
                                                            }
                                                            else
                                                            {
                                                                echo "<option value='$name'  >$name</option>";
                                                            }
                                                            
                                                        }
                                                        echo "</select>";
                                                    echo "</div>";
                                                echo "</div>";
                                            echo "</div>";
                                            echo "<div class='row'>";
                                                echo "<div class='col-lg-12'>";
                                                    echo "<div>"; 
                                                        echo "<button type='submit' id= 'reset' class='btn btn-default' >Submit details </button> "; //post to set session variables
                                                        echo "<a class ='btn btn-default' href='dashboard.php'>Back</a> "; 
                                                        echo "<a class ='btn btn-default' href='administrator_tools.php'>Show latest requests</a> "; //show the screen with no employee details
                                                        echo "<br>";
                                                        echo "<a class ='btn btn-default' href='leave_overview.php'>Employee leave overview</a> "; //employee leave overview
                                                    echo "</div>";
                                                echo "</div>";
                                            echo "</div>";
                                            
                                        echo "</form>";
                                   
                                        
                                   ?>
                                
                                
                                    
                                  
                         </div>
                    </div>
                </div>
               </div>
                    <div class ="col-lg-12">
                        <div class="information">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Next requests</div>
                                <div class="panel-body">
                                 <div class='row'>
                                    <div class='col-lg-12'>
                                        
                                        
                                      <?php
                                        if ($individual_flag == 0)//decide which table to show - employee specific or all
                                        { //show table one (for all employees)
                                            
                                        
                            
                                        $query = $q -> getRequests();
                                       
                                        if (empty($query))
                                        {
                                            //case: no data found at all
                                           echo '<div class="alert">There are no requests made to the system</div>'; 
                                        }
                                       else
                                       { //display all the next requests for all employees in date order 
                                          echo "<div class='statustable'>";
                                               echo "<div class='panel panel-default'>";
                                                   echo "<div class='panel-heading'>Upcoming requests</div>";
                                                       echo "<div class='panel-body'>";
                                                           echo "<table class='table'>";
                                                               echo "<thead>";
                                                                       echo "<tr>";
                                                                               echo "<th style='min-width:100px'>";
                                                                                       echo "Employee name";
                                                                               echo "</th>";
                                                                               echo "<th style='min-width:100px'>";
                                                                                       echo "Dates of leave";
                                                                               echo "</th>";
                                                                               echo "<th style='min-width:100px'>";
                                                                                       echo "Confirmation status";
                                                                               echo "</th>";
                                                                               echo "<th style='min-width:100px'>";
                                                                                       echo "Approved by";
                                                                               echo "</th>";
                                                                                  echo "<th style='min-width:100px'>";
                                                                                       echo "Type of leave";
                                                                               echo "</th>";
                                                                               echo "<th style='min-width:100px'>";
                                                                                       echo "Employee reason";
                                                                               echo "</th>";
                                                                               echo "<th style='min-width:100px'>";
                                                                                       echo "Manager reason";
                                                                               echo "</th>";
                                                                               echo "<th style='min-width:100px'>";
                                                                                       echo "Edit request";
                                                                               echo "</th>";
                                                                       echo "</tr>";
                                                               echo "</thead>";
                                                               echo "<tbody>";

                                                           foreach($query as $data)
                                                           {
                                                               $confirmation_status = strtoupper($data["confirmation_status"]);
                                                               if ($confirmation_status !== "DENIED") 
                                                               {

                                                                $sd = $data["date_start"];
                                                                $ed = $data["date_end"];
                                                                $request_id = $data['request_id'];
                                                                $employee_name = $data['employee_name'];
                                                                $temp = DateTime::createFromFormat('Y-m-d', "$sd");
                                                                $sd = $temp->format('F j, Y');
                                                                $exploded = explode(", ", $sd);
                                                                $year = $exploded[1];
                                                                $hours_requested = $data['hours_requested'];
                                                                $temp = DateTime::createFromFormat('Y-m-d', "$ed");
                                                                $ed = $temp->format('F j, Y');
                                                                $type = $data['type'];



                                                                $approved_by = $data['approved_by'];

                                                                $date = $sd . " - " . $ed;
                                                                $startdate = $data['date_submitted'];
            //                                                    var_dump($data["confirmation_status"]);
                                                                if ($data['reason_manager'] == "")
                                                                {
                                                                    $managerReason = "N/A";
                                                                }
                                                                else
                                                                {
                                                                    $managerReason = $data['reason_manager'];
                                                                }

                                                                if ($data['reason_employee'] == "")
                                                                {
                                                                    $employeeReason = "N/A";
                                                                }
                                                                else
                                                                {
                                                                    $employeeReason = $data['reason_employee'];
                                                                }


                                                                switch($confirmation_status)
                                                                    {
                                                                        case "APPROVED":
                                                                            $status = "success";
                                                                            break;
                                                                        case "PENDING":
                                                                            $status = "warning";
                                                                            break;
                                                                        case "DENIED":
                                                                            $status = "danger";
                                                                            break;
                                                                    }

                                                                echo "<tr class=$status>";
                                                                    echo '<td>';
                                                                        echo "$employee_name";
                                                                    echo '</td>';
                                                                    echo '<td>';
                                                                        echo "$date";
                                                                    echo '</td>';
                                                                    echo '<td>';
                                                                        echo "$confirmation_status";
                                                                    echo '</td>';
                                                                    echo '<td>';
                                                                        echo "$approved_by";
                                                                    echo '</td>';
                                                                    echo '<td>';
                                                                        echo "$type";
                                                                    echo '</td>';
                                                                    echo '<td>';
                                                                        echo "$employeeReason";
                                                                    echo '</td>';
                                                                    echo '<td>';
                                                                        echo "$managerReason";
                                                                    echo '</td>';
                                                                    echo '<td>';
                                                                        echo "<button class='btn btn-danger btn-sm' id='modal_submit' data-toggle='modal' data-target='#myModalx$request_id'>"; //approve button here
                                                                            echo "Edit";
                                                                        echo "</button>";
                                                                    echo '</td>';
                                                                echo '</tr>';
                                                                ?>
                                                                <div class="modal fade" id="myModalx<?php echo $request_id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalxLabel" aria-hidden="true">
                                                                 <form class="requestLeave" id="form1" role="form" action="administrator_tools_handle.php" method="post" >
                                                                  <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                      <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                        <h4 class="modal-title" id="myModalxLabel">Edit Request</h4>
                                                                      </div>
                                                                      <div class="modal-body" id="dates_">
                                                                         You are editing leave requested by <?php echo $employee_name; ?> on the dates <?php echo $date;?>.   
                                                                          <br>
                                                                         Please select from below options.

                                                                         <input type ="hidden" name="year" value ="<?php echo $year; ?>" >
                                                                         <input type ="hidden" name="request_id" value ="<?php echo $request_id; ?>" >
                                                                         <input type ="hidden" name="hours_requested" value ="<?php echo $hours_requested; ?>" >
                                                                         <input type ="hidden" name="confirmation_status" value ="<?php echo $confirmation_status; ?>" >

                                                                      </div>
                                                                      <div class="modal-footer">

                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>

                                                                        <?php if ($confirmation_status == "PENDING" ) { ?>
                                                                        <button type="submit" class="btn btn-primary" name="approve">Approve</button>
                                                                        <button type="submit" class="btn btn-primary" name="deny">Deny</button>
                                                                        <?php } ?>
                                                                        <button type="submit" class="btn btn-primary" name="delete">Delete</button>
                                                                      </div>
                                                                    </div>
                                                                  </div>
                                                                 </form>
                                                                </div>


                                                         <?php     
                                                               }
                                                              }            
                                                           }
                                                           ?>
                                                           </tbody>
                                                       </table>
                                                         <?php       
                                                                      
                                                            }
                                                            else // case: individual has been picked - display all requests (from the beginning of time!) for the specific 
                                                            {
                                                                $employee_name = $_SESSION['employee_name_temp'];
                                                                
                                                                $query = $q -> getHistoricalRequests($employee_name); 
                                                                unset ($_SESSION['individual_flag']);

                                                                        if (empty($query))
                                                                        {
                                                                            //case: no data found at all
                                                                           echo '<div class="alert">There are no requests made to the system</div>'; 
                                                                        }
                                                                       else
                                                                       {
                                                                          echo "<div class='statustable'>";
                                                                               echo "<div class='panel panel-default'>";
                                                                                   echo "<div class='panel-heading'>Requests made by $employee_name </div>";
                                                                                       echo "<div class='panel-body'>";
                                                                                           echo "<table class='table'>";
                                                                                               echo "<thead>";
                                                                                                       echo "<tr>";
                                                                                                               echo "<th style='min-width:100px'>";
                                                                                                                       echo "Request date";
                                                                                                               echo "</th>";
                                                                                                               echo "<th style='min-width:100px'>";
                                                                                                                       echo "Dates of leave";
                                                                                                               echo "</th>";
                                                                                                               echo "<th style='min-width:100px'>";
                                                                                                                       echo "Confirmation status";
                                                                                                               echo "</th>";
                                                                                                               echo "<th style='min-width:100px'>";
                                                                                                                       echo "Approved by";
                                                                                                               echo "</th>";
                                                                                                                echo "<th style='min-width:100px'>";
                                                                                                                       echo "Type of leave";
                                                                                                               echo "</th>";
                                                                                                               echo "<th style='min-width:100px'>";
                                                                                                                       echo "Employee reason";
                                                                                                               echo "</th>";
                                                                                                               echo "<th style='min-width:100px'>";
                                                                                                                       echo "Manager reason";
                                                                                                               echo "</th>";
                                                                                                               echo "<th style='min-width:100px'>";
                                                                                                                       echo "Edit request";
                                                                                                               echo "</th>";
                                                                                                       echo "</tr>";
                                                                                               echo "</thead>";
                                                                                               echo "<tbody>";

                                                                                           foreach($query as $data) 
                                                                                           {
                                                                                               
                                                                                               $sd = $data["date_start"];
                                                                                               $ed = $data["date_end"];
                                                                                               $request_id = $data['request_id'];
                                                                                               $temp = DateTime::createFromFormat('Y-m-d', "$sd");
                                                                                               $sd = $temp->format('F j, Y');
                                                                                               $exploded = explode(", ", $sd);
                                                                                               $year = $exploded[1];
                                                                                               $hours_requested = $data['hours_requested'];
                                                                                               $temp = DateTime::createFromFormat('Y-m-d', "$ed");
                                                                                               $ed = $temp->format('F j, Y');
                                                                                               $type = $data['type'];
                                                                                               $approved_by = $data['approved_by'];

                                                                                               $date = $sd . " - " . $ed;
                                                                                               $startdate = $data['date_submitted'];
                                           //                                                    var_dump($data["confirmation_status"]);
                                                                                               if ($data['reason_manager'] == "")
                                                                                               {
                                                                                                   $managerReason = "N/A";
                                                                                               }
                                                                                               else
                                                                                               {
                                                                                                   $managerReason = $data['reason_manager'];
                                                                                               }

                                                                                               if ($data['reason_employee'] == "")
                                                                                               {
                                                                                                   $employeeReason = "N/A";   
                                                                                               }
                                                                                               else
                                                                                               {
                                                                                                   $employeeReason = $data['reason_employee'];
                                                                                               }

                                                                                               $confirmation_status = strtoupper($data["confirmation_status"]);
                                                                                               switch($confirmation_status)
                                                                                                   {
                                                                                                       case "APPROVED":
                                                                                                           $status = "success";
                                                                                                           break;
                                                                                                       case "PENDING":
                                                                                                           $status = "warning";
                                                                                                           break;
                                                                                                       case "DENIED":
                                                                                                           $status = "danger";
                                                                                                           break;
                                                                                                   }

                                                                                               echo "<tr class=$status>";
                                                                                                   echo '<td>';
                                                                                                       echo "$employee_name";
                                                                                                   echo '</td>';
                                                                                                   echo '<td>';
                                                                                                       echo "$date";
                                                                                                   echo '</td>';
                                                                                                   echo '<td>';
                                                                                                       echo "$confirmation_status";
                                                                                                   echo '</td>';
                                                                                                   echo '<td>';
                                                                                                       echo "$approved_by";
                                                                                                   echo '</td>';
                                                                                                   echo '<td>';
                                                                                                      echo "$type";
                                                                                                   echo '</td>';
                                                                                                   echo '<td>';
                                                                                                       echo "$employeeReason";
                                                                                                   echo '</td>';
                                                                                                   echo '<td>';
                                                                                                       echo "$managerReason";
                                                                                                   echo '</td>';
                                                                                                   echo '<td>';
                                                                                                       echo "<button class='btn btn-danger btn-sm' id='modal_submit' data-toggle='modal' data-target='#myModalx$request_id'>"; //approve button here
                                                                                                           echo "Edit";
                                                                                                       echo "</button>";
                                                                                                   echo '</td>';
                                                                                               echo '</tr>';
                                                                                               ?>
                                                                                               <div class="modal fade" id="myModalx<?php echo $request_id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalxLabel" aria-hidden="true">
                                                                                                <form class="requestLeave" id="form1" role="form" action="administrator_tools_handle.php" method="post" >
                                                                                                 <div class="modal-dialog">
                                                                                                   <div class="modal-content">
                                                                                                     <div class="modal-header">
                                                                                                       <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                                                       <h4 class="modal-title" id="myModalxLabel">Approve</h4>
                                                                                                     </div>
                                                                                                     <div class="modal-body" id="dates_">
                                                                                                        You are about to edit leave requested by <?php echo $employee_name; ?> on the dates <?php echo $date;?>.   
                                                                                                         <br>
                                                                                                        Please select from the options available:

                                                                                                        <input type ="hidden" name="year" value ="<?php echo $year; ?>" >
                                                                                                        <input type ="hidden" name="request_id" value ="<?php echo $request_id; ?>" >
                                                                                                        <input type ="hidden" name="hours_requested" value ="<?php echo $hours_requested; ?>" >
                                                                                                        <input type ="hidden" name="confirmation_status" value ="<?php echo $confirmation_status; ?>" >

                                                                                                     </div>
                                                                                                     <div class="modal-footer">

                                                                                                       <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>

                                                                                                        <?php if ($confirmation_status == "PENDING" ) { ?>
                                                                                                        <button type="submit" class="btn btn-primary" name="approve">Approve</button>
                                                                                                        <button type="submit" class="btn btn-primary" name="deny">Deny</button>
                                                                                                        <?php } ?>
                                                                                                        <button type="submit" class="btn btn-primary" name="delete">Delete</button>
                                                                                                     </div>
                                                                                                   </div>
                                                                                                 </div>
                                                                                                </form>
                                                                                               </div>


                                                                                        <?php       
                                                                                              }            
                                                                                           }
                                                                                         }
                                                                                            
                                                                                    
                                                                                           ?>
                                                                                           </tbody>
                                                                                       </table>
                                                                                       
                                    </div>
                                 </div>
                             </div>
                        </div>
                    </div>
                   </div>

              </div>
            </div>
        
        
        
    </body>

</html>
