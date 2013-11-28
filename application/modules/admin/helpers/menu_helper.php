<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('load_header_menu'))
{
	function load_header_menu(&$data)
	{
		$ci =& get_instance();

		$ci->lang->load('admin/common/banner');
        $ci->lang->load('admin/common/menu');

        // load header and menu
        $common_data = array();
        $common_data['loggedin_flg']    = $ci->session->userdata('username');
        $common_data['text_logged']     = sprintf(lang('admin.banner.logged'), $ci->session->userdata('username'));
        $common_data['text_home']       = lang('admin.menu.text_dashboard');
        $common_data['text_system']     = lang('admin.menu.text_system');
        $common_data['text_setting']    = lang('admin.menu.text_setting');
        $common_data['text_users']      = lang('admin.menu.text_users');
        $common_data['text_usergrp']    = lang('admin.menu.text_usergrp');
        $common_data['text_logout']     = lang('admin.menu.text_logout');
        $common_data['text_frontend']   = lang('admin.menu.text_frontend');
        $common_data['text_catalog']    = lang('admin.menu.text_catalog');
        $common_data['text_category']   = lang('admin.menu.text_category');
        $common_data['text_country']    = lang('admin.menu.text_country');
        $common_data['text_language']   = lang('admin.menu.text_language');
        $common_data['text_currency']   = lang('admin.menu.text_currency');
        $common_data['text_stock_status']= lang('admin.menu.text_stock_status');
        $common_data['text_zone']       = lang('admin.menu.text_zone');
        $common_data['text_product']    = lang('admin.menu.text_product');
        $common_data['text_news']       = lang('admin.menu.text_news');
        $common_data['text_information']= lang('admin.menu.text_information');
        $common_data['text_manufacturer']= lang('admin.menu.text_manufacturer');
        $common_data['text_review']		= lang('admin.menu.text_review');
		$common_data['text_faq']		= lang('admin.menu.text_faq');
		$common_data['text_contact']	= lang('admin.menu.text_contact');

        $data = array_merge($data, $common_data);
	}
}
?>