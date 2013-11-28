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
				<a onclick="location='<?php echo $insert; ?>'" class="button">
					<span class="button_left button_insert"></span>
					<span class="button_middle"><?php echo $button_insert; ?></span>
					<span class="button_right"></span>
				</a>
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
							<td class="left">
								<?php if ($sort == 'fd.title') { ?>
									<a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
								<?php } else { ?>
									<a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
								<?php } ?>
							</td>
							<td class="right">
								<?php if ($sort == 'f.sort_order') { ?>
									<a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
								<?php } else { ?>
									<a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
								<?php } ?>
							</td>
							<td class="right">
								<?php if ($sort == 'f.status') { ?>
									<?php echo $column_status; ?>
								<?php } else { ?>
									<?php echo $column_status; ?>
								<?php } ?>
							</td>
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($faqs) { ?>
							<?php $class = 'odd'; ?>
							<?php foreach ($faqs as $faq) { ?>
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
								<tr class="<?php echo $class; ?>">
									<td align="center">
										<?php if ($faq['delete']) { ?>
											<input type="checkbox" name="delete[]" value="<?php echo $faq['faq_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="delete[]" value="<?php echo $faq['faq_id']; ?>" />
										<?php } ?>
									</td>
									<td class="left"><?php echo $faq['title']; ?></td>
									<td class="right"><?php echo $faq['sort_order']; ?></td>
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
								<td class="center" colspan="5"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>
