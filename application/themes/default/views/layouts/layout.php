<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title><?php echo $title; ?></title>
	<?php echo $template['partials']['metadata'];?>
	</head>
	<body>
		<div id="wrapper">
			<div class="container1">
				<?php echo $template['partials']['header']; ?>
				<?php echo $template['partials']['menu']; ?>
			</div>
			<?php echo $template['body']; ?>
			<?php echo $template['partials']['footer']; ?>
		</div>
	</body>
</html>