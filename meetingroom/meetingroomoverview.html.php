<!--This is the HTML form for DISPLAYING MEETING ROOMS for all users-->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Meeting Room</title>
		<link rel="stylesheet" type="text/css" href="/CSS/myCSS.css">
		<script src="/scripts/myFunctions.js"></script>
		<style>
			label {
				width: 85px;
			}
			table.innerTable th {
				height: 50px; 
				max-width: 100px;
				word-wrap: break-word;
			}
			table.innerTable tr {
				height: 40px;
			}
			table.innerTable td {
				min-width: 50px;
				max-width: 50px;
				word-wrap: break-word;
				height: inherit;
			}
			table.innerTable td.occupied {
				background-color: #ff3333;
				text-align: center;
			}
			table.innerTable td.available {
				background-color: #33ff33;
			}
			div.overflow {
				overflow: hidden;
				height: inherit;
			}
		</style>
		<script>
			var dateSelected = '<?php htmlout($dateSelected); ?>';

			function createBooking(meetingRoomID, timeSelected){
				alert("Trying to create booking for meeting room ID: " + meetingRoomID + " for the datetime: " + dateSelected + " " + timeSelected + ":00");
			}
			
			function editBooking(bookingID){
				alert("Trying to edit the booking with ID: " + bookingID);
			}
		</script>
	</head>
	<body onload="startTime(); refreshPageTimer(<?php htmlout(SECONDS_BEFORE_REFRESHING_MEETINGROOM_PAGE); ?>);">

		<?php include_once $_SERVER['DOCUMENT_ROOT'] .'/includes/topnav.html.php'; ?>

		<div class="left">
			<span><b>Last Refresh: <?php htmlout(getDatetimeNowInDisplayFormat()); ?></b></span>
			<form method="post">
				<?php if(isSet($_SESSION['DefaultMeetingRoomInfo'])) : ?>
				<?php $default = $_SESSION['DefaultMeetingRoomInfo']; ?>
					<?php if((!isSet($_GET['meetingroom'])) OR
							(isSet($_GET['meetingroom']) AND $_GET['meetingroom'] != $default['TheMeetingRoomID'])) : ?>
						<input type="submit" name="action" value="Show Default Room Only">
					<?php else : ?>
						<input type="submit" name="action" value="Show All Rooms">
					<?php endif; ?>
				<?php endif; ?>
			</form>
		</div>

		<?php if(isSet($_SESSION['DefaultMeetingRoomInfo']) AND !isSet($defaultMeetingRoomFeedback)) : ?>
			<div class="left">
				<form method="post">
					<label style="width: 295px;" for="defaultMeetingRoomName">The Default Meeting Room For This Device: </label>
					<span><b><?php htmlout($_SESSION['DefaultMeetingRoomInfo']['TheMeetingRoomName']); ?></b></span>
					<?php if($adminLoggedIn) : ?>
						<div class="left">
							<input type="submit" name="action" value="Change Default Room">
						</div>
					<?php endif; ?>
				</form>
			</div>
		<?php elseif($adminLoggedIn) : ?>
			<div class="left">
				<form method="post">
					<input type="submit" name="action" value="Set Default Room">
				</form>
			<div>
		<?php endif; ?>

		<?php if(isSet($_SESSION['MeetingRoomAllUsersFeedback'])) : ?>
			<div class="left"><b class="feedback"><?php htmlout($_SESSION['MeetingRoomAllUsersFeedback']); ?></b></div>
			<?php unset($_SESSION['MeetingRoomAllUsersFeedback']); ?>
		<?php endif; ?>

		<?php if(isSet($defaultMeetingRoomFeedback)) : ?>
			<div class="left"><b class="feedback"><?php htmlout($defaultMeetingRoomFeedback); ?></b></div>
		<?php endif; ?>

		<?php if(!empty($_GET['meetingroom'])) : ?>
			<?php if(!empty($meetingrooms)) : ?>
				<table><tr>
					<?php foreach($meetingrooms AS $meetingRoomID => $bookings): ?>
						<td><form method="post">
							<table class="innerTable">
								<?php if($displayingToday) : ?>
									<?php $currentStartTimeInMinutes = $timeNowInMinutes; ?> 
								<?php else : ?>
									<?php $currentStartTimeInMinutes = 0; ?>
								<?php endif; ?>
								<?php if(!empty($bookings)) : ?>
									<tr>
										<th colspan="2"><?php htmlout($bookings[0]['MeetingRoomName']); ?></th>
									</tr>
									<?php foreach($bookings AS $bookingInfo) : ?>
										<?php if(!empty($bookingInfo['StartTimeInMinutesSinceMidnight']) AND !empty($bookingInfo['EndTimeInMinutesSinceMidnight'])) : ?>
											<?php $nextStartTimeInMinutes = $bookingInfo['StartTimeInMinutesSinceMidnight']; ?>
											<?php $nextEndTimeInMinutes = $bookingInfo['EndTimeInMinutesSinceMidnight']; ?>
											<?php if($currentStartTimeInMinutes == $nextStartTimeInMinutes AND $timeNowInMinutes <= $nextStartTimeInMinutes) : ?>
												<?php if($nextEndTimeInMinutes > $nextStartTimeInMinutes+$bookingMinuteChunks) : ?>
													<?php for($currentStartTimeInMinutes; $currentStartTimeInMinutes<$nextEndTimeInMinutes;) : ?>
														<?php $endTimeInMinutes = getNextBookingEndTime($currentStartTimeInMinutes, $currentStartTimeInMinutes+$bookingMinuteChunks, $bookingMinuteChunks); ?>
														<tr onclick="editBooking(<?php htmlout($bookingInfo['BookingID']); ?>)">
															<td><?php echo convertMinutesToTime($currentStartTimeInMinutes); ?> - <?php echo convertMinutesToTime($endTimeInMinutes); ?></td>
															<td class="occupied"><div class="overflow"><?php htmlout($bookingInfo['BookingDisplayName']); ?></div></td>
														</tr>
														<?php $currentStartTimeInMinutes = $endTimeInMinutes; ?>
													<?php endfor; ?>
												<?php else : ?>
													<tr onclick="editBooking(<?php htmlout($bookingInfo['BookingID']); ?>)">
														<td><?php htmlout($bookingInfo['MeetingStartTime']); ?> - <?php htmlout($bookingInfo['MeetingEndTime']); ?></td>
														<td class="occupied"><div class="overflow"><?php htmlout($bookingInfo['BookingDisplayName']); ?></div></td>
													</tr>
												<?php endif; ?>
											<?php elseif($currentStartTimeInMinutes >= $nextStartTimeInMinutes AND $timeNowInMinutes > $nextStartTimeInMinutes) : ?>
												<?php if($nextEndTimeInMinutes > $nextStartTimeInMinutes+$bookingMinuteChunks) : ?>
													<?php for($currentStartTimeInMinutes; $currentStartTimeInMinutes<$nextEndTimeInMinutes;) : ?>
														<?php $endTimeInMinutes = getNextBookingEndTime($currentStartTimeInMinutes, $currentStartTimeInMinutes+$bookingMinuteChunks, $bookingMinuteChunks); ?>
														<tr onclick="editBooking(<?php htmlout($bookingInfo['BookingID']); ?>)">
															<td><?php echo convertMinutesToTime($currentStartTimeInMinutes); ?> - <?php echo convertMinutesToTime($endTimeInMinutes); ?></td>
															<td class="occupied"><div class="overflow"><?php htmlout($bookingInfo['BookingDisplayName']); ?></div></td>
														</tr>
														<?php $currentStartTimeInMinutes = $endTimeInMinutes; ?>
													<?php endfor; ?>
												<?php else : ?>
													<tr onclick="editBooking(<?php htmlout($bookingInfo['BookingID']); ?>)">
														<td><?php htmlout($displayTimeNow); ?> - <?php htmlout($bookingInfo['MeetingEndTime']); ?></td>
														<td class="occupied"><div class="overflow"><?php htmlout($bookingInfo['BookingDisplayName']); ?></div></td>
													</tr>
												<?php endif; ?>
											<?php else : ?>
												<?php for($currentStartTimeInMinutes; $currentStartTimeInMinutes < $nextStartTimeInMinutes;) : ?>
													<?php if($currentStartTimeInMinutes+$bookingMinuteChunks > $nextStartTimeInMinutes) : ?>
														<?php $endTimeInMinutes = getNextBookingEndTime($currentStartTimeInMinutes, $nextStartTimeInMinutes, $bookingMinuteChunks); ?>
													<?php else : ?>
														<?php $endTimeInMinutes = getNextBookingEndTime($currentStartTimeInMinutes, $currentStartTimeInMinutes+$bookingMinuteChunks, $bookingMinuteChunks); ?>
													<?php endif; ?>
													<tr onclick="createBooking('<?php htmlout($meetingRoomID); ?>','<?php echo convertMinutesToTime($currentStartTimeInMinutes); ?>')">
														<td><?php echo convertMinutesToTime($currentStartTimeInMinutes); ?> - <?php echo convertMinutesToTime($endTimeInMinutes); ?></td>
														<td class="available"></td>
													</tr>
													<?php $currentStartTimeInMinutes = $endTimeInMinutes; ?>
												<?php endfor; ?>
												<?php if($nextEndTimeInMinutes > $nextStartTimeInMinutes+$bookingMinuteChunks) : ?>
													<?php for($currentStartTimeInMinutes; $currentStartTimeInMinutes<$nextEndTimeInMinutes;) : ?>
														<?php $endTimeInMinutes = getNextBookingEndTime($currentStartTimeInMinutes, $currentStartTimeInMinutes+$bookingMinuteChunks, $bookingMinuteChunks); ?>
														<tr onclick="editBooking(<?php htmlout($bookingInfo['BookingID']); ?>)">
															<td><?php echo convertMinutesToTime($currentStartTimeInMinutes); ?> - <?php echo convertMinutesToTime($endTimeInMinutes); ?></td>
															<td class="occupied"><div class="overflow"><?php htmlout($bookingInfo['BookingDisplayName']); ?></div></td>
														</tr>
														<?php $currentStartTimeInMinutes = $endTimeInMinutes; ?>
													<?php endfor; ?>
												<?php else : ?>
													<tr onclick="editBooking(<?php htmlout($bookingInfo['BookingID']); ?>)">
														<td><?php htmlout($bookingInfo['MeetingStartTime']); ?> - <?php htmlout($bookingInfo['MeetingEndTime']); ?></td>
														<td class="occupied"><div class="overflow"><?php htmlout($bookingInfo['BookingDisplayName']); ?></div></td>
													</tr>
												<?php endif; ?>
											<?php endif; ?>
											<?php $currentStartTimeInMinutes = $nextEndTimeInMinutes; ?>
											<input type="hidden" name="bookingStartTime" value="">
											<input type="hidden" name="BookingID" value="<?php htmlout($bookingInfo['BookingID']); ?>">
											<input type="hidden" name="MeetingRoomName" value="<?php htmlout($bookingInfo['MeetingRoomName']); ?>">
											<input type="hidden" name="MeetingRoomID" value="<?php htmlout($meetingRoomID); ?>">
										<?php endif; ?>
									<?php endforeach; ?>

									<?php if($currentStartTimeInMinutes < 1440) : ?>
										<?php $endTimeInMinutes = getNextBookingEndTime($currentStartTimeInMinutes, $currentStartTimeInMinutes+$bookingMinuteChunks, $bookingMinuteChunks); ?>
										<?php for($currentStartTimeInMinutes; $currentStartTimeInMinutes < 1440;) : ?>
											<tr onclick="createBooking('<?php htmlout($meetingRoomID); ?>','<?php echo convertMinutesToTime($currentStartTimeInMinutes); ?>')">
												<td><?php echo convertMinutesToTime($currentStartTimeInMinutes); ?> - <?php echo convertMinutesToTime($endTimeInMinutes); ?></td>
												<td class="available"></td>
											</tr>
											<?php $currentStartTimeInMinutes=$endTimeInMinutes; ?>
											<?php $endTimeInMinutes+=$bookingMinuteChunks; ?>
										<?php endfor; ?>
									<?php endif; ?>
								<?php else : ?>
									<?php $endTimeInMinutes = getNextBookingEndTime($currentStartTimeInMinutes, $currentStartTimeInMinutes+$bookingMinuteChunks, $bookingMinuteChunks); ?>
									<?php for($currentStartTimeInMinutes; $currentStartTimeInMinutes < 1440;) : ?>
										<tr onclick="createBooking('<?php htmlout($meetingRoomID); ?>','<?php echo convertMinutesToTime($currentStartTimeInMinutes); ?>')">
											<td><?php echo convertMinutesToTime($currentStartTimeInMinutes); ?> - <?php echo convertMinutesToTime($endTimeInMinutes); ?></td>
											<td class="available"></td>
										</tr>
										<?php $currentStartTimeInMinutes=$endTimeInMinutes; ?>
										<?php $endTimeInMinutes+=$bookingMinuteChunks; ?>
									<?php endfor; ?>
								<?php endif; ?>
							</table>
						</form></td>
					<?php endforeach; ?>
				</tr></table>
			<?php else : ?>
				<div class="left"><h2>This isn't a valid meeting room.</h2></div>
			<?php endif; ?>
		<?php else : ?>
			<?php if(!empty($meetingrooms)) :?>
				<table><tr>
					<?php foreach($meetingrooms AS $meetingRoomID => $bookings): ?>
						<td><form method="post">
							<table class="innerTable">
								<?php if($displayingToday) : ?>
									<?php $currentStartTimeInMinutes = $timeNowInMinutes; ?> 
								<?php else : ?>
									<?php $currentStartTimeInMinutes = 0; ?>
								<?php endif; ?>
								<?php if(!empty($bookings)) : ?>
									<tr>
										<th colspan="2"><?php htmlout($bookings[0]['MeetingRoomName']); ?></th>
									</tr>
									<?php foreach($bookings AS $bookingInfo) : ?>
										<?php if(!empty($bookingInfo['StartTimeInMinutesSinceMidnight']) AND !empty($bookingInfo['EndTimeInMinutesSinceMidnight'])) : ?>
											<?php $nextStartTimeInMinutes = $bookingInfo['StartTimeInMinutesSinceMidnight']; ?>
											<?php $nextEndTimeInMinutes = $bookingInfo['EndTimeInMinutesSinceMidnight']; ?>
											<?php if($currentStartTimeInMinutes == $nextStartTimeInMinutes AND $timeNowInMinutes <= $nextStartTimeInMinutes) : ?>
												<?php if($nextEndTimeInMinutes > $nextStartTimeInMinutes+$bookingMinuteChunks) : ?>
													<?php for($currentStartTimeInMinutes; $currentStartTimeInMinutes<$nextEndTimeInMinutes;) : ?>
														<?php $endTimeInMinutes = getNextBookingEndTime($currentStartTimeInMinutes, $currentStartTimeInMinutes+$bookingMinuteChunks, $bookingMinuteChunks); ?>
														<tr onclick="editBooking(<?php htmlout($bookingInfo['BookingID']); ?>)">
															<td><?php echo convertMinutesToTime($currentStartTimeInMinutes); ?> - <?php echo convertMinutesToTime($endTimeInMinutes); ?></td>
															<td class="occupied"><div class="overflow"><?php htmlout($bookingInfo['BookingDisplayName']); ?></div></td>
														</tr>
														<?php $currentStartTimeInMinutes = $endTimeInMinutes; ?>
													<?php endfor; ?>
												<?php else : ?>
													<tr onclick="editBooking(<?php htmlout($bookingInfo['BookingID']); ?>)">
														<td><?php htmlout($bookingInfo['MeetingStartTime']); ?> - <?php htmlout($bookingInfo['MeetingEndTime']); ?></td>
														<td class="occupied"><div class="overflow"><?php htmlout($bookingInfo['BookingDisplayName']); ?></div></td>
													</tr>
												<?php endif; ?>
											<?php elseif($currentStartTimeInMinutes >= $nextStartTimeInMinutes AND $timeNowInMinutes > $nextStartTimeInMinutes) : ?>
												<?php if($nextEndTimeInMinutes > $nextStartTimeInMinutes+$bookingMinuteChunks) : ?>
													<?php for($currentStartTimeInMinutes; $currentStartTimeInMinutes<$nextEndTimeInMinutes;) : ?>
														<?php $endTimeInMinutes = getNextBookingEndTime($currentStartTimeInMinutes, $currentStartTimeInMinutes+$bookingMinuteChunks, $bookingMinuteChunks); ?>
														<tr onclick="editBooking(<?php htmlout($bookingInfo['BookingID']); ?>)">
															<td><?php echo convertMinutesToTime($currentStartTimeInMinutes); ?> - <?php echo convertMinutesToTime($endTimeInMinutes); ?></td>
															<td class="occupied"><div class="overflow"><?php htmlout($bookingInfo['BookingDisplayName']); ?></div></td>
														</tr>
														<?php $currentStartTimeInMinutes = $endTimeInMinutes; ?>
													<?php endfor; ?>
												<?php else : ?>
													<tr onclick="editBooking(<?php htmlout($bookingInfo['BookingID']); ?>)">
														<td><?php htmlout($displayTimeNow); ?> - <?php htmlout($bookingInfo['MeetingEndTime']); ?></td>
														<td class="occupied"><div class="overflow"><?php htmlout($bookingInfo['BookingDisplayName']); ?></div></td>
													</tr>
												<?php endif; ?>
											<?php else : ?>
												<?php for($currentStartTimeInMinutes; $currentStartTimeInMinutes < $nextStartTimeInMinutes;) : ?>
													<?php if($currentStartTimeInMinutes+$bookingMinuteChunks > $nextStartTimeInMinutes) : ?>
														<?php $endTimeInMinutes = getNextBookingEndTime($currentStartTimeInMinutes, $nextStartTimeInMinutes, $bookingMinuteChunks); ?>
													<?php else : ?>
														<?php $endTimeInMinutes = getNextBookingEndTime($currentStartTimeInMinutes, $currentStartTimeInMinutes+$bookingMinuteChunks, $bookingMinuteChunks); ?>
													<?php endif; ?>
													<tr onclick="createBooking('<?php htmlout($meetingRoomID); ?>','<?php echo convertMinutesToTime($currentStartTimeInMinutes); ?>')">
														<td><?php echo convertMinutesToTime($currentStartTimeInMinutes); ?> - <?php echo convertMinutesToTime($endTimeInMinutes); ?></td>
														<td class="available"></td>
													</tr>
													<?php $currentStartTimeInMinutes = $endTimeInMinutes; ?>
												<?php endfor; ?>
												<?php if($nextEndTimeInMinutes > $nextStartTimeInMinutes+$bookingMinuteChunks) : ?>
													<?php for($currentStartTimeInMinutes; $currentStartTimeInMinutes<$nextEndTimeInMinutes;) : ?>
														<?php $endTimeInMinutes = getNextBookingEndTime($currentStartTimeInMinutes, $currentStartTimeInMinutes+$bookingMinuteChunks, $bookingMinuteChunks); ?>
														<tr onclick="editBooking(<?php htmlout($bookingInfo['BookingID']); ?>)">
															<td><?php echo convertMinutesToTime($currentStartTimeInMinutes); ?> - <?php echo convertMinutesToTime($endTimeInMinutes); ?></td>
															<td class="occupied"><div class="overflow"><?php htmlout($bookingInfo['BookingDisplayName']); ?></div></td>
														</tr>
														<?php $currentStartTimeInMinutes = $endTimeInMinutes; ?>
													<?php endfor; ?>
												<?php else : ?>
													<tr onclick="editBooking(<?php htmlout($bookingInfo['BookingID']); ?>)">
														<td><?php htmlout($bookingInfo['MeetingStartTime']); ?> - <?php htmlout($bookingInfo['MeetingEndTime']); ?></td>
														<td class="occupied"><div class="overflow"><?php htmlout($bookingInfo['BookingDisplayName']); ?></div></td>
													</tr>
												<?php endif; ?>
											<?php endif; ?>
											<?php $currentStartTimeInMinutes = $nextEndTimeInMinutes; ?>
											<input type="hidden" name="bookingStartTime" value="">
											<input type="hidden" name="BookingID" value="<?php htmlout($bookingInfo['BookingID']); ?>">
											<input type="hidden" name="MeetingRoomName" value="<?php htmlout($bookingInfo['MeetingRoomName']); ?>">
											<input type="hidden" name="MeetingRoomID" value="<?php htmlout($meetingRoomID); ?>">
										<?php endif; ?>
									<?php endforeach; ?>

									<?php if($currentStartTimeInMinutes < 1440) : ?>
										<?php $endTimeInMinutes = getNextBookingEndTime($currentStartTimeInMinutes, $currentStartTimeInMinutes+$bookingMinuteChunks, $bookingMinuteChunks); ?>
										<?php for($currentStartTimeInMinutes; $currentStartTimeInMinutes < 1440;) : ?>
											<tr onclick="createBooking('<?php htmlout($meetingRoomID); ?>','<?php echo convertMinutesToTime($currentStartTimeInMinutes); ?>')">
												<td><?php echo convertMinutesToTime($currentStartTimeInMinutes); ?> - <?php echo convertMinutesToTime($endTimeInMinutes); ?></td>
												<td class="available"></td>
											</tr>
											<?php $currentStartTimeInMinutes=$endTimeInMinutes; ?>
											<?php $endTimeInMinutes+=$bookingMinuteChunks; ?>
										<?php endfor; ?>
									<?php endif; ?>
								<?php else : ?>
									<?php $endTimeInMinutes = getNextBookingEndTime($currentStartTimeInMinutes, $currentStartTimeInMinutes+$bookingMinuteChunks, $bookingMinuteChunks); ?>
									<?php for($currentStartTimeInMinutes; $currentStartTimeInMinutes < 1440;) : ?>
										<tr onclick="createBooking('<?php htmlout($meetingRoomID); ?>','<?php echo convertMinutesToTime($currentStartTimeInMinutes); ?>')">
											<td><?php echo convertMinutesToTime($currentStartTimeInMinutes); ?> - <?php echo convertMinutesToTime($endTimeInMinutes); ?></td>
											<td class="available"></td>
										</tr>
										<?php $currentStartTimeInMinutes=$endTimeInMinutes; ?>
										<?php $endTimeInMinutes+=$bookingMinuteChunks; ?>
									<?php endfor; ?>
								<?php endif; ?>
							</table>
						</form></td>
					<?php endforeach; ?>
				</tr></table>
			<?php else : ?>
				<div class="left"><h2>There are no meeting rooms.</h2></div>
			<?php endif; ?>
		<?php endif; ?>
	</body>
</html>