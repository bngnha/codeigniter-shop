<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $template['partials']['metadata'];?>
		<title><?php echo $title;?></title>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<?php echo $template['partials']['menu']; ?>
				<?php echo $template['partials']['banner']; ?>
			</div>
			<?php echo $template['body']; ?>
		</div>
		<?php echo $template['partials']['footer']; ?>
	</body>
</html>