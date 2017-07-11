<!-- This is the HTML form used for EDITING or ADDING CREDITS information-->
<?php include_once $_SERVER['DOCUMENT_ROOT'] .
 '/includes/helpers.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="/CSS/myCSS.css">
		<title><?php htmlout($pageTitle); ?></title>
		<style>
			label {
				width: 320px;
			}
		</style>		
	</head>
	<body>
		<h1><?php htmlout($pageTitle); ?></h1>
		
		<div>
			<?php if(isset($_SESSION['EditCreditsError'])) :?>
				<span><b class="feedback"><?php htmlout($_SESSION['EditCreditsError']); ?></b></span>
				<?php unset($_SESSION['EditCreditsError']); ?>
			<?php endif; ?>
		</div>
		
		<form action="" method="post">
			<?php if($button == 'Edit Credits') : ?>
				<div>
					<label for="OriginalCreditsName">Original Credits Name: </label>
					<span><b><?php htmlout($originalCreditsName); ?></b></span>
				</div>
			<?php endif; ?>		
			<div>
				<label for="CreditsName">Set New Credits Name: </label>
				<?php if(	isset($_SESSION['EditCreditsOriginalInfo']) AND 
							$_SESSION['EditCreditsOriginalInfo']['CreditsName'] == 'Default') : ?>
					<input type="hidden" name="CreditsName" id="CreditsName"
					value="<?php htmlout($CreditsName); ?>">
					<input type="text" name="DisabledCreditsName" id="DisabledCreditsName"
					disabled value="Can't change.">				
				<?php else : ?>
					<input type="text" name="CreditsName" id="CreditsName" 
					placeholder="Enter Credits Name"
					value="<?php htmlout($CreditsName); ?>">		
				<?php endif; ?>
			</div>
			
			<?php if($button == 'Edit Credits') : ?>
				<div>
					<label for="OriginalCreditsDescription">Original Credits Description: </label>
					<span><b><?php htmlout($originalCreditsDescription); ?></b></span>
				</div>
			<?php endif; ?>
			
			<div>
				<label class="description" for="CreditsDescription">Set New Credits Description: </label>
				<?php if(	isset($_SESSION['EditCreditsOriginalInfo']) AND 
							$_SESSION['EditCreditsOriginalInfo']['CreditsName'] == 'Default') : ?>
					<input type="hidden" name="CreditsDescription" id="CreditsDescription"
					value="<?php htmlout($CreditsDescription); ?>">
					<input type="text" name="DisabledCreditsDescription" id="DisabledCreditsDescription"
					disabled value="Can't change.">	
				<?php else : ?>
					<textarea rows="4" cols="50" name="CreditsDescription" id="CreditsDescription"
					placeholder="Enter Credits Description"><?php htmlout($CreditsDescription); ?></textarea>
				<?php endif; ?>
			</div>
			
			<?php if($button == 'Edit Credits') : ?>
				<div>
					<label for="OriginalCreditsAmount">Original Credits Amount: </label>
					<span><b><?php htmlout($originalCreditsAmount); ?></b></span>
				</div>
			<?php endif; ?>
			
			<div>
				<label for="CreditsAmount">Set New Credits Amount: </label>
				<input type="number" name="CreditsAmount" id="CreditsAmount" 
				min="0" max="65535"
				placeholder="Minutes"
				value="<?php htmlout($CreditsAmount); ?>">
			</div>
			
			<?php if($button == 'Edit Credits') : ?>
				<div>
					<label for="OriginalCreditsMonthlyPrice">Original Monthly Subscription Price: </label>
					<span><b><?php htmlout($originalCreditsMonthlyPrice); ?></b></span>
				</div>
			<?php endif; ?>

			<div>
				<label for="CreditsMonthlyPrice">Set New Monthly Subscription Price: </label>
				<input type="number" name="CreditsMonthlyPrice" id="CreditsMonthlyPrice" 
				min="0" max="65535"
				value="<?php htmlout($CreditsMonthlyPrice); ?>">
			</div>
			
			<?php if($button == 'Edit Credits') : ?>
				<div>
					<label for="OriginalCreditsHourPrice">Original Over Credits Fee (Charged per hour): </label>
					<span><b><?php htmlout($originalCreditsHourPrice); ?></b></span>
				</div>
			<?php endif; ?>
			
			<div>
				<label for="CreditsHourPrice">Set New Over Credits Fee (Charged per hour): </label>
				<input type="number" name="CreditsHourPrice" id="CreditsHourPrice" 
				min="0" max="65535" placeholder="e.g. 150"
				value="<?php htmlout($CreditsHourPrice); ?>"><span style="color: red;">*</span>
			</div>
			
			<?php if($button == 'Edit Credits') : ?>
				<div>
					<label for="OriginalCreditsMinutePrice">Original Over Credits Fee (Charged per minute): </label>
					<span><b><?php htmlout($originalCreditsMinutePrice); ?></b></span>
				</div>
			<?php endif; ?>
			
			<div>
				<label for="CreditsMinutePrice">Set New Over Credits Fee (Charged per minute): </label>
				<input type="text" name="CreditsMinutePrice" id="CreditsMinutePrice" 
				placeholder="e.g. 2.50"
				value="<?php htmlout($CreditsMinutePrice); ?>"><span style="color: red;">*</span>
			</div>			
			<div class="left">
				<input type="hidden" name="CreditsID" value="<?php htmlout($CreditsID); ?>">
				<input type="submit" name="action" value="<?php htmlout($button); ?>">
				<span style="color: red;">* Select which method to use.</span>
			</div>
			
			<div class="left">
				<?php if($button == 'Confirm Credits') : ?>
					<input type="submit" name="add" value="Reset">
					<input type="submit" name="add" value="Cancel">
				<?php elseif($button == 'Edit Credits') : ?>
					<input type="submit" name="edit" value="Reset">
					<input type="submit" name="edit" value="Cancel">				
				<?php endif; ?>
			</div>
		</form>

	<div class="left"><a href="..">Return to CMS home</a></div>
	
	<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logout.inc.html.php'; ?>
	</body>
</html>