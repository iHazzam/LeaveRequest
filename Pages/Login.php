<!--allows user to log in to system with username and password-->
<html> 
<head>
        <title>Login</title> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
           <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
           <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
           <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
           
           <style> 
               body {
                   padding-top: 250px;
                   padding-left: 540px;
                   background-color: #c0c0c0;
               }
               .blah {
                   margin-left: auto ;
                   margin-right: auto ;
                   width: 80%;
                   text-align: center;
               }
               
                ._er { 
                  color: #ff0000;
                }
                ._cnf{ 
                      color: #00FF00;
                }
               
               
           </style>
        
</head>
<body>
     
<form class="blah" role="form" action="login_handle.php" method="post">
    <div class="row">
        
        <div class="col-md-4">
       <?php 
       session_start(); //this allws accessing variables that have been stored in "error" and "message" throughout the website 
                        //in this case failed login messages and successful password reset messages
       if (array_key_exists("error", $_SESSION))
       {
           echo "<span class='_er'>" . $_SESSION["error"] . "  </span>";
            
       }
       else if (array_key_exists("message", $_SESSION))
            {
                echo "<span class='_cnf'>" . $_SESSION["message"] . "  </span>";
            }
       session_unset(); //get rid of messages so they only show once - at this point, these messages are the only thing in session
       ?>
            <h3> Please log in. </h3>
            <div class="form-group" > 
              <label for="username">Username</label>
              <input class="form-control" name ="username" id="username" placeholder="Enter username" maxlength="25">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="password" type="password">Password</label>
            <input class="form-control" type="password" name="password" id="password" placeholder="Enter password" maxlength="16">
          </div>
        </div>
    </div>
    <div class="row">
     <div class="col-md-4">
      <div>
        <button type="submit" id= "login" class="btn btn-default">Login</button> 
        <a class ="btn btn-default" href="reset_password.php">Reset password</a>
      </div>
     </div>
    </div>
</form>
    

  

   
</body>


