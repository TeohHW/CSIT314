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
		$bidderCount = 0;
		$test = 0;
		$reviewerArray = array();
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
            if ($user->get_type() == "ConferenceChair") 
			{
                $paperObj = new Papers();
				$userObj = new Users();
                $paperArray = $paperObj->findPaper($paperNo);
				$userArray = $userObj->getAllReviewers();
				
				  foreach ($paperArray as $paper) 
				{	
				    $paperNameHere  = $paper->get_paperName();
					$paperContentHere = $paper->get_paperContent();
					$paperBidders = $paper->get_paperBidders();
					$paperBidderArr = explode(" ", trim($paperBidders));
				}
				foreach($userArray as $user)
				{
					array_push($reviewerArray,trim($user->get_email()));
				}
				    $bidderArray = array_intersect($paperBidderArr, $reviewerArray);
            } 
			else
			{
                echo '<script>window.location.href = "index.php";</script>';
            }
        } 
		function array_has_dupes($array) 
		{
			return count($array) !== count(array_unique($array));
		}
		 if ($_SERVER["REQUEST_METHOD"] == "POST") 
		{	
				if (isset($_POST['allocate'])) 
				 {
						$allocatedReviewerEmail = $_POST['allocate'];
						$_SESSION['allocatedReviewerEmail'] = $allocatedReviewerEmail;
						$userInfoArray = $userObj->getInfoByMail($allocatedReviewerEmail);
						array_push($_SESSION['allocation'],$allocatedReviewerEmail );
						foreach ($userInfoArray as $info)
						{
							$maxWorkload = $info->get_maxReviewed();
							$currentWorkload = $info->get_currentlyReviewed();
						}
						if(array_has_dupes($_SESSION['allocation']))
						{							
							array_pop($_SESSION['allocation']);
							$lblWarning = "Cannot allocate more than once!";
						}
						if($currentWorkload>=$maxWorkload)
						{
							array_pop($_SESSION['allocation']);
							echo "Cannot exceed workload limit!";
						}
				 }
				 
				 else if (isset($_POST['cancel'])) 
				 {
						$_SESSION['allocation']=array();
						$_SESSION['allocatedReviewerEmail']="";
				 }
				 else if(isset($_POST['Confirm'])) 
				 {
							$biddingStatus ="Unavailable";
							$paperStatus = "Reviewing";
							$updateUserObj = new Users();
							$updateUserObj->incrementCurrentWorkload($_SESSION['allocatedReviewerEmail']);
							$allocatedReviewers = implode(",",$_SESSION['allocation']);
							$paperObj->allocateReviewer($paperNo,$allocatedReviewers);
							$paperObj->addBidder($paperNo,$biddingStatus);
							$paperObj->chgStatus($paperNo,$paperStatus);
							echo '<script>alert("Reviewer allocated successfully")</script>';
							echo '<script>window.location.href = "allocatePaper.php";</script>';
							$_SESSION['allocatedReviewerEmail']="";
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
				   <li><a href="allocatePaper.php">Allocate Paper(s)</a></li>
				  <li><a href="viewPending.php">View Pending Papers</a></li>
				  <li><a href="viewAllPapers.php">View All Papers</a></li>
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
					<tr>
					<?php 
					echo "</table>";
                }
			if($userArray !=NULL)
			{
				echo "<h2>Select Reviewer</h2>";
                echo "<table border='1' class='table table-bordered'>";
				
				echo "<th> Bidder Name </th>
					  <th> Bidder Email </th>
					  <th> Bidder Max Workload </th>
					  <th> Bidder Current Workload </th>
					  <th> Action </th>";
					  
				echo "<tr>";
				foreach ($bidderArray as $bidder)
			    {
					foreach ($userArray as $reviewers)
					{
						if($reviewers->get_email() == $bidder)
						{
						echo "<td>{$reviewers->get_name()}</td>";
						echo "<td>{$reviewers->get_email()}</td>";
						echo "<td>{$reviewers->get_maxReviewed()}</td>";
						echo "<td>{$reviewers->get_currentlyReviewed()}</td>";
						if($reviewers->get_currentlyReviewed()> $reviewers->get_maxReviewed()) 
							echo "<td><button disabled class='btn btn-danger' type='submit' name='allocate' id='allocate' value='{$reviewers->get_email()}'>Allocate</button> </td>"; 
						else
						{
							    echo "<td><button class='btn btn-success' type='submit' name='allocate' id='allocate' value='{$reviewers->get_email()}'> Allocate </button> </td>"; 
						}
						echo "<tr>";
						}
					}
					
				}
			}
			?>
				</table>
				<h4>Current List of Reviewers Allocated<h4>
				<textarea disabled id="content" name='content' rows='5' cols='50'> <?php echo implode(",",$_SESSION['allocation']); ?></textarea>
				<button class="btn btn-danger" type="submit" name="cancel" id="cancel">Undo Allocations</button>
				<br>
				<br>
				<input type="submit" name="Confirm" id="Confirm" value="Confirm">
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