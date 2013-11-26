<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manufacturer extends MX_Controller
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

            // Manufacturer
            $this->lang->load('admin/catalog/manufacturer');
            $this->load->model('catalog/manufacturer_model');

            // Manufacturer
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

	function req()
    {
        $this->getList();
    }
  
  	public function insert()
  	{
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
		{
			$this->manufacturer_model->addManufacturer($this->input->post());

			$this->session->set_userdata('success', lang('text_success'));
			redirect('admin/manufacturer');
		}
    
    	$this->getForm();
  	} 
   
  	public function update()
  	{
    	if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
    	{
    		$parameters = $this->uri->uri_to_assoc(4);
			$this->manufacturer_model->editManufacturer($parameters['pid'], $this->input->post());

			$this->session->set_userdata('success', lang('text_success'));

			redirect('admin/manufacturer');
		}
    
    	$this->getForm();
  	}   

  	public function delete()
  	{
    	if ($this->input->post('selected') && $this->validateDelete())
    	{
			foreach ($this->input->post['selected'] as $manufacturer_id)
			{
				$this->manufacturer_model->deleteManufacturer($manufacturer_id);
			}
			$this->session->set_userdata('success', lang('text_success'));
			
			redirect('admin/manufacturer');
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
			'href'      => base_url().'admin/manufacturer',
      		'separator' => ' :: '
   		);

		$this->data['insert'] = base_url().'catalog/manufacturer/insert';
		$this->data['delete'] = base_url().'catalog/manufacturer/delete';	
		
		// limit and offset
        $limit = $this->session->userdata('config_admin_limit');
        $offset = 0;
  		$parameters = $this->uri->uri_to_assoc(4);
  		if (isset($parameters['sort']))
		{
			$sort = $parameters['sort'];
		}
		else
		{
			$sort = 'name';
		}
		
		if (isset($parameters['order']))
		{
			$order = $parameters['order'];
		}
		else
		{
			$order = 'asc';
		}
  		
		if (isset($parameters['page']))
        {
            $offset = $parameters['page'];
        }
        else
        {
            $offset = '0';  
        }

        $order_name = 'asc';
        if ((isset($parameters['sort']) && $parameters['sort'] == 'name')
            && $order == 'asc')
        {
            $order_name = 'desc';
        }

        $order_order = 'asc';
        if ((isset($parameters['sort']) && $parameters['sort'] == 'sort_order')
            && $order == 'asc')
        {
            $order_order = 'desc';
        }

		$this->data['manufacturers'] = array();
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => $offset,
			'limit' => $limit
		);
		
		$manufacturer_total = $this->manufacturer_model->getTotalManufacturers();
		$results = $this->manufacturer_model->getManufacturers($data);
 
    	foreach ($results as $result)
    	{
			$action = array();
			$action[] = array(
				'text' => lang('text_edit'),
				'href' => base_url().'admin/manufacturer/update/pid/'.$result['manufacturer_id']
			);
						
			$this->data['manufacturers'][] = array(
				'manufacturer_id' => $result['manufacturer_id'],
				'name'            => $result['name'],
				'sort_order'      => $result['sort_order'],
				'selected'        => $this->input->post('selected') && in_array($result['manufacturer_id'], $this->input->post('selected')),
				'action'          => $action
			);
		}	
	
		$this->data['sort'] 	= $sort;
		$this->data['order'] 	= $order;
		$this->data['text_no_results'] 	= lang('text_no_results');
		$this->data['column_name'] 		= lang('column_name');
		$this->data['column_sort_order']= lang('column_sort_order');
		$this->data['column_action'] 	= lang('column_action');		
		$this->data['button_insert'] 	= lang('button_insert');
		$this->data['button_delete'] 	= lang('button_delete');
 
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
		
		$this->data['sort_name'] 		= base_url().'admin/manufacturer/req/sort/name/order/'.$order_name;
		$this->data['sort_sort_order'] 	= base_url().'admin/manufacturer/req/sort/sort_order/order/'.$order_order;
		
		$config['base_url']       = base_url().'admin/manufacturer/req/page/';
        $config['total_rows']     = $manufacturer_total;
        $config['cur_page']       = $offset;
        $this->data['pagination'] = create_pagination($config);

        $this->load->view('template/common/header', $this->data);
        $this->load->view('template/catalog/manufacturer_list');
        $this->load->view('template/common/footer');
	}
  
  	private function getForm()
  	{
    	$this->data['text_enabled'] 	= lang('text_enabled');
    	$this->data['text_disabled'] 	= lang('text_disabled');
		$this->data['text_default'] 	= lang('text_default');
    	$this->data['text_image_manager'] = lang('text_image_manager');
		$this->data['text_browse'] 	= lang('text_browse');
		$this->data['text_clear'] 	= lang('text_clear');			
		$this->data['text_percent'] = lang('text_percent');
		$this->data['text_amount'] 	= lang('text_amount');
				
		$this->data['entry_name'] 	= lang('entry_name');
		$this->data['entry_store'] 	= lang('entry_store');
		$this->data['entry_keyword']= lang('entry_keyword');
    	$this->data['entry_image'] 	= lang('entry_image');
		$this->data['entry_sort_order'] 	= lang('entry_sort_order');
		$this->data['entry_customer_group'] = lang('entry_customer_group');
		  
    	$this->data['button_save'] 	= lang('button_save');
    	$this->data['button_cancel']= lang('button_cancel');
		$this->data['tab_general'] = lang('tab_general');
			  
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
			$this->data['error_name'] = '';
		}
		    
  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('text_home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('heading_title'),
			'href'      => base_url().'admin/manufacturer',
      		'separator' => ' :: '
   		);

  		$parameters = $this->uri->uri_to_assoc(4);
        if (sizeof($parameters) > 0 && isset($parameters['pid']))
        {
            $this->data['action'] = base_url().'admin/manufacturer/update/pid/'.$parameters['pid'];
        }
        else
        {
            $this->data['action'] = base_url().'admin/manufacturer/insert';
        }
        
		$this->data['cancel'] = base_url().'admin/manufacturer';
		
		if (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
      		$manufacturer_info = $this->manufacturer_model->getManufacturer($parameters['pid']);
    	}

    	if ($this->input->post('name'))
    	{
      		$this->data['name'] = $this->input->post('name');
    	}
    	elseif (!empty($manufacturer_info))
    	{
			$this->data['name'] = $manufacturer_info['name'];
		}
		else
		{	
      		$this->data['name'] = '';
    	}
		
		if ($this->input->post('keyword'))
		{
			$this->data['keyword'] = $this->input->post('keyword');
		}
		elseif (!empty($manufacturer_info))
		{
			$this->data['keyword'] = $manufacturer_info['keyword'];
		}
		else
		{
			$this->data['keyword'] = '';
		}

		if ($this->input->post('image'))
		{
			$this->data['image'] = $this->input->post('image');
		}
		elseif (!empty($manufacturer_info))
		{
			$this->data['image'] = $manufacturer_info['image'];
		}
		else
		{
			$this->data['image'] = '';
		}
		
		$this->load->model('tool/image_model');

		if (!empty($manufacturer_info) && $manufacturer_info['image'] 
		&& file_exists(DIR_IMAGE . $manufacturer_info['image']))
		{
			$this->data['thumb'] = $this->image_model->resize($manufacturer_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->image_model->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->image_model->resize('no_image.jpg', 100, 100);
		
		if ($this->input->post('sort_order'))
		{
      		$this->data['sort_order'] = $this->input->post('sort_order');
    	}
    	elseif (!empty($manufacturer_info))
    	{
			$this->data['sort_order'] = $manufacturer_info['sort_order'];
		}
		else
		{
      		$this->data['sort_order'] = '';
    	}

    	$this->load->view('template/common/header', $this->data);
        $this->load->view('template/catalog/manufacturer_form');
        $this->load->view('template/common/footer');
	}  
	 
  	private function validateForm()
  	{
    	//if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
      	//	$this->error['warning'] = lang('error_permission');
    	//}

    	if ((utf8_strlen($this->input->post('name')) < 3)
    	 || (utf8_strlen($this->input->post('name')) > 64))
    	 {
      		$this->error['name'] = lang('error_name');
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
    	//if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
		//	$this->error['warning'] = lang('error_permission');
    	//}	
		
		$this->load->model('catalog/product_model');
		foreach ($this->input->post('selected') as $manufacturer_id)
		{
  			$product_total = $this->product_model->getTotalProductsByManufacturerId($manufacturer_id);
			if ($product_total)
			{
	  			$this->error['warning'] = sprintf(lang('error_product'), $product_total);	
			}	
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
}
?>