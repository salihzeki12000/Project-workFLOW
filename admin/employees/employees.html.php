<!-- This is the HTML form used for DISPLAYING a list of COMPANIES-->
<?php include_once $_SERVER['DOCUMENT_ROOT'] .
 '/includes/helpers.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<style>
			#companyemployeestable {
				font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
				border-collapse: collapse;
				width: 100%;
			}
			
			#companyemployeestable th {
				padding: 12px;
				text-align: left;
				background-color: #4CAF50;
				color: white;
			}
			
			#companyemployeestable tr {
				padding: 8px;
				text-align: left;
				border-bottom: 1px solid #ddd;
			}
			
			#companyemployeestable td {
				padding: 8px;
				text-align: left;
				border: 1px solid #ddd;
			}

			#companyemployeestable tr:hover{background-color:#ddd;}
			
			#companyemployeestable tr:nth-child(even) {background-color: #f2f2f2;}
			
			#companyemployeestable caption {
				padding: 8px;
				font-size: 300%;
			}
		</style>
		<title>Manage Company Employees</title>
	</head>
	<body>
		<h1>Manage Company Employees</h1>
		<?php $goto = "http://$_SERVER[HTTP_HOST]/admin/companies/"; ?>
		<?php if(isset($_GET['Company'])) :?>
			<form action="<?php htmlout($goto); ?>" method="post">
				<input type="submit" value="Return to Companies">
			</form>
		<?php $goto = "http://$_SERVER[HTTP_HOST]/admin/employees/"; ?>
			<form action="<?php htmlout($goto); ?>" method="post">
				<input type="submit" value="Get All Employees">
			</form>
		<?php endif; ?>
		<form action="" method="post">
		<?php if($rowNum>0) :?>
			<input type="submit" name="action" value="Add Employee">
			<table id= "companyemployeestable">
				<caption>Company Employees</caption>
				<tr>
					<th>Company Name</th>
					<th>Company Role</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Email</th>
					<th>Booking time used (this month)</th>
					<th>Booking time used (all time)</th>
					<th>Added At</th>
					<th></th>
					<th></th>
				</tr>
				<?php foreach ($employees as $employee): ?>
					<form action="" method="post">
						<tr>
							<td>
								<?php htmlout($employee['CompanyName']); ?>
								<input type="submit" name="CompanyName" value="<?php htmlout($employee['CompanyName']); ?>">
							</td>
							<td><?php htmlout($employee['PositionName']); ?></td>
							<td><?php htmlout($employee['firstName']); ?></td>
							<td><?php htmlout($employee['lastName']); ?></td>
							<td><?php htmlout($employee['email']); ?></td>						
							<td><?php htmlout($employee['MonthlyBookingTimeUsed']); ?></td>
							<td><?php htmlout($employee['TotalBookingTimeUsed']); ?></td>
							<td><?php htmlout($employee['StartDateTime']); ?></td>
							<td><input type="submit" name="action" value="Change Role"></td>
							<td><input type="submit" name="action" value="Remove"></td>
							<input type="hidden" name="UserID" value="<?php htmlout($employee['UsrID']); ?>">
							<input type="hidden" name="CompanyID" value="<?php htmlout($employee['CompanyID']); ?>">
							<input type="hidden" name="UserName" value="<?php htmlout($employee['lastName'] . ", " . $employee['firstName']); ?>">
						</tr>
					</form>
				<?php endforeach; ?>
			</table>
		<?php else : ?>
			<tr><b>There are no employees registered in the database.</b></tr>
			<tr><input type="submit" name="action" value="Add Employee"></tr>
		<?php endif; ?>
		</form>
		<p><a href="..">Return to CMS home</a></p>
		<?php include '../logout.inc.html.php'; ?>
	</body>
</html>
