<?php
session_start();
?>
<html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<style>
			body{ font: 20px sans-serif; }
			.wrapper{ width: 500px; padding: 15px; margin: auto;}
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
		
		function IsNullOrEmptyString($str)
		{
			return ($str === null || trim($str) === '');
		}
		 if (isset($_SESSION["user"])) 
		{
            $user = unserialize($_SESSION["user"]);
            if ($user->get_type() == "Author") 
			{
                $paperObj = new Papers();
                $paperArray = $paperObj->findPaper($paperNo);
            } 
			else
			{
                echo '<script>window.location.href = "index.php";</script>';
            }
        } 
		$placeholderArray = array(
            'paperContent' => '',
            'paperNo' => '',
        );
		 if ($_SERVER["REQUEST_METHOD"] == "POST") 
		{
			if (isset($_POST['submit']))
			{				
				$reviewScore = $_POST["reviewScore"];
				$paperObj->reviewPaperAuthor($paperNo,$reviewScore);
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
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <?php            
			
            if ($paperArray != NULL) 
			{
                echo "<form method ='post' action=''";
                echo htmlspecialchars($_SERVER['PHP_SELF']);
                echo "'>";
				echo "Paper Name: " ; 
                echo "<table border='1' class='table table-bordered'>";
					
                echo "<tr>";
                echo "<th>Paper Content</th>";
                foreach ($paperArray as $paper) 
				{	
					echo "{$paper->get_paperName()}";   
					echo "<tr>";
					echo "<td ><textarea id='updatePaperContent' name='updatePaperContent' rows='20' cols='50' value='{$placeholderArray['paperContent']};'>{$paper->get_paperContent()}</textarea></td>";    
                }
				
                echo "</table>";
            } 
			else {
                echo "<p>No Paper(s) found!</p>";
            }
            ?>
			<?php
				$reviewers = explode(",", $paper->get_paperRatingsReviewer());
			?>
			<table>
			<td><h3 style="font-weight: bold" >Reviewer score(s):<h3></td>
			<tr>
			<?php foreach ($reviewers as $review)
			echo "<td>{$review}</td><tr>";
			?>
			</table>
			
			<?php if(IsNullOrEmptyString($paper->get_paperRatingsAuthor()==true)){	
			?>
			<h2>Rate Review</h2>
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
				<input type="submit" name="submit" value="Submit Review">
			<?php }
			else
			{
				echo "<td><h4 style='font-weight:bold'>Your Review<h4></td><tr>";
				echo "<td>{$paper->get_paperRatingsAuthor()}</td>";
			}
			?>
			<h4 style="font-weight:bold">Comments:<h4>
			</form>
			<br>
			<br>
			<button onclick="location.href='viewPapers.php'">Back to view all papers</button>
			<button onclick="location.href='submitPaper.php'">Go back submission page</button>
        </div>
    </body>
</html>