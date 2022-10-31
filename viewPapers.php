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
		$_SESSION['paperNo']="";
        if (isset($_SESSION["user"])) {
            $user = unserialize($_SESSION['user']);
            if ($user->get_type() == "Author") 
			{
				$user = unserialize($_SESSION['user']);
				$user_name = ucwords($user->get_name()) . " " . ucwords($user->get_surname());
                $paperArr = [];
                $paperObj = new Papers();
                $paperArr = $paperObj->listAllPapersByAuthor($user_name);
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
					 <th>Status</th>
					 <th>Action(s)</th>";

                foreach ($paperArr as $paper) 
				{
                    echo "<tr>";
                    echo "<td>{$paper->get_paperName()}</td>";
                    echo "<td>{$paper->get_author()}</td>";
					echo "<td>{$paper->get_otherAuthors()}</td>";
					echo "<td>{$paper->get_paperStatus()}</td>";
					if ($user->get_type() == "Author") 
					{
                        if ($paper->get_paperStatus() == "Pending") 
						{
							echo "<td><a href='editPaper.php?paperNo={$paper->get_paperNo()};'>View/Edit</a></td>"; 
							$_SESSION['paperNo'] = $paper->get_paperNo();
							
						} 
						else if($paper->get_paperStatus() == "Accepted" || $paper->get_paperStatus() == "Rejected") 
						{
							echo "<td><a href='viewPaper.php?paperNo={$paper->get_paperNo()};'>View</a></td>"; 
						} 
						
                    }
                    echo "</tr>";
                }
				
                echo "</table>";
                echo "</form>";
            } 
			else {
                echo "<p>No paper(s) found!</p>";
            }
            ?>
			
        </div>
    </body>
</html>