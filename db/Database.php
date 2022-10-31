<?php

class Database {
    protected $conn;
    protected $user;
    protected $pw;
    protected $servername;
    protected $db;

    function __construct($servername = "localhost", $user = "root", $pw = "") {
        $this->servername = $servername;
        $this->user = $user;
        $this->pw = $pw;
        $this->conn = new mysqli($servername, $user, $pw);
        if ($this->conn->connect_error) {
            die("Connection Failed: " . $this->conn->errno);
        }
    }

    // Connect to database
    function connectdb($db = "research_management_system") {
        $this->db = $db;
        $this->conn = new mysqli($this->servername, $this->user, $this->pw, $db);
        if ($this->conn->connect_error) {
            die("Connection Failed: " . $this->conn->errno);
        }
        return $this->conn;
    }

    // Create Database
    function createdb($db = "research_management_system") {
        $sql = "CREATE DATABASE IF NOT EXISTS {$db}";
        if ($this->conn->query($sql) == TRUE) {
           // echo "Database Exist or Created Successfully";
        } 
		else {
           // echo "Error creating database: " . $this->conn->error;
        }
    }

    // Create Table
    function createTable($table, $cols) {
        $check_table = $this->conn->query("SHOW TABLES LIKE '$table'");
        $table_exists = $check_table->num_rows >=1;

        $sql = "CREATE TABLE $table (" . $cols . ")"; 
        if (!$table_exists) {
            if ($this->conn->query($sql) == TRUE){
				//echo "Table $table created successfully<br/>";
            }
			else {
				//echo "Error creating table" . $this->conn->error;
            }
        }
    }
    
    // Close Connection
    function close() {
        $this->conn->close();
    }
}
?>