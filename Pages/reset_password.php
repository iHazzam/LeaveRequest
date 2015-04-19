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
                   padding-top: 150px;
                   background-color: #c0c0c0;
               }
               .resetf {
                   margin-left: auto ;
                   margin-right: auto ;
                   width: 85%;
                   text-align: center;
               }
               ._er{
                      color: #ff0000;
                }
            
           </style>
        
</head>

<body>
    
<form class="resetf" role="form" action="reset_handle.php" method="post">
    <div class="row">
        
        <div class="col-md-5">
       <?php 
            session_start();
            if (array_key_exists("error", $_SESSION))
            {
                echo "<span class='_er'>" . $_SESSION["error"] . "  </span>"; //display any error message stored in the session

            }
            
            session_unset(); //unset the whole session - safe as login not yet used
       ?>
            <h5> If you have forgotten your password, please enter your username and your email address. <br> A new password will be emailed to your inbox. This can then be changed on the dashboard. </h5>
            <div class="form-group" >
              <label for="username">Username</label>
              <input class="form-control" name ="username" id="username" placeholder="Enter username" maxlength="25">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
          <div class="form-group">
            <label for="password" type="password">Email Address</label>
            <input class="form-control" type="email" name="email" id="email" placeholder="Enter your registered email address" maxlength="40">
          </div>
        </div>
    </div>
    <div class="row">
     <div class="col-md-5">
      <div>
          <button type="submit" id= "reset" class="btn btn-default">Reset password</button> 
        <a class ="btn btn-default" href="Login.php">Back</a>
      </div>
     </div>
    </div>
</form>    
    
    
    
    
</body>
</html>