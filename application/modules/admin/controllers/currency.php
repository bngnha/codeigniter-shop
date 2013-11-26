<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Currency extends MX_Controller
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
	
	        // Currency
	        $this->lang->load('admin/localisation/currency');
	        $this->load->model('localisation/currency_model');
            
            // Currency
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
       if ($this->input->post('title'))
			{
				$data['title'] = $this->input->post('title');
			}
			else
			{
				$data['title'] = '';
			}
	
			if ($this->input->post('code'))
			{
				$data['code'] = $this->input->post('code');
			}
			else
			{
				$data['code'] = '';
			}
	
			if ($this->input->post('symbol_left'))
			{
				$data['symbol_left'] = $this->input->post('symbol_left');
			}
			else
			{
				$data['symbol_left'] = '';
			}
	
			if ($this->input->post('symbol_right'))
			{
				$data['symbol_right'] = $this->input->post('symbol_right');
			}
			else
			{
				$data['symbol_right'] = '';
			}
	
			if ($this->input->post('decimal_place'))
			{
				$data['decimal_place'] = $this->input->post('decimal_place');
			}
			else
			{
				$data['decimal_place'] = '';
			}
	
			if ($this->input->post('value'))
			{
				$data['value'] = $this->input->post('value');
			}
			else
			{
				$data['value'] = '';
			}
	
	    	if ($this->input->post('status'))
	    	{
	      		$data['status'] = $this->input->post('status');
	    	}
			else
			{
	      		$data['status'] = '';
	    	}

            $this->currency_model->addCurrency($data);
            $this->session->set_userdata('success', lang('text_success'));
            redirect('admin/currency');
		}
		$this->getForm();
	}

	public function update()
	{
	   if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
       {
       		$parameters = $this->uri->uri_to_assoc(4);
	        if (sizeof($parameters) > 0 && isset($parameters['pid']))
			{
				$currency_info = $this->currency_model->getCurrency($parameters['pid']);
			}
	       	if ($this->input->post('title'))
			{
				$data['title'] = $this->input->post('title');
			}
			elseif (!empty($currency_info))
			{
				$data['title'] = $currency_info['title'];
			}
			else
			{
				$data['title'] = '';
			}
	
			if ($this->input->post('code'))
			{
				$data['code'] = $this->input->post('code');
			} elseif (!empty($currency_info))
			{
				$data['code'] = $currency_info['code'];
			}
			else
			{
				$data['code'] = '';
			}
	
			if ($this->input->post('symbol_left'))
			{
				$data['symbol_left'] = $this->input->post('symbol_left');
			}
			elseif (!empty($currency_info))
			{
				$data['symbol_left'] = $currency_info['symbol_left'];
			}
			else
			{
				$data['symbol_left'] = '';
			}
	
			if ($this->input->post('symbol_right'))
			{
				$data['symbol_right'] = $this->input->post('symbol_right');
			}
			elseif (!empty($currency_info))
			{
				$data['symbol_right'] = $currency_info['symbol_right'];
			}
			else
			{
				$data['symbol_right'] = '';
			}
	
			if ($this->input->post('decimal_place'))
			{
				$data['decimal_place'] = $this->input->post('decimal_place');
			}
			elseif (!empty($currency_info))
			{
				$data['decimal_place'] = $currency_info['decimal_place'];
			}
			else
			{
				$data['decimal_place'] = '';
			}
	
			if ($this->input->post('value'))
			{
				$data['value'] = $this->input->post('value');
			}
			elseif (!empty($currency_info))
			{
				$data['value'] = $currency_info['value'];
			}
			else
			{
				$data['value'] = '';
			}
	
	    	if ($this->input->post('status'))
	    	{
	      		$data['status'] = $this->input->post('status');
	    	}
	    	elseif (!empty($currency_info))
	    	{
				$data['status'] = $currency_info['status'];
			}
			else
			{
	      		$data['status'] = '';
	    	}

            $this->currency_model->editCurrency($parameters['pid'], $data);
            $this->session->set_userdata('success', lang('text_success'));

            redirect('admin/currency');
		}
		$this->getForm();
	}

	public function delete()
	{
		if ($this->input->post('selected') && $this->validateDelete())
        {
			foreach ($this->input->post('selected') as $currency_id)
			{
				$this->currency_model->deleteCurrency($currency_id);
			}

			$this->session->set_userdata('success', lang('text_success'));
			redirect('admin/currency');
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
			'href'      => base_url().'admin/currency',
      		'separator' => ' :: '
   		);

		$this->data['insert'] = base_url().'admin/currency/insert';
		$this->data['delete'] = base_url().'admin/currency/delete';
		$this->data['column_title']           = lang('column_title');
        $this->data['column_code']            = lang('column_code');
        $this->data['column_value']           = lang('column_value');
        $this->data['column_date_modified']   = lang('column_date_modified');
        $this->data['column_action']          = lang('column_action');
        $this->data['button_insert']          = lang('button_insert');
        $this->data['button_delete']          = lang('button_delete');

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
            $this->data['sort'] = 'title';
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
        $order_title = 'asc';
        if ($this->data['sort'] == 'title'
            && $this->data['order'] == 'asc')
        {
            $order_title = 'desc';
        }
        $order_code = 'asc';
        if ($this->data['sort'] == 'code'
            && $this->data['order'] == 'asc')
        {
            $order_code = 'desc';
        }
	    $order_value = 'asc';
        if ($this->data['sort'] == 'value'
            && $this->data['order'] == 'asc')
        {
            $order_value = 'desc';
        }
        $order_modified = 'asc';
        if ($this->data['sort'] == 'date_modified'
            && $this->data['order'] == 'asc')
        {
            $order_modified = 'desc';
        }

        $this->data['sort_title']           = base_url().'admin/currency/req/sort/title/order/'.$order_title;
        $this->data['sort_code']            = base_url().'admin/currency/req/sort/code/order/'.$order_code;
        $this->data['sort_value']           = base_url().'admin/currency/req/sort/value/order/'.$order_value;
        $this->data['sort_date_modified']   = base_url().'admin/currency/req/sort/date_modified/order/'.$order_modified;

        $search = array();
		$currency_total = $this->currency_model->getTotalCurrencies();
		$results = $this->currency_model->getCurrencies($search, $limit, $offset, $this->data['sort'], $this->data['order']);

		$this->data['currencies'] = array();
		if (count($results) > 0) {
			foreach ($results as $result)
			{
				$action = array();
				$action[] = array(
					'text' => lang('text_edit'),
					'href' => base_url().'admin/currency/update/pid/'.$result['currency_id'],
				);

				$this->data['currencies'][] = array(
					'currency_id'   => $result['currency_id'],
					'title'         => $result['title'],
					'code'          => $result['code'],
					'value'         => $result['value'],
					'date_modified' => date(lang('date_format_short'), strtotime($result['date_modified'])),
					'selected'      => $this->input->post('selected') && in_array($result['currency_id'], $this->input->post('selected')),
					'action'        => $action
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
		}
		else
		{
			$this->data['success'] = '';
		}

        $config['base_url']         = base_url().'admin/currency/req/page/';
        $config['total_rows']       = $currency_total;
        $config['cur_page']         = $offset;
        $this->data['pagination']   = create_pagination($config);

        $this->load->view('template/common/header', $this->data);
        $this->load->view('template/localisation/currency_list');
        $this->load->view('template/common/footer');
	}

	private function getForm()
	{
		$this->data['text_enabled']   = lang('text_enabled');
    	$this->data['text_disabled']  = lang('text_disabled');
		
		$this->data['entry_title']    = lang('entry_title');
		$this->data['entry_code']     = lang('entry_code');
		$this->data['entry_value']    = lang('entry_value');
		$this->data['entry_symbol_left']  = lang('entry_symbol_left');
		$this->data['entry_symbol_right'] = lang('entry_symbol_right');
		$this->data['entry_decimal_place']= lang('entry_decimal_place');
		$this->data['entry_status']       = lang('entry_status');

		$this->data['button_save']        = lang('button_save');
		$this->data['button_cancel']      = lang('button_cancel');
		$this->data['tab_general']        = lang('tab_general');

 		if (isset($this->error['warning']))
 		{
			$this->data['error_warning'] = $this->error['warning'];
		}
		else
		{
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['title']))
 		{
			$this->data['error_title'] = $this->error['title'];
		}
		else
		{
			$this->data['error_title'] = '';
		}
		
 		if (isset($this->error['code']))
 		{
			$this->data['error_code'] = $this->error['code'];
		}
		else
		{
			$this->data['error_code'] = '';
		}

  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('text_home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('heading_title'),
			'href'      => base_url().'admin/currency',
      		'separator' => ' :: '
   		);

   		$parameters = $this->uri->uri_to_assoc(4);
        if (sizeof($parameters) > 0 && isset($parameters['pid']))
        {
            $this->data['action'] = base_url().'admin/currency/update/pid/'.$parameters['pid'];
		}
		else
		{
            $this->data['action'] = base_url().'admin/currency/insert';
		}
				
		$this->data['cancel'] = base_url().'admin/currency';

		if (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
			$currency_info = $this->currency_model->getCurrency($parameters['pid']);
		}

		if ($this->input->post('title'))
		{
			$this->data['title'] = $this->input->post('title');
		}
		elseif (!empty($currency_info))
		{
			$this->data['title'] = $currency_info['title'];
		}
		else
		{
			$this->data['title'] = '';
		}

		if ($this->input->post('code'))
		{
			$this->data['code'] = $this->input->post('code');
		} elseif (!empty($currency_info))
		{
			$this->data['code'] = $currency_info['code'];
		}
		else
		{
			$this->data['code'] = '';
		}

		if ($this->input->post('symbol_left'))
		{
			$this->data['symbol_left'] = $this->input->post('symbol_left');
		}
		elseif (!empty($currency_info))
		{
			$this->data['symbol_left'] = $currency_info['symbol_left'];
		}
		else
		{
			$this->data['symbol_left'] = '';
		}

		if ($this->input->post('symbol_right'))
		{
			$this->data['symbol_right'] = $this->input->post('symbol_right');
		}
		elseif (!empty($currency_info))
		{
			$this->data['symbol_right'] = $currency_info['symbol_right'];
		}
		else
		{
			$this->data['symbol_right'] = '';
		}

		if ($this->input->post('decimal_place'))
		{
			$this->data['decimal_place'] = $this->input->post('decimal_place');
		}
		elseif (!empty($currency_info))
		{
			$this->data['decimal_place'] = $currency_info['decimal_place'];
		}
		else
		{
			$this->data['decimal_place'] = '';
		}

		if ($this->input->post('value'))
		{
			$this->data['value'] = $this->input->post('value');
		}
		elseif (!empty($currency_info))
		{
			$this->data['value'] = $currency_info['value'];
		}
		else
		{
			$this->data['value'] = '';
		}

    	if ($this->input->post('status'))
    	{
      		$this->data['status'] = $this->input->post('status');
    	}
    	elseif (!empty($currency_info))
    	{
			$this->data['status'] = $currency_info['status'];
		}
		else
		{
      		$this->data['status'] = '';
    	}

        $this->load->view('template/common/header', $this->data);
        $this->load->view('template/localisation/currency_form');
        $this->load->view('template/common/footer');
	}
	
	private function validateForm()
	{ 
		if ((utf8_strlen($this->input->post('title')) < 3) 
			|| (utf8_strlen($this->input->post('title')) > 32))
		{
			$this->error['title'] = lang('error_title');
		}

		if (utf8_strlen($this->input->post('code')) != 3)
		{
			$this->error['code'] = lang('error_code');
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
		//$this->load->model('setting/store');
		//$this->load->model('sale/order');
		
		foreach ($this->input->post('selected') as $currency_id)
		{
			$currency_info = $this->currency_model->getCurrency($currency_id);
			if ($currency_info)
			{
				if ($this->config->item('config_currency') == $currency_info['code'])
				{
					$this->error['warning'] = lang('error_default');
				}
				
				//$store_total = $this->model_setting_store->getTotalStoresByCurrency($currency_info['code']);	
				//if ($store_total)
				//{
				//	$this->error['warning'] = sprintf(lang('error_store'), $store_total);
				//}					
			}

			//$order_total = $this->model_sale_order->getTotalOrdersByCurrencyId($currency_id);
			//if ($order_total)
			//{
			//	$this->error['warning'] = sprintf(lang('error_order'), $order_total);
			//}					
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