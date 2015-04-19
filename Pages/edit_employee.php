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
                 $id = $_SESSION["employee_id"]; //used next php as well
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
                                echo "<a href='#'>Edit employee</a>";
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
                            <div class="panel-heading">Edit existing employee details</div>
                            <div class="panel-body">
                                <?php 
                                   
                                    
                                  //  print_r($_SESSION);
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
                                        
                                        
                                        if ((array_key_exists("disabled", $_SESSION ))) //if disabled not set
                                        {
                                            $disabled = '';
                                            $disabled2 = 'disabled="true"';
                                        }
                                        else
                                        {
                                            $disabled = 'disabled="true"';
                                            $disabled2 = '';
                                        }
                                        /* populate form with existing values */
                                        //have seperate form here. on submit, come back here do this again properly 
                                        /*
                                        <form class="newemp" role="form" action="edit_employee_handle.php" method="post">
                                         * 
                                        */
//                                        <
                                        $namelist = $q->fetchEmployeeNames();
                                        
                                        echo "<form class = 'detailsform' role='form' action = 'getDetails.php' method='post'>";
                                            echo "<div class='row'>";
                                                echo "<div class='col-lg-12'>";
                                                    echo "<h5> Edited values will be updated and saved. </h5>";
                                                    echo "<div class='form-group' >"; 
                                                        echo "<label for='employee_name'>Employee Name</label>";
                                                        //echo "<input class='form-control' name ='employee_name' required='true' maxlength='25' $disabled2 >";
                                                        echo "<select class='form-control' name ='employee_name' $disabled2 >";
                                                       print_r($_SESSION['details']);
                                                        foreach($namelist as $value)
                                                        {
                                                            $name = $value["employee_name"];
                                                            if ($name == $_SESSION['employee_name_query'])
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
                                                        echo "<button type='submit' id= 'reset' class='btn btn-default' $disabled2>Submit details </button> ";
                                                    echo "</div>";
                                                echo "</div>";
                                            echo "</div>";
                                            
                                        echo "</form>";
                                        
                                        if (array_key_exists('details', $_SESSION))
                                        {
                                            
                                            
                                            $username = ($_SESSION['details']['username']);
                                            $email_address = ($_SESSION['details']['email_address']);
                                            $hours_day = ($_SESSION['details']['hours_day']); 
                                            $manager_name = ($_SESSION['details']['manager_name']);
                                            $employee_id = ($_SESSION['details']['employee_id']);
                                            $is_manager = ($_SESSION['details']['is_manager']);
                                            $is_admin = ($_SESSION['details']['is_admin']);
                                            $employee_name = ($_SESSION["employee_name_query"]);
                                            $hours_this = ($_SESSION['details']["hours_hol_this"]);
                                            $hours_next = ($_SESSION['details']["hours_hol_next"]);
                                            $_SESSION['new_leave_id'] = $employee_id;
                                           
                                            
                                    
                                            if ($is_admin == true)
                                            {
                                                $admA = "active";
                                                $manA = "";
                                                $empA = "";
                                            }
                                            elseif ($is_manager == true)
                                            {
                                                $manA = "active";
                                                $admA = "";
                                                $empA = "";
                                            }
                                            else
                                            {
                                                $empA = "active";
                                                $manA = "";
                                                $admA = "";
                                            }
                                        }
                                        else
                                        {
                                            $empA = "";
                                            $manA = "";
                                            $admA = "";
                                            $username = "";
                                            $email_address = "";
                                            $hours_day = ""; 
                                            $manager_name = "";
                                            $employee_name = "";
                                            $is_manager = "";
                                            $is_admin = "";
                                            $employee_id = "";
                                            $hours_this = "";
                                            $hours_next = "";
                                        }
                                       
                                   ?>
                                <br>
                                
                                <form class="editemp" role="form" action="edit_employee_handle.php" method="post"> 
                                <div class="row">
                                    <div class="col-lg-12">
                                      <div class="form-group">
                                        <label for="username">Employee username - This will be used to log in. May not contain spaces or special characters, and must be unique</label>
                                        <input class="form-control" name ="username" value="<?php echo $username ;?>" required="true" maxlength="25" <?php echo $disabled ;?>>
                                      </div>
                                    </div>
                                </div>
                               <div class="row">
                                    <div class="col-lg-12">
                                      <div class="form-group">
                                        <label for="email_address">Employee email address - this will be used to send the employee their password automatically. </label>
                                        <input class="form-control" type="email" name ="email_address" value="<?php echo $email_address ;?>" required="true" maxlength="40" <?php echo $disabled ;?>>
                                      </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                      <div class="form-group">
                                        <label for="email_address">Please enter the numerical number of hours the employee is contracted to work a day.</label>
                                        <input class="form-control" name ="hours_day" value="<?php echo $hours_day ;?>" type = "number" required="true" maxlength="40" <?php echo $disabled ;?>>
                                      </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                      <div class="btn-group" data-toggle-name="is_private" data-toggle="buttons" >
                                        <label for="btn-group">Please select the role of the employee in the company</label>
                                        <label class="btn btn-default <?php echo $empA;?>" <?php echo $disabled ;?>>
                                          <input type="radio" name="employee_type" value="employee"  <?php
                                          if ($empA == "active")
                                          {
                                              echo "checked";
                                          }
                                                                                                         ?>> Employee
                                        </label>
                                        <label class="btn btn-default <?php echo $manA;?>" <?php echo $disabled ;?>>
                                          <input type="radio" name="employee_type" value="manager"  <?php
                                          if ($manA == "active")
                                          {
                                              echo "checked";
                                          }
                                                                                                         ?>> Manager
                                        </label>
                                        <label class="btn btn-default <?php echo $admA;?>" <?php echo $disabled ;?>>
                                          <input type="radio" name="employee_type" value="administrator" <?php
                                          if ($admA == "active")
                                          {
                                              echo "checked";
                                          }
                                                                                                         ?>> Administrator
                                        </label>
                                      </div>
                                     
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                      <div class="form-group">
                                        <label for="role">Please select the name of this employee's line manager.</label>
                                        <?php
                                        
                                        
                                          $managers = $q->fetchManagerNames();
                                          
                                        
                                            echo "<select class='form-control' name ='manager_name' $disabled >";
                                          
                                            foreach($managers as $value)
                                            {
                                                //var_dump($value);
                                                $name = $value["employee_name"]; //query returns "employee name" table - In this case it is manager name.
                                                if ($name == $manager_name)
                                                {
                                                    echo "<option value='$name' selected='selected' >$name</option>";
                                                }
                                                else
                                                {
                                                    echo "<option value='$name'  >$name</option>";
                                                }
                                                 
                                                
                                            }
                                            echo "</select>";
                                        
                                        
                                        
                                        ?>
                                        
                                        
<!--                                       <input class="form-control" name ="manager_name" value="<?php// echo $manager_name;?>" required="true" maxlength="40" <?php// echo $disabled ;?> >-->
                                        <input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>">
                                        <input type="hidden" name="employee_name" value="<?php echo $employee_name; ?>">
                                      </div>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-lg-12">
                                      <div class="form-group">
                                        <label for="hours_this">Please enter the amount of holiday to be allocated in <?php   $year = date("Y"); echo $year ;?></label>
                                        <input class="form-control" name ="hours_this" value="<?php echo $hours_this ;?>" type = "number" required="true" maxlength="40" <?php echo $disabled ;?>>
                                         <input type="hidden" name="hours_this_existing" value="<?php echo $hours_this; ?>">
                                      </div>
                                    </div>
                                </div>
                                <div class="row">
                                 <div class="col-lg-12">
                                  <div>
                                    <button type="submit" id= "reset" class="btn btn-success " <?php echo $disabled ;?>>Submit details </button> 
                                    <a class ="btn btn-warning" href="dashboard.php">Back</a>
                                   
                                   </div>
                                  </div>
                                 </div>
                            </form>   
                                <a class ="btn btn-info" href="setupLeave.php"  <?php echo $disabled ;?>>Allocate leave for  <?php   $year_next = date("Y") + 1; echo $year_next ;?></a>
                             <button class="btn btn-danger" id="modal_submit" data-toggle="modal" data-target="#myModal" <?php echo $disabled ;?>> Delete this employee record </button>
                                    <div>
                                       <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <form class="requestLeave" id="form" role="form" action="delete_record.php" method="post" >
                                         <div class="modal-dialog">
                                           <div class="modal-content">
                                             <div class="modal-header">
                                              Are you sure?
                                             </div>
                                             <div class="modal-body" id="dates_">
                                                 You are about to delete all employee records for 
                                                   <?php $employee_name;
                                                         echo ".";
                                                         if ($is_manager == true)
                                                         {
                                                             echo " As this employee is a manager, all records for his employees will need a new manager assigning. You will recieve an email with the name of any employees that need updating. <br>";
                                                         }
                                                         if ($is_admin == true)
                                                         {
                                                             echo " All administrator privileges of this user will also be revoked. <br> ";
                                                         }  
                                                         echo "<input type ='hidden' name='employee_id' id='empid' value='$employee_id'>";
                                                         echo "<input type ='hidden' name='is_manager' id='del' value='$is_manager'>";
                                                         ?>
                                                        
                                                         <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                                                         <button type="submit" class="btn btn-danger" id="submitButton">Delete</button>
                                                         
                                                   
                                             </div>
                                             <div class="modal-footer">
                                               
                                               
                                             </div>
                                           </div>
                                         </div>
                                        </form>
                                       </div>
                                    </div>
                                    
                                 <?php unset($_SESSION['details']);
                                       unset($_SESSION['disabled']);
                                       unset($_SESSION['employee_name_query']);
                                 ?>
                         </div>
                    </div>
                </div>
               </div>
              </div>
            </div>
        
        
        
    </body>

</html>

