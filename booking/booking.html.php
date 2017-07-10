<!-- This is the HTML form used to display booking information to normal users-->
<?php include_once $_SERVER['DOCUMENT_ROOT'] .
 '/includes/helpers.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" HTTP-EQUIV="refresh" CONTENT="<?php htmlout(SECONDS_BEFORE_REFRESHING_BOOKING_PAGE); ?>"> <!-- Refreshes every 30 sec -->
		<title>Booking Information</title>
		<link rel="stylesheet" type="text/css" href="/CSS/myCSS.css">
		<script src="/scripts/myFunctions.js"></script>		
	</head>
	<body onload="startTime()">
		<?php include_once $_SERVER['DOCUMENT_ROOT'] .'/includes/topnav.html.php'; ?>
	
		<div id="ClockPlacement">
			<b id="Clock"></b>
		</div>
		
		<div id="feedback">
		<?php if(isset($_SESSION['normalBookingFeedback'])) : ?>
			<b><?php htmlout($_SESSION['normalBookingFeedback']); ?></b>
			<?php unset($_SESSION['normalBookingFeedback']); ?>
		<?php endif; ?>
		</div>
		
		<?php if(isset($_GET['cancellationcode'])) : ?>
			<h1>Cancel Your Booking!</h1>
		<?php elseif(isset($_SESSION['loggedIn']) AND $_SESSION['loggedIn']) : ?>
			<h1>Booking Information Overview</h1>
			
			<?php if(isset($_SESSION['LoggedInUserName'])) : ?>
				<h3>Logged in as <?php htmlout($_SESSION['LoggedInUserName']); ?>.</h3>
			<?php else : ?>
				<h3>Logged in</h3>
			<?php endif; ?>
			
			<form action="" method="post">
				<div>
					<input type="submit" name="action" value="Create Meeting">
					<input type="submit" name="action" value="Refresh">
					<b>Last Refresh: <?php htmlout(getDatetimeNowInDisplayFormat()); ?></b>
				</div>		
			</form>
			
			<table>
				<caption>Active Bookings Today</caption>
				<tr>
					<th colspan="8">Booking information</th>
					<th colspan="2">Alter Booking</th>
				</tr>				
				<tr>
					<th>Status</th>
					<th>Room Name</th>
					<th>Start Time</th>
					<th>End Time</th>
					<th>Display Name</th>
					<th>For Company</th>
					<th>Description</th>
					<th>Created At</th>
					<th>Edit</th>			
					<th>Cancel</th>
				</tr>
			<?php if(isset($bookingsActiveToday)) :?>					
				<?php foreach ($bookingsActiveToday AS $booking): ?>
					<form action="" method="post">				
						<?php if(isset($_SESSION['LoggedInUserID']) AND $_SESSION['LoggedInUserID'] == $booking['BookedUserID']) : ?>					
							<tr class="LoggedInUserBooking">
						<?php else : ?>
							<tr>
						<?php endif; ?>
							<td><?php htmlout($booking['BookingStatus']);?></td>
							<td><?php htmlout($booking['BookedRoomName']); ?></td>
							<td><?php htmlout($booking['StartTime']); ?></td>
							<td>
								<?php if($booking['BookingStatus'] == "Completed Today") : ?>
									<?php htmlout($booking['BookingWasCompletedOn']); ?>
								<?php else : ?>
									<?php htmlout($booking['EndTime']); ?>
								<?php endif; ?>
							</td>
							<td><?php htmlout($booking['BookedBy']); ?></td>
							<td><?php htmlout($booking['BookedForCompany']); ?></td>
							<td><?php htmlout($booking['BookingDescription']); ?></td>
							<td><?php htmlout($booking['BookingWasCreatedOn']); ?></td>
							<td>
								<?php if(isset($_SESSION['LoggedInUserID']) AND $_SESSION['LoggedInUserID'] == $booking['BookedUserID']) : ?>
									<input type="submit" name="action" value="Edit">
								<?php endif; ?>
							</td>							
							<td>
								<?php if(isset($_SESSION['LoggedInUserID']) AND $_SESSION['LoggedInUserID'] == $booking['BookedUserID']) : ?>
									<input type="submit" name="action" value="Cancel">
								<?php endif; ?>
							</td>
							<input type="hidden" name="id" value="<?php htmlout($booking['id']); ?>">
							<input type="hidden" name="UserInfo" id="UserInfo"
							value="<?php htmlout($booking['UserInfo']); ?>">
							<input type="hidden" name="MeetingInfo" id="MeetingInfo"
							value="<?php htmlout($booking['MeetingInfo']); ?>">
							<input type="hidden" name="BookingStatus" id="BookingStatus"
							value="<?php htmlout($booking['BookingStatus']); ?>">
							<input type="hidden" name="Email" id="Email"
							value="<?php htmlout($booking['email']); ?>">
						</tr>
					</form>
					<?php endforeach; ?>
			<?php endif; ?>				
				</table>
				<table>
					<caption>Future Bookings</caption>
					<tr>
						<th colspan="8">Booking information</th>
						<th colspan="2">Alter Booking</th>
					</tr>				
					<tr>
						<th>Status</th>
						<th>Room Name</th>
						<th>Start Time</th>
						<th>End Time</th>
						<th>Display Name</th>
						<th>For Company</th>
						<th>Description</th>
						<th>Created At</th>
						<th>Edit</th>			
						<th>Cancel</th>
					</tr>
				<?php if(isset($bookingsFuture)) :?>		
					<?php foreach ($bookingsFuture AS $booking): ?>
						<form action="" method="post">
						<?php if(isset($_SESSION['LoggedInUserID']) AND $_SESSION['LoggedInUserID'] == $booking['BookedUserID']) : ?>					
							<tr class="LoggedInUserBooking">
						<?php else : ?>
							<tr>
						<?php endif; ?>
								<td><?php htmlout($booking['BookingStatus']);?></td>
								<td><?php htmlout($booking['BookedRoomName']); ?></td>
								<td><?php htmlout($booking['StartTime']); ?></td>
								<td><?php htmlout($booking['EndTime']); ?></td>
								<td><?php htmlout($booking['BookedBy']); ?></td>
								<td><?php htmlout($booking['BookedForCompany']); ?></td>
								<td><?php htmlout($booking['BookingDescription']); ?></td>
								<td><?php htmlout($booking['BookingWasCreatedOn']); ?></td>
								<td>
									<?php if(isset($_SESSION['LoggedInUserID']) AND $_SESSION['LoggedInUserID'] == $booking['BookedUserID']) : ?>
										<input type="submit" name="action" value="Edit">
									<?php endif; ?>
								</td>							
								<td>
									<?php if(isset($_SESSION['LoggedInUserID']) AND $_SESSION['LoggedInUserID'] == $booking['BookedUserID']) : ?>
										<input type="submit" name="action" value="Cancel">
									<?php endif; ?>
								</td>
								<input type="hidden" name="id" value="<?php htmlout($booking['id']); ?>">
								<input type="hidden" name="UserInfo" id="UserInfo"
								value="<?php htmlout($booking['UserInfo']); ?>">
								<input type="hidden" name="MeetingInfo" id="MeetingInfo"
								value="<?php htmlout($booking['MeetingInfo']); ?>">
								<input type="hidden" name="BookingStatus" id="BookingStatus"
								value="<?php htmlout($booking['BookingStatus']); ?>">
								<input type="hidden" name="Email" id="Email"
								value="<?php htmlout($booking['email']); ?>">
							</tr>
						</form>
					<?php endforeach; ?>
				<?php endif; ?>	
				</table>
		<?php elseif(!isset($_SESSION['loggedIn'])) : ?>
			<h1>Booking Information Overview</h1>
			
			<?php if(!isset($_SESSION["DefaultMeetingRoomInfo"])) : ?>
				<form action="" method="post">
					<input type="submit" name="login" value="Log In">
				</form>
			<?php endif; ?>
			
			<form action="" method="post">
				<div>
					<?php if(isset($_SESSION["DefaultMeetingRoomInfo"])) : ?>
						<input type="submit" name="action" value="Create Meeting">
					<?php endif; ?>
					<input type="submit" name="action" value="Refresh">
					<b>Last Refresh: <?php htmlout(getDatetimeNowInDisplayFormat()); ?></b>
				</div>		
			</form>
			
			<table>
				<caption>Bookings Today</caption>
				<tr>
					<th colspan="4">Booking information</th>
					<th colspan="2">Alter Booking</th>
				</tr>				
				<tr>
					<th>Status</th>
					<th>Room Name</th>
					<th>Start Time</th>
					<th>End Time</th>
					<th>Edit</th>			
					<th>Cancel</th>
				</tr>
			<?php if(isset($bookingsActiveToday)) :?>				
				<?php foreach ($bookingsActiveToday AS $booking): ?>
					<form action="" method="post">
						<tr>
							<td><?php htmlout($booking['BookingStatus']);?></td>
							<td><?php htmlout($booking['BookedRoomName']); ?></td>
							<td><?php htmlout($booking['StartTime']); ?></td>
							<td>
								<?php if($booking['BookingStatus'] == "Completed Today") : ?>
									<?php htmlout($booking['BookingWasCompletedOn']); ?>
								<?php else : ?>
									<?php htmlout($booking['EndTime']); ?>
								<?php endif; ?>
							</td>
							<td>
								<?php if(isset($_SESSION["DefaultMeetingRoomInfo"])) : ?>
									<input type="submit" name="action" value="Edit">
								<?php endif; ?>
							</td>							
							<td>
								<?php if(isset($_SESSION["DefaultMeetingRoomInfo"])) : ?>
									<input type="submit" name="action" value="Cancel">
								<?php endif; ?>
							</td>
							<input type="hidden" name="id" value="<?php htmlout($booking['id']); ?>">
							<input type="hidden" name="MeetingInfo" id="MeetingInfo"
							value="<?php htmlout($booking['MeetingInfo']); ?>">
							<input type="hidden" name="BookingStatus" id="BookingStatus"
							value="<?php htmlout($booking['BookingStatus']); ?>">
						</tr>
					</form>
				<?php endforeach; ?>
			<?php endif; ?>	
			</table>
			
			<table>
				<caption>Future Bookings</caption>
				<tr>
					<th colspan="4">Booking information</th>
					<th colspan="2">Alter Booking</th>
				</tr>				
				<tr>
					<th>Status</th>
					<th>Room Name</th>
					<th>Start Time</th>
					<th>End Time</th>
					<th>Edit</th>			
					<th>Cancel</th>
				</tr>
			<?php if(isset($bookingsFuture)) :?>		
				<?php foreach ($bookingsFuture AS $booking): ?>
					<form action="" method="post">			
						<tr>
							<td><?php htmlout($booking['BookingStatus']);?></td>
							<td><?php htmlout($booking['BookedRoomName']); ?></td>
							<td><?php htmlout($booking['StartTime']); ?></td>
							<td><?php htmlout($booking['EndTime']); ?></td>
							<td>
								<?php if(isset($_SESSION["DefaultMeetingRoomInfo"])) : ?>
									<input type="submit" name="action" value="Edit">
								<?php endif; ?>
							</td>							
							<td>
								<?php if(isset($_SESSION["DefaultMeetingRoomInfo"])) : ?>
									<input type="submit" name="action" value="Cancel">
								<?php endif; ?>
							</td>
							<input type="hidden" name="id" value="<?php htmlout($booking['id']); ?>">
							<input type="hidden" name="MeetingInfo" id="MeetingInfo"
							value="<?php htmlout($booking['MeetingInfo']); ?>">
							<input type="hidden" name="BookingStatus" id="BookingStatus"
							value="<?php htmlout($booking['BookingStatus']); ?>">
						</tr>
					</form>
				<?php endforeach; ?>
			</table>
			
			<?php endif; ?>
			</form>		
		<?php endif; ?>
		
		<?php if(isset($_SESSION['loggedIn'])) : ?>
			<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logout.inc.html.php'; ?>
		<?php endif; ?>
	</body>
</html>