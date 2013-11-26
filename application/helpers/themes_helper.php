<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('load_layout'))
{
	function load_layout($theme_name)
	{
		$ci =& get_instance();
		
		if ($theme_name == 'default')
		{
			$ci->template->set_theme('default');
			$ci->template->set_layout('layout');
			$ci->template->set_partial('metadata', 'partials/metadata.php');
			$ci->template->set_partial('header', 'partials/header.php');
			$ci->template->set_partial('menu', 'partials/menu.php');
			$ci->template->set_partial('footer', 'partials/footer.php');
		}
		else if ($theme_name == 'admin')
		{
			$ci->template->set_theme('admin');
			$ci->template->set_layout('layout');
			$ci->template->set_partial('metadata', 'partials/metadata.php');
			$ci->template->set_partial('header', 'partials/header.php');
			$ci->template->set_partial('menu', 'partials/menu.php');
			$ci->template->set_partial('banner', 'partials/banner.php');
			$ci->template->set_partial('footer', 'partials/footer.php');	
		}
	}
}
?>