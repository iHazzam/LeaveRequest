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
                            <div class="panel panel-primary">
                                <div class="panel-heading">Company leave overview</div>
                                <div class="panel-body">
                                 <div class='row'>
                                    <div class='col-lg-12'>
                                        
                                        
                                      <?php
                                        
                                        $query = $q -> getEmployeeLeave();
                                        
                                        
                                        
                                        if (empty($query))
                                        {
                                            //case: no data found at all
                                           echo '<div class="alert">There are no requests made to the system</div>'; 
                                        }
                                       else
                                       { //display all the next requests for all employees in date order 
                                           $year = date("Y");
                                          echo "<div class='statustable'>";
                                               echo "<div class='panel panel-default'>";
                                                   echo "<div class='panel-heading'>Company leave status - $year</div>";
                                                       echo "<div class='panel-body'>";
                                                           echo "<table class='table'>";
                                                               echo "<thead>";
                                                                       echo "<tr>";
                                                                               echo "<th style='min-width:100px'>";
                                                                                       echo "Employee name";
                                                                               echo "</th>";
                                                                               echo "<th style='min-width:100px'>";
                                                                                       echo "Hours pending approval";
                                                                               echo "</th>";
                                                                               echo "<th style='min-width:100px'>";
                                                                                       echo "Hours of leave remaining";
                                                                               echo "</th>";
                                                                               echo "<th style='min-width:100px'>";
                                                                                       echo "Days of leave remaining";
                                                                               echo "</th>";
                                                                       echo "</tr>";
                                                               echo "</thead>";
                                                               echo "<tbody>";

                                                           foreach($query as $data)
                                                           {
                                                               $employee_name = $data['employee_name'];
                                                               $hours_remaining = $data['total_hours'] - $data['hours_holiday'];
                                                               $hours_pending = $data['hours_pending'];
                                                               $hours_day = $data['hours_day'];
                                                               
                                                               $days_remaining = $hours_remaining / $hours_day;
                                                               
                                                                echo "<tr>";
                                                                    echo '<td>';
                                                                        echo "$employee_name";
                                                                    echo '</td>';
                                                                     echo '<td>';
                                                                        echo "$hours_pending";
                                                                    echo '</td>';
                                                                    echo '<td>';
                                                                        echo "$hours_remaining";
                                                                    echo '</td>';
                                                                    echo '<td>';
                                                                        echo "$days_remaining";
                                                                    echo '</td>';
                                                                echo '</tr>';
                                                           }
                                                         ?>
                                                         <a class ="btn btn-info" href="leave_overview_month.php" >Generate monthly leave overview</a>
                                                           </tbody>
                                                       </table>
                                  <?php } ?> 
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
