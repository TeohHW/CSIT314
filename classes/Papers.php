<?php
require_once("./db/Database.php");

class Papers {
    private $paperNo;
    private $author;
    private $authorEmail;
	private $otherAuthors;
    private $paperName;
    private $paperContent;
    private $paperStatus;
	private $paperReviewer;
    private $paperRatingsReviewer;
	private $paperRatingsAuthor;
	private $paperComments;
	private $paperBidders;
    private $table = 'papers';

    function __construct($author = NULL, $authorEmail = NULL, $otherAuthors = NULL, $paperName = NULL, $paperContent = NULL, $paperStatus = NULL, $paperReviewer = NULL, $paperRatingsReviewer = NULL, $paperRatingsAuthor = NULL, $paperComments=NULL,$paperBidders = NULL, $paperNo = NULL) 
	{
        $this->author = $author;
        $this->authorEmail = $authorEmail;
		$this->otherAuthors = $otherAuthors;
        $this->paperName = $paperName;
        $this->paperContent = $paperContent;
        $this->paperStatus = $paperStatus;
		$this->paperReviewer = $paperReviewer;
        $this->paperRatingsReviewer = $paperRatingsReviewer;
		$this->paperRatingsAuthor = $paperRatingsAuthor;
		$this->paperComments = $paperComments;
		$this->paperBidders = $paperBidders;
        $this->paperNo = $paperNo;
    }

    // Accessors
    function get_paperNo() 
	{
        return $this->paperNo;
    }

    function get_author() 
	{
        return $this->author;
    }

    function get_authorEmail() 
	{
        return $this->authorEmail;
    }
	 function get_otherAuthors() 
	{
        return $this->otherAuthors;
    }
    function get_paperName() 
	{
        return $this->paperName;
    }
    function get_paperContent() 
	{
        return $this->paperContent;
    }

    function get_paperStatus() 
	{
        return $this->paperStatus;
    }
	function get_paperReviewer() 
	{
        return $this->paperReviewer;
    }
    function get_paperRatingsReviewer() 
	{
        return $this->paperRatingsReviewer;
    }
	 function get_paperRatingsAuthor() 
	{
        return $this->paperRatingsAuthor;
    }

	function get_paperComments() 
	{
        return $this->paperComments;
    }
	function get_paperBidders() 
	{
        return $this->paperBidders;
    }
    // Mutators
    function set_author($author) 
	{
        $this->category = $author;
    }

    function set_authorEmail($authorEmail) 
	{
        $this->authorEmail = $authorEmail;
    }
	 function set_otherAuthors($otherAuthors) 
	{
        $this->otherAuthors = $otherAuthors;
    }
    function set_paperName($paperName) 
	{
        $this->paperName = $paperName;
    }
    function set_paperContent($paperContent) 
	{
        $this->paperContent = $paperContent;
    }
	
    function set_paperStatus($paperStatus) 
	{
        $this->paperStatus = $paperStatus;
    }
	function set_paperReviewer($paperReviewer) 
	{
        $this->paperReviewer = $paperReviewer;
    }
    function set_paperRatingsReviewer($paperRatingsReviewer) 
	{
        $this->paperRatingsReviewer = $paperRatingsReviewer;
    }
	function set_paperRatingsAuthor($paperRatingsAuthor) 
	{
        $this->paperRatingsAuthor = $paperRatingsAuthor;
    }

	function set_paperComments($paperComments) 
	{
		$this->paperComments = $paperComments;
	}
	function set_paperBidders($paperBidders) 
	{
        $this->paperBidders = $paperBidders;
    }

    // List all papers
    function listAllPapers() 
	{
        $paper_list = array();

        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $row_counts = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            $papers = new papers($row['author'], $row['authorEmail'],$row['otherAuthors'], $row['paperName'], $row['paperContent'], $row['paperStatus'],  $row['paperReviewer'], $row['paperRatingsReviewer'],$row['paperRatingsAuthor'],$row['paperComments'],$row['paperBidders'],$row['paperNo']);
            $paper_list[] = $papers;
        }
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) 
		{
            return $paper_list;
        } 
		else {
            return NULL;
        }
    }
	// Find Paper
	function listAllPapersByAuthor($paperAuthor) 
		{
        $paper_list = array();

        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table} WHERE author = ? ";
        $stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $paperAuthor);
        $stmt->execute();
        $result = $stmt->get_result();

        $row_counts = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            $papers = new papers($row['author'], $row['authorEmail'],$row['otherAuthors'], $row['paperName'], $row['paperContent'], $row['paperStatus'],$row['paperReviewer'], $row['paperRatingsReviewer'],$row['paperRatingsAuthor'],$row['paperComments'],$row['paperBidders'],$row['paperNo']);
            $paper_list[] = $papers;
        }
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) 
		{
            return $paper_list;
        } 
		else {
            return NULL;
        }
    }
	 // List all papers
    function listPendingPapers() 
	{
        $paper_list = array();

        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table} WHERE paperStatus = 'Pending' ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $row_counts = $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            $papers = new papers($row['author'], $row['authorEmail'],$row['otherAuthors'], $row['paperName'], $row['paperContent'], $row['paperStatus'], $row['paperReviewer'], $row['paperRatingsReviewer'],$row['paperRatingsAuthor'],$row['paperComments'],$row['paperBidders'],$row['paperNo']);
            $paper_list[] = $papers;
        }
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) 
		{
            return $paper_list;
        } 
		else {
            return NULL;
        }
    }
	 // List all papers
    function listPapersToBid() 
	{
        $paper_list = array();

        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table} WHERE paperStatus = 'To Be Reviewed' ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $row_counts = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            $papers = new papers($row['author'], $row['authorEmail'],$row['otherAuthors'], $row['paperName'], $row['paperContent'], $row['paperStatus'], $row['paperReviewer'], $row['paperRatingsReviewer'],$row['paperRatingsAuthor'],$row['paperComments'],$row['paperBidders'],$row['paperNo']);
            $paper_list[] = $papers;
        }
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) 
		{
            return $paper_list;
        } 
		else {
            return NULL;
        }
    }
	 // List all papers
    function listReviewedPapers() 
	{
        $paper_list = array();

        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table} WHERE paperStatus = 'Review Done' ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $row_counts = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            $papers = new papers($row['author'], $row['authorEmail'],$row['otherAuthors'], $row['paperName'], $row['paperContent'], $row['paperStatus'], $row['paperReviewer'], $row['paperRatingsReviewer'],$row['paperRatingsAuthor'],$row['paperComments'],$row['paperBidders'],$row['paperNo']);
            $paper_list[] = $papers;
        }
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) 
		{
            return $paper_list;
        } 
		else {
            return NULL;
        }
    }
	function listPendingPaperByAuthor($authorName) 
		{
        $paper_list = array();

        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table} WHERE author = ? AND paperStatus = 'Pending' ";
        $stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $authorName);
        $stmt->execute();
        $result = $stmt->get_result();

        $row_counts = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            $papers = new papers($row['author'], $row['authorEmail'],$row['otherAuthors'], $row['paperName'], $row['paperContent'], $row['paperStatus'],$row['paperReviewer'], $row['paperRatingsReviewer'],$row['paperRatingsAuthor'],$row['paperComments'],$row['paperBidders'],$row['paperNo']);
            $paper_list[] = $papers;
        }
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) 
		{
            return $paper_list;
        } 
		else {
            return NULL;
        }
    }
	function listPapersToReview($reviewerMail) 
		{
        $paper_list = array();

        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table} WHERE paperReviewer = ? AND paperStatus = 'Reviewing' ";
        $stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $reviewerMail);
        $stmt->execute();
        $result = $stmt->get_result();

        $row_counts = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            $papers = new papers($row['author'], $row['authorEmail'],$row['otherAuthors'], $row['paperName'], $row['paperContent'], $row['paperStatus'],$row['paperReviewer'], $row['paperRatingsReviewer'],$row['paperRatingsAuthor'],$row['paperComments'],$row['paperBidders'],$row['paperNo']);
            $paper_list[] = $papers;
        }
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) 
		{
            return $paper_list;
        } 
		else {
            return NULL;
        }
    }
	function listPapersToAllocate() 
	{
        $paper_list = array();

        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table} WHERE paperStatus = 'To Be Reviewed' ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $row_counts = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            $papers = new papers($row['author'], $row['authorEmail'],$row['otherAuthors'], $row['paperName'], $row['paperContent'], $row['paperStatus'], $row['paperReviewer'], $row['paperRatingsReviewer'],$row['paperRatingsAuthor'],$row['paperComments'],$row['paperBidders'],$row['paperNo']);
            $paper_list[] = $papers;
        }
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) 
		{
            return $paper_list;
        } 
		else {
            return NULL;
        }
    }
	function listTBRPapers() 
	{
        $paper_list = array();

        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table} WHERE paperStatus = 'To Be Reviewed' ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $row_counts = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            $papers = new papers($row['author'], $row['authorEmail'],$row['otherAuthors'], $row['paperName'], $row['paperContent'], $row['paperStatus'], $row['paperReviewer'], $row['paperRatingsReviewer'],$row['paperRatingsAuthor'],$row['paperComments'],$row['paperBidders'],$row['paperNo']);
            $paper_list[] = $papers;
        }
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) 
		{
            return $paper_list;
        } 
		else {
            return NULL;
        }
    }
	// List Search Results
    function listSearchedPapers($searchedValue) 
	{
        $paper_list = array();
        $tempArr = array();
        $paperName = "";
        foreach ($searchedValue as $key => $value) 
		{
            $paperName .= 's';
            if ($key == "paperContent") {
                $searchedValue[$key] = $value . "%";
            } 
			else {
                $searchedValue[$key] = "%" . $value . "%";
            }
        }
        $tempArr[] = $paperName;

        $parameters = array_merge($tempArr, array_values($searchedValue));

        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "SELECT * FROM {$this->table}";

        if ($searchedValue) 
		{
            $sql .= " WHERE " . implode(" LIKE ? AND ", array_keys($searchedValue));
            $sql .= " LIKE ? ";
        }

        $tmp = array();
        foreach ($parameters as $key => $value) 
		{
            $tmp[$key] = &$parameters[$key];
        }
        $stmt = $conn->prepare($sql);
        call_user_func_array(array($stmt, 'bind_param'), $tmp);
        $stmt->execute();
        $result = $stmt->get_result();

        $row_counts = $result->num_rows;

        while ($row = $result->fetch_assoc()) 
		{
            $papers = new papers($row['author'], $row['authorEmail'],$row['otherAuthors'], $row['paperName'], $row['paperContent'], $row['paperStatus'], $row['paperReviewer'],$row['paperRatingsReviewer'],$row['paperRatingsAuthor'],$row['paperComments'],$row['paperBidders'],$row['paperNo']);
            $paper_list[] = $papers;
        }

        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) {
            return $paper_list;  
        } 
		else {
            return NULL;
        }
    }
	
	// Find Paper
	function findPaper($paperNo) 
		{
        $paper_list = array();

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
            $papers = new papers($row['author'], $row['authorEmail'],$row['otherAuthors'], $row['paperName'], $row['paperContent'], $row['paperStatus'],$row['paperReviewer'], $row['paperRatingsReviewer'],$row['paperRatingsAuthor'],$row['paperComments'],$row['paperBidders'],$row['paperNo']);
            $paper_list[] = $papers;
        }
        // Close Connections
        $stmt->close();
        $conn->close();

        if ($row_counts != 0) 
		{
            return $paper_list;
        } 
		else {
            return NULL;
        }
    }
	
    // Add new paper
    function addPaper(papers $papers) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        if ($conn->connect_error) 
		{
            die("Connection Failed: " . $conn->connect_errno);
        }
		
        // Prepare Statements
        $sql = "INSERT INTO {$this->table} (author, authorEmail,otherAuthors, paperName, paperContent, paperStatus, paperRatingsReviewer,paperRatingsAuthor,paperComments,paperBidders) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $papers->author, $papers->authorEmail,$papers->otherAuthors, $papers->paperName, $papers->paperContent, $papers->paperStatus, $papers->paperRatingsReviewer,$papers->paperRatingsAuthor,$papers->paperComments,$papers->paperBidders);
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
	
    function chgStatus($paperNo, $paperStatus) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "UPDATE {$this->table} SET paperStatus = ? WHERE paperNo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $paperStatus, $paperNo);
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
	/*
	    function updatePaper($paperNo, $paperName, $paperContent, $otherAuthors) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "UPDATE {$this->table} SET paperName = ? , paperContent = ? , otherAuthors = ? WHERE paperNo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $paperNo , $paperName, $paperContent,$otherAuthors);
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
	*/
	 function updateContent($paperNo, $content) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "UPDATE {$this->table} SET paperContent = ? WHERE paperNo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $content, $paperNo);
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
	function updateName($paperNo, $name) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "UPDATE {$this->table} SET paperName = ? WHERE paperNo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $paperNo);
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
	  function updateCoAuthor($paperNo, $coAuthor) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "UPDATE {$this->table} SET otherAuthors = ? WHERE paperNo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $coAuthor, $paperNo);
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
	   function addBidder($paperNo, $paperBidders) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "UPDATE {$this->table} SET paperBidders = ? WHERE paperNo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $paperBidders, $paperNo);
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
	  function reviewPaper($paperNo, $score) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "UPDATE {$this->table} SET paperRatingsReviewer = ? WHERE paperNo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $score, $paperNo);
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
	function reviewPaperAuthor($paperNo, $score) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "UPDATE {$this->table} SET paperRatingsAuthor = ? WHERE paperNo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $score, $paperNo);
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
	 function allocateReviewer($paperNo, $email) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "UPDATE {$this->table} SET paperReviewer = concat_ws(',',paperReviewer,?) WHERE paperNo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $paperNo);
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
	 function clearAllocations($paperNo) 
	{
        $conn = new Database();
        $conn = $conn->connectdb();

        // Prepare Statements
        $sql = "UPDATE {$this->table} SET paperReviewer = NULL WHERE paperNo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $paperNo);
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