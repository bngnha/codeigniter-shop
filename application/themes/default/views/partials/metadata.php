<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
<?php
	Asset::css('stylesheet.css');
	Asset::css('jquery/jquery-tooltip/jquery.tooltip.css');
	Asset::css('jquery/cloud_zoom/cloud-zoom.css');
	Asset::css('slideshow.css');
	
	Asset::js('jquery/jquery-1.7.2.js');
	
	Asset::js('jquery/superfish/js/superfish.js');
	Asset::js('jquery/ajaxupload.js');
	Asset::js('jquery/jquery-tooltip/lib/jquery.bgiframe.js');
	Asset::js('jquery/jquery-tooltip/lib/jquery.dimensions.js');
	Asset::js('jquery/jquery-tooltip/jquery.tooltip.js');
	Asset::js('jquery/cloud_zoom/cloud-zoom.1.0.2.js');
	Asset::js('jquery/nivo-slider/jquery.nivo.slider.js');
?>

<?php //if (isset($analytic_visits) OR isset($analytic_views)): ?>
	<?php //Asset::js('jquery/jquery.excanvas.min.js'); ?>
	<?php //Asset::js('jquery/jquery.flot.js'); ?>
<?php //endif; ?>

<script type="text/javascript">
	//pyro = { 'lang' : {} };
	//var APPPATH_URI				= "<?php //echo APPPATH_URI;?>";
	//var SITE_URL					= "<?php //echo rtrim(site_url(), '/').'/';?>";
	//var BASE_URL					= "<?php //echo BASE_URL;?>";
	//var BASE_URI					= "<?php //echo BASE_URI;?>";
	//var UPLOAD_PATH				= "<?php //echo UPLOAD_PATH;?>";
	//var DEFAULT_TITLE				= "<?php //echo addslashes($this->settings->site_name); ?>";
	//pyro.admin_theme_url			= "<?php //echo BASE_URL . $this->admin_theme->path; ?>";
	//pyro.apppath_uri				= "<?php //echo APPPATH_URI; ?>";
	//pyro.base_uri					= "<?php //echo BASE_URI; ?>";
	//pyro.lang.remove				= "<?php //echo lang('global:remove'); ?>";
	//pyro.lang.dialog_message 		= "<?php //echo lang('global:dialog:delete_message'); ?>";
	//pyro.csrf_cookie_name			= "<?php //echo config_item('cookie_prefix').config_item('csrf_cookie_name'); ?>";
	//pyro.foreign_characters		= <?php //echo json_encode(accented_characters()); ?>
</script>

<?php //Asset::css(array('plugins.css', 'jquery/colorbox.css', 'codemirror.css')); ?>

<?php echo Asset::render(); ?>

<!--[if lt IE 9]>
<?php //echo Asset::css('ie8.css', null, 'ie8'); ?>
<?php //echo Asset::render_css('ie8'); ?>
<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<?php //if ($module_details['sections']): ?>
<style>section#content {margin-top: 170px!important;}</style>
<?php //endif; ?>

<?php echo $template['metadata']; ?>
