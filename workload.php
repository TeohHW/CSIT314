<html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<style>
			body{ font: 20px sans-serif; }
			.wrapper{ width: 1250px; padding: 15px; margin: auto;}
</style>
    <head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <head>
        <title>All Papers</title>
    </head>
    <body>
        <?php
        session_start();
        require("db/dbconnect.php");
        require("classes/Papers.php");
        require("classes/Users.php");
		
        if (isset($_SESSION["user"])) {
            $user = unserialize($_SESSION['user']);
            if ($user->get_type() == "Reviewer") 
			{
				$user = unserialize($_SESSION['user']);
				$user_email = $user->get_email();
				$user_name = ucwords($user->get_name()) . " " . ucwords($user->get_surname());
                $userArr = [];
                $userObj = new Users();
                $userArr = $userObj->getInfoByMail($user_email);
            }
            else 
			{
                echo '<script>window.location.href = "index.php";</script>';
            }
        } 
		else 
		{
            echo '<script>window.location.href = "index.php";</script>';
        }
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") 
		{
					if (isset($_POST['update'])) 
				 {
			   {
					$newWorkload = htmlspecialchars($_POST['maxWorkload']);
					$userObj->updateWorkload($user_email,$newWorkload);
					echo '<script>alert("Max Workload Updated!")</script>';
					echo '<script>window.location.href = "workload.php";</script>';
					$_SESSION['coAuthors']=array();
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
				  <li><a href="viewPapersToBid.php">Bid for paper</a></li>
				  <li><a href="viewPapersToReview.php">Review Paper(s)</a></li>
				  <li><a href="workload.php">View/Edit Workload</a></li>
				</ul>
			  </li>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Log out</a></li>
			</ul>
		  </div>
		</nav>		
		<div class="wrapper">
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <?php
			
            echo "<p id='Title' style='font-size:30px;'>Manage Workload</p>";

                 if ($userArr != NULL) 
			{
                echo "<form method ='post' action=''";
                echo htmlspecialchars($_SERVER['PHP_SELF']);
                echo "'>";
                echo "<table border='1' class='table table-bordered'>";
                echo "<tr>";
                echo "
					 <th>Current Max Workload</th>
					 <th>Current Workload</th>";

                foreach ($userArr as $info) 
				{
                    echo "<tr>";
					echo "<td>{$info->get_maxReviewed()}</td>";
                    echo "<td>{$info->get_currentlyReviewed()}</td>";
					echo "</table>";
					if ($user->get_type() == "Reviewer") 
					{
					   echo "</br>";
					   echo "<h2>Want to increase your workload? </h2>";
					   echo "<h4>Enter new max workload here: </h4>";
					   echo "<input type='number' id='maxWorkload' name='maxWorkload' class='form-control' min='0' max='100'</input>";
					   echo "</br>";
                       echo "<button class='btn btn-success' type='submit' name='update' value='{$info->get_uid()}'>Update Workload</button>"; 
                    }

                }
				
                
                echo "</form>";
            } 
			else {
                echo "<p>No info(s) found!</p>";
            }
            ?>
			</form>
        </div>
    </body>
</html>