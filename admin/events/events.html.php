<!--This is the HTML form for DISPLAYING a list of EVENTS -->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="/CSS/myCSS.css">
		<script src="/scripts/myFunctions.js"></script>
		<title>Scheduled Events</title>
	</head>
	<body onload="startTime()">
		<?php include_once $_SERVER['DOCUMENT_ROOT'] .'/includes/admintopnav.html.php'; ?>

		<h1>Scheduled Events</h1>

		<form method="post">
				<div class="right"">
				<?php if(isSet($_SESSION['eventsEnableDelete']) AND $_SESSION['eventsEnableDelete']) : ?>
					<input type="submit" name="action" value="Disable Delete">
				<?php else : ?>
					<input type="submit" name="action" value="Enable Delete">
				<?php endif; ?>
			</div>
		</form>

		<?php if(isSet($_SESSION['EventsUserFeedback'])) : ?>
			<div class="left">
				<span><b class="feedback"><?php htmlout($_SESSION['EventsUserFeedback']); ?></b></span>
				<?php unset($_SESSION['EventsUserFeedback']); ?>
			</div>
		<?php endif; ?>

		<table class="myTable">
			<caption>Active Events</caption>
			<tr>
				<th colspan="11">Event information</th>
				<th>Alter Event</th>
			</tr>
			<tr>
				<th>Status</th>
				<th>Next Start</th>
				<th>Event Name</th>
				<th>Description</th>
				<th>Meeting Room(s)</th>
				<th>Day(s) Selected</th>
				<th>Start Time</th>
				<th>End Time</th>
				<th>First Date</th>
				<th>Last Date</th>
				<th>Created At</th>
				<th>Delete</th>
			</tr>
		<?php if(isSet($activeEvents)) : ?>
			<?php foreach ($activeEvents AS $event) : ?>
				<form method="post">
					<tr>
						<td style="white-space: pre-wrap;"><?php htmlout($event['EventStatus']); ?></td>
						<td><?php htmlout($event['NextStart']); ?></td>
						<td style="white-space: pre-wrap;"><?php htmlout($event['EventName']); ?></td>
						<td style="white-space: pre-wrap;"><?php htmlout($event['EventDescription']); ?></td>
						<td style="white-space: pre-wrap;"><?php htmlout($event['UsedMeetingRooms']); ?></td>
						<td style="white-space: pre-wrap;"><?php htmlout($event['DaysSelected']); ?></td>
						<td><?php htmlout($event['StartTime']); ?></td>
						<td><?php htmlout($event['EndTime']); ?></td>
						<td style="white-space: pre-wrap;"><?php htmlout($event['StartDate']); ?></td>
						<td style="white-space: pre-wrap;"><?php htmlout($event['LastDate']); ?></td>
						<td><?php htmlout($event['DateTimeCreated']); ?></td>
						<td>
							<?php if(isSet($_SESSION['eventsEnableDelete']) AND $_SESSION['eventsEnableDelete']) : ?>
								<input type="submit" name="action" value="Delete">
							<?php else : ?>
								<input type="submit" name="disabled" value="Delete" disabled>
							<?php endif; ?>
						</td>
						<input type="hidden" name="EventID" value="<?php htmlout($event['EventID']); ?>">
						<input type="hidden" name="EventInfo" id="EventInfo"
						value="<?php htmlout($event['EventInfo']); ?>">
						<input type="hidden" name="EventStatus" id="EventStatus"
						value="<?php htmlout($event['EventStatus']); ?>">
					</tr>
				</form>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td colspan="12"><b>There are no active events.</b></td>
			</tr>
		<?php endif; ?>

			<form method="post">
				<tr>
					<td colspan="12">
						<input type="hidden" name="action" value="Create Event">
						<input type="submit" style="font-size: 150%; color: green;" value="+">
					</td>
				</tr>
			</form>

			<tr>
				<th colspan="12"></th>
			</tr>
		</table>

		<table class="myTable">
			<caption>Completed Events</caption>
			<tr>
				<th colspan="10">Event information</th>
				<th>Alter Event</th>
			</tr>
			<tr>
				<th>Status</th>
				<th>Event Name</th>
				<th>Description</th>
				<th>Meeting Room(s)</th>
				<th>Day(s) Selected</th>
				<th>Start Time</th>
				<th>End Time</th>
				<th>First Date</th>
				<th>Last Date</th>
				<th>Created At</th>
				<th>Delete</th>
			</tr>
		<?php if(isSet($completedEvents)) : ?>
			<?php foreach ($completedEvents AS $event) : ?>
				<form method="post">
					<tr>
						<td style="white-space: pre-wrap;"><?php htmlout($event['EventStatus']); ?></td>
						<td style="white-space: pre-wrap;"><?php htmlout($event['EventName']); ?></td>
						<td style="white-space: pre-wrap;"><?php htmlout($event['EventDescription']); ?></td>
						<td style="white-space: pre-wrap;"><?php htmlout($event['UsedMeetingRooms']); ?></td>
						<td style="white-space: pre-wrap;"><?php htmlout($event['DaysSelected']); ?></td>
						<td><?php htmlout($event['StartTime']); ?></td>
						<td><?php htmlout($event['EndTime']); ?></td>
						<td style="white-space: pre-wrap;"><?php htmlout($event['StartDate']); ?></td>
						<td style="white-space: pre-wrap;"><?php htmlout($event['LastDate']); ?></td>
						<td><?php htmlout($event['DateTimeCreated']); ?></td>
						<td>
							<?php if(isSet($_SESSION['eventsEnableDelete']) AND $_SESSION['eventsEnableDelete']) : ?>
								<input type="submit" name="action" value="Delete">
							<?php else : ?>
								<input type="submit" name="disabled" value="Delete" disabled>
							<?php endif; ?>
						</td>
						<input type="hidden" name="EventID" value="<?php htmlout($event['EventID']); ?>">
						<input type="hidden" name="EventInfo" id="EventInfo"
						value="<?php htmlout($event['EventInfo']); ?>">
						<input type="hidden" name="EventStatus" id="EventStatus"
						value="<?php htmlout($event['EventStatus']); ?>">
					</tr>
				</form>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td colspan="11"><b>There are no completed events.</b></td>
			</tr>
		<?php endif; ?>
		</table>

	</body>
</html>	