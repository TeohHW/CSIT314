<?php
require_once("./db/Database.php");

class Notifications {
    private $notificationId;
    private $message;
    private $recipient;
    private $table = 'notifications';
	
	//Constructor
    function __construct($notificationId = NULL, $message = NULL, $recipient = NULL) 
	{
        $this->notificationId = $notificationId;
        $this->message = $message;
        $this->recipient = $recipient;
    }

    // Accessors
    function get_notificationId() 
	{
        return $this->notificationId;
    }

    function get_message() 
	{
        return $this->message;
    }

    function get_recipient() 
	{
        return $this->recipient;
    }
    // Mutators
    function set_notificationId($notificationId) 
	{
        $this->notificationId = $notificationId;
    }

    function set_message($message) 
	{
        $this->message = $message;
    }

    function set_recipient($recipient) 
	{
        $this->recipient = $recipient;
    }
	
	function getNotifications($email) 
		{
        $notification_list = array();

        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table} WHERE recipient = ? ";
        $stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $row_counts = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            $notifications = new notifications($row['notificationId'], $row['message'],$row['recipient']);
            $notification_list[] = $notifications;
        }
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) 
		{
            return $notification_list;
        } 
		else {
            return NULL;
        }
    }

    //Create new user 
    function addNotification($message,$email) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        if ($conn->connect_error) 
		{
            die("Connection Failed: " . $conn->connect_errno);
        }
        // Prepare Statements
        $sql = "INSERT INTO {$this->table} (message, recipient) VALUES (?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $message, $email); 
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
	function deleteMsg($notificationId) 
		{
			$conn = new Database();
			$conn = $conn->connectdb();

			// Prepare Statements
			$sql = "DELETE FROM {$this->table} WHERE notificationId = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("s",$notificationId);
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