<!-- This is the HTML form used for DISPLAYING a list of DETAILS for a selected ORDER for STAFF users-->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="/CSS/myCSS.css">
		<style>
			label {
				width: 210px;
			}
			#extraTable table th,tr,td {
				
			}
		</style>
		<script src="/scripts/myFunctions.js"></script>
		<title>Order Details</title>
	</head>
	<body onload="startTime()">
		<?php if($accessRole == "Admin") : ?>
			<?php include_once $_SERVER['DOCUMENT_ROOT'] .'/includes/topnav.html.php'; ?>
		<?php else : ?>
			<?php include_once $_SERVER['DOCUMENT_ROOT'] .'/includes/stafftopnav.html.php'; ?>
		<?php endif; ?>

		<form action="" method="post">
			<fieldset><legend>Order Details:</legend>

				<div class="left">
					<?php if(isSet($_SESSION['OrderStaffDetailsFeedback'])) : ?>
						<span><b class="feedback"><?php htmlout($_SESSION['OrderStaffDetailsFeedback']); ?></b></span>
						<?php unset($_SESSION['OrderStaffDetailsFeedback']); ?>
					<?php endif; ?>
				</div>

				<div>
					<label>Date Created: </label>
					<span><b><?php htmlout($originalOrderCreated); ?></b></span>
				</div>

				<div>
					<label>Last Update: </label>
					<span><b><?php htmlout($originalOrderUpdated); ?></b></span>
				</div>

				<div>
					<label>Order User Notes: </label>
					<span style="white-space: pre-wrap;"><b><?php htmlout($originalOrderUserNotes); ?></b></span>
				</div>

				<div>
					<label>Messages:</label>
					<span style="white-space: pre-wrap;"><b><?php htmlout($orderMessages); ?></b></span>
				</div>

				<div>
					<label class="description">Send New Message To User: </label>
					<textarea rows="4" cols="50" name="OrderCommunicationToUser" placeholder="Enter New Message To User"><?php htmlout($orderCommunicationToUser); ?></textarea>
				</div>

				<div>
					<label>Order Approval Status: </label>
					<?php if($originalOrderIsApproved == 1) : ?>
						<span><b><?php htmlout("Order Approved"); ?></b></span>
					<?php else : ?>
						<span><b><?php htmlout("Order Not Approved"); ?></b></span>
					<?php endif; ?>
				</div>
			</fieldset>

			<table>
				<caption>Extra Ordered</caption>
				<tr>
					<th colspan="4">Extra</th>
					<th colspan="3">Approved For Purchase</th>
					<th colspan="3">Set As Purchased</th>
				</tr>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Amount</th>
					<th>Price</th>
					<th>Approved?</th>
					<th>By Staff</th>
					<th>At Date</th>
					<th>Purchased?</th>
					<th>By Staff</th>
					<th>At Date</th>
				</tr>
				<?php if(isSet($extraOrdered)) : ?>
					<?php foreach($extraOrdered as $row): ?>
						<tr>
							<td><?php htmlout($row['ExtraName']); ?></td>
							<td style="white-space: pre-wrap;"><?php htmlout($row['ExtraDescription']); ?></td>
							<td><?php htmlout($row['ExtraAmount']); ?></td>
							<td><?php htmlout($row['ExtraPrice']); ?></td>
							<td>
								<?php if($row['ExtraBooleanApprovedForPurchase'] == 1) : ?>
									<label style="width: auto;"><input type="checkbox" name="isApprovedForPurchase[]" value="<?php htmlout($row['ExtraID']); ?>" checked>Approved</label>
								<?php else : ?>
									<label style="width: auto;"><input type="checkbox" name="isApprovedForPurchase[]" value="<?php htmlout($row['ExtraID']); ?>">Approved</label>
								<?php endif; ?>
							</td>
							<td><?php htmlout($row['ExtraApprovedForPurchaseByUser']); ?></td>
							<td><?php htmlout($row['ExtraDateTimeApprovedForPurchase']); ?></td>
							<td>
								<?php if($row['ExtraBooleanPurchased'] == 1) : ?>
									<label style="width: auto;"><input type="checkbox" name="isPurchased[]" value="<?php htmlout($row['ExtraID']); ?>" checked>Purchased</label>
								<?php else : ?>
									<label style="width: auto;"><input type="checkbox" name="isPurchased[]" value="<?php htmlout($row['ExtraID']); ?>">Purchased</label>
								<?php endif; ?>
							</td>
							<td><?php htmlout($row['ExtraPurchasedByUser']); ?></td>
							<td><?php htmlout($row['ExtraDateTimePurchased']); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr><td colspan="10"><b>This order has nothing in it.</b></td></tr>
				<?php endif; ?>
			</table>

			<div class="left">
				<input type="hidden" name="OrderID" value="<?php htmlout($orderID); ?>">
				<input type="submit" name="action" value="Cancel">
				<input type="submit" name="action" value="Reset">
				<input type="submit" name="action" value="Submit Changes">
			</div>
		</form>
	</body>
</html>