<?php
require_once("./db/Database.php");

class Users {
    private $uid;
    private $name;
    private $surname;
    private $phone;
    private $email;
    private $password;
    private $type;
	private $currentPaper;
	private $maxReviewed;
	private $currentlyReviewed;
	private $currentlyReviewing;
	private $notification;
	private $currentBid;
    private $table = 'users';
	
	//Constructor
    function __construct($name = NULL, $surname = NULL, $phone = NULL, $email = NULL, $type = NULL, $currentPaper = NULL, $password = NULL, $maxReviewed=NULL, $currentlyReviewed=NULL,$currentlyReviewing=NULL,$notification=NULL,$currentBid=NULL) 
	{
        $this->name = $name;
        $this->surname = $surname;
        $this->phone = $phone;
        $this->email = $email;
        $this->password = $password;
        $this->type = $type;
		$this->type = $currentPaper;
		$this->maxReviewed = $maxReviewed;
		$this->currentlyReviewed = $currentlyReviewed;
		$this->currentlyReviewing = $currentlyReviewing;
		$this->notification = $notification;
		$this->currentBid = $currentBid;
    }

    // Accessors
    function get_uid() 
	{
        return $this->uid;
    }

    function get_name() 
	{
        return $this->name;
    }

    function get_surname() 
	{
        return $this->surname;
    }

    function get_phone() 
	{
        return $this->phone;
    }

    function get_email() 
	{
        return $this->email;
    }
    function get_password() 
	{
        return $this->password;
    }
    function get_type() 
	{
        return $this->type;
    }
	function get_currentPaper() 
	{
        return $this->currentPaper;
    }
	function get_maxReviewed() 
	{
        return $this->maxReviewed;
    }
	function get_currentlyReviewed() 
	{
        return $this->currentlyReviewed;
    }
	function get_currentlyReviewing() 
	{
        return $this->currentlyReviewing;
    }
	function get_notification()
	{
		return $this->notification;
	}
	function get_currentBid()
	{
		return $this->currentBid;
	}
    // Mutators
    function set_name($name) 
	{
        $this->name = $name;
    }

    function set_surname($surname) 
	{
        $this->surname = $surname;
    }

    function set_phone($phone) 
	{
        $this->phone = $phone;
    }

    function set_email($email) 
	{
        $this->email = $email;
    }

    function set_password($password) 
	{
        $this->password = $password;
    }

    function set_type($type) 
	{
        $this->type = $type;
    }
	function set_currentPaper($type) 
	{
        $this->type = $currentPaper;
    }
	function set_maxReviewed($maxReviewed) 
	{
        $this->maxReviewed = $maxReviewed;
    }
	function set_currentlyReviewed($currentlyReviewed) 
	{
        $this->currentlyReviewed = $currentlyReviewed;
    }
	function set_currentlyReviewing($currentlyReviewing) 
	{
        $this->currentlyReviewing = $currentlyReviewing;
    }
	function set_notification($notification)
	{
		$this->notification = $notification;
	}
	function set_currentBid($currentBid)
	{
		$this->currentBid = $currentBid;
	}
    // Class Functions 
	
	//get information with email
    function getMail($email)
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        if ($conn->connect_error) 
		{
            die("Connection Failed: " . $conn->connect_errno);
        }
        // Prepare Statements
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Get Number Of Rows
        $row_counts = $result->num_rows;

        // Close Connections
        $stmt->close();
        $conn->close();

        // If record exist
        if ($row_counts != 0) 
		{
            return TRUE;
        } 
		else {
            return FALSE;
        }
    }
	
		// Find Paper
	function getInfoByMail($useremail) 
		{
        $user_list = array();

        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table} WHERE email = ? ";
        $stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $useremail);
        $stmt->execute();
        $result = $stmt->get_result();

        $row_counts = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            $user = new users($row['name'], $row['surname'],$row['phone'], $row['email'], $row['type'], $row['password'],$row['currentPaper'], $row['maxReviewed'],$row['currentlyReviewed'],$row['currentlyReviewing'],$row['notification'],$row['currentBid'],$row['uid'],);
            $user_list[] = $user;
        }
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) 
		{
            return $user_list;
        } 
		else {
            return NULL;
        }
    }
		// Find Paper
	function getAllReviewers() 
		{
        $user_list = array();

        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table} WHERE type = 'Reviewer' ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $row_counts = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            $user = new users($row['name'], $row['surname'],$row['phone'], $row['email'], $row['type'], $row['password'],$row['currentPaper'], $row['maxReviewed'],$row['currentlyReviewed'],$row['currentlyReviewing'],$row['notification'],$row['currentBid'],$row['uid'],);
            $user_list[] = $user;
        }
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) 
		{
            return $user_list;
        } 
		else {
            return NULL;
        }
    }
    //Login 
    function authenticate($email, $password) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        if ($conn->connect_error) 
		{
            die("Connection Failed: " . $conn->connect_errno);
        }

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table} WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        // Get Number Of Rows
        $row_counts = $result->num_rows;

        while ($row = $result->fetch_assoc()) 
		{
            $user = new Users($row['name'], $row['surname'], $row['phone'], $row['email'], $row['type'] , $row['type'] ,$row['currentPaper'],$row['maxReviewed'],$row['currentlyReviewed'],$row['currentlyReviewing'],$row['notification'],$row['currentBid']);
        }

        // Close Connections
        $stmt->close();
        $conn->close();
		
		// If record exist
        if ($row_counts != 0) 
		{
            return $user;
        } 
		else 
		{
            return NULL;
        }
    }
	function updateWorkload($email, $workload) 
			{
				$conn = new Database();
				$conn = $conn->connectdb();

				// Prepare Statements
				$sql = "UPDATE {$this->table} SET maxReviewed = ? WHERE email = ?";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("ss", $workload, $email);
				$stmt->execute();

				$result = $stmt->affected_rows;
				// Close Connections
				$stmt->close();
				$conn->close();

				if ($result != 0) 
				{
					return TRUE; 
				} 
				else {
					return FALSE;
				}
			}
		function updateBid($email, $paperName) 
			{
				$conn = new Database();
				$conn = $conn->connectdb();

				// Prepare Statements
				$sql = "UPDATE {$this->table} SET currentBid = ? WHERE email = ?";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("ss", $email, $paperName);
				$stmt->execute();

				$result = $stmt->affected_rows;
				// Close Connections
				$stmt->close();
				$conn->close();

				if ($result != 0) 
				{
					return TRUE; 
				} 
				else {
					return FALSE;
				}
			}
    function updateCurrentWorkload($email, $workload) 
			{
				$conn = new Database();
				$conn = $conn->connectdb();

				// Prepare Statements
				$sql = "UPDATE {$this->table} SET currentlyReviewed = ? WHERE email = ?";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("ss", $workload, $email);
				$stmt->execute();

				$result = $stmt->affected_rows;
				// Close Connections
				$stmt->close();
				$conn->close();

				if ($result != 0) 
				{
					return TRUE; 
				} 
				else {
					return FALSE;
				}
			}
    //Create new user 
    function addUser(Users $user) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        if ($conn->connect_error) 
		{
            die("Connection Failed: " . $conn->connect_errno);
        }
        // Prepare Statements
        $sql = "INSERT INTO {$this->table} (name, surname, phone, email, type, password) VALUES (?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $user->name, $user->surname, $user->phone, $user->email, $user->type, $user->password); 
        $stmt->execute();
        $result = $stmt->affected_rows; 
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($result != 0) 
		{
            return TRUE;
        } 
		else 
		{
            return FALSE;
        }
    }
	function incrementCurrentWorkload($email) 
			{
				$conn = new Database();
				$conn = $conn->connectdb();

				// Prepare Statements
				$sql = "UPDATE {$this->table} SET currentlyReviewed = currentlyReviewed + 1 WHERE email = ?";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("s",$email);
				$stmt->execute();

				$result = $stmt->affected_rows;
				// Close Connections
				$stmt->close();
				$conn->close();

				if ($result != 0) 
				{
					return TRUE; 
				} 
				else {
					return FALSE;
				}
			}	
	}
?>