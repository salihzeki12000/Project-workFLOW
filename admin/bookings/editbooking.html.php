<!-- This is the HTML form used for EDITING BOOKING information-->
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Edit Booking</title>
	</head>
	<body>
		<h1>Edit Booking</h1>
		<?php if(isset($_SESSION['EditBookingError'])) : ?>
			<p><b><?php htmlout($_SESSION['EditBookingError']); ?></b></p>
			<?php unset($_SESSION['EditBookingError']); ?>
		<?php endif; ?>
		<form action="" method="post">
			<div>
				<label for="originalMeetingRoomName">Booked Meeting Room: </label>
				<b><?php htmlout($originalMeetingRoomName); ?></b>
			</div>
			<div>
				<label for="meetingRoomID">Set New Meeting Room: </label>
				<select name="meetingRoomID" id="meetingRoomID">
					<?php foreach($meetingroom as $row): ?> 
						<?php if($row['meetingRoomID']==$selectedMeetingRoomID):?>
							<option selected="selected" 
									value=<?php htmlout($row['meetingRoomID']); ?>>
									<?php htmlout($row['meetingRoomName']);?>
							</option>
						<?php else : ?>
							<option value=<?php htmlout($row['meetingRoomID']); ?>>
									<?php htmlout($row['meetingRoomName']);?>
							</option>
						<?php endif;?>
					<?php endforeach; ?>
				</select>				
			</div>
			<div>
				<label for="originalStartDateTime">Booked Start Time: </label>
				<b><?php htmlout($originalStartDateTime); ?></b>
			</div>
			<div>
				<label for="startDateTime">Set New Start Time: </label>				
				<input type="text" name="startDateTime" id="startDateTime" 
				required placeholder="dd-mm-yyyy hh:mm:ss" 
				oninvalid="this.setCustomValidity('Enter Your Starting Date And Time Here')"
				oninput="setCustomValidity('')"
				value="<?php htmlout($startDateTime); ?>">
			</div>
			<div>	
				<label for="originalEndDateTime">Booked End Time: </label>
				<b><?php htmlout($originalEndDateTime); ?></b>
			</div>
			<div>
				<label for="endDateTime">Set New End Time: </label>
				<input type="text" name="endDateTime" id="endDateTime" 
				required placeholder="dd-mm-yyyy hh:mm:ss" 
				oninvalid="this.setCustomValidity('Enter Your Ending Date And Time Here')"
				oninput="setCustomValidity('')"
				value="<?php htmlout($endDateTime); ?>">
			</div>
			<?php if(isset($_SESSION['EditBookingChangeUser']) AND $_SESSION['EditBookingChangeUser']) :?>
				<div>
					<label for="originalSelectedUser">Booked For User: </label>
					<b><?php htmlout($originalUserInformation); ?></b>
				</div>
				<div>
					<label for="SelectedUser">Select A New User: </label>
					<?php if(isset($users)) : ?>
						<select name="userID" id="userID">
							<?php foreach($users as $row): ?> 
								<?php if($row['userID']==$SelectedUserID):?>
									<option selected="selected" 
											value=<?php htmlout($row['userID']); ?>>
											<?php htmlout($row['userInformation']);?>
									</option>
								<?php else : ?>
									<option value=<?php htmlout($row['userID']); ?>>
											<?php htmlout($row['userInformation']);?>
									</option>
								<?php endif;?>
							<?php endforeach; ?>
						</select>
						<input type="submit" name="action" value="Select This User">
					<?php else : ?>
						<b>The search found 0 users.</b>
					<?php endif; ?>
				</div>
				<div>
					<label for="usersearchstring">Search for User:</label>
					<input type="text" name="usersearchstring" 
					value=<?php htmlout($usersearchstring); ?>>
					<input type="submit" name="action" value="Search">
				</div>
			<?php else : ?>
				<div>
					<label for="UserInformation">Booked For User: </label>
					<b><?php htmlout($userInformation); ?> </b>
					<input type="submit" name="action" value="Change User">
					<input type="hidden" name="userID" id="userID"
					value="<?php htmlout($SelectedUserID);?>">
				</div>			
			<?php endif; ?>
			<div>
				<label for="originalCompanyInBooking">Booked for Company: </label>
				<?php if(isset($originalCompanyName)) :?>
					<b><?php htmlout($originalCompanyName); ?></b>
				<?php else : ?>
					<b>This user does not work for a company</b>
				<?php endif; ?>
			</div>
			<div>
				<?php if($displayCompanySelect == TRUE) : ?>
					<label for="companyID">Set New Company: </label>
					<select name="companyID" id="companyID">
						<?php foreach($company as $row): ?> 
							<?php if($row['companyID']==$selectedCompanyID):?>
								<option selected="selected" 
										value=<?php htmlout($row['companyID']); ?>>
										<?php htmlout($row['companyName']);?>
								</option>
							<?php else : ?>
								<option value=<?php htmlout($row['companyID']); ?>>
										<?php htmlout($row['companyName']);?>
								</option>
							<?php endif;?>
						<?php endforeach; ?>
					</select>
				<?php else : ?>
					<input type="hidden" name="companyID" id="companyID" 
					value="<?php htmlout($companyID); ?>">
				<?php endif; ?>
			</div>
			<div>
				<label for="originalDisplayName">Booked Display Name: </label>
				<b>
					<?php if($originalDisplayName == "") : ?>
						This booking has no Display Name set.
					<?php else : ?>
						<?php htmlout($originalDisplayName); ?>
					<?php endif; ?>
				</b>
			</div>
			<div>
				<label for="displayName">Set New Display Name: </label>
				<input type="text" name="displayName" id="displayName" 
				value="<?php htmlout($displayName); ?>">
			</div>
			<div>
				<label for="originalBookingDescription">Booked Description: </label>
				<b>
					<?php if($originalBookingDescription == "") : ?>
						This booking has no Booking Description set.
					<?php else : ?>
						<?php htmlout($originalBookingDescription); ?>
					<?php endif; ?>
				</b>
			</div>
			<div>
				<label for="description">Set New Booking Description: </label>
				<input type="text" name="description" id="description" 
				value="<?php htmlout($description); ?>">
			</div>
			<div>
				<input type="hidden" name="bookingID" id="bookingID" 
				value="<?php htmlout($bookingID); ?>">
				<?php if(isset($_SESSION['EditBookingChangeUser']) AND $_SESSION['EditBookingChangeUser']) : ?>
					<b>You need to select the user you want before you can finish editing.</b>
				<?php else : ?>
					<input type="submit" name="action" value="Edit Booking">
				<?php endif; ?>
			</div>
		</form>
	<p><a href="..">Return to CMS home</a></p>
	<?php include '../logout.inc.html.php'; ?>
	</body>
</html>