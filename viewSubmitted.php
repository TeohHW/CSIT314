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
		
		$paperNo = $_SESSION["paperNo"];
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
           if (isset($_POST["update"])) {
				$updatedPaperContent =  htmlspecialchars($_POST['updatePaperContent']);
				$paperObj->updatePaper($paperNo,$updatedPaperContent);
				echo '<script>alert("Paper content updated successfully")</script>';
                echo '<script>window.location.href = "viewPapers.php";</script>';
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
				  <li><a href="newPaper.php"></a>New Paper</li>
				   <li><a href="viewPapers.php"></a>View Paper(s)</a></li>
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
					echo "<tr>";
                }
				
                echo "</table>";
                echo "</form>";
            } 
			else {
                echo "<p>No Paper(s) found!</p>";
            }
            ?>
			</form>
        </div>
    </body>
</html>