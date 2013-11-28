<div id="content">
	<div class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
<?php } ?>
	  </div>
	<div class="box">
		<div class="heading">
		  <h1><img src="<?php echo site_url().APPPATH?>modules/admin/views/images/review.png" alt="" /><?php echo $heading_title; ?></h1>
		  	<div class="buttons">
			  	<a onclick="$('#form').submit();" class="button">
			  		<span class="button_left button_save"></span>
			  		<span class="button_middle"><?php echo $button_save; ?></span>
			  		<span class="button_right"></span>
			  	</a>
			  	<a onclick="location='<?php echo $cancel; ?>';" class="button">
			  		<span class="button_left button_cancel"></span>
			  		<span class="button_middle"><?php echo $button_cancel; ?></span>
			  		<span class="button_right"></span>
			  	</a>
		 	</div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			  <div id="tab_general">
	          	<div>
				    <table class="form">
				        <tr>
				          <td width="15%"><?php echo $entry_subject; ?></td>
				          <td>
				          	<?php echo @$faq_info['title']; ?>
				          </td>
				        </tr>
				        <tr>
				          <td width="15%"><?php echo $entry_content; ?></td>
				          <td>
				          	<?php echo @$faq_info['content']; ?>
				          </td>
				        </tr>
				        <tr>
				          <td width="15%"><?php echo $entry_name; ?></td>
				          <td>
				          	<?php echo @$faq_info['full_name']; ?>
				          </td>
				        </tr>
				        <tr>
				          <td width="15%"><?php echo $entry_phone; ?></td>
				          <td>
				          	<?php echo @$faq_info['phone']; ?>
				          </td>
				        </tr>
				        <tr>
				          <td width="15%"><?php echo $entry_email; ?></td>
				          <td>
				          	<?php echo @$faq_info['email']; ?>
				          </td>
				        </tr>
				    </table>
			    </div>
			  </div>
			  <div id="tab_data" class="page">
			    <table class="form">
			      <tr>
			        <td width="15%"><?php echo $entry_status; ?></td>
			        <td><select name="status">
			          <?php if (@$faq_info['status'] ==1) { ?>
			          <option value="1" selected="selected"><?php echo $text_read; ?></option>
			          <option value="0"><?php echo $text_unread; ?></option>
			          <?php } else { ?>
			          <option value="1"><?php echo $text_read; ?></option>
			          <option value="0" selected="selected"><?php echo $text_unread; ?></option>
			          <?php } ?>
			        </select></td>
			      </tr>
			    </table>
			  </div>
			</form>
		</div>
	</div>
</div>
