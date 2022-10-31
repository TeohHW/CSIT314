<?php
	include ("Database.php");
	
	$conn = new Database();
	$conn = $conn->connectdb();
	
	$paperTable = "INSERT INTO papers " .
		"(author, authorEmail, paperName, paperContent, paperStatus, paperReviewer, paperRatingsReviewer,paperRatingsAuthor) ".
		" VALUES " .
		"('Joey Tan', 'joey@hotmail.com', 'paper1', 'papercontent1', 'Accepted', 'thomas@hotmail.com', '',''), " .
		"('Joey Tan', 'joey@hotmail.com', 'paper2', 'papercontent2', 'Rejected', 'thomas@hotmail.com', '','')," .
		"('Joey Tan', 'joey@hotmail.com', 'papername', 'papercontent2', 'Pending', 'thomas@hotmail.com', '','')," .
		"('Joey Tan', 'joey@hotmail.com', 'papername2', 'papercontent2', 'Pending', 'thomas@hotmail.com', '','')," .
		"('Joey Tan', 'joey@hotmail.com', 'papernamehere', 'papercontent2', 'To Be Reviewed', 'thomas@hotmail.com', '','')," .
		"('Joey Tan', 'joey@hotmail.com', 'placeholdername', 'contenthere', 'To Be Reviewed', 'thomas@hotmail.com', '','')," .
		"('Jonathan Soh', 'jon@gmail.com', 'paper3', 'papercontent3', 'Accepted', 'thomas@hotmail.com','','')";
		
	
	$result = mysqli_query($conn, $paperTable);
	
	if($result){
		echo "Data added successfully";
	}
	else{
		echo "Error adding Data";
	}
?>