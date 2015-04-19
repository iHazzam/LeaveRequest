<html>
    <head>
        <title>Form</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
           <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
           <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
           <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
           <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/tooltip.js"></script>
           <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/modal.js"></script>
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
                 $id = $_SESSION["employee_id"]; 
                        if ($_SESSION['is_manager'] == true)
                        {
                            $to_approve = $q->HowManyRequests($id); //find out how many requests there are 
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
                    <div class="form">
                        <div class="panel panel-default">
                            <div class="panel-heading">Request leave</div>
                            <div class="panel-body">
                                
                                <div class="row">

                                    <div class="col-md-5">
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
                                        
                                      
                                        ?>
                                        <br>
                                        <h5> Please fill out this form to request leave. Clicking the "down" arrow brings up a calendar view. </h5>
                                        <div class="form-group" >
                                          <label for="startdate">First day of leave</label>
                                          <input class="form-control" type = "date" name="start_date" id="date1" placeholder="" maxlength="16">
                                          </div>
                                        </div>
                                    </div>
                                <div class="row">
                                    <div class="col-md-5">
                                      <div class="form-group">
                                        <label for="enddate">Last day of leave</label>
                                          <input class="form-control" type = "date" name="end_date" id="date2" placeholder="" maxlength="16">
                                      </div>
                                    </div>
                                </div>
                              <div class="form-group">
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="unpaid"> If you want to request unpaid leave, please check this box.
                                        </label>
                                    </div>
                                </div>
                              </div>
                                
                                 <div class="row">
                                    <div class="col-md-5">
                                      <div class="form-group">
                                        <label for="hours">Please calculate the number of hours leave you require, taking into account bank holidays and weekends</label>
                                        <input class="form-control" type = "number" name="hours" id="hours" placeholder="" maxlength="3">
                                      </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                      <div class="form-group">
                                        <label for="text" type="text">Reason for leave request (optional) </label>
                                        <textarea class="form-control" rows="3" id="text_a" >
                                        </textarea>
                                    </div>
                                    </div>
                                </div>
                                <div class="row">
                                 <div class="col-md-5">
                                  <div>
                                       <button class="btn btn-primary btn-lg" id="modal_submit" data-toggle="modal" data-target="#myModal">
                                         Request Leave
                                       </button>

                                       <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                           <form class="requestLeave" id="form1" role="form" action="request_leave_handle.php" method="post" >
                                         <div class="modal-dialog">
                                           <div class="modal-content">
                                             <div class="modal-header">
                                               <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                               <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                                             </div>
                                             <div class="modal-body" id="dates_">
                                                 You have requested leave between 
                                                 <input  type = "text" name="start_date" disabled="" id="date1_modal" placeholder="" maxlength="16" min="
                                                 <?php  
                                                        $date = date('c');
                                                        $year = date("Y");
                                                        echo $date;
                                                  ?>
                                                  ">
                                                 and
                                                    <input  type = "text" name="end_date" disabled="" id="date2_modal" placeholder="" maxlength="16"  min="
                                                 <?php  
                                                        $date = date('c');
                                                        echo $date;
                                                  ?>
                                                  "> 
                                                If this is what you want to submit , please press confirm - otherwise, press back and re-enter your information
                                                
                                                <input type ="hidden" name="dateone" id="h_date1">
                                                <input type ="hidden" name="datetwo" id="h_date2"> 
                                                <input type ="hidden" name="textarea" id="h_texta"> 
                                                <input type ="hidden" name="hours" id="h_hours">
                                                <input type ="hidden" name="unpaid" id="h_unpaid">
                                             </div>
                                             <div class="modal-footer">
                                                 
                                               <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                                               <button type="submit" class="btn btn-primary" id="confirmButton">Confirm</button>
                                             </div>
                                           </div>
                                         </div>
                                        </form>
                                       </div>
<!--                                      <button type="submit" id= "confirm" class="btn btn-default">Request leave</button> -->
                                  </div>
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
           <script src="javascript/script.js"></script>