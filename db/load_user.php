<?php
	include ("Database.php");
	
	$conn = new Database();
	$conn = $conn->connectdb();
	
	$user = "INSERT INTO users" . 
		"(name, surname, phone, email, type, password) " .
		" VALUES " .
		"('Joey', 'Tan' , '91234567', 'joey@hotmail.com', 'Author', 'pw123'), " .
		"('Jonathan', 'Soh' , '95134628', 'jon@gmail.com', 'Author', 'pw123'), " .
		"('amos', 'Soh' , '95134628', 'amos@gmail.com', 'Author', 'pw123'), " .
		"('tom', 'Soh' , '95134628', 'tom@gmail.com', 'Author', 'pw123'), " .
		"('Thomas', 'Teo' , '84625137', 'thomas@hotmail.com', 'Reviewer', 'pw123'), " .
		"('Popeye', 'Lim' , '81234567', 'popeye@gmail.com', 'Reviewer', 'pw123'), " .
		"('abc', 'xyz' , '81234567', 'abc@gmail.com', 'System Admin', 'pw123'), " .
		"('Leroy', 'Koh' , '91234567', 'leroy@gmail.com', 'ConferenceChair', 'pw123')";
	
	$result = mysqli_query($conn, $user);
	
	if($result)
	{
		echo "Users added successfully";
	}
	else{
		echo "Error adding Users";
	}
?>