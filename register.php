<html>
    <head>
        <title>Registration</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<style>
			body{ font: 15px sans-serif; }
			.wrapper{ width: 400px; padding: 15px; margin:auto;}
		</style>
    </head>
    <body>
        <?php
        require_once("db/dbconnect.php");
        require_once("classes/Users.php");
		
		$nameErrorMsg = "";
		$surnameErrorMsg = "";
		$emailErrorMsg = "";
		$phoneErrorMsg = "";
		$pwErrorMsg = "";
		$confirmPwErrorMsg = "";
		$userTypeErrorMsg = "";
		
        function format($input) 
		{
            $input = trim($input); 
            $input = stripslashes($input);  
            $input = htmlspecialchars($input);
            $input = strtolower($input);  
            return $input;
        }

        $registerArr = array(
            'name' => '',
            'surname' => '',
            'phone' => '',
            'email' => '',
            'password' => '',
            'confirm_password' => '',
            'type' => '',
        );
        
		$valid = array();
        
        $validateName = "/^(?![ .]+$)[a-zA-Z ,]*$/";
		$validatePhone = "/^[89]{1}[0-9]{7}$/";
		$validateEmail = '/^[a-zA-Z0-9]+(.[_a-z0-9-]+)(?!.*[~@\%\/\\\&\?\,\'\;\:\!\-]{2}).*@[a-z0-9-]+(.[a-z0-9-]+)(.[a-z]{2,3})$/';
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
		{
            foreach ($_POST as $key => $value) 
			{
                if (isset($registerArr[$key])) 
				{
                    $registerArr[$key] = htmlspecialchars($value);
                }
            }
            if (empty($registerArr['name'])) 
			{
				$nameErrorMsg = "Please enter your name!";
                $valid[] = FALSE;
            } 
			else if (!preg_match($validateName, format($registerArr['name']))) 
			{
				$nameErrorMsg = "Invalid name!";
                $valid[] = FALSE;
            } 
			else {
				$registerArr['name'] = format($registerArr['name']);
                $valid[] = TRUE;
            }

            if (empty($registerArr['surname'])) 
			{
				$surnameErrorMsg = "Please enter your surname!";
                $valid[] = FALSE;
            } 
			else if (!preg_match($validateName, format($registerArr['surname']))) 
			{
				$surnameErrorMsg = "Invalid surname!";
                $valid[] = FALSE;
            } 
			else 
			{
				$registerArr['surname'] = format($registerArr['surname']);
                $valid[] = TRUE;
            }

            if (empty($registerArr['phone'])) 
			{
				$phoneErrorMsg = "Please enter your contact number!";
                $valid[] = FALSE;
            } 
			else if (!preg_match($validatePhone, format($registerArr['phone']))) 
			{
				$phoneErrorMsg = "Invalid contact number!";
                $valid[] = FALSE;
            } 
			else {
                $registerArr['phone'] = format($registerArr['phone']);
                $valid[] = TRUE;
            }

            if (empty($registerArr['email']))
				{
				$emailErrorMsg = "Please enter your email!";
                $valid[] = FALSE;
            } 
			else if (!preg_match($validateEmail, format($registerArr['email']))) {
				$emailErrorMsg = "Invalid email!";
                $valid[] = FALSE;
            } 
			else {
                $valid[] = TRUE;
            }

            if (empty($registerArr['password'])) 
			{
				$pwErrorMsg = "Please enter a password!";
                $valid[] = FALSE;
            } 
			else {
                $valid[] = TRUE;
            }
			
            if (empty($registerArr['confirm_password'])) 
			{
				$confirm_err = "Please confirm password!";
                $valid[] = FALSE;
            } 
			else if ($registerArr['confirm_password'] !== $registerArr['password']) 
			{
				$confirm_err = "Password does not match!";
                $valid[] = FALSE;
            } 
			else {
                $valid[] = TRUE;
            }

            if (empty($registerArr['type'])) 
			{
				$userTypeErrorMsg = "Please select a user type!";
                $valid[] = FALSE;
            } 
			else {
                $valid[] = TRUE;
            }

            if (!in_array(FALSE, $valid)) 
			{
                $checkUser = new Users();
                if (!$checkUser->getMail($registerArr['email'])) 
				{
                    $userObj = new Users($registerArr['name'], $registerArr['surname'], $registerArr['phone'], $registerArr['email'], $registerArr['type'], $registerArr['password']);
                    $success = $userObj->addUser($userObj);

                    if ($success) {
                        echo '<script>alert("Account registered successfully")</script>';
						echo '<script>window.location.href = "login.php";</script>';
                    } 
					else {
                        echo '<script>alert("Account registration failed")</script>';
                    }
                    
                    $valid = array();
                    $registerArr = array(
                        'name' => '',
                        'surname' => '',
                        'phone' => '',
                        'email' => '',
                        'password' => '',
                        'confirm_password' => '',
                        'type' => '',
                    );
                } 
				else {
                    $emailErrorMsg = "Email already exists";
                }
            } 
        }
        ?>
		
		<div class="wrapper">
        <h2>Registration</h2>
        <p>Please fill this form to register for an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
			<div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control <?php echo (!empty($nameErrorMsg)) ? 'is-invalid' : ''; ?>" value="<?php echo $registerArr['name']; ?>">
                <span class="invalid-feedback"><?php echo $nameErrorMsg; ?></span>
            </div>
			<div class="form-group">
                <label>Surname</label>
                <input type="text" name="surname" class="form-control <?php echo (!empty($surnameErrorMsg)) ? 'is-invalid' : ''; ?>" value="<?php echo $registerArr['surname']; ?>">
                <span class="invalid-feedback"><?php echo $surnameErrorMsg; ?></span>
            </div>
			<div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="phone" class="form-control <?php echo (!empty($phoneErrorMsg)) ? 'is-invalid' : ''; ?>" value="<?php echo $registerArr['phone']; ?>">
                <span class="invalid-feedback"><?php echo $phoneErrorMsg; ?></span>
            </div>
			<div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control <?php echo (!empty($emailErrorMsg)) ? 'is-invalid' : ''; ?>" value="<?php echo $registerArr['email']; ?>">
                <span class="invalid-feedback"><?php echo $emailErrorMsg; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($pwErrorMsg)) ? 'is-invalid' : ''; ?>" value="<?php echo $registerArr['password']; ?>">
                <span class="invalid-feedback"><?php echo $pwErrorMsg; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirmPwErrorMsg)) ? 'is-invalid' : ''; ?>" value="<?php echo $registerArr['confirm_password'];; ?>">
                <span class="invalid-feedback"><?php echo $confirmPwErrorMsg; ?></span>
            </div>
			
			<div class="form-group">
				<label>User Type</label>
				<select id="type" name="type" class="form-control">
					<option id="hideSelectPH" value="" hidden selected>Select User Type</option>
					<option value="Author" <?php
					if ($registerArr['type'] == "Author") 
					{
						echo ' selected="selected"';
					}
					?>>Author</option>
					<option value="Reviewer" <?php
					if ($registerArr['type'] == "Reviewer") 
					{
						echo ' selected="selected"';
					}
					?>>Reviewer</option>
					<option value="ConferenceChair" <?php
					if ($registerArr['type'] == "ConferenceChair") 
					{
						echo ' selected="selected"';
					}
					?>>Conference Chair</option>
				</select>
			</div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <a href="index.php">Go back to main menu</a>
        </form>
    </div>    
    </body>
</html>