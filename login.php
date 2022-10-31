<html>
    <head>
        <title>Login</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<style>
			body{ font: 15px sans-serif; }
			.wrapper{ width: 360px; padding: 15px; margin: auto;}
		</style>
    </head>
    <body>
    <?php
		session_start();
        require_once("db/dbconnect.php");
        require_once("classes/Users.php");
		
		$email_err = "";
		$password_err = "";
		$loginErr = "";
             
        function format($input) 
		{
            $input = trim($input);  			
            $input = stripslashes($input);  	
            $input = htmlspecialchars($input);  
            $input = strtolower($input);   
            return $input;
        }

        $loginArr = array(
            'email' => '',
            'password' => '',
        );
		
        $valid = FALSE;
		
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
		{
            foreach ($_POST as $key => $value) {
                if (isset($loginArr[$key])) {
                    $loginArr[$key] = htmlspecialchars($value);
                }
            }
			
            $emailValidate = '/^[a-zA-Z0-9]+(.[_a-z0-9-]+)(?!.*[~@\%\/\\\&\?\,\'\;\:\!\-]{2}).*@[a-z0-9-]+(.[a-z0-9-]+)(.[a-z]{2,3})$/';
            
            if (!empty($loginArr['email']) || !empty($loginArr['password'])) 
			{     
                if (preg_match($emailValidate, format($loginArr["email"]))) 
				{  
                    $loginArr["email"] = format($loginArr["email"]);
                    $valid = TRUE;
                } 
				else 
				{
                    $email_err = "Invalid Email!";
                    $valid = FALSE;
                }
            } 
			else 
			{
                $email_err = "Please enter your email!";
				$password_err = "Please enter your password!";
                $valid = FALSE;
            }

            if ($valid) 
			{
                $userObj = new Users();
                $user = $userObj->authenticate($loginArr["email"], $loginArr["password"]);
                if ($user instanceof users) 
				{
                    echo "Successfully found";
                    $_SESSION['user'] = serialize($user);
                    header("Location:index.php");
                } 
				else 
				{
                    $loginErr = "Login Failed";
                }
                $loginArr = array(
                    'email' => '',
                    'password' => '',
                );
            }
        }
    ?>
	
	<div class="wrapper">
        <h2>Login</h2>
        <p>Please enter email/password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $loginArr['email']; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $loginArr['password'];?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
			
			<?php
			if (!empty($loginErr))
			{
				 echo '<script>alert("' . $loginErr . '"); window.location.href = "index.php";</script>';
			}
			?>
        </form>
    </div>
    </body>
</html>