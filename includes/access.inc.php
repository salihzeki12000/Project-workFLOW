<?php
// These are functions to handle user access

// Constants used to salt passwords
require_once 'salts.inc.php';

// Function to salt and hash passwords
function hashPassword($rawPassword){
	$SaltedPassword = $rawPassword . PW_SALT;
	$HashedPassword = hash('sha256', $SaltedPassword);
	return $HashedPassword;
}

// returns TRUE if user is logged in
function userIsLoggedIn()
{
	session_start();
	// If user is trying to log in
	if (isset($_POST['action']) and $_POST['action'] == 'login')
	{
		// Check if user has filled in the necessary information
		if (!isset($_POST['email']) or $_POST['email'] == '' or
		!isset($_POST['password']) or $_POST['password'] == '')
		{
			// User didn't fill in enough info
			// Save a custom error message for the user
			$_SESSION['loginError'] = 'Please fill in both fields';
			return FALSE;
		}
		
		// User has filled in both fields, check if login details are correct
			// Add our custom password salt and compare the finished hash to the database
		$SubmittedPassword = $_POST['password'];
		$password = hashPassword($SubmittedPassword);
		if (databaseContainsUser($_POST['email'], $password))
		{
			// Correct log in info! Update the session data to know we're logged in
			$_SESSION['loggedIn'] = TRUE;
			$_SESSION['email'] = $_POST['email'];
			$_SESSION['password'] = $password;
			$_SESSION['LoggedInUserID'] = $_SESSION['DatabaseContainsUserID'];
			$_SESSION['LoggedInUserName'] = $_SESSION['DatabaseContainsUserName'];
			unset($_SESSION['DatabaseContainsUserID']);
			unset($_SESSION['DatabaseContainsUserName']);
			return TRUE;
		}
		else
		{
			// Wrong log in info.
			// Or user data has changed since last check
			// Meaning the login data isn't correct anymore
			// So we log out a user if previously logged in
			unset($_SESSION['loggedIn']);
			unset($_SESSION['email']);
			unset($_SESSION['password']);
			unset($_SESSION['LoggedInUserID']);
			unset($_SESSION['LoggedInUserName']);
			unset($_SESSION['LoggedInUserIsOwnerInTheseCompanies']);
			
			$_SESSION['loginError'] = 
			'The specified email address or password was incorrect.';
			return FALSE;
		}
	}
	// If user wants to log out
	if (isset($_POST['action']) and $_POST['action'] == 'logout')
	{
		unset($_SESSION['loggedIn']);
		unset($_SESSION['email']);
		unset($_SESSION['password']);
		unset($_SESSION['LoggedInUserID']);
		unset($_SESSION['LoggedInUserName']);
		unset($_SESSION['LoggedInUserIsOwnerInTheseCompanies']);
		header('Location: ' . $_POST['goto']);
		exit();
	}
	
	// The user is in a session that was previously logged in
	// Let's check if the user STILL EXISTS in the database
	// i.e. if the login info is still correct
	// This causes an extra SQL QUERY every single time a page
	// is loaded again. But is more secure than just checking for the 
	// loggedIn = true session variable in the case that user info
	// has been altered while someone is already logged in with old data
	if (isset($_SESSION['loggedIn']))
	{
		return databaseContainsUser($_SESSION['email'],
		$_SESSION['password']);
	}
}

// Function to check if the submitted user exists in our database
// AND has been activated
function databaseContainsUser($email, $password)
{
	try
	{
		include_once 'db.inc.php';
		$pdo = connect_to_db();
		$sql = 'SELECT 	COUNT(*),
						`userID`,
						`firstname`,
						`lastname`
				FROM 	`user`
				WHERE 	email = :email 
				AND 	password = :password
				AND		`isActive` > 0
				LIMIT 	1';
		$s = $pdo->prepare($sql);
		$s->bindValue(':email', $email);
		$s->bindValue(':password', $password);
		$s->execute();
		
		$pdo = null;
	}
	catch (PDOException $e)
	{
		$error = 'Error searching for user.';
		include_once 'error.html.php';
		$pdo = null;
		exit();
	}
	
	$row = $s->fetch();
	// If we got a hit, then the user info was correct
	if ($row[0] > 0)
	{
		$_SESSION['DatabaseContainsUserID'] = $row['userID'];
		$_SESSION['DatabaseContainsUserName'] = $row['lastname'] . ", " . $row['firstname'];
		return TRUE;
	}
	else
	{
		unset($_SESSION['DatabaseContainsUserID']);		
		return FALSE;
	}
}

// Check if user has the specific access we're looking for
function userHasAccess($access)
{
	try
	{
		include_once 'db.inc.php';
		$pdo = connect_to_db();
		$sql = "SELECT 		COUNT(*) 
				FROM 		`user` u
				INNER JOIN 	accesslevel a
				ON 			u.AccessID = a.AccessID
				WHERE 		u.email = :email 
				AND 		a.AccessName = :AccessName
				LIMIT 	1";
		$s = $pdo->prepare($sql);
		$s->bindValue(':email', $_SESSION['email']);
		$s->bindValue(':AccessName', $access);
		$s->execute();
		
		$pdo = null;
	}
	catch (PDOException $e)
	{
		$error = 'Error searching for user access.';
		include_once 'error.html.php';
		$pdo = connect_to_db();
		exit();
	}
	
	$row = $s->fetch();
	if ($row[0] > 0)
	{
		// User has the access we were looking for!
		return TRUE;
	}
	else
	{
		// User does NOT have the access needed.
		return FALSE;
	}
}

// Function to check if the email submitted already is being used
function databaseContainsEmail($email)
{
	try
	{
		include_once 'db.inc.php';
		$pdo = connect_to_db();
		$sql = 'SELECT 	COUNT(*) 
				FROM 	`user`
				WHERE 	email = :email
				LIMIT 	1';
		$s = $pdo->prepare($sql);
		$s->bindValue(':email', $email);
		$s->execute();
		
		$pdo = null;
	}
	catch (PDOException $e)
	{
		$error = 'Error validating email.';
		include_once 'error.html.php';
		$pdo = null;
		exit();
	}
	
	$row = $s->fetch();
	// If we got a hit, then the email exists in our database
	if ($row[0] > 0)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

// Function to check if the booking code submitted already is being used
// TO-DO: UNTESTED
function databaseContainsBookingCode($rawBookingCode)
{
	$hashedBookingCode = hashPassword($rawBookingCode);
	
	try
	{
		include_once 'db.inc.php';
		$pdo = connect_to_db();
		$sql = 'SELECT 	COUNT(*) 
				FROM 	`user`
				WHERE 	`bookingCode` = :BookingCode
				AND		`isActive` > 0
				LIMIT 	1';
		$s = $pdo->prepare($sql);
		$s->bindValue(':BookingCode', $hashedBookingCode);
		$s->execute();
		
		$pdo = null;		
	}
	catch(PDOException $e)
	{
		$error = 'Error validating booking code.';
		include_once 'error.html.php';
		$pdo = null;
		exit();		
	}
	
	$row = $s->fetch();
	// If we got a hit, then the booking code exists in our database
	if ($row[0] > 0)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}	
	
}

// Function to get user information based on the booking code submitted
// TO-DO: UNTESTED
function getUserInfoFromBookingCode($rawBookingCode)
{
	if(!databaseContainsBookingCode($rawBookingCode))
	{
		// The booking code we received does not exist in the database.
		// Can't retrieve any info then
		return NULL;
	}
	
	// We know the code exists. Let's get the info of the person it belongs to
	$hashedBookingCode = hashPassword($rawBookingCode);
	
	try
	{
		include_once 'db.inc.php';
		$pdo = connect_to_db();
		$sql = "SELECT 	`userID`						AS TheUserID,
						`email`							AS TheUserEmail,
						`firstName`						AS TheUserFirstname,
						`lastName`						AS TheUserLastname,
						`displayName`					AS TheUserDisplayName,
						`bookingDescription`			AS TheUserBookingDescription
				FROM 	`user`
				WHERE 	`bookingCode` = :BookingCode
				AND		`isActive` > 0
				LIMIT 	1";
		$s = $pdo->prepare($sql);
		$s->bindValue(':BookingCode', $hashedBookingCode);
		$s->execute();
		
		$pdo = null;		
	}
	catch(PDOException $e)
	{
		$error = 'Error fetching user info based on booking code.';
		include_once 'error.html.php';
		$pdo = null;
		exit();		
	}
	
	$row = $s->fetch();
	return $row;
}

// Function to make sure user is Admin
function isUserAdmin(){
		// Check if user is logged in
	if (!userIsLoggedIn())
	{
		// Not logged in. Send user a login prompt.
		include_once '../login.html.php';
		exit();
	}
		// Check if user has Admin access
	if (!userHasAccess('Admin'))
	{
		// User is NOT ADMIN.
		$error = 'Only Admin may access this page.';
		include_once '../accessdenied.html.php';
		return false;
	}
	return true;
}

// Function to make sure user is In-House User
function isUserInHouseUser(){
	// Check if user is logged in
	if (!userIsLoggedIn())
	{
		// Not logged in. Send user a login prompt.
		include_once '../login.html.php';
		exit();
	}
		// Check if user has In-House User access
	if (!userHasAccess('In-House User'))
	{
		// User is NOT IN-HOUSE USER.
		$error = 'Only In-House Users can access this page.';
		include_once '../accessdenied.html.php';
		return false;
	}
	return true;
}

// Function to make sure user is the owner of the company
// TO-DO: UNTESTED!
function isUserCompanyOwner(){
	session_start();
	
	if(!isset($_SESSION['LoggedInUserIsOwnerInTheseCompanies'])){
		// Check if user is a company owner
		try
		{
			$UserID = $_SESSION['LoggedInUserID'];
			
			include_once 'db.inc.php';
			$pdo = connect_to_db();
			$sql = "SELECT 		COUNT(*),
								c.`name`		AS CompanyName,
								c.`companyID`   AS CompanyID
					FROM 		`employee` e
					INNER JOIN 	`companyposition` cp
					ON			e.`PositionID` = cp.`PositionID`
					LEFT JOIN	`company` c
					ON			c.`companyID` = e.`companyID`
					WHERE 		e.`UserID` = :UserID 
					AND 		cp.`name` = 'Owner'";
			$s = $pdo->prepare($sql);
			$s->bindValue(':UserID', $UserID);
			$s->execute();
			
			$pdo = null;
		}
		catch (PDOException $e)
		{
			$error = 'Error checking if user is company owner.' . $e->getMessage();
			include_once 'error.html.php';
			$pdo = null;
			exit();
		}
		 
		$result = $s->fetchAll();
		// If we got a hit, then the user is an owner for at least 1 company in our database
		if ($result[0] > 0)
		{
			foreach($result AS $row){
				$OwnerInCompanies[] = array (
												'CompanyName' => $row['CompanyName'],
												'CompanyID' => $row['CompanyID']
											);
			}
			
			$_SESSION['LoggedInUserIsOwnerInTheseCompanies'] = $OwnerInCompanies;
			
			return TRUE;
		}
		else
		{
			unset($_SESSION['LoggedInUserIsOwnerInTheseCompanies']);
			return FALSE;
		}
	} else {
		return TRUE;
	}
}

?>