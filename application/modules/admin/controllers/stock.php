<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Stock extends MX_Controller
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
	
	        // Stock
	        $this->lang->load('admin/localisation/stock');
	        $this->load->model('localisation/stock_model');

            // Stock
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
			$data = array();
			if ($this->input->post('stock_status'))
			{
				$data['stock_status'] = $this->input->post('stock_status');
			}

			$this->stock_model->addStockStatus($data);
            $this->session->set_userdata('success', lang('text_success'));
			redirect('admin/stock');
		}
    	$this->getForm();
  	}

  	public function update()
  	{
  		if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
        {
        	$parameters = $this->uri->uri_to_assoc(4);
        	$data = array();
            if ($this->input->post('stock_status'))
            {
                $data['stock_status'] = $this->input->post('stock_status');
            }

            $this->stock_model->editStockStatus($parameters['pid'], $data);
            $this->session->set_userdata('success', lang('text_success'));
			redirect('admin/stock');
    	}
    	$this->getForm();
  	}

  	public function delete()
  	{
    	if ($this->input->post('selected') && $this->validateDelete())
    	{
			foreach ($this->input->post('selected') as $stock_status_id)
			{
				$this->stock_model->deleteStockStatus($stock_status_id);
			}

			$this->session->set_userdata('success', lang('text_success'));
			redirect('admin/stock');
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
			'href'      => base_url().'admin/stock',
      		'separator' => ' :: '
   		);

		$this->data['insert'] = base_url().'admin/stock/insert';
		$this->data['delete'] = base_url().'admin/stock/delete';
        $this->data['column_name']   = lang('column_name');
        $this->data['column_action'] = lang('column_action');
        $this->data['button_insert'] = lang('button_insert');
        $this->data['button_delete'] = lang('button_delete');

  	    // limit and offset
        $limit = $this->session->userdata('config_admin_limit');
        $offset = 0;
        $parameters = $this->uri->uri_to_assoc(4);
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
        $this->data['sort_name'] = base_url().'admin/stock/req/sort/name/order/'.$order_name;

		$search = array();
		$stock_status_total = $this->stock_model->getTotalStockStatuses();
		$results = $this->stock_model->getStockStatuses($search, $limit, $offset, $this->data['sort'], $this->data['order']);

        $this->data['stock_statuses'] = array();
        if (count($results) > 0)
        {
	    	foreach ($results as $result) 
	    	{
				$action = array();
				$action[] = array(
					'text' => lang('text_edit'),
					'href' => base_url().'admin/stock/update/pid/'.$result['stock_status_id'],
				);

				$this->data['stock_statuses'][] = array(
					'stock_status_id' => $result['stock_status_id'],
					'name'            => $result['name'],
					'selected'        => ($this->input->post('selected')) && in_array($result['stock_status_id'], $this->input->post('selected')),
					'action'          => $action
				);
			}
        }
        else
        {
		  $this->data['text_no_results'] = lang('text_no_results');
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
		} else {
			$this->data['success'] = '';
		}

        $config['base_url']         = base_url().'admin/stock/req/page/';
        $config['total_rows']       = $stock_status_total;
        $config['cur_page']         = $offset;
        $this->data['pagination'] 	= create_pagination($config);

        $this->load->view('template/common/header', $this->data);
        $this->load->view('template/localisation/stock_status_list');
        $this->load->view('template/common/footer');
  	}

  	private function getForm()
  	{
    	$this->data['entry_name']      = lang('entry_name');
		$this->data['entry_sort_order']= lang('entry_sort_order');
    	$this->data['button_save']     = lang('button_save');
    	$this->data['button_cancel']   = lang('button_cancel');
    	$this->data['tab_general'] 	   = lang('tab_general');

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

  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('text_home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('heading_title'),
			'href'      => base_url().'admin/stock',
      		'separator' => ' :: '
   		);

   		$parameters = $this->uri->uri_to_assoc(4);
        if (sizeof($parameters) > 0 && isset($parameters['pid']))
        {
			$this->data['action'] = base_url().'admin/stock/update/pid/'.$parameters['pid'];
		}
		else
		{
			$this->data['action'] = base_url().'admin/stock/insert';
		}

		$this->data['cancel'] = base_url().'admin/stock';

		$this->load->model('localisation/language_model');
		$this->data['languages'] = $this->language_model->getLanguages();

		if ($this->input->post('stock_status'))
		{
			$this->data['stock_status'] = $this->input->post('stock_status');
		}
		elseif (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
			$this->data['stock_status'] = $this->stock_model->getStockStatusDescriptions($parameters['pid']);
		}
		else
		{
			$this->data['stock_status'] = array();
		}

		$this->load->view('template/common/header', $this->data);
        $this->load->view('template/localisation/stock_status_form');
        $this->load->view('template/common/footer');
  	}

	private function validateForm()
	{
    	foreach ($this->input->post('stock_status') as $language_id => $value)
    	{
      		if ((utf8_strlen($value['name']) < 3) 
      			|| (utf8_strlen($value['name']) > 32))
      		{
        		$this->error['name'][$language_id] = lang('error_name');
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

  	private function validateDelete()
  	{
		$this->load->model('catalog/product_model');

		foreach ($this->input->post('selected') as $stock_status_id)
		{
			if ($this->config->item('config_stock_status_id') == $stock_status_id)
			{
				$this->error['warning'] = lang('error_default');
			}

			$product_total = $this->product_model->getTotalProductsByStockStatusId($stock_status_id);
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