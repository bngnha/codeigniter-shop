<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( !function_exists('get_setting_to_session'))
{
	function get_setting_to_session(&$data='')
	{
		$ci =& get_instance();

		// Get information configuration
		$ci->load->model('setting/setting_model');
		$results = $ci->setting_model->getSetting();
		if (is_array($results) && sizeof($results) > 0)
		{
			$ci->session->set_userdata($results);
		}
	}
}