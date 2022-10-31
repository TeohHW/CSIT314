<html>
    <html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<style>
			body{ font: 14px sans-serif; }
			.wrapper{ width: 400px; padding: 15px; margin: auto;}
</style>
    <head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body> 
	
        <?php
        session_start();
        require_once("db/dbconnect.php");
        require_once("classes/Users.php");
        require_once("classes/Papers.php");
		
		$nameErrorMsg = "";
		$contentErrorMsg = "";
		$coAuthorWarning = "";
		if (isset($_SESSION["user"])) 
		{
            $user = unserialize($_SESSION['user']);
			$user_email = $user->get_email();
            $user_name = ucwords($user->get_name()) . " " . ucwords($user->get_surname());
        } 
        $insertArr = array(
			"author" => "{$user_name}",
			"authorEmail" => "{$user_email}",
			"otherAuthors" =>"",
            "paperName" => "",
            "paperContent" => "",
            "paperStatus" => "Pending",
            "paperRatingsReviewer" => "",
            "paperRatingsAuthor" => "",
			"paperComments" => ""
        );
		
        if ($_SERVER['REQUEST_METHOD'] == "GET") 
		{
            if (isset($_SESSION["user"])) 
			{
                $userType = unserialize($_SESSION["user"])->get_type();
                if ($userType != "Author") 
				{
                    if ($userType == "Reviewer") 
					{
                        echo '<script>window.location.href="index.php";</script>';
                    }
                }
            } 
			else {
                echo '<window.location.href="login.php";</script>';
            }
        }
		function array_has_dupes($array) {
   return count($array) !== count(array_unique($array));
}
        $valid = array();
     if ($_SERVER['REQUEST_METHOD'] == "POST") 
		{	
		//Adding Co-Authors
			 if (isset($_POST['btnAddAuthor'])) 
			 {
				   $var = $_POST['coAuthors'];
				   array_push($_SESSION['coAuthors'],$var);
				   if(count(array_unique($_SESSION['coAuthors']))<count($_SESSION['coAuthors']))
				   {
							array_pop($_SESSION['coAuthors']);
							$coAuthorWarning="Cannot add same author more than once!";
				   }
				   else 
				   {
							$coAuthorWarning="";
				   }
				   $insertArr['paperName'] = $_POST['paperName'];
				   $insertArr['paperContent'] = $_POST['paperContent'];
			} 
			else if (isset($_POST['btnRemoveAuthor'])) 
			 {
				   $var = $_POST['coAuthors'];
				   array_pop($_SESSION['coAuthors']);
			} 
			// Submitting 
			else {
   
            foreach ($_POST as $key => $value) 
			{
                if (isset($insertArr[$key])) 
				{
                    $insertArr[$key] = htmlspecialchars($value);
                }
            }
            
            if (empty($insertArr['paperName'])) 
			{
				$nameErrorMsg = "Please enter name!";
                $valid[] = FALSE;
            } 
			else {
                $valid[] = TRUE;
            }
            if (empty($insertArr['paperContent'])) 
			{
				$contentErrorMsg = "Please enter content!";
                $valid[] = FALSE;
            } 
			else 
			{
                $valid[] = TRUE;
            }
            if ((!in_array(FALSE, $valid)) && (count($valid) != 0)) 
			{
				$coAuthors = implode(" ",$_SESSION['coAuthors']);
				$insertArr['otherAuthors'] = $coAuthors;
                $paperObj = new Papers ($insertArr['author'], $insertArr['authorEmail'],$insertArr['otherAuthors'],$insertArr['paperName'], $insertArr['paperContent'], $insertArr['paperStatus'], $insertArr['paperRatingsReviewer'],  $insertArr['paperRatingsAuthor'], $insertArr['paperComments']);
				
				$success = $paperObj->addPaper($paperObj);
                if ($success) 
				{
                    echo '<script>alert("Paper Added")</script>';
					echo '<script>window.location.href = "viewPapers.php";</script>';
                } 
				else 
				{
					
                    echo '<script>alert("Paper NOT Added")</script>';
                }
                $valid = array();
                $insertArr = array(
                    "author" => "{$user_name}",
					"authorEmail" => "{$user_email}",
					"otherAuthors" =>"",
					"paperName" => "",
					"paperContent" => "",
					"paperStatus" => "Pending",
					"paperRatingsReviewer" => "",
					"paperRatingsAuthor" => "",
					"paperComments" => ""
                );
            } 
				$_SESSION['coAuthors']=array();
			}
        }
		
        ?>

		<nav class="navbar navbar-inverse">
		  <div class="container-fluid">
			<div class="navbar-header">
			  <p class="navbar-text">Research Conference Management System</p>
			</div>
			<ul class="nav navbar-nav">
			  <li class="active"><a href="index.php">Search</a></li>
			  <li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">Manage
				<span class="caret"></span></a>
				<ul class="dropdown-menu">
				  <li><a href="newPaper.php">Add Paper</a></li>
				   <li><a href="viewPapers.php">View Papers</a></li>
				</ul>
			  </li>
				 <li><a href="submitPaper.php">Submit Paper</a></li>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Log out</a></li>
			</ul>
		  </div>
		</nav>			
			
		<div class="wrapper">
		<h2>New Paper</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
			<div class="form-group">
                <label>Paper Name</label>
                <input type="text" name="paperName" class="form-control <?php echo (!empty($nameErrorMsg)) ? 'is-invalid' : ''; ?>" value="<?php echo $insertArr['paperName']; ?>">
                <span class="invalid-feedback"><?php echo $nameErrorMsg; ?></span>
            </div>
			<div class="form-group">
                <label>Paper Content</label>
				<textarea type="text" name="paperContent" class="form-control" rows="20" <?php echo (!empty($contentErrorMsg)) ? 'is-invalid' : ''; ?>" value="<?php echo $insertArr['paperContent'];?>"> <?php echo $insertArr['paperContent']; ?> </textarea> 
				<span class="invalid-feedback"><?php echo $contentErrorMsg; ?></span>
            </div>
			<div>
			<p>Add Co-Author</p>
			<?php
				//Dropdown list of authors
				$conn = new mysqli('localhost', 'root', '', 'research_management_system') 
				or die ('Cannot connect to db');

				$result = $conn->query("select uid, name, surname,email from users Where type = 'Author' AND email!= '$user_email' ");
				
				echo "<select id='coAuthors' name='coAuthors'>";	

				while ($row = $result->fetch_assoc()) 
				{
							  unset($id, $name);
							  $id = $row['id']; 
							  echo '<option value="'.htmlspecialchars($row['name'])." ".htmlspecialchars($row['surname']).'">'.htmlspecialchars($row['name'])." ".htmlspecialchars($row['surname']).'</option>';

				}
				echo "</select>";
				//End of Dropdown List

			?> 	
			</div>
			<label for="coAuthors" style="color:red";><?php echo $coAuthorWarning; ?></label><br>
			<br>
			<textarea type="text" name="listOfCoAuthors" class="form-control" rows="5" value="listOfCoAuthors">  <?php echo implode(",",$_SESSION['coAuthors']); ?> </textarea>
            <div class="form-group">
				<input type="submit" name="btnAddAuthor" class="btn btn-primary" value="Add Co-Author">
				<input type="submit" name="btnRemoveAuthor" class="btn btn-primary" value="Remove Last Co-Author">
				<input type="submit" name="btnSubmit" class="btn btn-primary" value="Add Paper">
            </div>
        </form>
		</div>    
    </body>
</html>