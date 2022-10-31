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
    </head>
    <body>
        <?php
        require_once("db/dbconnect.php");
        require_once("classes/Papers.php");
		require_once("classes/Users.php");
		function searchFilter($input) 
		{
            return ($input != '' && $input != NULL);
        }
		$_SESSION['coAuthors']=array();
		$_SESSION['paperContent']="";
		$_SESSION['paperNo']="";
		$_SESSION['paperBidders']=array();
		$_SESSION['allocation'] =array();
		$_SESSION['allocatedReviewerEmail']="";
		 $searchArr = array(
            "paperName" => "",
            "paperStatus" => ""
        );
        $valid = array(); 
        $err_msg = "";
			
        // Check if logged in
        if (isset($_SESSION["user"])) 
		{
            $user = unserialize($_SESSION['user']);
			$user_email = $user->get_email();
            $user_name = ucwords($user->get_name()) . " " . ucwords($user->get_surname());
        } 
		else 
		{
            echo '<script>window.location.href = "login.php";</script>';
        }
		 if ($_SERVER["REQUEST_METHOD"] == "POST") 
		{
            foreach ($_POST as $key => $value) 
			{
                if (!empty($_POST[$key])) {
                    if (isset($searchArr[$key])) 
					{
                        $searchArr[$key] = htmlspecialchars($value);
                    }
                }
            }
            
            if (!empty($searchArr['paperName']) || !empty($searchArr['paperStatus'])) 
			{
                foreach ($searchArr as $key => $value) 
				{
                    if (empty($searchArr[$key]))
					{
                        $searchArr[$key] = NULL;
                    }
                }
                $valid[] = TRUE;
            } 
			else 
			{
                $err_msg .= "Minimum 1 search field required";
                $valid[] = FALSE;
            }
            if (!in_array(FALSE, $valid)) 
			{
                $paperObj = new Papers();
                $searchArr = array_filter($searchArr, "searchFilter");

                $_SESSION['searchArr'] = $searchArr;
                header("Location:searchResult.php");

                // Clear Array
                	 $searchArr = array(
						"paperName" => "",
						"paperStatus" => ""
						);
            } 
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
				  <li><a href="viewPapersToBid.php">Bid for paper</a></li>
				  <li><a href="viewPapersToReview.php">Review Paper(s)</a></li>
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
        <form id="searchContainer" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			<p id="searchTitle" style="font-size:35px;">Search Paper</p>
			<div class="form-group">
			<label> Paper Name </label>
            <input class="form-control" type="text" id="paperName" name="paperName" placeholder = "Enter Paper Name" class="searchFields"/>
			</div>
			<div class="form-group">
			<label> Paper Status </label>
            <input class="form-control" type="text" id="paperStatus" name="paperStatus" placeholder = "Enter Paper Status" class="searchFields"/>
			</div>
			<button class="btn btn-primary" id ="searchButton" type="submit">Search</button>
        </form>
		<div id="error_Container">
            <div id="err_msg"><?php echo $err_msg; ?></div>
        </div>
		</div>
    </body>
</html>