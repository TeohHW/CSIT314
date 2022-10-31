<?php
session_start();
?>
<html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<style>
			body{ font: 20px sans-serif; }
			.wrapper{ width: 500px; padding: 20px; margin: auto;}
			
.container {
  display: block;
  position: relative;
  cursor: pointer;
  font-size: 15px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default radio button */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom radio button */
.checkmark {
  position: absolute;
  top: 0;
  left: -20;
  height: 25px;
  width: 25px;
  background-color: #eee;
  border-radius: 50%;
}

/* On mouse-over, add a grey background color */
.container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.container .checkmark:after {
 	top: 9px;
	left: 9px;
	width: 8px;
	height: 8px;
	border-radius: 50%;
	background: white;
}
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
		if(!isset($_GET["paperNo"])) 
		{
			$paperNo = $_SESSION['paperNo'];
		}
		else
		{
			$paperNo = $_GET["paperNo"];
			$_SESSION['paperNo'] = $paperNo;
		}
		 if (isset($_SESSION["user"])) 
		{
            $user = unserialize($_SESSION["user"]);
			$user_email = $user->get_email();
            $user_name = ucwords($user->get_name()) . " " . ucwords($user->get_surname());
            if ($user->get_type() == "Reviewer") 
			{
                $paperObj = new Papers();
                $paperArray = $paperObj->findPaper($paperNo);
				  foreach ($paperArray as $paper) 
				{	
				    $paperNameHere  = $paper->get_paperName();
					$paperContentHere = $paper->get_paperContent();
            } 
				}
				
			else
			{
                echo '<script>window.location.href = "index.php";</script>';
            }
        } 
			function array_has_dupes($array) {
   return count($array) !== count(array_unique($array));
}
		 if ($_SERVER["REQUEST_METHOD"] == "POST") 
		{	
			$reviewScore = $_POST["reviewScore"]." - ".$user_name;
		foreach ($paperArray as $paper) 
			{
				$paperScores = $paper->get_paperRatingsReviewer();
			}
			
			if (empty($paperScores) || is_null($paperScores))
			{
				$paperObj->reviewPaper($paperNo,$reviewScore);
			}
			else
			{
				$paperScores .=", ".$reviewScore;
				$paperObj->reviewPaper($paperNo,$paperScores);
			}
			
			//
			
			//echo '<script>alert("Review submitted.")</script>';
			//echo '<script>window.location.href = "viewPapersToReview.php";</script>';
			//$_SESSION["paperNo"]="";
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
		<a href="viewPapers.php">Go back</a>
		<div class="wrapper">
		
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <?php            
			
            if ($paperArray != NULL) 
			{
                echo "<form method ='post' action=''";
                echo htmlspecialchars($_SERVER['PHP_SELF']);
                echo "'>";
				echo "<th><b>Paper Name: </b></th>" ; 
                echo "<table border='1' class='table table-bordered'>";					
                echo "<tr>";
                echo "<th>Paper Content</th>";
                foreach ($paperArray as $paper) 
				{	
					echo "<tr>";
					?>
					<input disabled type="text" id="paperName" name="paperName" class="form-control <?php echo (!empty($nameErrorMsg)) ? 'is-invalid' : ''; ?>" value="<?php echo $paperNameHere;?>">
					<?php 
					echo "<tr>";
					?>
				    <td ><textarea disabled id="content" name='content' rows='20' cols='50'> <?php echo $paperContentHere ?></textarea></td> 
					<?php 
					echo "<tr>";
                }
				
                echo "</table>";
				?>
				<h2>Select review score:</h2>
				 <label class="container">-3 (Strong Reject)
				  <input type="radio" checked="checked" name="reviewScore" value="Strong Reject">
				  <span class="checkmark"></span>
				</label>
				<label class="container">-2 (Reject)
				  <input type="radio" name="reviewScore" value="Reject">
				  <span class="checkmark"></span>
				</label>
				<label class="container">-1 (Weak Reject)
				  <input type="radio" name="reviewScore" value="Weak Reject">
				  <span class="checkmark"></span>
				</label>
				<label class="container">0 (Borderline)
				  <input type="radio" name="reviewScore" value="Borderline">
				  <span class="checkmark"></span>
				</label>
				<label class="container">1 (Weak Accept)
				  <input type="radio" name="reviewScore" value="Weak Accept">
				  <span class="checkmark"></span>
				</label>
				<label class="container">2 (Accept)
				  <input type="radio" name="reviewScore" value="Accept">
				  <span class="checkmark"></span>
				</label>
				<label class="container">3 (Strong Accept)
				  <input type="radio" name="reviewScore" value="Strong Accept">
				  <span class="checkmark"></span>
				</label>
				<br>
				<input type="submit" value="Submit Review">
				<?php
                echo "</form>";
            } 
			else {
                echo "<p>No Paper(s) found!</p>";
            }
            ?>
			
        </div>
    </body>
</html>