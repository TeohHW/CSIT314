<?php
session_start();
?>
<html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<style>
			body{ font: 20px sans-serif; }
			.wrapper{ width: 500px; padding: 20px; margin: auto;}
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
		require_once("classes/Comments.php");
		
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
				$commentObj = new Comments();
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
			
			if (isset($_POST['submitComment']))
			{				
				$comment = $_POST["commentBox"];
				$commentObj->addComment($paperNo,$comment,$user_name,$user_email);
			}
			else if (isset($_POST['delete'])) 
			{
				$commentId = $_POST["delete"];
				$commentObj->deleteComment($commentId);
			}
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
			<h3>
			<p>Paper Reviews</p>
			<?php echo $paper->get_paperRatingsReviewer();?>
			</h3>
			<?php if ($paper->get_paperStatus() == "Reviewing" || $paper->get_paperStatus() == "Accepted" || $paper->get_paperStatus() == "Rejected")
			{
			echo "<h4 style='font-weight:bold'>Comment(s)<h4>";
			
				$commentObj = new Comments();
				$commentArray = $commentObj->getCommentTable($paperNo);
            if ($commentArray != NULL) 
			{
                echo "<form method ='post' action=''";
                echo htmlspecialchars($_SERVER['PHP_SELF']);
                echo "'>";
                echo "<table border='1' class='table table-bordered'>";		
				echo "<th>Comment</th>";
				echo "<th>Commenter</th>";
                foreach ($commentArray as $comment) 
				{	
					echo "<tr>";
					echo "<td>{$comment->get_comment()}</td>";  
					echo "<td>{$comment->get_commenterName()}</td>"; 
					echo "<td>{$comment->get_commenterEmail()}</td>"; 
					if($comment->get_commenterEmail()== $user_email)
					{
						 echo "<td><button class='btn btn-danger' type='submit' name='delete' id='delete' value='{$comment->get_commentId()}'>Delete</button> </td>"; 
					}
                }
				
                echo "</table>";
            } 
			echo "<h4 style='font-weight:bold'>Leave a comment down below!<h4>";
			echo "<textarea name='commentBox' id='commentBox' rows='4' cols='50' placeholder='Add a comment...'></textarea>";
			echo "<input type='submit' name='submitComment' value='Comment'>";
			echo "</form>";
			}
			else
			{
				echo "</form>";
			}
			?>
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
