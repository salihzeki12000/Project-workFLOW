<?php
require_once 'variables.inc.php';
// This is a collection of functions we use to check if user inputs are OK

// Function to check if variables are too big for MySQL or our liking

	// Order Messages
// Returns TRUE on invalid, FALSE on valid
function isLengthInvalidOrderMessage($orderMessage){
	// utf8 maximum of 21,844 characters (MySQL VARCHAR(21844))
	// Due to max column size limitations we've set it to VARCHAR(20000)

	$orderMessageLength = strlen(utf8_decode($orderMessage));
	$orderMessageMaxLength = 20000; // TO-DO: Adjust max length if needed.
	if($orderMessageLength > $orderMessageMaxLength){
		return TRUE;
	}
	return FALSE;
}

	// Display Names
// Returns TRUE on invalid, FALSE on valid
function isLengthInvalidDisplayName($name){
	// Has to be less than 255 chars (MySQL - VARCHAR 255)

	$nameLength = strlen(utf8_decode($name));
	$maxLength = 255; // TO-DO: Adjust max length if needed.
	if($nameLength > $maxLength){
		return TRUE;
	}
	return FALSE;
}

	// Extra Names
// Returns TRUE on invalid, FALSE on valid
function isLengthInvalidExtraName($name){
	// Has to be less than 255 chars (MySQL - VARCHAR 255)

	$nameLength = strlen(utf8_decode($name));
	$maxLength = 255; // TO-DO: Adjust max length if needed.
	if($nameLength > $maxLength){
		return TRUE;
	}
	return FALSE;
}

	// Booking Descriptions
// Returns TRUE on invalid, FALSE on valid
function isLengthInvalidBookingDescription($description){
	// Has to be less than 65,535 bytes (MySQL - TEXT) (too much anyway)

	$descriptionLength = strlen(utf8_decode($description));
	$maxLength = 500; // TO-DO: Adjust max length if needed.
	if($descriptionLength > $maxLength){
		return TRUE;
	}
	return FALSE;
}

	// Equipment Descriptions
// Returns TRUE on invalid, FALSE on valid
function isLengthInvalidEquipmentDescription($description){
	// Has to be less than 65,535 bytes (MySQL - TEXT) (too much anyway)

	$descriptionLength = strlen(utf8_decode($description));
	$maxLength = 500; // TO-DO: Adjust max length if needed.
	if($descriptionLength > $maxLength){
		return TRUE;
	}
	return FALSE;
}

	// Extra Descriptions
// Returns TRUE on invalid, FALSE on valid
function isLengthInvalidExtraDescription($description){
	// Has to be less than 65,535 bytes (MySQL - TEXT) (too much anyway)

	$descriptionLength = strlen(utf8_decode($description));
	$maxLength = 500; // TO-DO: Adjust max length if needed.
	if($descriptionLength > $maxLength){
		return TRUE;
	}
	return FALSE;
}

	// Order User Notes
// Returns TRUE on invalid, FALSE on valid
function isLengthInvalidOrderUserNotes($description){
	// Has to be less than 65,535 bytes (MySQL - TEXT) (too much anyway)

	$descriptionLength = strlen(utf8_decode($description));
	$maxLength = 500; // TO-DO: Adjust max length if needed.
	if($descriptionLength > $maxLength){
		return TRUE;
	}
	return FALSE;
}

	// Meeting Room Descriptions
// Returns TRUE on invalid, FALSE on valid
function isLengthInvalidMeetingRoomDescription($meetingRoomDescription){
	// Has to be less than 65,535 bytes (MySQL - TEXT) (too much anyway)

	$mtngrmDscrptnLength = strlen(utf8_decode($meetingRoomDescription));
	$mtngrmDscrptnMaxLength = 500; // TO-DO: Adjust max length if needed.
	if($mtngrmDscrptnLength > $mtngrmDscrptnMaxLength){
		return TRUE;
	}
	return FALSE;
}

	// Meeting Room Location
// Returns TRUE on invalid, FALSE on valid
function isLengthInvalidMeetingRoomLocation($meetingRoomCapacity){
	// Has to be less than 65,535 bytes (MySQL - TEXT) (too much anyway)

	$mtngrmCapacityLength = strlen(utf8_decode($meetingRoomCapacity));
	$mtngrmCapacityMaxLength = 500; // TO-DO: Adjust max length if needed.
	if($mtngrmCapacityLength > $mtngrmCapacityMaxLength){
		return TRUE;
	}
	return FALSE;
}

	// Meeting Room Capacity
// Returns TRUE on invalid, FALSE on valid
function isNumberInvalidMeetingRoomCapacity($capacityNumber){
	// Has to be between 0 and 255
	// In practice the meeting room needs at least room for 1 person.

	$maxNumber = MAXIMUM_UNSIGNED_TINYINT_NUMBER;
	$minNumber = 1;
	if($capacityNumber < $minNumber OR $capacityNumber > $maxNumber){
		return TRUE;
	}
	return FALSE;
}

	// Extra Order Amount
// Returns TRUE on invalid, FALSE on valid
function isNumberInvalidOrderAmount($creditsAmount){
	// Has to be between 0 and 255
	// In practice an extra order always needs at least 1 amount.

	$maxNumber = MAXIMUM_UNSIGNED_TINYINT_NUMBER;
	$minNumber = 1;
	if($creditsAmount < $minNumber OR $creditsAmount > $maxNumber){
		return TRUE;
	}
	return FALSE;
}

	// Credits Amount
// Returns TRUE on invalid, FALSE on valid
function isNumberInvalidCreditsAmount($creditsAmount){
	// Has to be between 0 and 65535 (minutes)

	$maxNumber = MAXIMUM_UNSIGNED_SMALLINT_NUMBER;	// To-do: change if needed
	$minNumber = 0;
	if($creditsAmount < $minNumber OR $creditsAmount > $maxNumber){
		return TRUE;
	}
	return FALSE;
}

	// Credits Hour Price
// Returns TRUE on invalid, FALSE on valid
function isNumberInvalidCreditsHourPrice($creditsHourPrice){
	// Is a float so it has a large range
	// In practice we only need from 0 to some big number

	$maxNumber = MAXIMUM_FLOAT_NUMBER;
	$minNumber = 0;
	if($creditsHourPrice < $minNumber OR $creditsHourPrice > $maxNumber){
		return TRUE;
	}
	return FALSE;
}

	// Credits Monthly Subscription Price
// Returns TRUE on invalid, FALSE on valid
function isNumberInvalidCreditsMonthlyPrice($creditsMonthlyPrice){
	// Is a float so it has a large range
	// In practice we only need from 0 to some big number

	$maxNumber = MAXIMUM_FLOAT_NUMBER;
	$minNumber = 0;
	if($creditsMonthlyPrice < $minNumber OR $creditsMonthlyPrice > $maxNumber){
		return TRUE;
	}
	return FALSE;
}

	// Booking Code Digits
// Returns TRUE on invalid, FALSE on valid
function isNumberInvalidBookingCode($bookingCode){
	// Has to be between 0 and 6 digits (ideally 6 digits all the time, but we add 0's to make it 6)
	// Also has to return invalid on blocked digits, if implemented
	$bookingCodeLength = BOOKING_CODE_LENGTH;
	// Make sure we have enough digits submitted	
	if(strlen($bookingCode) < $bookingCodeLength){
		$sprintftext = "%0" . $bookingCodeLength . "u";
		$bookingCode = sprintf($sprintftext, $bookingCode); // Add 0s before submitted digits
	}
	// For security reasons we want to disable some easy to guess codes
	/*$blockedDigits = array(	'000000', '111111', '222222', '333333', '444444', 	
								'555555', '666666', '777777', '888888', '999999', 
								'012345', '123456', '234567', '345678', '456789',	// Block ascending digits
								'567890', '678901', '789012', '890123', '901234',
								'987654', '876543', '765432', '654321', '543210',	// Block descendig digits
								'432109', '321098', '210987', '109876', '098765'
							); This has been changed from manual to code generated*/
	$ascNum = "01234567890123456789";
	$descNum = "98765432109876543210";
	for($i=0; $i < 10; $i++){
		$blockedDigits[] = str_repeat($i,$bookingCodeLength); // Block all equal digits
		$blockedDigits[] = substr($ascNum,$i,$bookingCodeLength); // Block ascending digits
		$blockedDigits[] = substr($descNum,$i,$bookingCodeLength); // Block descending digits
	}

	$minNumber = 0;
	$maxNumber = (10 ** BOOKING_CODE_LENGTH)-1; // Sets the highest number with our set digits (10^digits - 1)
	if($bookingCode < $minNumber OR $bookingCode > $maxNumber){
		return TRUE;
	}
	foreach($blockedDigits AS $number){
		if($bookingCode == $number){
			return TRUE;
		}
	}
	return FALSE;
}

// Function that (hopefully) removes excess white space, line feeds etc.
function trimExcessWhitespaceButLeaveLinefeed($oldString){
	// Inner preg replaces takes all white space before and after a line feed and turns it into a single line feed
	// Outer preg replaces takes all excess spaces and tabs between words on a line and replaces with a single space
	// trim removes excess spaces before/after
	return trim(preg_replace('/[ \t]+/', ' ', preg_replace('/\s*\R+\s*/u', "\n", $oldString)));
}

// Function that (hopefully) removes excess white space, line feeds etc.
function trimExcessWhitespace($oldString){
	// Replace any amount of white space with a single space
	// Also remove excess space at start/end
	return trim(preg_replace('/\s+/', ' ', $oldString));
}

// Function that (hopefully) removes all white space
function trimAllWhitespace($oldString){
	return preg_replace('/\s+/', '', $oldString);
}

// Function to check if input string uses legal characters and trims the input down
// For Names
// Allows empty strings
function validateNames($oldString){
	// Check if string uses allowed characters
		// We allow all language letters and accents.
		// Also space, and the symbols ', . and -
		// TO-DO: Change if we need other symbols
	if (preg_match("/^[\p{L}\p{M} '-]*$/u", $oldString)) {
		return TRUE;
	} else {
		return FALSE;
	}		
}

// Function to check if input string uses legal characters and trims the input down
// Allows empty strings
function validateString($oldString){
	// Check if string uses allowed characters
		// " -~" matches all printable ASCII characters (A-Z, a-z, 0-9, etc.)
		// For unicode we add /u and p{L} for all language letters and p{M} for all accents
		// There are still characters that are not allowed, like currency symbols
		// and symbols like ´ (when not used as an accent)
		// For currency symbols add \p{Sc}
		// For math symbols add \p{Sm}
		
	if (preg_match('/^[ -~\p{L}\p{M}\r\n]*$/u', $oldString)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

// Function to check if input string uses legal characters for an integer number only
// \d = any digit
function validateIntegerNumber($oldString){
	if(preg_match('/^[+-]?\d+$/', $oldString)){
		return TRUE;
	} else {
		return FALSE;
	}
}

// Function to check if input string uses legal characters for a float number only
// We also allow + or - in front and a single decimal point (.)
function validateFloatNumber($oldString){
	$oldString = str_replace(',','.',$oldString);
	if(preg_match('/^[+-]?\d+\.?\d*$/', $oldString)){
			return TRUE;
		} else {
			return FALSE;
		}	
}

// Function to check if input string uses legal characters for our datetime convertions and trims excess spaces
// Allows empty strings
// Returns TRUE on valid, FALSE on invalid.
function validateDateTimeString($oldString){
	// Check if string uses allowed characters
		// We allow the characters , . : / - _ and space
	if (preg_match('/^[A-Za-z0-9.:\/_ -]*$/', $oldString)) {
		return TRUE;
	} else {
		return FALSE;
	}	
}

// Function to check if the submitted time has a valid minute slice
// e.g. with 15 minute booking slices it will be 00, 15, 30 or 45.
// Returns TRUE on invalid, FALSE on valid
function isBookingDateTimeMinutesInvalid($timeString){
	$time = stringToDateTime($timeString, 'Y-m-d H:i:s');
	$timeMinutePart = $time->format('i');
	
	$minimumBookingTime = MINIMUM_BOOKING_TIME_IN_MINUTES;
	
	for($i = 0; $i <= $timeMinutePart; ){
		if($timeMinutePart == $i){
			return FALSE;
		}
		$i += $minimumBookingTime;	
	}		
	
	return TRUE;
}

// Function to check if the two submitted times are a valid length apart
// Returns TRUE on invalid, FALSE on valid
function isBookingTimeDurationInvalid($startDateTime, $endDateTime){
	$differenceInMinutes = convertTwoDateTimesToTimeDifferenceInMinutes($startDateTime, $endDateTime);
	
	$minimumBookingTime = MINIMUM_BOOKING_TIME_IN_MINUTES;
	
	if($differenceInMinutes < $minimumBookingTime){
		return TRUE;
	} else {
		return FALSE;
	}
}
?>