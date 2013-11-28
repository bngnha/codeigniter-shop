<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Country extends MX_Controller
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

	        // Country
	        $this->lang->load('admin/localisation/country');
	        $this->load->model('localisation/country_model');

            // Country
            $this->data['title']        	= lang('heading_title');
            $this->data['heading_title']   	= lang('heading_title');
            
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
		    if ($this->input->post('name'))
		    {
				$data['name'] = $this->input->post('name');
			}
	
			if ($this->input->post('iso_code_2'))
			{
				$data['iso_code_2'] = $this->input->post('iso_code_2');
			}
			else
			{
				$data['iso_code_2'] = '';
			}
	
			if ($this->input->post('iso_code_3'))
			{
				$data['iso_code_3'] = $this->input->post('iso_code_3');
			}
			else
			{
				$data['iso_code_3'] = '';
			}
	
			if ($this->input->post('address_format'))
			{
				$data['address_format'] = $this->input->post('address_format');
			}
			else
			{
				$data['address_format'] = '';
			}
	
			if ($this->input->post('postcode_required'))
			{
				$data['postcode_required'] = $this->input->post('postcode_required');
			}
			else
			{
				$data['postcode_required'] = '';
			}
					
			if ($this->input->post('status'))
			{
				$data['status'] = $this->input->post('status');
			}
			else
			{
				$data['status'] = 0;
			}
       	
            $this->country_model->addCountry($data);
            $this->session->set_userdata('success', lang('text_success'));

			redirect('admin/country');
		}
		$this->getForm();
	}

	public function update()
	{
	   if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
       {
       		$parameters = $this->uri->uri_to_assoc(4);
       		$country_info = $this->country_model->getCountry($parameters['pid']);
	       	if ($this->input->post('name'))
	       	{
				$data['name'] = $this->input->post('name');
			}
			elseif (!empty($country_info))
			{
				$data['name'] = $country_info['name'];
			}
			else
			{
				$data['name'] = '';
			}
	
			if ($this->input->post('iso_code_2'))
			{
				$data['iso_code_2'] = $this->input->post('iso_code_2');
			}
			elseif (!empty($country_info))
			{
				$data['iso_code_2'] = $country_info['iso_code_2'];
			}
			else
			{
				$data['iso_code_2'] = '';
			}
	
			if ($this->input->post('iso_code_3'))
			{
				$data['iso_code_3'] = $this->input->post('iso_code_3');
			}
			elseif (!empty($country_info))
			{
				$data['iso_code_3'] = $country_info['iso_code_3'];
			}
			else
			{
				$data['iso_code_3'] = '';
			}
	
			if ($this->input->post('address_format'))
			{
				$data['address_format'] = $this->input->post('address_format');
			}
			elseif (!empty($country_info))
			{
				$data['address_format'] = $country_info['address_format'];
			}
			else
			{
				$data['address_format'] = '';
			}
	
			if ($this->input->post('postcode_required'))
			{
				$data['postcode_required'] = $this->input->post('postcode_required');
			}
			elseif (!empty($country_info))
			{
				$data['postcode_required'] = $country_info['postcode_required'];
			}
			else
			{
				$data['postcode_required'] = 0;
			}
					
			if ($this->input->post('status'))
			{
				$data['status'] = $this->input->post('status');
			}
			elseif (!empty($country_info))
			{
				$data['status'] = $country_info['status'];
			}
			else
			{
				$data['status'] = '1';
			}
            $this->country_model->editCountry($parameters['pid'], $data);
            $this->session->set_userdata('success', lang('text_success'));

			redirect('admin/country');
		}
		$this->getForm();
	}
 
	public function delete()
	{
		if ($this->input->post('selected') && $this->validateDelete())
        {
            foreach ($this->input->post('selected') as $country_id)
            {
                $this->country_model->deleteCountry($country_id);
            }
            $this->session->set_userdata('success', lang('text_success'));
            redirect('admin/country');
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
			'href'      => base_url().'admin/country',
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] 	= base_url().'admin/country/insert';
		$this->data['delete'] 	= base_url().'admin/country/delete';
		$this->data['column_name'] 			= lang('column_name');
		$this->data['column_iso_code_2'] 	= lang('column_iso_code_2');
		$this->data['column_iso_code_3'] 	= lang('column_iso_code_3');
		$this->data['column_action'] 		= lang('column_action');	
		$this->data['button_insert'] 		= lang('button_insert');
		$this->data['button_delete'] 		= lang('button_delete');

		// limit and offset
		$limit    = $this->session->userdata('config_admin_limit');
		$offset   = 0;
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
    	$order_code2 = 'asc';
    	if ($this->data['sort'] == 'iso_code_2'
    		&& $this->data['order'] == 'asc')
    	{
    		$order_code2 = 'desc';		
    	}
    	$order_code3 = 'asc';
    	if ($this->data['sort'] == 'iso_code_3'
    		&& $this->data['order'] == 'asc')
    	{
    		$order_code3 = 'desc';		
    	}
    	$this->data['sort_name'] 		= base_url().'admin/country/req/sort/name/order/'.$order_name;
		$this->data['sort_iso_code_2'] 	= base_url().'admin/country/req/sort/iso_code_2/order/'.$order_code2;
		$this->data['sort_iso_code_3'] 	= base_url().'admin/country/req/sort/iso_code_3/order/'.$order_code3;
		
		$search = array();
		$country_total = $this->country_model->getTotalCountries();
		$results = $this->country_model->getCountries($search, $limit, $offset, $this->data['sort'], $this->data['order']);
		
		$this->data['countries'] = array();
		if (count($results) > 0)
		{
			foreach ($results as $result)
			{
				$action = array();
				$action[] = array(
					'text' => lang('text_edit'),
					'href' => base_url().'admin/country/update/pid/'.$result['country_id']
				);
	
				$this->data['countries'][] = array(
					'country_id' => $result['country_id'],
					'name'       => $result['name'],
					'iso_code_2' => $result['iso_code_2'],
					'iso_code_3' => $result['iso_code_3'],
					'selected'   => ($this->input->post('selected')) && in_array($result['country_id'], $this->input->post('selected')),				
					'action'     => $action
				);
			}
		}
		else
		{
			$this->data['text_no_results'] 		= lang('text_no_results');
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

		$config['base_url'] 		= base_url().'admin/country/req/page/';
		$config['total_rows'] 		= $country_total;
		$config['cur_page']			= $offset;
		$this->data['pagination'] = create_pagination($config);

		$this->load->view('template/common/header', $this->data);
        $this->load->view('template/localisation/country_list');
        $this->load->view('template/common/footer');
	}

	private function getForm()
	{
		$this->data['text_enabled']   = lang('text_enabled');
		$this->data['text_disabled']  = lang('text_disabled');
		$this->data['text_yes']       = lang('text_yes');
		$this->data['text_no']        = lang('text_no');

		$this->data['entry_name']         = lang('entry_name');
		$this->data['entry_iso_code_2']   = lang('entry_iso_code_2');
		$this->data['entry_iso_code_3']   = lang('entry_iso_code_3');
		$this->data['entry_address_format']   = lang('entry_address_format');
		$this->data['entry_postcode_required']= lang('entry_postcode_required');
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
			'href'      => base_url().'admin/country',
      		'separator' => ' :: '
   		);

   		$parameters = $this->uri->uri_to_assoc(4);
   		if (sizeof($parameters) > 0 && isset($parameters['pid']))
   		{
			$this->data['action'] = base_url().'admin/country/update/pid/'.$parameters['pid'];
		}
		else
		{
			$this->data['action'] = base_url().'admin/country/insert';
		}

		$this->data['cancel'] = base_url().'admin/country';

		if (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
			$country_info = $this->country_model->getCountry($parameters['pid']);
		}

		if ($this->input->post('name'))
		{
			$this->data['name'] = $this->input->post('name');
		}
		elseif (!empty($country_info))
		{
			$this->data['name'] = $country_info['name'];
		}
		else
		{
			$this->data['name'] = '';
		}

		if ($this->input->post('iso_code_2'))
		{
			$this->data['iso_code_2'] = $this->input->post('iso_code_2');
		}
		elseif (!empty($country_info))
		{
			$this->data['iso_code_2'] = $country_info['iso_code_2'];
		}
		else
		{
			$this->data['iso_code_2'] = '';
		}

		if ($this->input->post('iso_code_3'))
		{
			$this->data['iso_code_3'] = $this->input->post('iso_code_3');
		}
		elseif (!empty($country_info))
		{
			$this->data['iso_code_3'] = $country_info['iso_code_3'];
		}
		else
		{
			$this->data['iso_code_3'] = '';
		}

		if ($this->input->post('address_format'))
		{
			$this->data['address_format'] = $this->input->post('address_format');
		}
		elseif (!empty($country_info))
		{
			$this->data['address_format'] = $country_info['address_format'];
		}
		else
		{
			$this->data['address_format'] = '';
		}

		if ($this->input->post('postcode_required'))
		{
			$this->data['postcode_required'] = $this->input->post('postcode_required');
		}
		elseif (!empty($country_info))
		{
			$this->data['postcode_required'] = $country_info['postcode_required'];
		}
		else
		{
			$this->data['postcode_required'] = 0;
		}
				
		if ($this->input->post('status'))
		{
			$this->data['status'] = $this->input->post('status');
		}
		elseif (!empty($country_info))
		{
			$this->data['status'] = $country_info['status'];
		}
		else
		{
			$this->data['status'] = '1';
		}

        $this->load->view('template/common/header', $this->data);
        $this->load->view('template/localisation/country_form');
        $this->load->view('template/common/footer');
	}

	private function validateForm()
	{
		if ((utf8_strlen($this->input->post('name')) < 3)
			|| (utf8_strlen($this->input->post('name')) > 128))
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
		$this->load->model('localisation/zone_model');
		foreach ($this->input->post('selected') as $country_id)
		{
			if ($this->config->item('config_country_id') == $country_id)
			{
				$this->error['warning'] = lang('error_default');
			}

			$zone_total = $this->zone_model->getTotalZonesByCountryId($country_id);
			if ($zone_total)
			{
				$this->error['warning'] = sprintf(lang('error_zone'), $zone_total);
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