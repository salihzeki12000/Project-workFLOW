<!-- This is the HTML form used for EDITING or ADDING BOOKING information-->
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php htmlout($pageTitle); ?></title>
	</head>
	<body>
		<h1><?php htmlout($pageTitle); ?></h1>
		<form action="?<?php htmlout($action); ?>" method="post">
			<div>
				<label for="meetingRoomID">Meeting Room:
					<select name="meetingRoomID" id="meetingRoomID">
						<?php foreach($meetingroom as $row): ?> 
							<?php if($row['meetingRoomName']==$meetingroomname):?>
								<input type="hidden" name="MeetingRoomName" id="MeetingRoomName" value="<?php htmlout($row['meetingRoomName']); ?>">
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
				</label>
			</div>
			<div>
				<label for="startDateTime">Start Time: 
					<input type="text" name="startDateTime" id="startDateTime" 
					required placeholder="dd-mm-yyyy hh:mm:ss" 
					oninvalid="this.setCustomValidity('Enter Your Starting Date And Time Here')"
					oninput="setCustomValidity('')"
					value="<?php htmlout($startDateTime); ?>">
				</label>
			</div>
			<div>
				<label for="endDateTime">End Time: 
					<input type="text" name="endDateTime" id="endDateTime" 
					required placeholder="dd-mm-yyyy hh:mm:ss" 
					oninvalid="this.setCustomValidity('Enter Your Ending Date And Time Here')"
					oninput="setCustomValidity('')"
					value="<?php htmlout($endDateTime); ?>">
				</label>
			</div>
			<div>
				<?php if($displayCompanySelect == TRUE) : ?>
					<label for="companyID">Company: 
						<select name="companyID" id="companyID">
							<?php foreach($company as $row): ?> 
								<?php if($row['companyName']==$companyname):?>
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
					</label>
				<?php else : ?>
					<input type="hidden" name="companyID" id="companyID" 
					value="<?php htmlout($companyID); ?>">
				<?php endif; ?>
			</div>
			<div>
				<label for="displayName">Display Name: 
					<input type="text" name="displayName" id="displayName" 
					value="<?php htmlout($displayName); ?>">
				</label>
			</div>
			<div>
				<label for="description">Booking Description: 
					<input type="text" name="description" id="description" 
					value="<?php htmlout($description); ?>">
				</label>
			</div>
			<div>
				<input type="hidden" name="id" value="<?php htmlout($id); ?>">
				<input type="submit" value="<?php htmlout($button); ?>">
			</div>
			<div>
				<input type="<?php htmlout($reset); ?>">
			</div>
		</form>
	<p><a href="..">Return to CMS home</a></p>
	<?php include '../logout.inc.html.php'; ?>
	</body>
</html>