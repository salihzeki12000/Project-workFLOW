<?php 
// This is the index file for the booking folder (all users)
session_start();

// Include functions
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php';

// Function to cancel a booking from cancellation link
if(isset($_GET['cancellationcode'])){
	
	$cancellationCode = $_GET['cancellationcode'];
		
	// Check if code is correct (64 chars)
	if(strlen($cancellationCode)!=64){
		$_SESSION['normalBookingFeedback'] = "The cancellation code that was submitted is not a valid code.";
		header("Location: .");
		exit();
	}
		
	//	Check if the submitted code is in the database
	try
	{
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
		
		$pdo = connect_to_db();
		$sql = "SELECT 	`bookingID`,
						`meetingRoomID`									AS TheMeetingRoomID, 
						(
							SELECT	`name`
							FROM	`meetingroom`
							WHERE	`meetingRoomID` = TheMeetingRoomID 
						)												AS TheMeetingRoomName,
						`startDateTime`,
						`endDateTime`
				FROM	`booking`
				WHERE 	`cancellationCode` = :cancellationCode
				AND		`dateTimeCancelled` IS NULL
				LIMIT 	1";
		$s = $pdo->prepare($sql);
		$s->bindValue(':cancellationCode', $cancellationCode);
		$s->execute();
		
		//Close the connection
		$pdo = null;
	}
	catch(PDOException $e)
	{
		$error = 'Error validating cancellation code: ' . $e->getMessage();
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
		$pdo = null;
		exit();
	}
	
	// Check if the select even found something
	$rowCount = $s->rowCount();
	if($rowCount == 0){
		// No match.
		$_SESSION['normalBookingFeedback'] = "The cancellation code that was submitted is not a valid code.";
		header("Location: .");
		exit();
	}

	$result = $s->fetch();
	
	$bookingID = $result['bookingID'];
	$TheMeetingRoomName = $result['TheMeetingRoomName'];
	$startDateTime = $result['startDateTime'];
	$endDateTime = $result['endDateTime'];
	
	try
	{
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
		
		$pdo = connect_to_db();
		$sql = "UPDATE 	`booking`
				SET		`dateTimeCancelled` = CURRENT_TIMESTAMP,
						`cancellationCode` = NULL
				WHERE 	`bookingID` = :bookingID";
		$s = $pdo->prepare($sql);
		$s->bindValue(':bookingID', $bookingID);
		$s->execute();
		
		//Close the connection
		$pdo = null;
	}
	catch(PDOException $e)
	{
		$error = 'Error cancelling booking: ' . $e->getMessage();
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
		$pdo = null;
		exit();
	}	
	
	$_SESSION['normalBookingFeedback'] = "The booking for " . $TheMeetingRoomName . ". Starting at: " . $startDateTime . 
										" and ending at: " . $endDateTime . " has been cancelled!";
}




// Load the html template
include_once 'booking.html.php';
?>