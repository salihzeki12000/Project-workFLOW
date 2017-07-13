<!-- This is the HTML form used to display user information to normal users-->
<?php include_once $_SERVER['DOCUMENT_ROOT'] .
 '/includes/helpers.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="/CSS/myCSS.css">
		<script src="/scripts/myFunctions.js"></script>	
		<?php if(isset($_SESSION['loggedIn']) : ?>
			<title>Your User Information</title>
		<?php else : ?>
			<title>User Information</title>
		<?php endif; ?>
	</head>
	<body>
		<?php include_once $_SERVER['DOCUMENT_ROOT'] .'/includes/topnav.html.php'; ?>
		
		<?php if(isset($_SESSION['loggedIn']) : ?>
			<h1>Your User Information</h1>
		<?php else : ?>
			<h1>User Information</h1>
		<?php endif; ?>
		
		<div class="left">
			<?php if(isset($_SESSION['normalUserFeedback'])) : ?>
				<span><b class="feedback"><?php htmlout($_SESSION['normalUserFeedback']); ?></b></span>
				<?php unset($_SESSION['normalUserFeedback']); ?>
			<?php endif; ?>
		</div>

		<?php if(isset($_SESSION['loggedIn']) : ?>
			
		<?php endif; ?>
		
		<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logout.inc.html.php'; ?>
	</body>
</html>