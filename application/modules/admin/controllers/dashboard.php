<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
	}
	public function index()
	{
		if ($this->session->userdata('logged_in'))
		{
			// Switch language
	        $this->lang->switch_to($this->session->userdata('config_admin_language'));

	        $this->lang->load('admin/common/dashboard');
	        
	        // Dashboard
	        $this->data['title']           = lang('admin.dashboard.title');
			$this->data['heading_title']   = lang('admin.dashboard.header');
			
            $this->load->helper('menu');
            load_header_menu($this->data);
			
			// Get information configuration
			$this->load->model('setting/setting_model');
			$results = $this->setting_model->getSetting();
			if (is_array($results) && sizeof($results) > 0)
			{
				$this->session->set_userdata($results);
			}

			$this->data['breadcrumbs'] = array();
	        $this->data['breadcrumbs'][] = array(
	            'text'      => lang('text_home'),
	            'href'      => base_url().'admin/dashboard',
	            'separator' => false
	        );

	        $this->data['link_category']= base_url().'admin/category';
	        $this->data['link_product']	= base_url().'admin/product';
	        $this->data['link_news']	= base_url().'admin/news';
	        $this->data['link_information']	= base_url().'admin/information';
	        $this->data['link_manufacturer']= base_url().'admin/manufacturer';
	        $this->data['link_review']	= base_url().'admin/review';
	        $this->data['link_faq']		= base_url().'admin/faq';
	        $this->data['link_setting']	= base_url().'admin/setting';
	        $this->data['link_user']	= base_url().'admin/user';
	        $this->data['link_usergrp']	= base_url().'admin/usergrp';
	        $this->data['link_country']	= base_url().'admin/country';
	        $this->data['link_language']= base_url().'admin/language';
	        $this->data['link_currency']= base_url().'admin/currency';
	        $this->data['link_stock']	= base_url().'admin/stock';
	        $this->data['link_zone']	= base_url().'admin/zone';
	        $this->data['link_contact']	= base_url().'admin/contact';
	        
	        $this->data['link_help']	= base_url().'admin/help';

			$this->load->view('template/common/header', $this->data);
			$this->load->view('template/common/dashboard');
			$this->load->view('template/common/footer');
		}
		else
		{
			redirect('admin/login');
		}
	}
}