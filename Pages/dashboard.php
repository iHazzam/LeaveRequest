<html>
    <head>
        <title>Form</title>
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
                #redword{
                    color:#ff0000;
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
            <li><a href="contactUs.php">Contact administrator</a></li>
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
                 $id = $_SESSION["employee_id"]; //used next php as well
                        if ($_SESSION['is_manager'] == true)
                        {
                            $to_approve = $q->HowManyRequests($id);
                            $_SESSION['to_approve'] = $to_approve;
//                            
                              echo "<li><a href='approveLeave.php' id='approve'>Approve Leave <span class='badge badge-important'>$to_approve &nbsp &nbsp &nbsp</span></a></li>";
                        }
                        if ($_SESSION['is_admin'] == true)
                        {
                             echo "<li>";
                                echo "<a href='administrator_tools.php'>View administrator tools</a>";
                             echo "</li>";
//                             echo "<li>";
//                                echo "<a href='#'>View historical requests</a>";
//                             echo "</li>";
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
                <div class ="col-lg-4">
                    <div class="information">
                        <div class="panel panel-default">
                            <div class="panel-heading">Leave information</div>
                            <div class="panel-body">
                                 
                                <?php 
                                        
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
                                    $name = $_SESSION['employee_name'];
                                    $temp = explode(" ",$name);
                                    $name = $temp[0];
                                   
                                    $holidays = $q->getHolidayInfo($id);
                                    $date = date('Y');
                                    $date_next = $date + 1;
                                    
                                   
                                    foreach ($holidays as $h)
                                    {
                                         $hours_day = $holidays[0]['hours_day'];
                                         
                                        if ($h['year'] === $date)
                                        {
                                            //maths to work out holidays
                                            $days_remaining = round(($h['total_hours']-$h['hours_holiday'])/$hours_day, 2);
                                            $days_pending = round($h['hours_pending']/$hours_day, 2);
                                            $hours_pending = $h['hours_pending'];
                                            $hours_remaining = round($days_remaining*$hours_day,1);
                                            $hours_available = $hours_remaining - $hours_pending;
                                            $days_available = round($hours_available / $hours_day, 2);
                                            $hours_taken = $h['hours_holiday']; 
                                            $days_taken =   round($hours_taken/$hours_day,2);
                                        }
                                        elseif ($h['year'] == $date_next)
                                        {
                                     // for next year
                                     
                                            $next_days_pending = round($h['hours_pending']/$hours_day, 2);
                                            $next_hours_pending = $h['hours_pending'];
                                            $next_days_remaining = round(($h['total_hours']-$h['hours_holiday'])/$hours_day, 2);
                                            $next_hours_remaining = round($next_days_remaining*$hours_day,1);
                                            $next_hours_available = $next_hours_remaining - $next_hours_pending;
                                            $next_days_available = round($next_hours_available / $hours_day, 2);
                                            $next_hours_taken = $h['hours_holiday']; 
                                            $next_days_taken =   round($next_hours_taken/$hours_day,2);
                                            
                                        }
                                    }
                                   
                                    if (!isset($next_hours_available))
                                    {
                                        $next_hours_available = null;
                                    }
                                    if (!isset($next_hours_taken))
                                    {
                                        $next_hours_taken = null;
                                    }
                                
                                 
                                 
                                echo "<div class ='col-lg-12'>";
                                    echo "<div class='panel panel-info'>";
                                            echo "<div class='panel-heading'>$date</div>";
                                            echo "<div class='panel-body'>";
                                                    echo "Hours remaining in $date: <span id='redword'> $hours_remaining</span> ($days_remaining days)";
                                                    echo "<br>";
                                                    echo "Hours pending in $date: <span id='redword'>$hours_pending </span>($days_pending days)";
                                                    echo "<br>";
                                                    echo "Hours available in $date: <span id='redword'> $hours_available </span>($days_available days)";
                                                    echo "<br>";
                                                    echo "Hours taken in $date: <span id='redword'> $hours_taken </span>($days_taken days)";
                                                    echo "<br>";
                                            echo "</div>";
                                    echo "</div>";
                                echo "</div>";
                             
                             
                                
                                if (!(($next_hours_available == 0)&&($next_hours_taken == 0)))
                                {
                                echo "<div class ='col-lg-12'>";
                                        echo "<div class='panel panel-info'>";
                                            echo "<div class='panel-heading'>$date_next</div>";
                                            echo "<div class='panel-body'>";
                                                  echo "Hours remaining in $date_next:<span id='redword'> $next_hours_remaining </span>($next_days_remaining days)";
                                                    echo "<br>";
                                                    echo "Hours pending in $date_next: <span id='redword'>$next_hours_pending </span>($next_days_pending days)";
                                                    echo "<br>";
                                                    echo "Hours available in $date_next:<span id='redword'> $next_hours_available</span> ($next_days_available days)";
                                                    echo "<br>";
                                                    echo "Hours taken in $date_next: <span id='redword'> $next_hours_taken </span>($next_days_taken days)";
                                                    echo "<br>";
                                 ?>
                                            </div>
                                        </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class ="col-lg-6">
                    
                        <?php
                             
                            
                             $query = $q -> getRequestsFromID($id);
//                             print_r($query);
                             if (empty($query))
                             {
                                 //case: no data found at all
                                echo '<div class="alert">Please make requests to the system to see them here</div>'; 
                             }
                            else
                            {
                               echo "<div class='statustable'>";
                                    echo "<div class='panel panel-default'>";
                                        echo "<div class='panel-heading'>Current requests</div>";
                                            echo "<div class='panel-body'>";
                                                echo "<table class='table'>";
                                                    echo "<thead>";
                                                            echo "<tr>";
                                                                    echo "<th style='min-width:100px'>";
                                                                            echo "Date submitted";
                                                                    echo "</th>";
                                                                    echo "<th style='min-width:170px'>";
                                                                            echo "Dates of leave";
                                                                    echo "</th>";
                                                                    echo "<th>";
                                                                            echo "Hours booked";
                                                                    echo "</th>";
                                                                    echo "<th>";
                                                                            echo "Confirmation status";
                                                                    echo "</th>";
                                                                    echo "<th>";
                                                                            echo "Manager reason";
                                                                    echo "</th>";
                                                                     echo "<th>";
                                                                            echo "Cancel leave";
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
                                                    $sd = $temp->format('D F j, Y');
                                                    $exploded = explode(", ", $sd);
                                                    $year = $exploded[1];
                                                    $hours_requested = $data['hours_requested'];
                                                    $temp = DateTime::createFromFormat('Y-m-d', "$ed");
                                                    $ed = $temp->format('D F j, Y');
                                                    $hours_leave = $data['hours_requested'];
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
                                                            echo "$startdate";
                                                        echo '</td>';
                                                        echo '<td>';
                                                            echo "$date";
                                                        echo '</td>';
                                                         echo '<td>';
                                                            echo "$hours_leave";
                                                        echo '</td>';
                                                        echo '<td>';
                                                            echo "$confirmation_status";
                                                        echo '</td>';
                                                        echo '<td>';
                                                            echo "$managerReason";
                                                        echo '</td>';
                                                        echo '<td>';
                                                            echo "<button class='btn btn-danger btn-sm' id='modal_submit' data-toggle='modal' data-target='#myModal$request_id'>"; //approve button here
                                                                echo "Cancel";
                                                            echo "</button>";
                                                        echo '</td>';
                                                    echo '</tr>';
                                                    ?>
                                                    <div class="modal fade" id="myModal<?php echo $request_id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                     <form class="requestLeave" id="form1" role="form" action="cancel_leave.php" method="post" >
                                                      <div class="modal-dialog">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Approve</h4>
                                                          </div>
                                                          <div class="modal-body" id="dates_">
                                                             You are about to cancel leave requested on the dates <?php echo $date ?>.   
                                                              <br>
                                                             If this is what you want to do , please press confirm - otherwise, press back and re-enter your information
                                                         
                                                             <input type ="hidden" name="year" value ="<?php echo $year; ?>" >
                                                             <input type ="hidden" name="request_id" value ="<?php echo $request_id; ?>" >
                                                             <input type ="hidden" name="hours_requested" value ="<?php echo $hours_requested; ?>" >
                                                             <input type ="hidden" name="confirmation_status" value ="<?php echo $confirmation_status; ?>" >
                                                             
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
            </div>
        
        
        
    </body>

</html>
       
                                
                               
                            
                         