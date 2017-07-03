<?php
// This is the Index file for the EVENTS folder
session_start();

// Include functions
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php';

// CHECK IF USER TRYING TO ACCESS THIS IS IN FACT THE ADMIN!
if (!isUserAdmin()){
	exit();
}

// Function to clear sessions used to remember user inputs on refreshing the add booking form
function clearAddEventSessions(){
	unset($_SESSION['AddEventWeeksSelected']);
	unset($_SESSION['AddEventDaysSelected']);
	unset($_SESSION['AddEventRoomChoiceSelected']);
	unset($_SESSION['AddEventRoomsSelected']);
	unset($_SESSION['AddEventInfoArray']);
	unset($_SESSION['AddEventOriginalInfoArray']);
}

// If admin wants to create a new event
if(	(isset($_POST['action']) AND $_POST['action'] == "Create Event") OR
	(isset($_SESSION['refreshAddEvent']) AND $_SESSION['refreshAddEvent'])
	){
	
	if(isset($_SESSION['refreshAddEvent']){
		// Acknowledge that we hav refreshed the page
		unset(isset($_SESSION['refreshAddEvent']); 
	}
	
	if(!isset($_SESSION['AddEventInfoArray'])){
		// Create an array with the row information we want to use	
		$_SESSION['AddEventInfoArray'] = array(
													'TheEventID' => '',
													'StartTime' => '',
													'EndTime' => '',
													'EventName' => '',
													'EventDescription' => '',
													'BookedForCompany' => '',
													'DaysSelected' => '',
													'startDate' => '',
													'lastDate' => ''
												);
		$_SESSION['AddEventOriginalInfoArray'] = $_SESSION['AddEventInfoArray'];
	}

	
	include_once 'addevent.html.php';
	exit();
}

// If admin wants to submit the created event
if(isset($_POST['add']) AND $_POST['add'] == "Create Event"){
	
	// TO-DO: Validate user inputs.
	// TO-DO: Check if time is available
	// TO-DO: Add Event to database
	// TO-DO: Create log event?
	
	header("Location: .");
	exit();
}

// If admin wants to leave the page and be directed back to the events page again
if (isset($_POST['add']) AND $_POST['add'] == 'Cancel'){

	$_SESSION['AddEventError'] = "You cancelled your new event.";
}

// Remove any unused variables from memory 
// TO-DO: Change if this ruins having multiple tabs open etc.
clearAddEventSessions();
//clearEditEventSessions();


// EVENTS OVERVIEW CODE SNIPPET START //

if(isset($refreshEvents) AND $refreshEvents) {
	// TO-DO: Add code that should occur on a refresh
	unset($refreshEvents);
}

// Get Event Data
try
{
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
	
	// Use connect to Database function from db.inc.php
	$pdo = connect_to_db();
	$sql = 'SELECT	`EventID`			AS TheEventID,
					`startTime`			AS StartTime,
					`endTime`			AS EndTime,
					`name`				AS EventName,
					`description`		AS EventDescription,
					`dateTimeCreated`	AS DateTimeCreated,
					`startDate`			AS StartDate,
					`lastDate`			AS LastDate,
					WEEK(`startDate`,3)	AS WeekStart,
					WEEK(`lastDate`,3)	AS WeekEnd,
					`daysSelected`		AS DaysSelected,
					(
						SELECT 		GROUP_CONCAT(m.`name` separator ",\n")
						FROM		`roomevent` rev
						INNER JOIN 	`meetingroom` m
						ON			rev.`meetingRoomID` = m.`meetingRoomID`
						WHERE		rev.`EventID` = TheEventID
					)					AS UsedMeetingRooms
			FROM 	`event`';
	$return = $pdo->query($sql);
	$result = $return->fetchAll(PDO::FETCH_ASSOC);
	if(isset($result)){
		$rowNum = sizeOf($result);
	} else {
		$rowNum = 0;
	}
	
	//Close connection
	$pdo = null;
}
catch (PDOException $e)
{
	$error = 'Error fetching events: ' . $e->getMessage();
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php'; 
	$pdo = null;
	exit();
}

// Create the array we will go through to display information in HTML
foreach ($result as $row)
{
	// Check if event is over or still active
	$startDate = $row['StartDate'];
	$lastDate = $row['LastDate'];
	$dateNow = getDateNow();
	$timeNow = getTimeNow();
	$startTime = $row['StartTime'];
	$endTime = $row['EndTime'];
	$weekStart = $row['WeekStart'];
	$weekEnd = $row['WeekEnd'];
	
	if($weekStart == $weekEnd){
		// single event
		if($dateNow > $lastDate AND $timeNow > $endTime){
			$status = "Completed\n(Single Event)";
		} else {
			$status = "Active\n(Single Event)";
		}
	} elseif($weekEnd > $weekStart) {
		// repeated event
		if($dateNow > $lastDate AND $timeNow > $endTime){
			$status = "Completed\n(Repeated Event)";
		} else {
			$status = "Active\n(Repeated Event)";
		}		
	}
	
	// Turn the datetime retrieved into a more displayable format
	$dateCreated = $row['DateTimeCreated'];
	$displayableDateCreated = convertDatetimeToFormat($dateCreated, 'Y-m-d H:i:s', DATETIME_DEFAULT_FORMAT_TO_DISPLAY);
	$displayableStartDate = convertDatetimeToFormat($startDate, 'Y-m-d', DATE_DEFAULT_FORMAT_TO_DISPLAY);
	$startDateWithWeekNumber = $displayableStartDate . "\nWeek #" . $weekStart;
	$displayableEndDate = convertDatetimeToFormat($lastDate, 'Y-m-d', DATE_DEFAULT_FORMAT_TO_DISPLAY);
	$endDateWithWeekNumber = $displayableEndDate . "\nWeek #" . $weekEnd;
	$displayableStartTime = convertDatetimeToFormat($startTime, 'H:i:s', TIME_DEFAULT_FORMAT_TO_DISPLAY);
	$displayableEndTime = convertDatetimeToFormat($endTime, 'H:i:s', TIME_DEFAULT_FORMAT_TO_DISPLAY);

	if(substr($status,0,6) == "Active"){
		$activeEvents[] = array(
							'EventStatus' => $status,
							'EventID' => $row['TheEventID'], 
							'DateTimeCreated' => $displayableDateCreated, 
							'EventName' => $row['EventName'], 
							'EventDescription' => $row['EventDescription'], 
							'UsedMeetingRooms' => $row['UsedMeetingRooms'],
							'DaysSelected' => $row['DaysSelected'],
							'StartTime' => $displayableStartTime,
							'EndTime' => $displayableEndTime,
							'StartDate' => $startDateWithWeekNumber,
							'LastDate' => $endDateWithWeekNumber
						);
	} else {
		$completedEvents[] = array(
							'EventStatus' => $status,
							'EventID' => $row['TheEventID'], 
							'DateTimeCreated' => $displayableDateCreated, 
							'EventName' => $row['EventName'], 
							'EventDescription' => $row['EventDescription'], 
							'UsedMeetingRooms' => $row['UsedMeetingRooms'],
							'DaysSelected' => $row['DaysSelected'],
							'StartTime' => $displayableStartTime,
							'EndTime' => $displayableEndTime,
							'StartDate' => $startDateWithWeekNumber,
							'LastDate' => $endDateWithWeekNumber
						);		
	}
}	

var_dump($_SESSION); // TO-DO: remove after testing is done

// Create the Events table in HTML
include_once 'events.html.php';
?>