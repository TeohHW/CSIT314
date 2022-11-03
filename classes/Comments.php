<?php
require_once("./db/Database.php");

class Comments {
    private $commentId;
    private $paperNo;
    private $comment;
    private $commenterName;
    private $commenterEmail;
    private $table = 'comments';
	
	//Constructor
    function __construct($commentId = NULL, $paperNo = NULL, $comment = NULL, $commenterName = NULL, $commenterEmail = NULL) 
	{
        $this->commentId = $commentId;
        $this->paperNo = $paperNo;
        $this->comment = $comment;
        $this->commenterName = $commenterName;
        $this->commenterEmail = $commenterEmail;
    }

    // Accessors
    function get_commentId() 
	{
        return $this->commentId;
    }

    function get_paperNo() 
	{
        return $this->paperNo;
    }

    function get_comment() 
	{
        return $this->comment;
    }

    function get_commenterName() 
	{
        return $this->commenterName;
    }

    function get_commenterEmail() 
	{
        return $this->commenterEmail;
    }
    // Mutators
    function set_paperNo($paperNo) 
	{
        $this->paperNo = $paperNo;
    }

    function set_comment($comment) 
	{
        $this->comment = $comment;
    }

    function set_commenterName($commenterName) 
	{
        $this->commenterName = $commenterName;
    }

    function set_commenterEmail($commenterEmail) 
	{
        $this->commenterEmail = $commenterEmail;
    }
	
	function getCommentTable($paperNo) 
		{
        $comments_list = array();

        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table} WHERE paperNo = ? ";
        $stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $paperNo);
        $stmt->execute();
        $result = $stmt->get_result();

        $row_counts = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            $comments = new comments($row['commentId'], $row['paperNo'],$row['comment'], $row['commenterName'], $row['commenterEmail']);
            $comments_list[] = $comments;
        }
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) 
		{
            return $comments_list;
        } 
		else {
            return NULL;
        }
    }

    //Create new user 
    function addComment($paperNo,$comment,$commenterName,$commenterEmail) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        if ($conn->connect_error) 
		{
            die("Connection Failed: " . $conn->connect_errno);
        }
        // Prepare Statements
        $sql = "INSERT INTO {$this->table} (paperNo, comment, commenterName, commenterEmail) VALUES (?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $paperNo, $comment,$commenterName,$commenterEmail); 
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
	function deleteComment($commentId) 
		{
			$conn = new Database();
			$conn = $conn->connectdb();

			// Prepare Statements
			$sql = "DELETE FROM {$this->table} WHERE commentId = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("s",$commentId);
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