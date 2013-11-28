<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Admin_Controller extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->error= array();

		// Load theme and template
		$this->load->helper('themes');
		load_layout('admin');

		$this->load->library('Asset');

		// Work out module, controller and method and make them accessable throught the CI instance
		$this->module = $this->module = $this->router->fetch_module();
		$this->controller = $this->controller = $this->router->fetch_class();
		$this->method = $this->method = $this->router->fetch_method();
		
		// Get meta data for the module
		$this->template->module_details = $this->module_details = $this->module_details = $this->module;

		Asset::add_path('module', APPPATH.'modules/'.$this->module.'/views/');

		// Theme directory path
		Asset::add_path('theme', APPPATH.'themes/admin/');
		Asset::set_path('theme');

		/*
		// Get language that set in db
		$lang_cd = 'en';          // default language
		$lang_name = 'english';   

		$this->load->model('setting/setting_model');
		$row = $this->setting_model->getSetting('config', 'config_admin_language');
		if (isset($row))
		{
			$lang_cd = $row['config_admin_language'];
			if ($lang_cd === 'vi')
			{
				$lang_name = 'vietnamese';
			}
		}
		$this->session->set_userdata('config_admin_language', $lang_cd);

		$this->lang->switch_to($lang_cd);
		$this->lang->load('admin/common/login');
		$this->load->model('user/user_model');
		*/


		// Load language
		//Get language that was set in db
		$lang_cd = 'en';
		$lang_name = 'english';

		$this->load->model('setting/setting_model');
		$row = $this->setting_model->getSetting('config', 'config_admin_language');
		if (isset($row))
		{
			$lang_cd = isset($row['config_admin_language']) ? $row['config_admin_language'] : 'en';
			if ($lang_cd === 'vi')
			{
				$lang_name = 'vietnamese';
			}
		}
		$this->session->set_userdata('config_admin_language', $lang_cd);
		
         // Get language id from language table and set to session
        $where = array();
        $where['status'] = 1;
        $where['code']   = $lang_cd;
        $this->db->select('language_id');
        $query = $this->db->get_where('language', $where);
        $result = $query->row_array();

        if (isset($result['language_id']))
        {
            $this->session->set_userdata('config_language_id', $result['language_id']);
        }
        
        // Load config
        //$this->load->helper('setting');
        //get_setting_to_session();

        // Load information for header
        $this->data['logo_img'] = base_url() . 'uploads/' . $this->session->userdata('config_logo');
        
        // Load menu
        //$this->load->helper('menu_page');
		//load_header_menu($this->data);
        
        // Load footer
        $this->data['shop_name'] = $this->session->userdata('config_name');
        $this->data['shop_address'] = $this->session->userdata('config_address');
        $this->data['shop_telephone'] = $this->session->userdata('config_telephone');
        $this->data['shop_email'] = $this->session->userdata('config_email');
    }
}