<?php
session_start();
?>
<html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<style>
			body{ font: 20px sans-serif; }
			.wrapper{ width: 500px; padding: 15px; margin: auto;}
</style>
    <head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <head>
        <title>Edit Paper</title>
    </head>
    <body>
        <?php
        require_once("db/dbconnect.php");
        require_once("classes/Users.php");
        require_once("classes/Papers.php");

		 if (isset($_SESSION["user"])) 
		{
            $user = unserialize($_SESSION["user"]);
			$user_email = $user->get_email();
            $user_name = ucwords($user->get_name()) . " " . ucwords($user->get_surname());
            if ($user->get_type() == "Author") 
			{
                $paperObj = new Papers();
                $paperArray = $paperObj->listPendingPaperByAuthor($user_name) ;
            } 
			else
			{
                echo '<script>window.location.href = "index.php";</script>';
            }
        } 
		if ($_SERVER["REQUEST_METHOD"] == "POST") 
		{
            if (isset($_POST['toggleStatus'])) 
			{
                $paperNo = $toggleStatus = "";
                $toggles = explode(",", $_POST['toggleStatus']);
                $paperNo = $toggles[0];
                $toggleStatus = $toggles[1];
                $author_paper = new Papers();
                $success = $author_paper->chgStatus($paperNo, $toggleStatus);
                
                if ($success) 
				{
					echo "<script>alert('Paper Submitted Successfully !');</script>";
                    echo '<script>window.location.href = "index.php";</script>';
                } 
				else {
                    echo "<script>alert('Not Success');</script>";
                }
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
            <?php
            echo "<p id='Title' style='font-size:35px;'> {$user_name}'s Papers</p>";
            
            if ($paperArray != NULL) 
			{
                echo "<form method ='post' action=''";
                echo htmlspecialchars($_SERVER['PHP_SELF']);
                echo "'>";
                echo "<table border='1' class='table table-bordered'>";

                echo "<tr>";
                echo "
				      <th>Paper Name</th>
					  <th>Action(s)</th>";
                foreach ($paperArray as $paper) 
				{	
					echo "<tr>";
					echo "<td>{$paper->get_paperName()}</td>";    
					echo "<td><a href='viewPaper.php?paperNo={$paper->get_paperNo()};'>View Paper Content</a></td>"; 
					echo "<td><button class='btn btn-danger' type='submit' name='toggleStatus' value='{$paper->get_paperNo()},To Be Reviewed'>Submit</button></td>";
                }

                echo "</table>";
                echo "</form>";
            } 
			else {
                echo "<p>No Paper(s) found!</p>";
            }
            ?>
        </div>
    </body>
</html>