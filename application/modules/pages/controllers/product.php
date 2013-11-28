<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template->append_css('module::extends.css');
        $this->template->append_css('module::carousel.css');
        $this->template->append_js('module::extends.js');
        $this->template->append_js('module::tabs.js');
        $this->template->append_js('module::jquery.jcarousel.js');
        
         $this->lang->load('pages/catalog/product');
        $this->load->helper('jcaptcha');
    }

    public function detail()
    {
    	$this->data['title'] = lang('heading_title');
    	
    	$parameters = $this->uri->uri_to_assoc(4);
    	if (sizeof($parameters) > 0 
    	&& isset($parameters['product_id']) 
    	&& $parameters['product_id'] > 0)
    	{
		    $this->data['contact_link'] = base_url().'pages/contact';
    		
    		$this->load->model('tool/image_model');
			$this->load->helper('number_format');

		    $product = $this->product_model->getProduct($parameters['product_id']);
		    $img_small 	= $this->image_model->resize($product['image'], $this->session->userdata('config_image_additional_width'), $this->session->userdata('config_image_additional_height'));
	    	$img_normal = $this->image_model->resize($product['image'], $this->session->userdata('config_image_product_width'), $this->session->userdata('config_image_product_height'));
	    	$img_large	= $this->image_model->resize($product['image'], $this->session->userdata('config_image_popup_width'), $this->session->userdata('config_image_popup_height'));
	   		$this->data['product']=  array(
	    		'product_id'	=> 	$product['product_id'],
	    		'name'			=> 	$product['name'],
	    		'price'			=> 	format_decimal($product['price']),
	   			'size'			=> 	$product['size'],
	   			'color'			=> 	$product['color'],
	   			'quantity'		=>	$product['quantity'],
	   			'viewed'		=>	$product['viewed'],
	   			'rating'		=> 	$product['rating'],
	   			'stock_status'	=>	$product['stock_status'],
	   			'description'	=>	$product['description'],
	   			'small'			=>	$img_small, 
	    		'normal'		=>	$img_normal,
	   			'large'			=>	$img_large,
	    		'href'			=>	base_url().'pages/product/detail/product_id/'.$product['product_id']  		
	    	);

	    	// additional
	    	$this->data['product_images'] = array();
	    	$product_images = $this->product_model->getProductImages($parameters['product_id']);
		    foreach ($product_images as $product_img)
		    	if ($product_img['image'] != 'no_image.jpg') {
		    	{
		    		$img_name_arr = explode("/", $product_img['image']);
		    		$this->data['product_images'][] = array(
		    			'name'		=>	$img_name_arr[1],
		    			'small'	   	=>	$this->image_model->resize($product_img['image'], $this->session->userdata('config_image_additional_width'), $this->session->userdata('config_image_additional_height')),
		    			'normal'	=>	$this->image_model->resize($product_img['image'], $this->session->userdata('config_image_product_width'), $this->session->userdata('config_image_product_height')),		
		    			'large'		=>	$this->image_model->resize($product_img['image'], $this->session->userdata('config_image_popup_width'), $this->session->userdata('config_image_popup_height'))
	    			);
		    	}
	    	}

	    	// related
	    	$this->data['related_products'] = array();
    		$related_products = $this->product_model->getProductRelated($parameters['product_id']);
	    	foreach ($related_products as $related_product)
	    	{
	    		$image_name_arr = explode("/", $related_product['image']);
	    		$this->data['related_products'][] = array(
	    			'product_id'	=>	$related_product['product_id'],
	    			'name'			=>	$related_product['name'],
	    			'price'			=>	format_decimal($related_product['price']),
	    			'href'			=>	base_url().'pages/product/detail/product_id/'.$related_product['product_id'],
	    			'image'			=>	$this->image_model->resize($related_product['image'], $this->session->userdata('config_image_related_width'), $this->session->userdata('config_image_related_height')),
	    			'atl'			=>	$image_name_arr[1]
    			);
	    	}

	    	// update viewed
	    	$this->product_model->updateViewed($parameters['product_id']);

	    	// create captcha
    		$this->data['captcha'] = createCaptcha('captcha_review'.$product['product_id']);

	    	// review action
	    	$this->data['review_action'] = base_url().'pages/product/ajaxReview';
	    	
	    	// get review data
	    	$this->load->model('catalog/review_model');
	    	$this->data['reviews'] = $this->review_model->getReviewsByProductId($parameters['product_id']);
	    	
	    	if (isset($this->error['name'])) {
				$this->data['error_name'] = $this->error['name'];
			} else {
			  $this->data['error_name'] = '';
			}

			if (isset($this->error['review'])) {
				$this->data['error_review'] = $this->error['review'];
			} else {
				$this->data['error_review'] = '';
			}

			if (isset($this->error['captcha'])) {
				$this->data['error_captcha'] = $this->error['captcha'];
			} else {
			  	$this->data['error_captcha'] = '';
			}
		}
		$this->template->build('template/product', $this->data);
  	}

	public function ajaxReview()
	{
		header('dataType: application/x-json, charset: utf-8');

		$data = array();
		$data['type'] = 1; // 0. no error, 1. error

		if (!$this->input->post('name')
		&& utf8_strlen($this->input->post('name')) <= 0)
		{
			$data['error_name'] = lang('error_name');
		}

		if (!$this->input->post('review')
		&& utf8_strlen($this->input->post('review') <= 10))
		{
			$data['error_review'] = lang('error_review');
		}

		$ip_address = $this->input->ip_address();
		$expiration = time() - 7200;
		
		$captcha_sess = $this->session->userdata('captcha_review'.$this->input->post('product_id'));
		if (!$this->input->post('captcha')
		|| $captcha_sess['captcha_word'] != $this->input->post('captcha')
		|| $captcha_sess['captcha_ip_address'] != $ip_address 
		|| $captcha_sess['captcha_time'] <= $expiration)
		{
			$data['error_captcha'] = lang('error_captcha');
		}

		if (($_SERVER['REQUEST_METHOD'] === 'POST') && count($data) <= 1)
		{
			$data['author'] = $this->input->post('name');
			$data['text'] = $this->input->post('review');
			$data['rating']= $this->input->post('rating');
			
			$this->load->model('catalog/review_model');

			$this->review_model->addReview($this->input->post('product_id'), $data);

			$data['type'] = 0;
			$data['success'] = lang('review_success');
		}

		echo json_encode($data);
	}
}
?>