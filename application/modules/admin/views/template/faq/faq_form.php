<div id="content">
	<div class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
<?php } ?>
	  </div>
<?php if ($error_warning) { ?>
	  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
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
			<div id="tabs" class="htabs">
				<a href="#tab_general"><?php echo $tab_general; ?></a>
				<a href="#tab_data"><?php echo $tab_data; ?></a>
			</div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			  <div id="tab_general">
			  	<div id="languages" class="htabs">
	            <?php foreach ($languages as $language) { ?>
	            	<a href="#language<?php echo $language['language_id']; ?>">
	            		<img src="<?php echo site_url().APPPATH?>modules/admin/views/images/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
	            	</a>
	            <?php } ?>
	          	</div>
	          	<?php foreach ($languages as $language) { ?>
	          	<div id="language<?php echo $language['language_id']; ?>">
				    <table class="form">
				        <tr>
				          <td width="25%"><span class="required">*</span> <?php echo $entry_title; ?></td>
				          <td>
				          	<input name="faq_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo @$faq_description[$language['language_id']]['title']; ?>" />
				            <?php if (@$error_title[$language['language_id']]) { ?>
				            <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
				            <?php } ?>
				          </td>
				        </tr>
				        <tr>
				          <td><?php echo $entry_keyword; ?></td>
				          <td><input type="text" name="faq_keyword[<?php echo $language['language_id']; ?>][keyword]" value="<?php echo @$faq_description[$language['language_id']]['keyword']; ?>" /></td>
				        </tr>
				        <tr>
				          <td><span class="required">*</span> <?php echo $entry_description; ?></td>
				          <td>
				          	<textarea name="faq_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo @$faq_description[$language['language_id']]['description']; ?></textarea>
				            <?php if (@$error_description[$language['language_id']]) { ?>
				            <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
				            <?php } ?></td>
				        </tr>
				    </table>
			    </div>
         		<?php } ?>
			  </div>
			  <div id="tab_data" class="page">
			    <table class="form">
			      <tr>
			        <td width="25%"><?php echo $entry_status; ?></td>
			        <td><select name="status">
			          <?php if ($status) { ?>
			          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
			          <option value="0"><?php echo $text_disabled; ?></option>
			          <?php } else { ?>
			          <option value="1"><?php echo $text_enabled; ?></option>
			          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
			          <?php } ?>
			        </select></td>
			      </tr>
			      <tr>
			        <td><?php echo $entry_sort_order; ?></td>
			        <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
			      </tr>
			    </table>
			  </div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_HTTP_RESOURCE;?>javascripts/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_HTTP_RESOURCE;?>javascripts/jquery/tabs.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
	CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: '<?php echo base_url();?>admin/common/filemanager',
	filebrowserImageBrowseUrl: '<?php echo base_url();?>admin/common/filemanager',
	filebrowserFlashBrowseUrl: '<?php echo base_url();?>admin/common/filemanager',
	filebrowserUploadUrl: '<?php echo base_url();?>admin/common/filemanager',
	filebrowserImageUploadUrl: '<?php echo base_url();?>admin/common/filemanager',
	filebrowserFlashUploadUrl: '<?php echo base_url();?>admin/common/filemanager'
});
<?php } ?>
//--></script>
<script type="text/javascript"><!--
	$('#tabs a').tabs();
	$('#languages a').tabs(); 
//--></script>
