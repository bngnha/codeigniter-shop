<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Information extends MX_Controller
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

            // Information
            $this->lang->load('admin/catalog/information');
            $this->load->model('catalog/information_model');

            // Information
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
			$this->information_model->addInformation($this->input->post());
			$this->session->data['success'] = $this->language->get('text_success');
			
			redirect(base_url().'admin/information');
		}

		$this->getForm();
	}

	public function update()
	{
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
		{
			$parameters = $this->uri->uri_to_assoc(4);
			$this->information_model->editInformation($parameters['pid'], $this->input->post());
			$this->session->set_userdata('success', lang('text_success'));

			redirect(base_url().'admin/information');
		}

		$this->getForm();
	}
 
	public function delete()
	{
		if ($this->input->post('selected') && $this->validateDelete())
		{
			foreach ($this->input->post('selected') as $information_id)
			{
				$this->information_model->deleteInformation($information_id);
			}
			$this->session->set_userdata('success', lang('text_success'));

			redirect(base_url().'admin/information');
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
			'href'      => base_url().'admin/information',
      		'separator' => ' :: '
   		);

		$this->data['insert'] = base_url().'admin/information/insert';
		$this->data['delete'] = base_url().'admin/information/delete';
		       
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
			$sort = 'id.title';
		}
		
		if (isset($parameters['order']))
		{
			$order = $parameters['order'];
		}
		else
		{
			$order = 'ASC';
		}

		if (isset($parameters['page']))
        {
            $offset = $parameters['page'];
        }
        else
        {
            $offset = '0';  
        }

        $order_title = 'asc';
        if ($sort == 'title'
            && $order == 'asc')
        {
            $order_title = 'desc';
        }
        if ($sort == 'title')
        {
        	$sort = 'id.title';
        }
        
		$order_order = 'asc';
        if ($sort == 'sort_order'
            && $order == 'asc')
        {
            $order_order = 'desc';
        }
        
        if ($sort == 'sort_order')
        {
        	$sort = 'i.sort_order';
        }

		$this->data['informations'] = array();
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => $offset,
			'limit' => $limit
		);
		$information_total = $this->information_model->getTotalInformations();
		$results = $this->information_model->getInformations($data);
 
    	foreach ($results as $result)
    	{
			$action = array();
			$action[] = array(
				'text' => lang('text_edit'),
				'href' => base_url().'admin/information/update/pid/'.$result['information_id']
			);
						
			$this->data['informations'][] = array(
				'information_id' => $result['information_id'],
				'title'          => $result['title'],
				'sort_order'     => $result['sort_order'],
				'selected'       => $this->input->post('selected') && in_array($result['information_id'], $this->input->post('selected')),
				'action'         => $action
			);
		}	

		$this->data['sort'] 	= $sort;
		$this->data['order'] 	= $order;
		$this->data['text_no_results']    = lang('text_no_results');
		$this->data['column_title']       = lang('column_title');
		$this->data['column_sort_order']  = lang('column_sort_order');
		$this->data['column_action']      = lang('column_action');		

		$this->data['button_insert'] = lang('button_insert');
		$this->data['button_delete'] = lang('button_delete');
 
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

		$this->data['sort_title']      = base_url().'admin/information/req/sort/title/order/'.$order_title;
		$this->data['sort_sort_order'] = base_url().'admin/information/req/sort/sort_order/order/'.$order_order;

		$config['base_url']       = base_url().'admin/information/req/page/';
        $config['total_rows']     = $information_total;
        $config['cur_page']       = $offset;
        $this->data['pagination'] = create_pagination($config);

        $this->load->view('template/common/header', $this->data);
        $this->load->view('template/catalog/information_list');
        $this->load->view('template/common/footer');
	}

	private function getForm()
	{
		$this->data['text_default']   = lang('text_default');
		$this->data['text_enabled']   = lang('text_enabled');
    	$this->data['text_disabled']  = lang('text_disabled');
		
		$this->data['entry_title']    = lang('entry_title');
		$this->data['entry_description'] = lang('entry_description');
		$this->data['entry_store']    = lang('entry_store');
		$this->data['entry_keyword']  = lang('entry_keyword');
		$this->data['entry_sort_order'] = lang('entry_sort_order');
		$this->data['entry_status']   = lang('entry_status');
		$this->data['entry_layout']   = lang('entry_layout');
		
		$this->data['button_save']    = lang('button_save');
		$this->data['button_cancel']  = lang('button_cancel');
    	
		$this->data['tab_general']    = lang('tab_general');
    	$this->data['tab_data']       = lang('tab_data');
		$this->data['tab_design']     = lang('tab_design');

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
			$this->data['error_title'] = array();
		}
		
	 	if (isset($this->error['description']))
	 	{
			$this->data['error_description'] = $this->error['description'];
		}
		else
		{
			$this->data['error_description'] = array();
		}
		
		$url = '';
		if ($this->input->get('sort'))
		{
			$url .= '&sort=' . $this->input->get('sort');
		}

		if ($this->input->get('order'))
		{
			$url .= '&order=' . $this->input->get('order');
		}
		
		if ($this->input->get('page'))
		{
			$url .= '&page=' . $this->input->get('page');
		}
		
  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('text_home'),
			'href'      => base_url().'admin/dashboard',     		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('heading_title'),
			'href'      => base_url().'admin/information',
      		'separator' => ' :: '
   		);

		$parameters = $this->uri->uri_to_assoc(4);
        if (sizeof($parameters) > 0 && isset($parameters['pid']))
        {
            $this->data['action'] = base_url().'admin/information/update/pid/'.$parameters['pid'];
        }
        else
        {
            $this->data['action'] = base_url().'admin/information/insert';
        }
		
		$this->data['cancel'] = base_url().'admin/information';

		if (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
			$information_info = $this->information_model->getInformation($parameters['pid']);
		}
		
		$this->load->model('localisation/language_model');
		$this->data['languages'] = $this->language_model->getLanguages();
		
		if ($this->input->post('information_description'))
		{
			$this->data['information_description'] = $this->input->post('information_description');
		}
		elseif (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
			$this->data['information_description'] = $this->information_model->getInformationDescriptions($parameters['pid']);
		}
		else
		{
			$this->data['information_description'] = array();
		}

		if ($this->input->post('status'))
		{
			$this->data['status'] = $this->input->post('status');
		}
		elseif (!empty($information_info))
		{
			$this->data['status'] = $information_info['status'];
		}
		else
		{
			$this->data['status'] = 1;
		}
		
		if ($this->input->post('information_store'))
		{
			$this->data['information_store'] = $this->input->post('information_store');
		}
		elseif ($this->input->get('information_id'))
		{
			$this->data['information_store'] = $this->information_model->getInformationStores($this->input->get('information_id'));
		}
		else
		{
			$this->data['information_store'] = array(0);
		}		
		
		if ($this->input->post('keyword'))
		{
			$this->data['keyword'] = $this->input->post('keyword');
		}
		elseif (!empty($information_info))
		{
			$this->data['keyword'] = $information_info['keyword'];
		}
		else
		{
			$this->data['keyword'] = '';
		}
		
		if ($this->input->post('sort_order'))
		{
			$this->data['sort_order'] = $this->input->post('sort_order');
		}
		elseif (!empty($information_info))
		{
			$this->data['sort_order'] = $information_info['sort_order'];
		}
		else
		{
			$this->data['sort_order'] = '';
		}
		
		$this->load->view('template/common/header', $this->data);
        $this->load->view('template/catalog/information_form');
        $this->load->view('template/common/footer');
	}

	private function validateForm()
	{
		//if (!$this->user->hasPermission('modify', 'catalog/information')) {
		//	$this->error['warning'] = lang('error_permission');
		//}

		foreach ($this->input->post('information_description') as $language_id => $value)
		{
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64))
			{
				$this->error['title'][$language_id] = lang('error_title');
			}
		
			if (utf8_strlen($value['description']) < 3)
			{
				$this->error['description'][$language_id] = lang('error_description');
			}
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
		//if (!$this->user->hasPermission('modify', 'catalog/information')) {
		//	$this->error['warning'] = lang('error_permission');
		//}

		//$this->load->model('setting/store');
		foreach ($this->input->post('selected') as $information_id)
		{
			//if ($this->config->item('config_account_id') == $information_id)
			//{
			//	$this->error['warning'] = lang('error_account');
			//}
			
			//if ($this->config->item('config_checkout_id') == $information_id)
			//{
			//	$this->error['warning'] = lang('error_checkout');
			//}
			
			//if ($this->config->item('config_affiliate_id') == $information_id)
			//{
			//	$this->error['warning'] = lang('error_affiliate');
			//}
						
			//$store_total = $this->model_setting_store->getTotalStoresByInformationId($information_id);

			//if ($store_total) {
			//	$this->error['warning'] = sprintf(lang('error_store'), $store_total);
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