<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data = array();
        $this->error= array();
 
        if ($this->session->userdata('logged_in'))
        {
            $this->lang->switch_to($this->session->userdata('config_admin_language'));

            // Not found
            $this->lang->load('admin/catalog/product');
            $this->load->language('admin/error/not_found');

            // Not found
            $this->data['title']            = lang('heading_title');
            $this->data['heading_title']    = lang('heading_title');
            
            $this->load->helper('menu');
            load_header_menu($this->data);
        }
        else
        {
            redirect('admin/login');
        }
    }
    
	public function index()
	{ 
		$this->data['text_not_found'] = $this->language->get('text_not_found');
  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('text_home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('heading_title'),
			'href'      => base_url().'admin/not_found',
      		'separator' => ' :: '
   		);

   		$this->load->view('template/common/header', $this->data);
        $this->load->view('template/error/not_found');
        $this->load->view('template/common/footer');
  	}
}
?>