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
var_dump($_SESSION); // TO-DO: remove after testing is done

// Function to clear sessions used to remember user inputs on refreshing the add booking form
function clearAddCreateBookingSessions(){
	unset($_SESSION['AddCreateBookingInfoArray']);
	unset($_SESSION['AddCreateBookingChangeUser']);
	unset($_SESSION['AddCreateBookingUsersArray']);
	unset($_SESSION['AddCreateBookingOriginalInfoArray']);
	unset($_SESSION['AddCreateBookingMeetingRoomsArray']);	
	unset($_SESSION['AddCreateBookingUserSearch']);
	unset($_SESSION['AddCreateBookingSelectedNewUser']);
	unset($_SESSION['AddCreateBookingSelectedACompany']);
	unset($_SESSION['AddCreateBookingDefaultDisplayNameForNewUser']);
	unset($_SESSION['AddCreateBookingDefaultBookingDescriptionForNewUser']);		
}

// Function to clear sessions used to remember user inputs on refreshing the edit booking form
function clearEditCreateBookingSessions(){
	unset($_SESSION['EditCreateBookingInfoArray']);
	unset($_SESSION['EditCreateBookingChangeUser']);
	unset($_SESSION['EditCreateBookingUsersArray']);
	unset($_SESSION['EditCreateBookingOriginalInfoArray']);
	unset($_SESSION['EditCreateBookingMeetingRoomsArray']);	
	unset($_SESSION['EditCreateBookingUserSearch']);
	unset($_SESSION['EditCreateBookingSelectedNewUser']);
	unset($_SESSION['EditCreateBookingSelectedACompany']);
	unset($_SESSION['EditCreateBookingDefaultDisplayNameForNewUser']);
	unset($_SESSION['EditCreateBookingDefaultBookingDescriptionForNewUser']);	
}

// Function to remember the user inputs in Edit Booking
function rememberEditCreateBookingInputs(){
	if(isset($_SESSION['EditCreateBookingInfoArray'])){
		$newValues = $_SESSION['EditCreateBookingInfoArray'];

			// The user selected, if the booking is for another user
		if(isset($_POST['userID'])){
			$newValues['TheUserID'] = $_POST['userID'];
		}
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
		
		$_SESSION['EditCreateBookingInfoArray'] = $newValues;			
	}
}

// Function to remember the user inputs in Add Booking
function rememberAddCreateBookingInputs(){
	if(isset($_SESSION['AddCreateBookingInfoArray'])){
		$newValues = $_SESSION['AddCreateBookingInfoArray'];

			// The user selected, if the booking is for another user
		if(isset($_POST['userID'])){
			$newValues['TheUserID'] = $_POST['userID'];
		}
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
		
		$_SESSION['AddCreateBookingInfoArray'] = $newValues;			
	}
}


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
	
	if(isset($_POST['bookingCode'])){
		$bookingCode = $_POST['bookingCode'];
	} else {
		$invalidInput = TRUE;
		$_SESSION[$FeedbackSessionToUse] = "A booking cannot be created without submitting a booking code.";		
	}
	
	// Remove excess whitespace and prepare strings for validation
	$validatedStartDateTime = trimExcessWhitespace($startDateTimeString);
	$validatedEndDateTime = trimExcessWhitespace($endDateTimeString);
	$validatedDisplayName = trimExcessWhitespaceButLeaveLinefeed($displayNameString);
	$validatedBookingDescription = trimExcessWhitespaceButLeaveLinefeed($bookingDescriptionString);
	$validatedBookingCode = trimExcessWhitespace($bookingCode);
	
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
	if(validateIntegerNumber($validatedBookingCode) === FALSE AND !$invalidInput){
		$invalidInput = TRUE;
		$_SESSION[$FeedbackSessionToUse] = "Your submitted booking code has illegal characters in it.";		
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
	if(isset($validatedBookingCode) AND $validatedBookingCode == "" AND !$invalidInput){
		$_SESSION[$FeedbackSessionToUse] = "You need to fill in a booking code to be able to create the booking.";	
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

	// Check if booking code submitted is a valid booking code
	if(databaseContainsBookingCode($validatedBookingCode) === FALSE AND !$invalidInput){
		$_SESSION[$FeedbackSessionToUse] = "The booking code you submitted is not valid.";	
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
	
	return array($invalidInput, $startDateTime, $endDateTime, $validatedBookingDescription, $validatedDisplayName, $validatedBookingCode);
}

// Check if we're accessing from a local device
// If so, set that meeting room's info as the default meeting room info
checkIfLocalDevice();


// Handles booking based on selected meeting room
if(	(isset($_POST['action']) AND $_POST['action'] == 'Create Meeting') OR
	(isset($_SESSION['refreshAddCreateBooking']) AND $_SESSION['refreshAddCreateBooking']))
{
	// Make sure user is logged in before going further
		// If local, use booking code
	if(isset($_SESSION['DefaultMeetingRoomInfo'])){
		// We're accessing a local device.
		// Confirm with booking code
		// TO-DO:
		// 
		
		include_once 'confirm.html.php';
		exit();
	}
		// If not local, use regular log in
	if(checkIfUserIsLoggedIn() === FALSE){
		makeUserLogIn();
		exit();
	}
		
	// We're logged in and can create the meeting
	if(isset($_SESSION['refreshAddCreateBooking']) AND $_SESSION['refreshAddCreateBooking']){
		// TO-DO: get old values on refresh
	} else {
			// Get information from database on booking information user can choose between
			if(!isset($_SESSION['AddCreateBookingMeetingRoomsArray'])){
				try
				{
					include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
					
					// Get booking information
					$pdo = connect_to_db();
					// Get name and IDs for meeting rooms
					$sql = 'SELECT 	`meetingRoomID`,
									`name` 
							FROM 	`meetingroom`';
					$result = $pdo->query($sql);
						
					//Close connection
					$pdo = null;
					
					// Get the rows of information from the query
					// This will be used to create a dropdown list in HTML
					foreach($result as $row){
						$meetingroom[] = array(
											'meetingRoomID' => $row['meetingRoomID'],
											'meetingRoomName' => $row['name']
											);
					}		
					
					$_SESSION['AddCreateBookingMeetingRoomsArray'] = $meetingroom;
				}
				catch (PDOException $e)
				{
					$error = 'Error fetching meeting room details: ' . $e->getMessage();
					include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
					$pdo = null;
					exit();		
				}
			}
		
			if(!isset($_SESSION['AddCreateBookingInfoArray'])){
				try
				{
					include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
					
					// Get the logged in user's default booking information
					$pdo = connect_to_db();
					$sql = 'SELECT	`bookingdescription`, 
									`displayname`,
									`firstName`,
									`lastName`,
									`email`
							FROM 	`user`
							WHERE 	`userID` = :userID
							LIMIT 	1';
						
					$s = $pdo->prepare($sql);
					$s->bindValue(':userID', $_SESSION['LoggedInUserID']);
					$s->execute();
					
					// Create an array with the row information we retrieved
					$result = $s->fetch();
						
					// Set default booking display name and booking description
					if($result['displayname']!=NULL){
						$displayName = $result['displayname'];
					}

					if($result['bookingdescription']!=NULL){
						$description = $result['bookingdescription'];
					}
					
					if($result['firstName']!=NULL){
						$firstname = $result['firstName'];
					}		
					
					if($result['lastName']!=NULL){
						$lastname = $result['lastName'];
					}	
					
					if($result['email']!=NULL){
						$email = $result['email'];
					}					
		
					//Close connection
					$pdo = null;
				}
				catch (PDOException $e)
				{
					$error = 'Error fetching default user details: ' . $e->getMessage();
					include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
					$pdo = null;
					exit();		
				}	
			
				// Create an array with the row information we want to use	
				$_SESSION['AddCreateBookingInfoArray'][] = array(
															'TheCompanyID' => '',
															'TheMeetingRoomID' => '',
															'StartTime' => '',
															'EndTime' => '',
															'BookingDescription' => '',
															'BookedBy' => '',
															'BookedForCompany' => '',
															'TheUserID' => '',
															'UserFirstname' => '',
															'UserLastname' => '',
															'UserEmail' => '',
															'UserDefaultDisplayName' => '',
															'UserDefaultBookingDescription' => ''
														);			
				$_SESSION['AddCreateBookingInfoArray']['UserDefaultBookingDescription'] = $description;
				$_SESSION['AddCreateBookingInfoArray']['UserDefaultDisplayName'] = $displayName;
				$_SESSION['AddCreateBookingInfoArray']['UserFirstname'] = $firstname;	
				$_SESSION['AddCreateBookingInfoArray']['UserLastname'] = $lastname;	
				$_SESSION['AddCreateBookingInfoArray']['UserEmail'] = $email;	
				$_SESSION['AddCreateBookingInfoArray']['TheUserID'] = $_SESSION['LoggedInUserID'];
				
				$_SESSION['AddCreateBookingOriginalInfoArray'] = $_SESSION['AddCreateBookingInfoArray'];
			}
			
			// Set the correct information on form call
			$SelectedUserID = $_SESSION['CreateBookingInfoArray']['TheUserID'];
	}

		// Check if we need a company select for the booking
	try
	{		
		// We want the companies the user works for to decide if we need to
		// have a dropdown select or just a fixed value (with 0 or 1 company)
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
		$pdo = connect_to_db();
		$sql = 'SELECT	c.`companyID`,
						c.`name` 					AS companyName
				FROM 	`user` u
				JOIN 	`employee` e
				ON 		e.userID = u.userID
				JOIN	`company` c
				ON 		c.companyID = e.companyID
				WHERE 	u.`userID` = :userID';
			
		$s = $pdo->prepare($sql);
		$s->bindValue(':userID', $SelectedUserID);
		$s->execute();
		
		// Create an array with the row information we retrieved
		$result = $s->fetchAll();
			
		foreach($result as $row){		
			// Get the companies the user works for
			// This will be used to create a dropdown list in HTML
			$company[] = array(
								'companyID' => $row['companyID'],
								'companyName' => $row['companyName']
								);
		}
			
		$pdo = null;
				
		// We only need to allow the user a company dropdown selector if they
		// are connected to more than 1 company.
		// If not we just store the companyID in a hidden form field
		if(isset($company)){
			if (sizeOf($company)>1){
				// User is in multiple companies
				
				$_SESSION['AddCreateBookingDisplayCompanySelect'] = TRUE;
			} elseif(sizeOf($company) == 1) {
				// User is in ONE company
				
				$_SESSION['AddCreateBookingSelectedACompany'] = TRUE;
				unset($_SESSION['AddCreateBookingDisplayCompanySelect']);
				$_SESSION['AddCreateBookingInfoArray']['TheCompanyID'] = $company[0]['companyID'];
				$_SESSION['AddCreateBookingInfoArray']['BookedForCompany'] = $company[0]['companyName'];
			}
		} else{
			// User is NOT in a company
			
			$_SESSION['AddCreateBookingSelectedACompany'] = TRUE;
			unset($_SESSION['AddCreateBookingDisplayCompanySelect']);
			$_SESSION['AddCreateBookingInfoArray']['TheCompanyID'] = "";
			$_SESSION['AddCreateBookingInfoArray']['BookedForCompany'] = "";
		}		
	}
	catch(PDOException $e)
	{
		$error = 'Error fetching user details: ' . $e->getMessage();
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
		$pdo = null;
		exit();					
	}
	
	// Set the correct information
	$row = $_SESSION['AddCreateBookingInfoArray'];
	$original = $_SESSION['AddCreateBookingOriginalInfoArray'];

		// Altered inputs
	if(isset($row['TheCompanyID'])){
		
			// Changed company?
		if(isset($company)){
			foreach($company AS $cmp){
				if($cmp['companyID'] == $row['TheCompanyID']){
					$row['BookedForCompany'] = $cmp['companyName'];
					break;
				}
			}				
		}
		
		$selectedCompanyID = $row['TheCompanyID'];
		$companyID = $row['TheCompanyID'];
	} else {
		$selectedCompanyID = '';
		$companyID = '';	
	}

	if(isset($row['BookedForCompany'])){
		$companyName = $row['BookedForCompany'];
	} else {
		$companyName = '';
	}	
	
	//	userID has been set earlier
	$meetingroom = $_SESSION['AddCreateBookingMeetingRoomsArray'];
	if(isset($row['TheMeetingRoomID'])){
		$selectedMeetingRoomID = $row['TheMeetingRoomID'];
	} else {
		$selectedMeetingRoomID = '';
	}
	if(isset($_GET['meetingroom'])){
		$selectedMeetingRoomID = $_GET['meetingroom'];
	}
	if(isset($row['StartTime'])){
		$startDateTime = $row['StartTime'];
	} else {
		$startDateTime = getDatetimeNow();
		$startDateTime = convertDatetimeToFormat($startDateTime , 'Y-m-d H:i:s', DATETIME_DEFAULT_FORMAT_TO_DISPLAY);
	}
	
	if(isset($row['EndTime'])){
		$endDateTime = $row['EndTime'];
	} else {
		$endDateTime = getDatetimeNow();
		$endDateTime = convertDatetimeToFormat($endDateTime , 'Y-m-d H:i:s', DATETIME_DEFAULT_FORMAT_TO_DISPLAY);
	}
	
	if(isset($row['BookedBy'])){
		$displayName = $row['BookedBy'];
	} else {
		$displayName = '';
	}
	
	if(isset($row['BookingDescription'])){
		$description = $row['BookingDescription'];
	} else {
		$description = '';
	}
	
	$userInformation = $row['UserLastname'] . ', ' . $row['UserFirstname'] . ' - ' . $row['UserEmail'];	

	$_SESSION['AddCreateBookingInfoArray'] = $row; // Remember the company/user info we changed based on user choice		
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
		$_SESSION['refreshAddCreateBooking'] = TRUE;
		
		header('Location: $location');
		exit();			
	}	
}

// When the user has added the needed information and wants to add the booking
if (isset($_POST['add']) AND $_POST['add'] == "Add booking")
{
	// Validate user inputs
	list($invalidInput, $startDateTime, $endDateTime, $bknDscrptn, $dspname, $bookingCode) = validateUserInputs('AddCreateBookingError');
					
	// handle feedback process on invalid input values
	if($invalidInput){
		
		rememberAddCreateBookingInputs();
		// Refresh.
		$_SESSION['refreshAddCreateBooking'] = TRUE;	

		header('Location: .');
		exit();			
	}
	
	// Check if the timeslot is taken for the selected meeting room
	try
	{
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
		$pdo = connect_to_db();
		$sql =	" 	SELECT 	COUNT(*)
					FROM 	`booking`
					WHERE 	`meetingRoomID` = :MeetingRoomID
					AND		
					(		
							(
								`startDateTime` 
								BETWEEN :StartTime
								AND :EndTime
							) 
					OR 		(
								`endDateTime`
								BETWEEN :StartTime
								AND :EndTime
							)
					OR 		(
								:EndTime
								BETWEEN `startDateTime`
								AND `endDateTime`
							)
					OR 		(
								:StartTime
								BETWEEN `startDateTime`
								AND `endDateTime`
							)
					)";
		$s = $pdo->prepare($sql);
		
		$s->bindValue(':MeetingRoomID', $_POST['meetingRoomID']);
		$s->bindValue(':StartTime', $startDateTime);
		$s->bindValue(':EndTime', $endDateTime);
		$s->execute();
		$pdo = null;
	}
	catch(PDOException $e)
	{
		$error = 'Error checking if booking time is available: ' . $e->getMessage();
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
		$pdo = null;
		exit();
	}

	// Check if we got any hits, if so the timeslot is already taken
	$row = $s->fetch();		
	if ($row[0] > 0){

		// Timeslot was taken
		rememberAddCreateBookingInputs();
		
		$_SESSION['AddCreateBookingError'] = "The booking couldn't be made. The timeslot is already taken for this meeting room.";
		$_SESSION['refreshAddCreateBooking'] = TRUE;	

		header('Location: .');
		exit();				
	}
	
	// Add the booking to the database
	try
	{	
		if(	isset($_POST['companyID']) AND $_POST['companyID'] != NULL AND 
			$_POST['companyID'] != ''){
			$companyID = $_POST['companyID'];
		} else {
			$companyID = NULL;
		}
	
		//Generate cancellation code
		$cancellationCode = generateCancellationCode();
		
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
		
		$pdo = connect_to_db();
		$sql = 'INSERT INTO `booking` 
				SET			`meetingRoomID` = :meetingRoomID,
							`userID` = :userID,
							`companyID` = :companyID,
							`displayName` = :displayName,
							`startDateTime` = :startDateTime,
							`endDateTime` = :endDateTime,
							`description` = :description,
							`cancellationCode` = :cancellationCode';

		$s = $pdo->prepare($sql);
		
		$s->bindValue(':meetingRoomID', $_POST['meetingRoomID']);
		$s->bindValue(':userID', $_POST['userID']);
		$s->bindValue(':companyID', $companyID);
		$s->bindValue(':displayName', $dspname);
		$s->bindValue(':startDateTime', $startDateTime);
		$s->bindValue(':endDateTime', $endDateTime);
		$s->bindValue(':description', $bknDscrptn);
		$s->bindValue(':cancellationCode', $cancellationCode);
		$s->execute();

		unset($_SESSION['lastBookingID']);
		$_SESSION['lastBookingID'] = $pdo->lastInsertId();
		
		//Close the connection
		$pdo = null;
	}
	catch (PDOException $e)
	{
		$error = 'Error adding submitted booking to database: ' . $e->getMessage();
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
		$pdo = null;
		exit();
	}
	
	$_SESSION['normalBookingUserFeedback'] .= "Successfully created the booking.";
	
	$displayValidatedStartDate = convertDatetimeToFormat($startDateTime , 'Y-m-d H:i:s', DATETIME_DEFAULT_FORMAT_TO_DISPLAY);
	$displayValidatedEndDate = convertDatetimeToFormat($endDateTime, 'Y-m-d H:i:s', DATETIME_DEFAULT_FORMAT_TO_DISPLAY);
	
	// Add a log event that a booking has been created
	try
	{
		// Get meeting room name
		$MeetingRoomName = 'N/A';
		foreach ($_SESSION['AddCreateBookingMeetingRoomsArray'] AS $room){
			if($room['meetingRoomID'] == $_POST['meetingRoomID']){
				$MeetingRoomName = $room['meetingRoomName'];
				break;
			}
		}
		unset($_SESSION['AddCreateBookingMeetingRoomsArray']);
		
		$meetinginfo = $MeetingRoomName . ' for the timeslot: ' . 
		$displayValidatedStartDate . ' to ' . $displayValidatedEndDate;
		
		// Get user information
		$userinfo = 'N/A';
		$info = $_SESSION['AddCreateBookingInfoArray']; 
		if(isset($info['UserLastname'])){
			$userinfo = $info['UserLastname'] . ', ' . $info['UserFirstname'] . ' - ' . $info['UserEmail'];
		}
		
		// Get company information
		$companyinfo = 'N/A';
		// TO-DO: Get company name
		
		// Save a description with information about the booking that was created
		$logEventDescription = 'A booking was created for the meeting room: ' . $meetinginfo . 
		', for the user: ' . $userinfo . ' and company: ' . $companyinfo . '. Booking was made by: ' . $_SESSION['LoggedInUserName'];
		
		if(isset($_SESSION['lastBookingID'])){
			$lastBookingID = $_SESSION['lastBookingID'];
			unset($_SESSION['lastBookingID']);				
		}

		
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
		
		$pdo = connect_to_db();
		$sql = "INSERT INTO `logevent` 
				SET			`actionID` = 	(
												SELECT `actionID` 
												FROM `logaction`
												WHERE `name` = 'Booking Created'
											),
							`userID` = :UserID,
							`meetingRoomID` = :MeetingRoomID,
							`bookingID` = :BookingID,
							`description` = :description";
		$s = $pdo->prepare($sql);
		$s->bindValue(':description', $logEventDescription);
		$s->bindValue(':BookingID', $lastBookingID);
		$s->bindValue(':MeetingRoomID', $_POST['meetingRoomID']);
		$s->bindValue(':UserID', $_POST['userID']);
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
	
	//Send email with booking information and cancellation code to the user who the booking is for.
		// TO-DO: This is UNTESTED since we don't have php.ini set up to actually send email	

	$emailSubject = "New Booking Information!";
	
	$emailMessage = 
	"Your meeting has been successfully booked!\n" . 
	"The meeting has been set to: \n" .
	"Meeting Room: " . $MeetingRoomName . ".\n" . 
	"Start Time: " . $displayValidatedStartDate . ".\n" .
	"End Time: " . $displayValidatedEndDate . ".\n\n" .
	"If you wish to cancel your meeting, or just end it early, you can easily do so by clicking the link given below.\n" .
	"Click this link to cancel your booked meeting: " . $_SERVER['HTTP_HOST'] . 
	"/booking/?cancellationcode=" . $cancellationCode;		

	$email = $_SESSION['AddCreateBookingInfoArray']['UserEmail'];
	
	$mailResult = sendEmail($email, $emailSubject, $emailMessage);
	
	if(!$mailResult){
		$_SESSION['normalBookingUserFeedback'] .= " [WARNING] System failed to send Email to user.";
	}
	
	$_SESSION['normalBookingUserFeedback'] .= "this is the email msg we're sending out: $emailMessage. Sent to email: $email."; // TO-DO: Remove after testing	
	
	// Booking a new meeting is done. Reset all connected sessions.
	clearAddCreateBookingSessions();
	
	// Load booking history list webpage with new booking
	header('Location: .');
	exit();
}

//	User wants to change the company the booking is for (after having already selected it)
if(isset($_POST['add']) AND $_POST['add'] == "Change Company"){
	
	// We want to select a company again
	unset($_SESSION['AddCreateBookingSelectedACompany']);
	
	// Let's remember what was selected if we do any changes before clicking "Change Company"
	rememberAddCreateBookingInputs();
	
	$_SESSION['refreshAddCreateBooking'] = TRUE;
	header('Location: .');
	exit();		
}

// User confirms what company he wants the booking to be for.
if(isset($_POST['add']) AND $_POST['add'] == "Select This Company"){

	// Remember that we've selected a new company
	$_SESSION['AddCreateBookingSelectedACompany'] = TRUE;
	
	// Let's remember what was selected if we do any changes before clicking "Select This Company"
	rememberAddCreateBookingInputs();
	
	$_SESSION['refreshAddCreateBooking'] = TRUE;
	header('Location: .');
	exit();	
}

// If user wants to get their default display name
if(isset($_POST['add']) AND $_POST['add'] == "Get Default Display Name"){
	  
	$displayName = $_SESSION['AddCreateBookingDefaultDisplayNameForNewUser'];
	if(isset($_SESSION['AddCreateBookingInfoArray'])){
		rememberAddCreateBookingInputs();

		if($displayName != ""){
			if($displayName != $_SESSION['AddCreateBookingInfoArray']['BookedBy']){
				
					// The user selected
				$_SESSION['AddCreateBookingInfoArray']['BookedBy'] = $displayName;

				unset($_SESSION['AddCreateBookingDefaultDisplayNameForNewUser']);				
			} else {
				// Description was already the default booking description
				$_SESSION['AddCreateBookingError'] = "This is already your default display name.";
			}
		} else {
			// The user has no default display name
			$_SESSION['AddCreateBookingError'] = "You have no default display name. You can set one in your user information.";
			$_SESSION['AddCreateBookingInfoArray']['BookedBy'] = "";
		}		
	}
	
	$_SESSION['refreshAddCreateBooking'] = TRUE;
	header('Location: .');
	exit();	
}

// If user wants to get their default booking description
if(isset($_POST['add']) AND $_POST['add'] == "Get Default Booking Description"){
	
	$bookingDescription = $_SESSION['AddCreateBookingDefaultBookingDescriptionForNewUser'];
	if(isset($_SESSION['AddCreateBookingInfoArray'])){
		
		rememberAddCreateBookingInputs();

		if($bookingDescription != ""){
			if($bookingDescription != $_SESSION['AddCreateBookingInfoArray']['BookingDescription']){
				$_SESSION['AddCreateBookingInfoArray']['BookingDescription'] = $bookingDescription;

				unset($_SESSION['AddCreateBookingDefaultBookingDescriptionForNewUser']);			
			} else {
				// Description was already the default booking description
				$_SESSION['AddCreateBookingError'] = "This is already your default booking description.";
			}
		} else {
			// The user has no default booking description
			$_SESSION['AddCreateBookingError'] = "You have no default booking description. You can set one in your user information.";
			$_SESSION['AddCreateBookingInfoArray']['BookingDescription'] = "";
		}
	}
	
	$_SESSION['refreshAddCreateBooking'] = TRUE;
	header('Location: .');
	exit();	
}

// If user wants to change the values back to the original values
if (isset($_POST['add']) AND $_POST['add'] == "Reset"){

	$_SESSION['AddCreateBookingInfoArray'] = $_SESSION['AddCreateBookingOriginalInfoArray'];
	unset($_SESSION['AddCreateBookingSelectedACompany']);
	unset($_SESSION['AddCreateBookingChangeUser']);
	unset($_SESSION['AddCreateBookingSelectedNewUser']);
	
	$_SESSION['refreshAddCreateBooking'] = TRUE;
	header('Location: .');
	exit();		
}

// If user wants to leave the page and be directed back to the booking page again
if (isset($_POST['add']) AND $_POST['add'] == 'Cancel'){

	$_SESSION['normalBookingFeedback'] = "You cancelled your new booking.";
}

// ADD BOOKING CODE SNIPPET // END //


// EDIT BOOKING CODE SNIPPET // START //



// EDIT BOOKING CODE SNIPPET // END //

// CANCELLATION CODE SNIPPET // START //


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

// CANCELLATION CODE SNIPPET // END //

// Remove any unused variables from memory 
// TO-DO: Change if this ruins having multiple tabs open etc.
clearAddBookingSessions();
clearEditBookingSessions();

if(isset($refreshBookings) AND $refreshBookings) {
	// TO-DO: Add code that should occur on a refresh
	unset($refreshBookings);
}

// Display relevant booked meetings
try
{
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
	$pdo = connect_to_db();
	$sql = "SELECT 		b.`bookingID`,
						b.`companyID`,
						m.`name` 										AS BookedRoomName, 
						b.startDateTime 								AS StartTime,
						b.endDateTime									AS EndTime, 
						b.displayName 									AS BookedBy,
						(	
							SELECT `name` 
							FROM `company` 
							WHERE `companyID` = b.`companyID`
						)												AS BookedForCompany,
						u.firstName, 
						u.lastName, 
						u.email, 
						GROUP_CONCAT(c.`name` separator ', ') 			AS WorksForCompany, 
						b.description 									AS BookingDescription, 
						b.dateTimeCreated 								AS BookingWasCreatedOn, 
						b.actualEndDateTime								AS BookingWasCompletedOn, 
						b.dateTimeCancelled								AS BookingWasCancelledOn 
			FROM 		`booking` b 
			LEFT JOIN 	`meetingroom` m 
			ON 			b.meetingRoomID = m.meetingRoomID 
			LEFT JOIN 	`user` u 
			ON 			u.userID = b.userID 
			LEFT JOIN 	`employee` e 
			ON 			e.UserID = u.userID 
			LEFT JOIN 	`company` c 
			ON 			c.CompanyID = e.CompanyID
			GROUP BY 	b.bookingID
			ORDER BY 	UNIX_TIMESTAMP(b.startDateTime)
			ASC";
	$result = $pdo->query($sql);
	$rowNum = $result->rowCount();

	//Close the connection
	$pdo = null;
}
catch (PDOException $e)
{
	$error = 'Error fetching booking information from the database: ' . $e->getMessage();
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
	$pdo = null;
	exit();
}

foreach ($result as $row)
{
	$datetimeNow = getDatetimeNow();
	$startDateTime = $row['StartTime'];	
	$endDateTime = $row['EndTime'];
	$completedDateTime = $row['BookingWasCompletedOn'];
	$dateOnlyNow = convertDatetimeToFormat($datetimeNow, 'Y-m-d H:i:s', 'Y-m-d');
	$dateOnlyCompleted = convertDatetimeToFormat($completedDateTime,'Y-m-d H:i:s','Y-m-d');
	$dateOnlyStart = convertDatetimeToFormat($startDateTime,'Y-m-d H:i:s','Y-m-d');
	$cancelledDateTime = $row['BookingWasCancelledOn'];
	$createdDateTime = $row['BookingWasCreatedOn'];	
	
	// Describe the status of the booking based on what info is stored in the database
	// If not finished and not cancelled = active
	// If meeting time has passed and finished time has updated (and not been cancelled) = completed
	// If cancelled = cancelled
	// If meeting time has passed and finished time has NOT updated (and not been cancelled) = Ended without updating
	// If none of the above = Unknown
	// TO-DO: CHECK IF THIS MAKES SENSE!
	if(			$completedDateTime == null AND $cancelledDateTime == null AND 
				$datetimeNow < $endDateTime AND $dateOnlyNow != $dateOnlyStart) {
		$status = 'Active';
		// Valid status
	} elseif(	$completedDateTime == null AND $cancelledDateTime == null AND 
				$datetimeNow < $endDateTime AND $dateOnlyNow == $dateOnlyStart){
		$status = 'Active Today';
		// Valid status		
	} elseif(	$completedDateTime != null AND $cancelledDateTime == null AND 
				$dateOnlyNow != $dateOnlyCompleted){
		$status = 'Completed';
		// Valid status
	} elseif(	$completedDateTime != null AND $cancelledDateTime == null AND 
				$dateOnlyNow == $dateOnlyCompleted){
		$status = 'Completed Today';
		// Valid status
	} elseif(	$completedDateTime == null AND $cancelledDateTime != null AND
				$startDateTime > $row['BookingWasCancelledOn']){
		$status = 'Cancelled';
		// Valid status
	} elseif(	$completedDateTime != null AND $cancelledDateTime != null AND
				$completedDateTime > $cancelledDateTime ){
		$status = 'Ended Early';
		// Valid status
	} elseif(	$completedDateTime != null AND $cancelledDateTime != null AND
				$completedDateTime < $cancelledDateTime ){
		$status = 'Cancelled after Completion';
		// This should not be allowed to happen eventually
	} elseif(	$completedDateTime == null AND $cancelledDateTime == null AND 
				$datetimeNow > $endDateTime){
		$status = 'Ended without updating database';
		// This should never occur
	} elseif(	$completedDateTime == null AND $cancelledDateTime != null AND 
				$row['EndTime'] < $row['BookingWasCancelledOn']){
		$status = 'Cancelled after meeting should have been Completed';
		// This should not be allowed to happen eventually
	} else {
		$status = 'Unknown';
		// This should never occur
	}
	
	$roomName = $row['BookedRoomName'];
	$firstname = $row['firstName'];
	$lastname = $row['lastName'];
	$email = $row['email'];
	$userinfo = $lastname . ', ' . $firstname . ' - ' . $row['email'];
	$worksForCompany = $row['WorksForCompany'];
	if(!isset($roomName) OR $roomName == NULL OR $roomName == ""){
		$roomName = "N/A - Deleted";
	}
	if(!isset($userinfo) OR $userinfo == NULL OR $userinfo == ",  - "){
		$userinfo = "N/A - Deleted";	
	}
	if(!isset($email) OR $email == NULL OR $email == ""){
		$firstname = "N/A - Deleted";
		$lastname = "N/A - Deleted";
		$email = "N/A - Deleted";		
	}
	if(!isset($worksForCompany) OR $worksForCompany == NULL OR $worksForCompany == ""){
		$worksForCompany = "N/A";
	}
	$displayValidatedStartDate = convertDatetimeToFormat($startDateTime , 'Y-m-d H:i:s', DATETIME_DEFAULT_FORMAT_TO_DISPLAY);
	$displayValidatedEndDate = convertDatetimeToFormat($endDateTime, 'Y-m-d H:i:s', DATETIME_DEFAULT_FORMAT_TO_DISPLAY);
	$displayCompletedDateTime = convertDatetimeToFormat($completedDateTime, 'Y-m-d H:i:s', DATETIME_DEFAULT_FORMAT_TO_DISPLAY);
	$displayCancelledDateTime = convertDatetimeToFormat($cancelledDateTime, 'Y-m-d H:i:s', DATETIME_DEFAULT_FORMAT_TO_DISPLAY);	
	$displayCreatedDateTime = convertDatetimeToFormat($createdDateTime, 'Y-m-d H:i:s', DATETIME_DEFAULT_FORMAT_TO_DISPLAY);
	
	$meetinginfo = $roomName . ' for the timeslot: ' . $displayValidatedStartDate . 
					' to ' . $displayValidatedEndDate;
	
	$bookings[] = array('id' => $row['bookingID'],
						'BookingStatus' => $status,
						'BookedRoomName' => $roomName,
						'StartTime' => $displayValidatedStartDate,
						'EndTime' => $displayValidatedEndDate,
						'BookedBy' => $row['BookedBy'],
						'BookedForCompany' => $row['BookedForCompany'],
						'BookingDescription' => $row['BookingDescription'],
						'firstName' => $firstname,
						'lastName' => $lastname,
						'email' => $email,
						'WorksForCompany' => $worksForCompany,
						'BookingWasCreatedOn' => $displayCreatedDateTime,
						'BookingWasCompletedOn' => $displayCompletedDateTime,
						'BookingWasCancelledOn' => $displayCancelledDateTime,	
						'UserInfo' => $userinfo,
						'MeetingInfo' => $meetinginfo
					);
}

// Load the html template
include_once 'booking.html.php';
?>