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
		$_SESSION['paperBidders']=array();
		$lblWarning = "";
		function array_has_dupes($array) 
		{
			return count($array) !== count(array_unique($array));
		}
        if (isset($_SESSION["user"])) {
            $user = unserialize($_SESSION['user']);
            if ($user->get_type() == "Reviewer") 
			{
				$user = unserialize($_SESSION['user']);
				$user_email = $user->get_email();
				$user_name = ucwords($user->get_name()) . " " . ucwords($user->get_surname());
                $paperArr = [];
                $paperObj = new Papers();
                $paperArr = $paperObj->listPapersToBid($user_name);
				$userArr = [];
                $userObj = new Users();
                $userArr = $userObj->getInfoByMail($user_email);
				foreach ($userArr as $info) 
				{
					$maxWorkload = $info->get_maxReviewed();
					$currentWorkload = $info->get_currentlyReviewed();
					$currentBid = $info->get_currentBid();
				}
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
			
			 if (isset($_POST['bid'])) 
				 {
			  {
				$paperNumber = $_POST['bid'];
				if($currentWorkload<$maxWorkload)
				{
					$paperArr2 = $paperObj->findPaper($paperNumber);
						foreach ($paperArr2 as $paper) 
						{
							$allBidders = $paper->get_paperBidders();
							array_push($_SESSION['paperBidders'],$allBidders);
						}
					$currentBidders = implode(" ",$_SESSION['paperBidders']);
					if(preg_match("/{$user_email}/i", $currentBidders)) 
				   {
								array_pop($_SESSION['paperBidders']);
								$lblWarning = "Cannot add bid same paper more than once!";
				   }
				   else
				   {
						$paperArr3 = $paperObj->findPaper($paperNumber);
						foreach ($paperArr3 as $paper) 
						{
							$paperName = $paper->get_paperName();
							$currentBid.=$paperName." ";
						}
						array_push($_SESSION['paperBidders'],$user_email);
						$bidders = implode(" ",$_SESSION['paperBidders']);
						$currentWorkload +=1;
						$paperObj->addBidder($paperNumber, $bidders);
						$userObj->updateCurrentWorkload($user_email,$currentWorkload);
						
						$userObj->updateBid($currentBid,$user_email);
						$lblWarning = "";
						$_SESSION['paperBidders'] = array();
						$_SESSION['coAuthors']=array();
						echo '<script>alert("Bid Submitted.")</script>';
					    echo '<script>window.location.href = "viewPapersToBid.php";</script>';
				   } 
				}
				else
					echo "Cannot exceed workload limit!";
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
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<div class="wrapper">
            <?php
            echo "<p id='Title' style='font-size:30px;'>All Papers</p>";

            if ($paperArr != NULL) 
			{
                echo "<form method ='post' action=''";
                echo htmlspecialchars($_SERVER['PHP_SELF']);
                echo "'>";
                echo "<table border='1' class='table table-bordered'>";
                echo "<tr>";
                echo "
					 <th>Paper Name</th>
					 <th>Author</th>
					 <th>Co-Author(s)</th>
					 <th>Bidder(s)</th>
					 <th>Action(s)</th>";

                foreach ($paperArr as $paper) 
				{
                    echo "<tr>";
                    echo "<td>{$paper->get_paperName()}</td>";
                    echo "<td>{$paper->get_author()}</td>";
					echo "<td>{$paper->get_otherAuthors()}</td>";
					echo "<td>{$paper->get_paperBidders()}</td>";
					if ($user->get_type() == "Reviewer") 
					{
                        if ($paper->get_paperStatus() == "To Be Reviewed") 
						{
							 echo "<td><button class='btn btn-success' type='submit' name='bid' id='bid' value='{$paper->get_paperNo()}'>Bid</button> </td>"; 
						} 
                    }
                    echo "</tr>";
                }
				echo "<label for='coAuthors' style='color:red';>{$lblWarning}</label>";
                echo "</table>";
                echo "</form>";
            } 
			else {
                echo "<p>No paper(s) found!</p>";
            }
            ?>
			</form>
        </div>
    </body>
</html>