<!-- This is the HTML form used for CHANGING ROOMEQUIPMENT AMOUNT-->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="/CSS/myCSS.css">
		<script src="/scripts/myFunctions.js"></script>
		<title>Change Meeting Room Equipment Amount</title>
		<style>
			label {
				width: 150px;
			}
		</style>
	</head>
	<body onload="startTime()">
		<?php include_once $_SERVER['DOCUMENT_ROOT'] .'/includes/admintopnav.html.php'; ?>

		<fieldset><legend>Change Meeting Room Equipment Amount</legend>
			<form method="post">
				<div>
					<label for="EquipmentName">Equipment Name:</label>
					<span><b id="EquipmentName"><?php htmlout($EquipmentName); ?></b></span>
				</div>
				<div>
					<label for="MeetingRoomName">Meeting Room Name:</label>
					<span><b id="MeetingRoomName"><?php htmlout($MeetingRoomName); ?></b></span>
				</div>
				<div>
					<label for="CurrentEquipmentAmount">Current Amount:</label>
					<span><b id="CurrentEquipmentAmount"><?php htmlout($CurrentEquipmentAmount); ?></b></span>
				</div>
				<div>
					<label for="EquipmentAmount">Set New Amount:</label>
					<input type="number" name="EquipmentAmount" 
					min="1" max="255" value="<?php htmlout($EquipmentAmount); ?>">
				</div>
				<div class="left">
					<input type="hidden" name="EquipmentID" value="<?php htmlout($EquipmentID); ?>">
					<input type="hidden" name="MeetingRoomID" value="<?php htmlout($MeetingRoomID); ?>">
					<input type="submit" name="action" value="Confirm Amount">
					<input type="submit" name="edit" value="Cancel">
				</div>
			</form>
		</fieldset>
	</body>
</html>