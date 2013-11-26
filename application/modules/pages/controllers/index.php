<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
         $this->lang->load('pages/common/dashboard');
        $this->template->append_js('module::extends.js');
		$this->template->append_css('module::extends.css');
		
		$this->load->model('product_model');
    }

    public function index()
    {
    	$this->data['title'] = lang('heading_title');

    	$products = $this->product_model->getLatestProducts($this->session->userdata('config_catalog_limit'));
    	$this->data['products'] = array();

    	$this->load->model('tool/image_model');

    	$this->data['banners'][] = $this->image_model->resize('data/banner/banner3.jpg', 940, 333);
    	$this->data['banners'][] = $this->image_model->resize('data/banner/banner2.jpg', 940, 333);
    	$this->data['banners'][] = $this->image_model->resize('data/banner/banner1.jpg', 940, 333);
    	
    	$this->load->helper('number_format');
    	foreach ($products as $product) {
			$img_normal = $this->image_model->resize($product['image'], $this->session->userdata('config_image_category_width'), $this->session->userdata('config_image_category_height'));
			$img_large  = $this->image_model->resize($product['image'], $this->session->userdata('config_image_thumb_width'), $this->session->userdata('config_image_thumb_height'));
			
    		$this->data['products'][] =  array(
    			'product_id'	=> 	$product['product_id'],
    			'name'			=> 	$product['name'],
    			'price'			=> 	format_decimal($product['price']),
    			'normal'		=>	$img_normal,
    			'large'			=> 	$img_large,  
    			'href'			=>	base_url().'pages/product/detail/product_id/'.$product['product_id']  		
    		);
    	}
    	
    	if (isset($this->session->userdata['success'])) {
			$this->data['success'] = $this->session->userdata['success'];
			$this->session->unset_userdata('success');
		} else {
			$this->data['success'] = '';
		}

		$this->template->build('template/index', $this->data);
  	}
}
?>