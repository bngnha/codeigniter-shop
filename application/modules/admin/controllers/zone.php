<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Zone extends MX_Controller
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
        	
	        // Zone
	        $this->lang->load('admin/localisation/zone');
	        $this->load->model('localisation/zone_model');
        
            // Zone
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
            if ($this->input->post('name'))
            {
                $data['name'] = $this->input->post('name');
            }
    
            if ($this->input->post('code'))
            {
                $data['code'] = $this->input->post('code');
            }
            else
            {
                $data['code'] = '';
            }
    
            if ($this->input->post('country_id'))
            {
                $data['country_id'] = $this->input->post('country_id');
            }
            else
            {
                $data['country_id'] = '';
            }

            if ($this->input->post('status'))
            {
                $data['status'] = $this->input->post('status');
            }
            else
            {
                $data['status'] = 0;
            }
			$this->zone_model->addZone($data);
	
			$this->session->set_userdata('success', lang('text_success'));
			redirect(base_url().'admin/zone');
		}

		$this->getForm();
	}

	public function update()
	{
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
		{
    		$parameters = $this->uri->uri_to_assoc(4);
    		$zone_info = $this->zone_model->getZone($parameters['pid']);
    		
    		$data = array();
	        if ($this->input->post('name'))
	        {
	            $data['name'] = $this->input->post('name');
	        }
	        else if (isset($zone_info))
	        {
	            $data['name'] = $zone_info['name'];
	        }
	        else
	        {
	            $data['name'] = '';
	        }
	
	        if ($this->input->post('code'))
	        {
	            $data['code'] = $this->input->post('code');
	        }
	        else if (isset($zone_info))
	        {
	            $data['code'] = $zone_info['code'];
	        }
	        else
	        {
	            $data['code'] = '';
	        }
	
	        if ($this->input->post('country_id'))
	        {
	            $data['country_id'] = $this->input->post('country_id');
	        }
	        else if (isset($zone_info))
	        {
	            $data['country_id'] = $zone_info['country_id'];
	        }
	        else
	        {
	            $data['country_id'] = '';
	        }

	        if ($this->input->post('status'))
            {
                $data['status'] = $this->input->post('status');
            }
            else if (isset($zone_info))
            {
                $data['status'] = $zone_info['status'];
            }
            else
            {
                $data['status'] = '1';
            }

			$this->zone_model->editZone($parameters['pid'], $data);

			$this->session->set_userdata('success', lang('text_success'));
			redirect(base_url().'admin/zone');
		}

		$this->getForm();
	}

	public function delete()
	{
		if ($this->input->post('selected') && $this->validateDelete())
		{
			foreach ($this->input->post('selected') as $zone_id)
			{
				$this->zone_model->deleteZone($zone_id);
			}

			$this->session->set_userdata('success', lang('text_success'));
			redirect(base_url().'admin/zone');
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
			'href'      => base_url().'admin/zone',
      		'separator' => ' :: '
   		);

		$this->data['insert'] = base_url().'admin/zone/insert';
		$this->data['delete'] = base_url().'admin/zone/delete';
    	$this->data['column_country']   = lang('column_country');
        $this->data['column_name']      = lang('column_name');
        $this->data['column_code']      = lang('column_code');
        $this->data['column_action']    = lang('column_action');

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
        $order_country = 'asc';
        if ($this->data['sort'] == 'country'
            && $this->data['order'] == 'asc')
        {
            $order_country = 'desc';
        }
        $order_name = 'asc';
        if ($this->data['sort'] == 'name'
            && $this->data['order'] == 'asc')
        {
            $order_name = 'desc';
        }
        $order_code = 'asc';
        if ($this->data['sort'] == 'code'
            && $this->data['order'] == 'asc') {
            $order_code = 'desc';
        }

        $this->data['sort_country'] = base_url().'admin/zone/req/sort/country/order/'.$order_country;
        $this->data['sort_name'] = base_url().'admin/zone/req/sort/name/order/'.$order_name;
        $this->data['sort_code'] = base_url().'admin/zone/req/sort/code/order/'.$order_code;

        $search = array();
		$zone_total = $this->zone_model->getTotalZones();
		$results = $this->zone_model->getZones($search, $limit, $offset, $this->data['sort'], $this->data['order']);

        $this->data['zones'] = array();
        if (sizeof($results) > 0)
        {
			foreach ($results as $result)
			{
				$action = array();
				$action[] = array(
					'text' => lang('text_edit'),
					'href' => base_url().'admin/zone/update/pid/'.$result['zone_id'],
				);
				$this->data['zones'][] = array(
					'zone_id'  => $result['zone_id'],
					'country'  => $result['country'],
					'name'     => $result['name']. (($result['zone_id'] == $this->config->item('config_zone_id')) ? lang('text_default') : null),
					'code'     => $result['code'],
					'selected' => $this->input->post('selected') && in_array($result['zone_id'], $this->input->post('selected')),
					'action'   => $action
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

        $config['base_url']         = base_url().'admin/zone/req/page/';
        $config['total_rows']       = $zone_total;
        $config['cur_page']         = $offset;
        $this->data['pagination']   = create_pagination($config);

        $this->load->view('template/common/header', $this->data);
        $this->load->view('template/localisation/zone_list');
        $this->load->view('template/common/footer');
	}

	private function getForm()
	{
		$this->data['entry_status']   = lang('entry_status');
		$this->data['entry_name']     = lang('entry_name');
		$this->data['entry_code']     = lang('entry_code');
		$this->data['entry_country']  = lang('entry_country');

		$this->data['text_enabled']   = lang('text_enabled');
		$this->data['text_disabled']  = lang('text_disabled');
		
		$this->data['button_save']    = lang('button_save');
		$this->data['button_cancel']  = lang('button_cancel');

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
			'href'      => base_url().'admin/zone',
      		'separator' => ' :: '
   		);

    	$parameters = $this->uri->uri_to_assoc(4);
        if (sizeof($parameters) > 0 && isset($parameters['pid']))
        {
            $this->data['action'] = base_url().'admin/zone/update/pid/'.$parameters['pid'];
        }
        else
        {
            $this->data['action'] = base_url().'admin/zone/insert';
        }

		$this->data['cancel'] = base_url().'admin/zone';

		if (sizeof($parameters) > 0 && isset($parameters['pid']))
        {
			$zone_info = $this->zone_model->getZone($parameters['pid']);
		}

		if ($this->input->post('status'))
		{
			$this->data['status'] = $this->input->post('status');
		}
		else if (isset($zone_info))
		{
			$this->data['status'] = $zone_info['status'];
		}
		else
		{
			$this->data['status'] = '1';
		}
		
		if ($this->input->post('name'))
		{
			$this->data['name'] = $this->input->post('name');
		}
		else if (isset($zone_info))
		{
			$this->data['name'] = $zone_info['name'];
		}
		else
		{
			$this->data['name'] = '';
		}

		if ($this->input->post('code'))
		{
			$this->data['code'] = $this->input->post('code');
		}
		else if (isset($zone_info))
		{
			$this->data['code'] = $zone_info['code'];
		}
		else
		{
			$this->data['code'] = '';
		}

		if ($this->input->post('country_id'))
		{
			$this->data['country_id'] = $this->input->post('country_id');
		}
		else if (isset($zone_info))
		{
			$this->data['country_id'] = $zone_info['country_id'];
		}
		else
		{
			$this->data['country_id'] = '';
		}

		$this->load->model('localisation/country_model');
		$this->data['countries'] = $this->country_model->getCountries();

		$this->load->view('template/common/header', $this->data);
        $this->load->view('template/localisation/zone_form');
        $this->load->view('template/common/footer');
	}

	private function validateForm()
	{
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
		foreach ($this->input->post('selected') as $zone_id)
		{
			if ($this->config->item('config_zone_id') == $zone_id)
			{
				$this->error['warning'] = lang('error_default');
			}

			//$address_total = $this->model_sale_customer->getTotalAddressesByZoneId($zone_id);
			//if ($address_total) {
			//	$this->error['warning'] = sprintf(lang('error_address'), $address_total);
			//}

			//$zone_to_geo_zone_total = $this->model_localisation_geo_zone->getTotalZoneToGeoZoneByZoneId($zone_id);
			//if ($zone_to_geo_zone_total) {
			//	$this->error['warning'] = sprintf(lang('error_zone_to_geo_zone'), $zone_to_geo_zone_total);
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