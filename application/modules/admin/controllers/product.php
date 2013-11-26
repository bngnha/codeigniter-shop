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
        	// Switch language
	        $this->lang->switch_to($this->session->userdata('config_admin_language'));

	        // Product
	        $this->lang->load('admin/catalog/product');
	        $this->load->model('catalog/product_model');

            // Product
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
		$this->getList();
  	}
  
    public function req()
    {
        $this->getList();
    }
  	
  	public function insert()
  	{
    	if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
    	{
			$this->product_model->addProduct($this->input->post());
			$this->session->set_userdata('success', lang('text_success'));

			redirect('admin/product');
    	}
    	$this->getForm();
  	}

  	public function update()
  	{
    	if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
    	{
    		$parameters = $this->uri->uri_to_assoc(4);
			$this->product_model->editProduct($parameters['pid'], $this->input->post());
			$this->session->set_userdata('success', lang('text_success'));
			
			redirect('admin/product');
		}
    	$this->getForm();
  	}

  	public function delete()
  	{
		if ($this->input->post('selected') && $this->validateDelete())
		{
			foreach ($this->input->post('selected') as $product_id)
			{
				$this->product_model->deleteProduct($product_id);
	  		}
			$this->session->set_userdata('success', lang('text_success'));

			redirect('admin/product');
		}
    	$this->getList();
  	}

  	public function copy()
  	{
		if ($this->input->post('selected') && $this->validateCopy())
		{
			foreach ($this->input->post('selected') as $product_id)
			{
				$this->product_model->copyProduct($product_id);
	  		}
			$this->session->set_userdata('success', lang('text_success'));
			redirect('admin/product');
		}

    	$this->getList();
  	}
	
  	private function getList() 
  	{
  		$this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'text'      => lang('text_home'),
            'href'      => base_url().'admin/dashboard',
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => lang('heading_title'),
            'href'      => base_url().'admin/product',
            'separator' => ' :: '
        );

        $this->data['insert']             = base_url().'admin/product/insert';
        $this->data['copy']               = base_url().'admin/product/copy';    
        $this->data['delete']             = base_url().'admin/product/delete';
        $this->data['text_enabled']       = lang('text_enabled');
        $this->data['text_disabled']      = lang('text_disabled');
        $this->data['text_no_results']    = lang('text_no_results');
        $this->data['text_image_manager'] = lang('text_image_manager');
        $this->data['column_image']       = lang('column_image');
        $this->data['column_name']        = lang('column_name');
        $this->data['column_model']       = lang('column_model');
        $this->data['column_price']       = lang('column_price');
        $this->data['column_quantity']    = lang('column_quantity');
        $this->data['column_status']      = lang('column_status');
        $this->data['column_action']      = lang('column_action');
        $this->data['button_copy']        = lang('button_copy');
        $this->data['button_insert']      = lang('button_insert');
        $this->data['button_delete']      = lang('button_delete');
        $this->data['button_filter']      = lang('button_filter');
        
        $parameters = $this->uri->uri_to_assoc(4);
		if (isset($parameters['filter_name']))
		{
			$filter_name = $parameters['filter_name'];
		}
		else
		{
			$filter_name = null;
		}

		if (isset($parameters['filter_model']))
		{
			$filter_model = $parameters['filter_model'];
		}
		else
		{
			$filter_model = null;
		}
		
		if (isset($parameters['filter_price']))
		{
			$filter_price = $parameters['filter_price'];
		}
		else
		{
			$filter_price = null;
		}

		if (isset($parameters['filter_quantity']))
		{
			$filter_quantity = $parameters['filter_quantity'];
		}
		else
		{
			$filter_quantity = null;
		}

		if (isset($parameters['filter_status']))
		{
			$filter_status = $parameters['filter_status'];
		}
		else
		{
			$filter_status = null;
		}

  	    if (isset($parameters['sort']))
  	    {
            $this->data['sort'] = isset($parameters['sort']);
        }
        else
        {
            $this->data['sort'] = 'pd.name';
        }
        
        if (isset($parameters['order']))
        {
            $this->data['order'] = isset($parameters['order']);
        }
        else
        {
            $this->data['order'] = 'ASC';
        }

  	    // limit and offset
        $limit = $this->session->userdata('config_admin_limit');
        $offset = 0;
        if (isset($parameters['page']))
        {
            $offset = $parameters['page'];
        }
        else
        {
            $offset = '0';  
        }

        if (isset($parameters['sort']))
        {
            $this->data['sort'] = $parameters['sort'];
        }
        else
        {
            $this->data['sort'] = 'name';
        }
        if (isset($parameters['order']))
        {
            $this->data['order'] = $parameters['order'];
        }
        else
        {
            $this->data['order'] = 'asc';
        }       
        
        // link sort
        $order_name = 'asc';
        if ($this->data['sort'] == 'name'
            && $this->data['order'] == 'asc')
        {
            $order_name = 'desc';
        }
        if ($this->data['sort'] == 'name')
        {
        	$this->data['sort'] = 'pd.name';
        }
        
        $order_model = 'asc';
        if ($this->data['sort'] == 'model'
            && $this->data['order'] == 'asc')
        {
            $order_model = 'desc';      
        }
		if ($this->data['sort'] == 'model')
		{
			$this->data['sort'] = 'p.model';
		}
        
        $order_price = 'asc';
        if ($this->data['sort'] == 'price'
            && $this->data['order'] == 'asc')
        {
            $order_price = 'desc';
        }
        if ($this->data['sort'] == 'price')
        {
        	$this->data['sort'] = 'p.price';
        }
        
  	    $order_quantity = 'asc';
        if ($this->data['sort'] == 'quantity'
            && $this->data['order'] == 'asc')
        {
            $order_quantity = 'desc';
        }
        if ($this->data['sort'] == 'quantity')
        {
        	$this->data['sort'] = 'p.quantity';
        }
        
  	    $order_status = 'asc';
        if ($this->data['sort'] == 'status'
            && $this->data['order'] == 'asc')
        {
            $order_status = 'desc';
        }
        if ($this->data['sort'] == 'status')
        {
        	$this->data['sort'] = 'p.status';
        }
        
  	    $order_order = 'asc';
        if ($this->data['sort'] == 'sort_order'
            && $this->data['order'] == 'asc')
        {
            $order_order = 'desc';
        }
        if ($this->data['sort'] == 'sort_order')
        {
        	$this->data['sort'] = 'p.sort_order';
        }

        $this->data['sort_name']    = base_url().'admin/product/req/sort/name/order/'.$order_name;
        $this->data['sort_model']   = base_url().'admin/product/req/sort/model/order/'.$order_model;
        $this->data['sort_price']   = base_url().'admin/product/req/sort/price/order/'.$order_price;
        $this->data['sort_quantity']= base_url().'admin/product/req/sort/quantity/order/'.$order_quantity;
        $this->data['sort_status']  = base_url().'admin/product/req/sort/status/order/'.$order_status;
        $this->data['sort_order']   = base_url().'admin/product/req/sort/order/order/'.$order_order;

        $data = array(
            'filter_name'     => $filter_name, 
            'filter_model'    => $filter_model,
            'filter_price'    => $filter_price,
            'filter_quantity' => $filter_quantity,
            'filter_status'   => $filter_status,
            'sort'            => $this->data['sort'],
            'order'           => $this->data['order'],
            'start'           => $offset,
            'limit'           => $limit
        );
        
        $this->load->model('tool/image_model');
		$product_total = $this->product_model->getTotalProducts($data);
		$results = $this->product_model->getProducts($data);

		$this->load->helper('number_format');
		
		$this->data['products'] = array();
		foreach ($results as $result)
		{
			$action = array();
			$action[] = array(
				'text' => lang('text_edit'),
				'href' => base_url().'admin/product/update/pid/'.$result['product_id'],
			);

			if ($result['image'] && file_exists(DIR_IMAGE . $result['image']))
			{
				$image = $this->image_model->resize($result['image'], 40, 40);
			}
			else
			{
				$image = $this->image_model->resize('no_image.jpg', 40, 40);
			}

			$special = false;
			$product_specials = $this->product_model->getProductSpecials($result['product_id']);
			foreach ($product_specials  as $product_special)
			{
				if (($product_special['date_start'] == '0000-00-00' 
				  || $product_special['date_start'] > date('Y-m-d'))
				 && ($product_special['date_end'] == '0000-00-00' 
				 || $product_special['date_end'] < date('Y-m-d')))
				{
					$special = format_decimal($product_special['price']);
					break;
				}
			}

      		$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => format_decimal($result['price']),
				'special'    => $special,
				'image'      => $image,
				'quantity'   => $result['quantity'],
				'status'     => ($result['status'] ? lang('text_enabled') : lang('text_disabled')),
				'selected'   => $this->input->post('selected') && in_array($result['product_id'], $this->input->post('selected')),
				'action'     => $action
			);
    	}

 		if (isset($this->error['warning']))
 		{
			$this->data['error_warning'] = $this->error['warning'];
		}
		else
		{
			$this->data['error_warning'] = '';
		}

		if ($this->session->userdata('success'))
		{
			$this->data['success'] = $this->session->userdata('success');
			$this->session->unset_userdata('success');
		}
		else
		{
			$this->data['success'] = '';
		}

		$config['base_url']         = base_url().'admin/product/req/page/';
        $config['total_rows']       = $product_total;
        $config['cur_page']         = $offset;
        $this->data['pagination']   = create_pagination($config);

        $this->data['filter_name']  = $filter_name;
		$this->data['filter_model'] = $filter_model;
		$this->data['filter_price'] = format_decimal($filter_price);
		$this->data['filter_quantity'] = $filter_quantity;
		$this->data['filter_status']= $filter_status;

        $this->load->view('template/common/header', $this->data);
        $this->load->view('template/catalog/product_list');
        $this->load->view('template/common/footer');
  	}

  	private function getForm()
  	{
    	$this->data['text_enabled']    = lang('text_enabled');
    	$this->data['text_disabled']   = lang('text_disabled');
    	$this->data['text_none']       = lang('text_none');
    	$this->data['text_yes']        = lang('text_yes');
    	$this->data['text_no']         = lang('text_no');
		$this->data['text_select_all'] = lang('text_select_all');
		$this->data['text_unselect_all'] = lang('text_unselect_all');
		$this->data['text_plus']       = lang('text_plus');
		$this->data['text_minus']      = lang('text_minus');
		$this->data['text_default']    = lang('text_default');
		$this->data['text_image_manager'] = lang('text_image_manager');
		$this->data['text_browse']     = lang('text_browse');
		$this->data['text_clear']      = lang('text_clear');
		$this->data['text_option']     = lang('text_option');
		$this->data['text_option_value'] = lang('text_option_value');
		$this->data['text_select']     = lang('text_select');
		$this->data['text_none']       = lang('text_none');
		$this->data['text_percent']    = lang('text_percent');
		$this->data['text_amount']     = lang('text_amount');

		$this->data['entry_name']      = lang('entry_name');
		$this->data['entry_meta_description'] = lang('entry_meta_description');
		$this->data['entry_meta_keyword'] = lang('entry_meta_keyword');
		$this->data['entry_description'] = lang('entry_description');
		$this->data['entry_keyword']   = lang('entry_keyword');
    	$this->data['entry_model']     = lang('entry_model');
		$this->data['entry_location']  = lang('entry_location');
		$this->data['entry_minimum']   = lang('entry_minimum');
		$this->data['entry_manufacturer'] = lang('entry_manufacturer');
    	$this->data['entry_date_available'] = lang('entry_date_available');
    	$this->data['entry_quantity']  = lang('entry_quantity');
		$this->data['entry_stock_status'] = lang('entry_stock_status');
    	$this->data['entry_price']     = lang('entry_price');
    	$this->data['entry_size']      = lang('entry_size');
    	$this->data['entry_color']     = lang('entry_color');
    	$this->data['entry_image']     = lang('entry_image');
    	$this->data['entry_category']  = lang('entry_category');
		$this->data['entry_related']   = lang('entry_related');
		$this->data['entry_text']      = lang('entry_text');
		$this->data['entry_required']  = lang('entry_required');
		$this->data['entry_sort_order']= lang('entry_sort_order');
		$this->data['entry_status']    = lang('entry_status');
		$this->data['entry_customer_group'] = lang('entry_customer_group');
		$this->data['entry_date_start']= lang('entry_date_start');
		$this->data['entry_date_end']  = lang('entry_date_end');
		$this->data['entry_priority']  = lang('entry_priority');
		$this->data['entry_tag']       = lang('entry_tag');
				
    	$this->data['button_save']     = lang('button_save');
    	$this->data['button_cancel']   = lang('button_cancel');
		$this->data['button_add_special'] = lang('button_add_special');
		$this->data['button_add_image']= lang('button_add_image');
		$this->data['button_remove']   = lang('button_remove');
		
    	$this->data['tab_general']     = lang('tab_general');
    	$this->data['tab_data']        = lang('tab_data');
		$this->data['tab_special']     = lang('tab_special');
    	$this->data['tab_image']       = lang('tab_image');		
		$this->data['tab_links']       = lang('tab_links');
		 
 		if (isset($this->error['warning']))
 		{
			$this->data['error_warning'] = $this->error['warning'];
		}
		else
		{
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name']))
 		{
			$this->data['error_name'] = $this->error['name'];
		}
		else
		{
			$this->data['error_name'] = array();
		}

 		if (isset($this->error['meta_description']))
 		{
			$this->data['error_meta_description'] = $this->error['meta_description'];
		}
		else
		{
			$this->data['error_meta_description'] = array();
		}		
   
   		if (isset($this->error['description']))
   		{
			$this->data['error_description'] = $this->error['description'];
		}
		else
		{
			$this->data['error_description'] = array();
		}	
		
   		if (isset($this->error['model']))
   		{
			$this->data['error_model'] = $this->error['model'];
		}
		else
		{
			$this->data['error_model'] = '';
		}		
     	
		if (isset($this->error['date_available']))
		{
			$this->data['error_date_available'] = $this->error['date_available'];
		}
		else
		{
			$this->data['error_date_available'] = '';
		}	

		$url = '';
		if ($this->input->get('filter_name'))
		{
			$url .= '/filter_name/' . $this->input->get('filter_name');
		}
		
		if ($this->input->get('filter_model'))
		{
			$url .= '/filter_model/' . $this->input->get('filter_model');
		}
		
		if ($this->input->get('filter_price'))
		{
			$url .= '/filter_price/' . $this->input->get['filter_price'];
		}
		
		if ($this->input->get('filter_quantity'))
		{
			$url .= '/filter_quantity/' . $this->input->get('filter_quantity');
		}	
		
		if ($this->input->get('filter_status'))
		{
			$url .= '/filter_status/' . $this->input->get('filter_status');
		}
								
		if ($this->input->get('sort'))
		{
			$url .= '/sort/' . $this->input->get('sort');
		}

		if ($this->input->get('order'))
		{
			$url .= '/order/' . $this->input->get('order');
		}
		
		if ($this->input->get('page'))
		{
			$url .= '/page/' . $this->input->get('page');
		}

  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('text_home'),
			'href'      => base_url().'admin/dashboard',
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('heading_title'),
			'href'      => base_url().'admin/product',
      		'separator' => ' :: '
   		);

   		$this->load->helper('number_format');
   		
  	    $parameters = $this->uri->uri_to_assoc(4);
        if (sizeof($parameters) > 0 && isset($parameters['pid']))
        {
            $this->data['action'] = base_url().'admin/product/update/pid/'.$parameters['pid'];
        }
        else
        {
            $this->data['action'] = base_url().'admin/product/insert';
        }

		$this->data['cancel'] = base_url().'admin/product';

		if (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
      		$product_info = $this->product_model->getProduct($parameters['pid']);
    	}

    	$this->load->model('localisation/language_model');
		$this->data['languages'] = $this->language_model->getLanguages();

		if ($this->input->post('product_description'))
		{
			$this->data['product_description'] = $this->input->post('product_description');
		}
		elseif (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
			$this->data['product_description'] = $this->product_model->getProductDescriptions($parameters['pid']);
		}
		else
		{
			$this->data['product_description'] = array();
		}

		if ($this->input->post('model'))
		{
      		$this->data['model'] = $this->input->post('model');
    	}
    	elseif (!empty($product_info))
    	{
			$this->data['model'] = $product_info['model'];
		}
		else
		{
      		$this->data['model'] = '';
    	}

		if ($this->input->post('location'))
		{
      		$this->data['location'] = $this->input->post('location');
    	}
    	elseif (!empty($product_info))
    	{
			$this->data['location'] = $product_info['location'];
		}
		else
		{
      		$this->data['location'] = '';
    	}

		if ($this->input->post('keyword'))
		{
			$this->data['keyword'] = $this->input->post('keyword');
		}
		elseif (!empty($product_info))
		{
			$this->data['keyword'] = $product_info['keyword'];
		}
		else
		{
			$this->data['keyword'] = '';
		}

		if ($this->input->post('product_tag'))
		{
			$this->data['product_tag'] = $this->input->post('product_tag');
		}
		elseif (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
			$this->data['product_tag'] = $this->product_model->getProductTags($parameters['pid']);
		}
		else
		{
			$this->data['product_tag'] = array();
		}

		if ($this->input->post('image'))
		{
			$this->data['image'] = $this->input->post('image');
		}
		elseif (!empty($product_info))
		{
			$this->data['image'] = $product_info['image'];
		}
		else
		{
			$this->data['image'] = '';
		}

		$this->load->model('tool/image_model');
		if (!empty($product_info) && $product_info['image']
		&& file_exists(DIR_IMAGE . $product_info['image']))
		{
			$this->data['thumb'] = $this->image_model->resize($product_info['image'], 100, 100);
		}
		else
		{
			$this->data['thumb'] = $this->image_model->resize('no_image.jpg', 100, 100);
		}

		$this->load->model('catalog/manufacturer_model');
    	$this->data['manufacturers'] = $this->manufacturer_model->getManufacturers();

    	if ($this->input->post('manufacturer_id'))
    	{
      		$this->data['manufacturer_id'] = $this->input->post('manufacturer_id');
		}
		elseif (!empty($product_info))
		{
			$this->data['manufacturer_id'] = $product_info['manufacturer_id'];
		}
		else
		{
      		$this->data['manufacturer_id'] = 0;
    	} 

    	if ($this->input->post('price'))
    	{
      		$this->data['price'] = $this->input->post('price');
    	}
    	else if (!empty($product_info))
    	{
			$this->data['price'] = format_decimal($product_info['price']);
		}
		else
		{
      		$this->data['price'] = '';
    	}
    	
  		if ($this->input->post('size'))
    	{
      		$this->data['size'] = $this->input->post('size');
    	}
    	else if (!empty($product_info))
    	{
			$this->data['size'] = $product_info['size'];
		}
		else
		{
      		$this->data['size'] = 'all';
    	}
  		
    	if ($this->input->post('color'))
    	{
      		$this->data['color'] = $this->input->post('color');
    	}
    	else if (!empty($product_info))
    	{
			$this->data['color'] = $product_info['color'];
		}
		else
		{
      		$this->data['color'] = 'all';
    	}

		if ($this->input->post('date_available'))
		{
       		$this->data['date_available'] = $this->input->post('date_available');
		}
		elseif (!empty($product_info))
		{
			$this->data['date_available'] = date('Y-m-d', strtotime($product_info['date_available']));
		}
		else
		{
			$this->data['date_available'] = date('Y-m-d', time() - 86400);
		}
								
    	if ($this->input->post('quantity'))
    	{
      		$this->data['quantity'] = $this->input->post('quantity');
    	}
    	elseif (!empty($product_info))
    	{
      		$this->data['quantity'] = $product_info['quantity'];
    	}
    	else
    	{
			$this->data['quantity'] = 1;
		}

		if ($this->input->post('minimum'))
		{
      		$this->data['minimum'] = $this->input->post('minimum');
    	}
    	elseif (!empty($product_info))
    	{
      		$this->data['minimum'] = $product_info['minimum'];
    	}
    	else
    	{
			$this->data['minimum'] = 1;
		}

		if ($this->input->post('sort_order'))
		{
      		$this->data['sort_order'] = $this->input->post('sort_order');
    	}
    	elseif (!empty($product_info))
    	{
      		$this->data['sort_order'] = $product_info['sort_order'];
    	}
    	else
    	{
			$this->data['sort_order'] = 1;
		}

		$this->load->model('localisation/stock_model');
		$this->data['stock_statuses'] = $this->stock_model->getStockStatuses();

		if ($this->input->post('stock_status_id'))
		{
      		$this->data['stock_status_id'] = $this->input->post('stock_status_id');
    	}
    	else if (!empty($product_info))
    	{
      		$this->data['stock_status_id'] = $product_info['stock_status_id'];
    	}
    	else
    	{
			$this->data['stock_status_id'] = $this->session->userdata('config_stock_status_id');
		}

    	if ($this->input->post('status'))
    	{
      		$this->data['status'] = $this->input->post('status');
    	}
    	else if (!empty($product_info))
    	{
			$this->data['status'] = $product_info['status'];
		}
		else
		{
      		$this->data['status'] = 1;
    	}

		if ($this->input->post('product_special'))
		{
			$this->data['product_specials'] = $this->input->post('product_special');
		}
		elseif (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
			$this->data['product_specials'] = $this->product_model->getProductSpecials($parameters['pid']);
		}
		else
		{
			$this->data['product_specials'] = array();
		}

		if ($this->input->post('product_image'))
		{
			$product_images = $this->input->post('product_image');
		}
		elseif (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
			$product_images = $this->product_model->getProductImages($parameters['pid']);
		}
		else
		{
			$product_images = array();
		}

		$this->data['product_images'] = array();
		foreach ($product_images as $product_image)
		{
			if ($product_image['image'] && file_exists(DIR_IMAGE . $product_image['image']))
			{
				$image = $product_image['image'];
			}
			else
			{
				$image = 'no_image.jpg';
			}
			
			$this->data['product_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->image_model->resize($image, 100, 100),
				'sort_order' => $product_image['sort_order'],
			);
		}
		$this->data['no_image'] = $this->image_model->resize('no_image.jpg', 100, 100);
		
		$this->load->model('catalog/category_model');
		$this->data['categories'] = $this->category_model->getCategories(0);
		
		if ($this->input->post('product_category'))
		{
			$this->data['product_category'] = $this->input->post('product_category');
		}
		elseif (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
			$this->data['product_category'] = $this->product_model->getProductCategories($parameters['pid']);
		}
		else
		{
			$this->data['product_category'] = array();
		}		

		if ($this->input->post('product_related'))
		{
			$products = $this->input->post('product_related');
		}
		elseif (sizeof($parameters) > 0 && isset($parameters['pid']))
		{		
			$products = $this->product_model->getProductRelated($parameters['pid']);
		}
		else
		{
			$products = array();
		}

		$this->data['product_related'] = array();
		foreach ($products as $product_id)
		{
			$related_info = $this->product_model->getProduct($product_id);
			
			if ($related_info)
			{
				$this->data['product_related'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}

    	$this->load->view('template/common/header', $this->data);
        $this->load->view('template/catalog/product_form');
        $this->load->view('template/common/footer');
  	} 

  	private function validateForm()
  	{ 
    	foreach ($this->input->post('product_description') as $language_id => $value)
    	{
      		if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255))
      		{
        		$this->error['name'][$language_id] = lang('error_name');
      		}
    	}
		
    	if ((utf8_strlen($this->input->post('model')) < 1) 
    	 || (utf8_strlen($this->input->post('model')) > 64))
    	{
      		$this->error['model'] = lang('error_model');
    	}

		if ($this->error && !isset($this->error['warning']))
		{
			$this->error['warning'] = lang('error_warning');
		}

    	if (!$this->error)
    	{
			return true;
    	}
    	else
    	{
      		return false;
    	}
  	}

  	private function validateDelete()
  	{
  		return true;
  	}

  	private function validateCopy()
  	{
  		return true;
  	}
		
	public function autocomplete()
	{
		$json = array();
		$parameters = $this->uri->uri_to_assoc(4);
		if (sizeof($parameters) > 0 
		&& (isset($parameters['filter_name']) || isset($parameters['filter_model']) || isset($parameters['filter_category_id'])))
		{
			$this->load->model('catalog/product_model');
			$this->load->helper('number_format');

			if (isset($parameters['filter_name']))
			{
				$filter_name = $parameters['filter_name'];
			}
			else
			{
				$filter_name = '';
			}

			if (isset($parameters['filter_model']))
			{
				$filter_model = $parameters['filter_model'];
			}
			else
			{
				$filter_model = '';
			}

			if (isset($parameters['filter_category_id']))
			{
				$filter_category_id = $parameters['filter_category_id'];
			}
			else
			{
				$filter_category_id = '';
			}

			if (isset($parameters['filter_sub_category']))
			{
				$filter_sub_category = $parameters['filter_sub_category'];
			}
			else
			{
				$filter_sub_category = '';
			}
			
			if (isset($parameters['limit']))
			{
				$limit = $parameters['limit'];	
			}
			else
			{
				$limit = 20;	
			}			
						
			$data = array(
				'filter_name'         => $filter_name,
				'filter_model'        => $filter_model,
				'filter_category_id'  => $filter_category_id,
				'filter_sub_category' => $filter_sub_category,
				'start'               => 0,
				'limit'               => $limit
			);

			$results = $this->product_model->getProducts($data);
			foreach ($results as $result)
			{
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),	
					'model'      => $result['model'],
					'price'      => format_decimal($result['price'])
				);	
			}
		}
		
		echo json_encode ($json);
	}
}
?>