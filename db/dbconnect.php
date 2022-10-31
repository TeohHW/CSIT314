<?php
require("Database.php");

// Database
$db = 'research_management_system';

// Tables
$userTable = 'users';
$paperTable = 'papers';
$commentTable = 'comments';

// Create Connection
$conn = new Database();

// Create Database
$conn->createdb($db);

// Connect to database
$conn->connectdb($db);

// Create Table if it does not exist
$conn->createTable($userTable, "
	uid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50) NOT NULL,
	surname VARCHAR(50) NOT NULL,
	phone VARCHAR(25),
	email VARCHAR(100), 
	type VARCHAR(50) NOT NULL, 
	password VARCHAR(50) NOT NULL,
	currentPaper VARCHAR(50) DEFAULT 'NULL',
	maxReviewed VARCHAR(10) DEFAULT '0',
	currentlyReviewed VARCHAR(10) DEFAULT '0',
	currentlyReviewing VARCHAR(50) DEFAULT 'NULL',
	notification VARCHAR(100),
	currentBid VARCHAR(1000)
");

// Create Table if it does not exist
$conn->createTable($paperTable, "
	paperNo INT(50) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	author VARCHAR(100) NOT NULL,
	authorEmail VARCHAR(100) NOT NULL,
	otherAuthors VARCHAR(500),
	paperName VARCHAR(100) NOT NULL,
	paperContent VARCHAR(1000) NOT NULL,
	paperStatus VARCHAR(100) NOT NULL,
	paperReviewer VARCHAR(100),
	paperRatingsReviewer VARCHAR(100),
	paperRatingsAuthor VARCHAR(100),
	paperComments VARCHAR(1000),
	paperBidders VARCHAR(500) DEFAULT 'NULL'
");
$conn->createTable($commentTable, "
	commentId INT(150) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	paperNo INT(50) ,
	comment VARCHAR(1000),
	commenter VARCHAR(100)
");

$conn->close();
?>