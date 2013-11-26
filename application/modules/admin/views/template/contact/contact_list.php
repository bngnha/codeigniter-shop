<div id="content">
	<div class="breadcrumb">
   <?php foreach ($breadcrumbs as $breadcrumb) { ?>
   <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
   <?php } ?>
  	</div>
  <?php if ($error_warning) { ?>
  	<div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  	<div class="success"><?php echo $success; ?></div>
  <?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="<?php echo site_url().APPPATH?>modules/admin/views/images/review.png" alt="" /><?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a onclick="$('form').submit();" class="button">
					<span class="button_left button_delete"></span>
					<span class="button_middle"><?php echo $button_delete; ?></span>
					<span class="button_right"></span>
				</a>
			</div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" align="center"><input type="checkbox" onclick="$('input[name*=\'delete\']').attr('checked', this.checked);" /></td>
							<td class="center"><?php echo $column_subject; ?></td>
							<td class="center"><?php echo $column_content; ?></td>
							<td class="center"><?php echo $column_name; ?></td>
							<td class="center"><?php echo $column_phone; ?></td>
							<td class="center"><?php echo $column_email; ?></td>
							<td class="center"><?php echo $column_status; ?></td>
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($contacts) { ?>
							<?php $class = 'odd'; ?>
							<?php foreach ($contacts as $faq) { ?>
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
								<tr class="<?php echo $class; ?>">
									<td align="center">
										<?php if ($faq['delete']) { ?>
											<input type="checkbox" name="delete[]" value="<?php echo $faq['contact_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="delete[]" value="<?php echo $faq['contact_id']; ?>" />
										<?php } ?>
									</td>
									<td class="left"><?php echo $faq['title']; ?></td>
									<td class="left"><?php echo $faq['content']; ?></td>
									<td class="left"><?php echo $faq['full_name']; ?></td>
									<td class="left"><?php echo $faq['phone']; ?></td>
									<td class="left"><?php echo $faq['email']; ?></td>
									<td class="right"><?php echo $faq['status']; ?></td>
									<td class="right">
										<?php foreach ($faq['action'] as $action) { ?>
											[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
						<?php } else { ?>
							<tr class="even">
								<td class="center" colspan="8"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>
