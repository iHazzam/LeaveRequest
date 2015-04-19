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
                 $id = $_SESSION["new_leave_id"]; //used next php as well
                        if ($_SESSION['is_manager'] == true)
                        {
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
                            <div class="panel-heading">Set up leave</div>
                            <div class="panel-body">
                                <?php 
                                  
                                    
                                       echo "<div class='col-md-5'>";
      
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
                                        $create_leave = false;
                                        $year = date("Y");
                                        $year_next = $year + 1 ; 
                                        $query = $q->getNewHolInfo($id, $year_next);
                                        
                                        if ($query == false) //if the query doesn't run
                                        {
                                             $create_leave = true; //no leave for the next year on the system - make it!
                                             $query = $q->getNewHolInfo($id, $year); //fetch required info to do this
                                             $employee_name = $query['employee_name'];
                                             $hours_day = $query['hours_day'];
                                             $leave_next = $query['total_hours'];
                                             $employee_id = $query['employee_id'];
                                        }
                                        else
                                        {
                                            //set variables for queries
                                            $employee_name = $query['employee_name'];
                                            $hours_day = $query['hours_day'];
                                            $leave_next = $query['total_hours'];
                                            $employee_id = $query['employee_id'];
                                        }
                                        
                                   ?>
                                <form class="newemp" role="form" action="setup_leave_handle.php" method="post">
                                    <div class="row">
                                        <div class="col-lg-12">
                                        <h5> Please enter how many hours of leave <?php echo $employee_name; ?> will receive in <?php echo $year_next; ?>. For your information, the system shows this employee works <?php echo $hours_day; ?> hours per day. </h5>
                                        <div class="form-group" >
                                          <label for="employee_name">Leave next year</label>
                                          <input class="form-control" name ="total_hours" value="<?php echo $leave_next ;?>" required="true" maxlength="25">
                                          <input type ="hidden" name="employee_id" value="<?php echo $employee_id ;?>"> <!-- hidden inputs allow variables to be posted across easily -->
                                          <input type ="hidden" name="create_leave" value="<?php echo $create_leave ;?>">
                                        </div>
                                    </div>
                                </div>
                                
                          
                                <div class="row">
                                 <div class="col-lg-12">
                                  <div>
                                      <button type="submit" id= "reset" class="btn btn-default">Submit</button> 
                                    <a class ="btn btn-default" href="dashboard.php">Back</a>
                                  </div>
                                 </div>
                                </div>
                                 
                            </form>   
                                
                                    
                                  
                         </div>
                    </div>
                </div>
               </div>
              </div>
            </div>
        
        
        
    </body>

</html>


