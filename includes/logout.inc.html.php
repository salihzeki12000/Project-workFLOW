<form action="" method="post">
	<div>
		<input type="hidden" name="action" value="logout">
		<?php if(isset($gotoPage)) : ?>
			<input type="hidden" name="goto" value="<?php htmlout($gotoPage); ?>">
		<?php else : ?>
			<input type="hidden" name="goto" value="/admin/">
		<?php endif; ?>
		<input type="submit" value="Log out">
	</div>
</form>