<?php 
// This is the index file for the booking folder (all users)
session_start();

// Include functions
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php';

/*
	TO-DO:
	Create booking from code
	Cancel booking from code
		Admin has master code
*/

// Function to validate user inputs
function validateUserInputs($FeedbackSessionToUse){
	// Get user inputs
	$invalidInput = FALSE;
	
	if(isset($_POST['startDateTime']) AND !$invalidInput){
		$startDateTimeString = $_POST['startDateTime'];
	} else {
		$invalidInput = TRUE;
		$_SESSION[$FeedbackSessionToUse] = "A booking cannot be created without submitting a start time.";
	}
	if(isset($_POST['endDateTime']) AND !$invalidInput){
		$endDateTimeString = $_POST['endDateTime'];
	} else {
		$invalidInput = TRUE;
		$_SESSION[$FeedbackSessionToUse] = "A booking cannot be created without submitting an end time.";
	}
	
	if(isset($_POST['displayName'])){
		$displayNameString = $_POST['displayName'];
	} else {
		$displayNameString = '';
	}
	if(isset($_POST['description'])){
		$bookingDescriptionString = $_POST['description'];
	} else {
		$bookingDescriptionString = '';
	}
	
	// Remove excess whitespace and prepare strings for validation
	$validatedStartDateTime = trimExcessWhitespace($startDateTimeString);
	$validatedEndDateTime = trimExcessWhitespace($endDateTimeString);
	$validatedDisplayName = trimExcessWhitespaceButLeaveLinefeed($displayNameString);
	$validatedBookingDescription = trimExcessWhitespaceButLeaveLinefeed($bookingDescriptionString);	
	
	// Do actual input validation
	if(validateDateTimeString($validatedStartDateTime) === FALSE AND !$invalidInput){
		$invalidInput = TRUE;
		$_SESSION[$FeedbackSessionToUse] = "Your submitted start time has illegal characters in it.";
	}
	if(validateDateTimeString($validatedEndDateTime) === FALSE AND !$invalidInput){
		$invalidInput = TRUE;
		$_SESSION[$FeedbackSessionToUse] = "Your submitted end time has illegal characters in it.";
	}
	if(validateString($validatedDisplayName) === FALSE AND !$invalidInput){
		$invalidInput = TRUE;
		$_SESSION[$FeedbackSessionToUse] = "Your submitted display name has illegal characters in it.";
	}
	if(validateString($validatedBookingDescription) === FALSE AND !$invalidInput){
		$invalidInput = TRUE;
		$_SESSION[$FeedbackSessionToUse] = "Your submitted booking description has illegal characters in it.";
	}
	
	// Are values actually filled in?
	if($validatedStartDateTime == "" AND $validatedEndDateTime == "" AND !$invalidInput){
		
		$_SESSION[$FeedbackSessionToUse] = "You need to fill in a start and end time for your booking.";	
		$invalidInput = TRUE;
	} elseif($validatedStartDateTime != "" AND $validatedEndDateTime == "" AND !$invalidInput) {
		$_SESSION[$FeedbackSessionToUse] = "You need to fill in an end time for your booking.";	
		$invalidInput = TRUE;		
	} elseif($validatedStartDateTime == "" AND $validatedEndDateTime != "" AND !$invalidInput){
		$_SESSION[$FeedbackSessionToUse] = "You need to fill in a start time for your booking.";	
		$invalidInput = TRUE;		
	}
	
	// Check if input length is allowed
		// DisplayName
	$invalidDisplayName = isLengthInvalidDisplayName($validatedDisplayName);
	if($invalidDisplayName AND !$invalidInput){
		$_SESSION[$FeedbackSessionToUse] = "The display name submitted is too long.";	
		$invalidInput = TRUE;		
	}	
		// BookingDescription
	$invalidBookingDescription = isLengthInvalidBookingDescription($validatedBookingDescription);
	if($invalidBookingDescription AND !$invalidInput){
		$_SESSION[$FeedbackSessionToUse] = "The booking description submitted is too long.";	
		$invalidInput = TRUE;		
	}
	
	// Check if the dateTime inputs we received are actually datetimes
	$startDateTime = correctDatetimeFormat($validatedStartDateTime);
	$endDateTime = correctDatetimeFormat($validatedEndDateTime);

	if (isset($startDateTime) AND $startDateTime === FALSE AND !$invalidInput){
		$_SESSION[$FeedbackSessionToUse] = "The start date you submitted did not have a correct format. Please try again.";
		$invalidInput = TRUE;
	}
	if (isset($endDateTime) AND $endDateTime === FALSE AND !$invalidInput){
		$_SESSION[$FeedbackSessionToUse] = "The end date you submitted did not have a correct format. Please try again.";
		$invalidInput = TRUE;
	}	
	
	$timeNow = getDatetimeNow();
	
	if($startDateTime > $endDateTime AND !$invalidInput){
		// End time can't be before the start time
		
		$_SESSION[$FeedbackSessionToUse] = "The start time can't be later than the end time. Please select a new start time or end time.";
		$invalidInput = TRUE;
	}
	
	if($startDateTime < $timeNow AND !$invalidInput){
		// You can't book a meeting starting in the past.
		
		$_SESSION[$FeedbackSessionToUse] = "The start time you selected is already over. Select a new start time.";
		$invalidInput = TRUE;
	}
	
	if($endDateTime < $timeNow AND !$invalidInput){
		// You can't book a meeting ending in the past.
		
		$_SESSION[$FeedbackSessionToUse] = "The end time you selected is already over. Select a new end time.";
		$invalidInput = TRUE;	
	}	
	
	if($endDateTime == $startDateTime AND !$invalidInput){
		$_SESSION[$FeedbackSessionToUse] = "You need to select an end time that is different from your start time.";	
		$invalidInput = TRUE;				
	} 

	//TO-DO: If we want to check if a booking is long enough, we do it here e.g. has to be longer than 10 min
	/*
	$timeDifferenceStartDate = new DateTime($startDateTime);
	$timeDifferenceEndDate = new DateTime($endDateTime);
	$timeDifference = $timeDifferenceStartDate->diff($timeDifferenceEndDate);
	$timeDifferenceInMinutes = $timeDifference->i;
	if(($timeDifferenceInMinutes < MINIMUM_BOOKING_TIME_IN_MINUTES) AND !$invalidInput){
		$_SESSION[$FeedbackSessionToUse] = "A meeting needs to be at least " . MINIMUM_BOOKING_TIME_IN_MINUTES . " minutes long.";
		$invalidInput = TRUE;	
	}*/
	
	return array($invalidInput, $startDateTime, $endDateTime, $validatedBookingDescription, $validatedDisplayName);
}

// Function to remember the user inputs in Create Meeting
function rememberCreateBookingInputs(){
	if(isset($_SESSION['CreateMeetingInfoArray'])){
		$newValues = $_SESSION['CreateMeetingInfoArray'];

			// The meeting room selected
		$newValues['TheMeetingRoomID'] = $_POST['meetingRoomID']; 
			// The company selected
		$newValues['TheCompanyID'] = $_POST['companyID'];
			// The user selected
		$newValues['BookedBy'] = trimExcessWhitespace($_POST['displayName']);
			// The booking description
		$newValues['BookingDescription'] = trimExcessWhitespaceButLeaveLinefeed($_POST['description']);
			// The start time
		$newValues['StartTime'] = trimExcessWhitespace($_POST['startDateTime']);
			// The end time
		$newValues['EndTime'] = trimExcessWhitespace($_POST['endDateTime']);
		
		$_SESSION['CreateMeetingInfoArray'] = $newValues;			
	}
}

// Function to remove locally set device information
function resetLocalDevice(){
	deleteMeetingRoomCookies();
	unset($_SESSION['DefaultMeetingRoomInfo']);
	unset($_SESSION['OriginalCookieMeetingRoomName']);
	unset($_SESSION['OriginalCookieMeetingRoomIDCode']);
	// TO-DO: Do anything more here to punish cookie manipulation?	
}

// Check if we're accessing from a local device
// If so, set that meeting room's info as the default meeting room info
if(isset($_COOKIE[MEETINGROOM_NAME]) AND isset($_COOKIE[MEETINGROOM_IDCODE]))
{
	// There are local meeting room identifiers set in cookies. Check if they are valid
	$meetingRoomName = $_COOKIE[MEETINGROOM_NAME];
	$meetingRoomIDCode = $_COOKIE[MEETINGROOM_IDCODE];
	
	if(!isset($_SESSION['OriginalCookieMeetingRoomName']) AND !isset($_SESSION['OriginalCookieMeetingRoomIDCode'])){
		$validMeetingRoom = databaseContainsMeetingRoomWithIDCode($meetingRoomName, $meetingRoomIDCode);
		if ($validMeetingRoom === TRUE){
			// Cookies are correctly identifying a meeting room
			// Hopefully this means it's a local device we set up and not someone malicious
			$_SESSION['OriginalCookieMeetingRoomName'] = $meetingRoomName;
			$_SESSION['OriginalCookieMeetingRoomIDCode'] = $meetingRoomIDCode;
			
			if(!isset($_SESSION['DefaultMeetingRoomInfo'])){
				try
				{
					include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
					
					$pdo = connect_to_db();
					$sql = "SELECT 	`meetingRoomID`							AS TheMeetingRoomID, 
									`name` 									AS TheMeetingRoomName,
									`capacity`								AS TheMeetingRoomCapacity,
									`description`							AS TheMeetingRoomDescription,
									`location`								AS TheMeetingRoomLocation
							FROM	`meetingroom`
							WHERE 	`name` = :meetingRoomName
							LIMIT 	1";
					$s = $pdo->prepare($sql);
					$s->bindValue(':meetingRoomName', $_COOKIE[MEETINGROOM_NAME]);
					$s->execute();
					$row = $s->fetch();
					$_SESSION['DefaultMeetingRoomInfo'] = $row;
					//Close the connection
					$pdo = null;
				}
				catch(PDOException $e)
				{
					$error = 'Error getting meeting room info: ' . $e->getMessage();
					include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
					$pdo = null;
					exit();
				}			
			}
		} elseif($validMeetingRoom === FALSE){
			// The cookies set does not match a meeting room i.e. someone manually changed a cookie
			resetLocalDevice();
		}	
	}
	if(	$_COOKIE[MEETINGROOM_NAME] != $_SESSION['OriginalCookieMeetingRoomName'] OR 
		$_COOKIE[MEETINGROOM_IDCODE] != $_SESSION['OriginalCookieMeetingRoomIDCode']){
			// Cookies have changed
			unset($_SESSION['OriginalCookieMeetingRoomName']);
			unset($_SESSION['OriginalCookieMeetingRoomIDCode']);
			unset($_SESSION['DefaultMeetingRoomInfo']);
		}
} else {
	unset($_SESSION['OriginalCookieMeetingRoomName']);
	unset($_SESSION['OriginalCookieMeetingRoomIDCode']);
	unset($_SESSION['DefaultMeetingRoomInfo']);
}

// Handles booking based on selected meeting room
if(	(isset($_POST['action']) AND $_POST['action'] == 'Create Meeting') OR
	(isset($_SESSION['refreshCreateMeeting']) AND $_SESSION['refreshCreateMeeting']))
{
	// Only a logged in user can create a meeting
	// or if we're on a local device that can take a booking code
	
	if(isset($_SESSION['DefaultMeetingRoomInfo'])){
		// We're accessing a local device.
		// Confirm with booking code
		
		// 
		
		include_once 'confirm.html.php';
	}
	
	// We're not making a booking locally. Make users be logged in
	if(makeUserLogIn() === TRUE){
		// We're logged in and can create the booking
		include_once 'confirm.html.php';
	}
}
//getUserInfoFromBookingCode();
//
if(isset($_POST['action']) AND $_POST['action'] == 'Confirm Meeting'){
	list($invalidInput, $startDateTime, $endDateTime, $validatedBookingDescription, $validatedDisplayName) = validateUserInputs('MeetingRoomAllUsersFeedback');
	
	if(isset($_GET['meetingroom'])){
		$meetingRoomID = $_GET['meetingroom'];
		$location = "http://$_SERVER[HTTP_HOST]/booking/?meetingroom=" . $meetingRoomID;
	} else {
		$meetingRoomID = $_POST['MeetingRoomID']; // TO-DO: Not set
		$location = '.';
	}
	
	if($invalidInput){
		
		rememberCreateBookingInputs();
		$_SESSION['refreshCreateMeeting'] = TRUE;
		
		header('Location: $location');
		exit();			
	}	
}


// Cancels a booking from a submitted cancellation link
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
						`endDateTime`,
						`actualEndDateTime`
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
	$startDateTimeString = $result['startDateTime'];
	$endDateTimeString = $result['endDateTime'];
	$actualEndDateTimeString = $result['actualEndDateTime'];
	
	$startDateTime = stringToDateTime($startDateTime);
	$endDateTime = stringToDateTime($endDateTime);
	
	$displayValidatedStartDate = convertDatetimeToFormat($startDateTimeString , 'Y-m-d H:i:s', DATETIME_DEFAULT_FORMAT_TO_DISPLAY);
	$displayValidatedEndDate = convertDatetimeToFormat($endDateTimeString, 'Y-m-d H:i:s', DATETIME_DEFAULT_FORMAT_TO_DISPLAY);	
	
	// Check if the meeting has already ended
	if($actualEndDateTimeString != "" AND $actualEndDateTimeString != NULL){
		// Meeting has not ended already.
		// Check if we're cancelling the booking or simply ending the booking early!
		$timeNow = getDatetimeNow();
		if($timeNow > $startDateTime AND $timeNow < $endDateTime) {
			// The booking is already active, so we're ending it early
			$sql = "UPDATE 	`booking`
					SET		`dateTimeCancelled` = CURRENT_TIMESTAMP,
							`actualEndDateTime` = CURRENT_TIMESTAMP,
							`cancellationCode` = NULL
					WHERE 	`bookingID` = :bookingID";
			$bookingFeedback = 	"The booking for " . $TheMeetingRoomName . ". Starting at: " . $displayValidatedStartDate . 
								" and ending at: " . $displayValidatedEndDate . " has been ended early by using the cancellation link.";
			$logEventDescription = $bookingFeedback;
		} elseif($timeNow < $startDateTime) {
			// The booking hasn't started yet, so we're actually cancelling the meeting
			$sql = "UPDATE 	`booking`
					SET		`dateTimeCancelled` = CURRENT_TIMESTAMP,
							`cancellationCode` = NULL
					WHERE 	`bookingID` = :bookingID";	
			$bookingFeedback = 	"The booking for " . $TheMeetingRoomName . ". Starting at: " . $displayValidatedStartDate . 
								" and ending at: " . $displayValidatedEndDate . " has been cancelled by using the cancellation link.";
			$logEventDescription = $bookingFeedback;								
		} elseif($timeNow > $endDateTime) {
			// The booking has (in theory) already ended, so there shouldn't be an active cancellation code for it
			// We just have to assume the booking failed to update itself on completion
			$sql = "UPDATE 	`booking`
					SET		`actualEndDateTime` = `endDateTime`
							`cancellationCode` = NULL
					WHERE 	`bookingID` = :bookingID";
			$bookingFeedback = 		"The booked meeting has already ended.";
			$logEventDescription = 	"The booking for " . $TheMeetingRoomName . ". Starting at: " . $displayValidatedStartDate . 
									" and ending at: " . $displayValidatedEndDate . " was attempted to be cancelled with the " . 
									"cancellation link, but the meeting should have already been completed." .
									" The end date of the booking has been updated to have occured on the scheduled time.";			
		}	
	} else {
		// Meeting has already ended. So there's no reason to cancel it.
		$bookingFeedback = 	"The booked meeting has already ended.";
		$sql = "UPDATE 	`booking`
				SET		`cancellationCode` = NULL
				WHERE 	`bookingID` = :bookingID";
		$bookingFeedback = 		"The booked meeting has already ended.";
		$logEventDescription = 	"The booking for " . $TheMeetingRoomName . ". Starting at: " . $displayValidatedStartDate . 
								" and ending at: " . $displayValidatedEndDate . " was attempted to be cancelled with the " . 
								"cancellation link, but the meeting had already ended so it had no effect.";
	}
	
	// Update the booked meeting
	try
	{
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
		
		$pdo = connect_to_db();
		$s = $pdo->prepare($sql);
		$s->bindValue(':bookingID', $bookingID);
		$s->execute();
		
		//Close the connection
		$pdo = null;
	}
	catch(PDOException $e)
	{
		$error = 'Error updating booking: ' . $e->getMessage();
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
		$pdo = null;
		exit();
	}	
	
	$_SESSION['normalBookingFeedback'] = $bookingFeedback;
										
	// Add a log event about the updated booking
	try
	{
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
		
		$pdo = connect_to_db();
		$sql = "INSERT INTO `logevent`
				SET			`actionID` = 	(
												SELECT `actionID` 
												FROM `logaction`
												WHERE `name` = 'Booking Cancelled'
											),
							`description` = :description,
							`bookingID` = :bookingID";
		$s = $pdo->prepare($sql);
		$s->bindValue(':description', $logEventDescription);
		$s->bindValue(':bookingID', $bookingID);
		$s->execute();
		
		//Close the connection
		$pdo = null;		
	}
	catch(PDOException $e)
	{
		$error = 'Error adding log event to database: ' . $e->getMessage();
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
		$pdo = null;
		exit();
	}
}

// TO-DO: Get booking default values from admin/booking


// Load the html template
include_once 'booking.html.php';
?>