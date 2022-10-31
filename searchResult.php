<html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<style>
			body{ font: 20px sans-serif; }
			.wrapper{ width: 850px; padding: 20px; margin: auto;}
</style>
    <head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <head>
        <title>Search Results</title>
    </head>
    <body>
    <?php
        session_start();
        require("db/dbconnect.php");
        require("classes/Papers.php");
		require("classes/Users.php");
        if (isset($_SESSION["user"])) 
		{
            $user = unserialize($_SESSION['user']);
			$user_email = $user->get_email();
            $user_name = ucwords($user->get_name()) . " " . ucwords($user->get_surname());
            $paperArr = [];
            $paperObj = new Papers();
            $paperArr = $paperObj->listSearchedPapers($_SESSION['searchArr']);
        } 
		else 
		{
            echo '<script>window.location.href = "index.php";</script>';
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
		{
          
        }
    ?>
		<?php
		if($user->get_type()=="Author"){
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
		<?php 
		}
		else if ($user->get_type()=="Reviewer")
		{
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
				  <li><a href="bidPaper.php">Bid for paper</a></li>
				  <li><a href="reviewPaper.php">Review Paper(s)</a></li>
				  <li><a href="workload.php">View/Edit Workload</a></li>
				</ul>
			  </li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Log out</a></li>
			</ul>
		  </div>
		</nav>	
		 <?php
		}
		else if ($user->get_type()=="ConferenceChair")
		{
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
				  <li><a href="allocatePaper.php">Allocate Paper(s)</a></li>
				  <li><a href="viewPending.php">View Pending Papers</a></li>
				  <li><a href="viewAllPapers.php">View All Papers</a></li>
				</ul>
				</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Log out</a></li>
			</ul>
		  </div>
		</nav>	
		 <?php
		}
		else if ($user->get_type()=="System Admin")
		{
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
				  <li><a href="viewAllPapers.php">View All Papers</a></li>
				</ul>
				<li><a href="register.php">Register Account</a></li>
			  </li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Log out</a></li>
			</ul>
		  </div>
		</nav>	
		 <?php
		}
		?>
		
		<div class="wrapper">
            <?php
            echo "<p id='Title' style='font-size:35px;'>Search Results</p>";
            echo "<div>";
            foreach ($_SESSION['searchArr'] as $key => $value) 
			{
                $key = strtoupper($key);
                echo "<b>{$key}</b> : {$value}&nbsp;&nbsp;&nbsp;";
            }
            if ($paperArr != NULL) 
			{
                echo "</div>";
                echo "<form method ='post' action='";
                echo htmlspecialchars($_SERVER['PHP_SELF']);
                echo "'>";
                echo "<table class='table table-bordered' border='1'>";
                echo "<tr>";
                echo "<th>Paper No</th>
				      <th>Paper Name</th>
					  <th>Author</th>
					  <th>Co-Authors</th>";

                foreach ($paperArr as $paper) 
				{
					if ($user->get_type()=="Author")
					{
						if($paper->get_author() == $user_name) //Only display papers by author
							{
								echo "<tr>";
								echo "<td >{$paper->get_paperNo()}</td>";
								echo "<td>{$paper->get_paperName()}</td>";
								echo "<td>{$paper->get_author()}</td>";
								echo "<td>{$paper->get_otherAuthors()}</td>";
								echo "<tr>";
							}
					    
					}
					else if ($user->get_type()=="Reviewer")
					{
							if($paper->get_paperReviewer() == $user_email) //Only display papers by author
							{
								echo "<tr>";
								echo "<td >{$paper->get_paperNo()}</td>";
								echo "<td>{$paper->get_paperName()}</td>";
								echo "<td>{$paper->get_author()}</td>";
								echo "<td>{$paper->get_otherAuthors()}</td>";
								echo "<td>{$paper->get_paperStatus()}</td>";
								echo "<tr>";
							}
					} 
					else if ($user->get_type()=="System Admin")
					{
								echo "<tr>";
								echo "<td >{$paper->get_paperNo()}</td>";
								echo "<td>{$paper->get_paperName()}</td>";
								echo "<td>{$paper->get_author()}</td>";
								echo "<td>{$paper->get_otherAuthors()}</td>";
								echo "<tr>";
					}
				}
                echo "</table>";
                echo "</form>";
            } 
			else 
			{
                echo "<p>No paper(s) found!</p>";
            }
            ?>
        </div>
    </body>
</html>