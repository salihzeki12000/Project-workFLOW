<?php 
// This is the index file for the meeting room folder (all users)
session_start();

// Include functions
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php';

/*
	TO-DO:
		Show meeting room status (booked or not?)
		Search meeting room status by datetime?

*/
var_dump($_SESSION);
// ADMIN INTERACTIONS // START //

// If Admin wants to set a meeting room as the default room on a local device
if(	(isset($_POST['action']) AND $_POST['action'] == "Set Default Room") OR 
	(isset($_SESSION['SetDefaultRoom']) AND $_SESSION['SetDefaultRoom'])){
		// CHECK IF USER TRYING TO ACCESS THIS IS IN FACT THE ADMIN!
	$_SESSION['SetDefaultRoom'] = TRUE;
	var_dump($_SESSION);
	
	if (!isUserAdmin()){
		echo "we exit here";
		exit();
	}
	unset($_SESSION['SetDefaultRoom']);
	// User logged in as Admin and can set the default meeting room on this local device
	// Display meeting room list to choose from.
	try
	{
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
		$pdo = connect_to_db();
		$sql = 'SELECT  	`meetingRoomID`	AS TheMeetingRoomID, 
							`name`			AS MeetingRoomName, 
							`capacity`		AS MeetingRoomCapacity, 
							`description`	AS MeetingRoomDescription,
							`idCode`		AS MeetingRoomIDCode
				FROM 		`meetingroom`';
		$result = $pdo->query($sql);
		$rowNum = $result->rowCount();
		
		//Close the connection
		$pdo = null;
	}
	catch (PDOException $e)
	{
		$error = 'Error fetching meeting rooms from the database: ' . $e->getMessage();
		include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
		$pdo = null;
		exit();
	}

	foreach ($result as $row)
	{
		$meetingrooms[] = array('MeetingRoomID' => $row['TheMeetingRoomID'], 
								'MeetingRoomName' => $row['MeetingRoomName'],
								'MeetingRoomCapacity' => $row['MeetingRoomCapacity'],
								'MeetingRoomDescription' => $row['MeetingRoomDescription'],
								'MeetingRoomIDCode' => $row['MeetingRoomIDCode']
						);
	}
	include_once 'adminroomselect.html.php';
	exit();
}

// If Admin has chosen a default meeting room from the available meeting rooms
if(isset($_POST['action']) AND $_POST['action'] == "Set As Default"){
		// CHECK IF USER TRYING TO ACCESS THIS IS IN FACT THE ADMIN!
	if (!isUserAdmin()){
		exit();
	}
	
		// Set the proper cookies for the meeting room and logout the Admin.
	if(isset($_POST['MeetingRoomName']) AND isset($_POST['MeetingRoomIDCode'])){
		$meetingRoomName = $_POST['MeetingRoomName'];
		
		setNewMeetingRoomCookies($meetingRoomName, $_POST['MeetingRoomIDCode']);
		destroySession();
		$defaultMeetingRoomFeedback = "Set $meetingRoomName as the default meeting room for this device. Also logged you off as Admin.";
	} else {
		$_SESSION['MeetingRoomAllUsersFeedback'] = "Couldn't set default meeting room for local device.";
	}
}
// ADMIN INTERACTIONS // END //



// NON-ADMIN INTERACTIONS // START //


// Redirect to booking when a room has been selected
if(isset($_POST['action']) AND $_POST['action'] == "Select Room"){

	$TheMeetingRoomID = $_POST['MeetingRoomID'];
	$location = "http://$_SERVER[HTTP_HOST]/booking/?meetingroom=" . $TheMeetingRoomID;
	header("Location: $location");
	exit();
}

if(isset($_POST['action']) AND $_POST['action'] == "Set New Max"){
	
	// Validate user input
	$roomDisplayLimitString = trimExcessWhiteSpace($_POST['logsToShow']);
	$isNumber = validateIntegerNumber($roomDisplayLimitString);
	if($isNumber === TRUE){
		$maxRoomsToShow = $roomDisplayLimitString;
		$roomDisplayLimit = $roomDisplayLimitString;
		if($roomDisplayLimitString != $_POST['oldDisplayLimit']){
			$_SESSION['MeetingRoomAllUsersFeedback'] = "Set new maximum rooms to display to: $maxRoomsToShow.";				
		} else {
			$_SESSION['MeetingRoomAllUsersFeedback'] = "No change were made.";
		}
	} else {
		$_SESSION['MeetingRoomAllUsersFeedback'] = "You tried to submit something that wasn't a valid number.";	 
	}
}

// Display meeting rooms
try
{
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
	$pdo = connect_to_db();
	$sql = 'SELECT  	m.`meetingRoomID`	AS TheMeetingRoomID, 
						m.`name`			AS MeetingRoomName, 
						m.`capacity`		AS MeetingRoomCapacity, 
						m.`description`		AS MeetingRoomDescription, 
						m.`location`		AS MeetingRoomLocation,
						COUNT(re.`amount`)	AS MeetingRoomEquipmentAmount
			FROM 		`meetingroom` m
			LEFT JOIN 	`roomequipment` re
			ON 			re.`meetingRoomID` = m.`meetingRoomID`
			GROUP BY 	m.`meetingRoomID`';
	$result = $pdo->query($sql);

	//Close the connection
	$pdo = null;
}
catch (PDOException $e)
{
	$error = 'Error fetching meeting rooms from the database: ' . $e->getMessage();
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
	$pdo = null;
	exit();
}

foreach ($result as $row)
{
	$meetingrooms[] = array('MeetingRoomID' => $row['TheMeetingRoomID'], 
							'MeetingRoomName' => $row['MeetingRoomName'],
							'MeetingRoomCapacity' => $row['MeetingRoomCapacity'],
							'MeetingRoomDescription' => $row['MeetingRoomDescription'],
							'MeetingRoomLocation' => $row['MeetingRoomLocation'],
							'MeetingRoomEquipmentAmount' => $row['MeetingRoomEquipmentAmount']
					);
}

$totalMeetingRooms = sizeOf($meetingrooms);

// Sets default values
if(!isset($maxRoomsToShow)){
	if($totalMeetingRooms < 10){
		$maxRoomsToShow = $totalMeetingRooms;
	} else {
		$maxRoomsToShow = 10;	
	}
}
if(!isset($roomDisplayLimit)){
	$roomDisplayLimit = $maxRoomsToShow;
}

// Load the html template
include_once 'meetingroomforallusers.html.php';
?>