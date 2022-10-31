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
		$coAuthorWarning = "";
		$arrayToCheckDupes = array();
		$paperNo = "";
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
            if ($user->get_type() == "Author") 
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
		 if ($_SERVER["REQUEST_METHOD"] == "POST") 
		{	
			 if (isset($_POST['btnAddAuthor'])) 
			 {		
				   $paperNoGET = $_SESSION['paperNo'];
				   $paperContentHere = $_POST['content'];
				   $paperNameHere = $_POST['paperName'];
				   $var = $_POST['coAuthors'];
				   array_push($arrayToCheckDupes,$var); //to compare
				   if (array_intersect($_SESSION['coAuthors'],$arrayToCheckDupes))
				   {
					   echo "Cannot add author that is already included!";
					   array_pop($arrayToCheckDupes);
					   array_pop($_SESSION['coAuthors']);
				   }
				   else 
				   {
							array_pop($arrayToCheckDupes);
							array_push($_SESSION['coAuthors'],$var);
							$coAuthorWarning="";
				   }
			} 
			   else if (isset($_POST['update'])) 
				 {
			   {
					$coAuthors = implode(",",$_SESSION['coAuthors']);
					$updatedPaperName =  $_POST['paperName'];
					$updatedPaperContent =  $_POST['content'];
					$paperObj->updateName($paperNo,$updatedPaperName);
					$paperObj->updateContent($paperNo,$updatedPaperContent);
					$paperObj->updateCoAuthor($paperNo,$coAuthors);
					echo '<script>alert("Paper content updated successfully")</script>';
					echo '<script>window.location.href = "viewPapers.php";</script>';
					$_SESSION['coAuthors']=array();
					$_SESSION["paperNo"]="";
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
					<input type="text" id="paperName" name="paperName" class="form-control <?php echo (!empty($nameErrorMsg)) ? 'is-invalid' : ''; ?>" value="<?php echo $paperNameHere;?>">
					<?php 
					echo "<tr>";
					?>
				    <td ><textarea id="content" name='content' rows='20' cols='50'><?php echo $paperContentHere?></textarea></td> 
					<?php 
					echo "<tr>";
                }
				
                echo "</table>";
				?>
				<div>
				<p>Add Co-Author</p>
				<?php
					//Dropdown list of authors
					$conn = new mysqli('localhost', 'root', '', 'research_management_system') 
					or die ('Cannot connect to db');

					$result = $conn->query("select uid, name, surname,email from users Where type = 'Author' AND email!= '$user_email' ");
					
					echo "<select id='coAuthors' name='coAuthors'>";	

					while ($row = $result->fetch_assoc()) 
					{
								  unset($id, $name);
								  $id = $row['id']; 
								  echo '<option value="'.htmlspecialchars($row['name'])." ".htmlspecialchars($row['surname']).'">'.htmlspecialchars($row['name'])." ".htmlspecialchars($row['surname']).'</option>';

					}
					echo "</select>";
					//End of Dropdown List

				?> 	
				</div>
				
				<label for="coAuthors" style="color:red";><?php echo $coAuthorWarning; ?></label><br>
				<br>
				<input type="submit" name="btnAddAuthor" class="btn btn-primary" value="Add Co-Author">
				<textarea type="text" name="listOfCoAuthors" class="form-control" rows="5" value="listOfCoAuthors"> <?php echo implode(",",$_SESSION['coAuthors']); ?></textarea>
				<?php
				echo "<td><button class='btn btn-success' type='submit' name='update' value='{$paper->get_paperNo()}'>Update</button></td>"; 
                echo "</form>";
            } 
			else {
                echo "<p>No Paper(s) found!</p>";
            }
            ?>
			
        </div>
    </body>
</html>