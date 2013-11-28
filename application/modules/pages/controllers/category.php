<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Category extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('pages/catalog/category');
        $this->load->model('category_model');
        $this->load->model('product_model');
        $this->load->helper('pagination_page');
    }

    public function detail()
    {
    	$this->data['title'] = lang('heading_title');

    	$parameters = $this->uri->uri_to_assoc(4);
    	if (sizeof($parameters) > 0 && isset($parameters['category_id'])) {

    		$limit = $this->session->userdata('config_catalog_limit');
	    	$offset = '0';
	    	if (array_key_exists('page', $parameters))
	        {
	            $offset = isset($parameters['page']) ? $parameters['page'] : '0' ;
	        }
	        else
	        {
	            $offset = '0';  
	        }

    		$category_id = 0;
    		if (array_key_exists('child_id', $parameters)){
    			$category_id = isset($parameters['child_id']) ? $parameters['child_id'] : 0;
    		} else if(array_key_exists('category_id', $parameters)){
    			$category_id = isset($parameters['category_id']) ? $parameters['category_id'] : 0;
    		}

    		$data = array(
				'filter_category_id' => $category_id,
				'sort'               => 'p.sort_order',
				'order'              => 'ASC',
				'start'              => $offset,
				'limit'              => $limit
			);

	    	$product_total = $this->product_model->getTotalProducts($data); 
			$results = $this->product_model->getProducts($data);
	    	
	    	$this->data['products'] = array();
	
	    	$this->load->model('tool/image_model');

	    	$this->data['banners'][] = $this->image_model->resize('data/banner/banner3.jpg', 940, 333);
    		$this->data['banners'][] = $this->image_model->resize('data/banner/banner2.jpg', 940, 333);
    		$this->data['banners'][] = $this->image_model->resize('data/banner/banner1.jpg', 940, 333);
	    	
    		$this->load->helper('number_format');
	    	foreach ($results as $product) {
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
			
	    	$link_page = "";
	    	if(array_key_exists('category_id', $parameters)){
    			$link_page = isset($parameters['category_id']) ? 'category_id/'.$parameters['category_id'].'/' : '';
    		}
    		if (array_key_exists('child_id', $parameters)){
    			$link_page .= isset($parameters['child_id']) ? 'child_id/'.$parameters['child_id'].'/' : '';
    		}
	    	if ($product_total > $limit) {
		    	$config['base_url']      	= base_url().'pages/category/detail/'.$link_page.'page/';
	        	$config['total_rows']    	= $product_total;
	        	$config['cur_page']         = $offset;
	        	$config['per_page']			= $limit;
	        	$this->data['pagination']   = create_pagination_pages($config);
	    	}
    	}
		$this->template->build('template/category', $this->data);
  	}
}
?>