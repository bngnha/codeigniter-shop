<div class="banner">
    <div class="logo">
    	<img src="" title="<?php echo $heading_title; ?>" onclick="location = '<?php echo base_url();?>admin/dashboard'" />
    </div>
    <?php if (isset($loggedin_flg) && $loggedin_flg) { ?>
    <div class="logged_in">
    	<img src="<?php echo site_url().APPPATH;?>modules/admin/views/images/lock.png" alt="" style="position: relative; top: 3px;" />&nbsp;<?php echo $text_logged; ?>
    </div>
    <?php } ?>
</div>