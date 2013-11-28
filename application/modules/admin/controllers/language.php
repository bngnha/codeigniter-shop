<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Language extends MX_Controller
{
	public function __construct()
    {
        parent::__construct();
        $this->data = array();
        $this->error= array();
 
        if ($this->session->userdata('logged_in'))
		{
			// Common
	        $this->lang->switch_to($this->session->userdata('config_admin_language'));

	        // Language
	        $this->lang->load('admin/localisation/language');
	        $this->load->model('localisation/language_model');

	        // Language
            $this->data['title']        	= lang('heading_title');
            $this->data['heading_title'] 	= lang('heading_title');
            
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
			$this->language_model->addLanguage($this->input->post());
			$this->session->set_userdata('success', lang('text_success'));

			redirect('admin/language');
    	}
    	$this->getForm();
	}

	public function update()
	{
		
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
    	{
    		$parameters = $this->uri->uri_to_assoc(4);
			$this->language_model->editLanguage($parameters['pid'], $this->input->post());
			$this->session->set_userdata('success', lang('text_success'));

			redirect('admin/language');
    	}
		$this->getForm();
	}

	public function delete()
	{
		if ($this->input->post('selected') && $this->validateDelete())
    	{
      		foreach ($this->input->post('selected') as $language_id)
      		{
				$this->language_model->deleteLanguage($language_id);
			}
			$this->session->set_userdata('success', lang('text_success'));

			redirect('admin/language');
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
			'href'      => base_url().'admin/language',
      		'separator' => ' :: '
   		);

		$this->data['insert'] = base_url().'admin/language/insert';
		$this->data['delete'] = base_url().'admin/language/delete';
		$this->data['column_name'] 		 = lang('column_name');
    	$this->data['column_code'] 		 = lang('column_code');
		$this->data['column_sort_order'] = lang('column_sort_order');
		$this->data['column_action'] 	 = lang('column_action');

		$this->data['button_insert'] 	 = lang('button_insert');
		$this->data['button_delete']  	 = lang('button_delete');

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
    	$order_code = 'asc';
    	if ($this->data['sort'] == 'code'
    		&& $this->data['order'] == 'asc')
    	{
    		$order_code = 'desc';
    	}
    	$order_sort = 'asc';
    	if ($this->data['sort'] == 'sort_order'
    		&& $this->data['order'] == 'asc')
    	{
    		$order_sort = 'desc';
    	}

		$this->data['sort_name'] = base_url().'admin/language/req/sort/name/order/'.$order_name;
		$this->data['sort_code'] = base_url().'admin/language/req/sort/code/order/'.$order_code;
		$this->data['sort_sort_order'] = base_url().'admin/language/req/sort/sort_order/order/'.$order_sort;

		$search = array();
		$language_total = $this->language_model->getTotalLanguages();
		$results = $this->language_model->getLanguagesBySearch($search, $limit, $offset, $this->data['sort'], $this->data['order']);

		$this->data['languages'] = array();
		if (count($results) > 0)
		{
			foreach ($results as $result)
			{
				$action = array();
				$action[] = array(
					'text' => lang('text_edit'),
					'href' => base_url().'admin/language/update/pid/'.$result['language_id']
				);
	
				$this->data['languages'][] = array(
					'language_id' => $result['language_id'],
					'name'        => $result['name'],
					'code'        => $result['code'],
					'sort_order'  => $result['sort_order'],
					'selected'    => $this->input->post('selected') && in_array($result['language_id'], $this->input->post('selected')),
					'action'      => $action
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
	
		$config['base_url'] 		= base_url().'admin/language/req/page/';
		$config['total_rows'] 		= $language_total;
		$config['cur_page']			= $offset;
		$this->data['pagination'] = create_pagination($config);

		$this->load->view('template/common/header', $this->data);
        $this->load->view('template/localisation/language_list');
        $this->load->view('template/common/footer');
	}

	private function getForm()
	{
    	$this->data['text_enabled'] 	= lang('text_enabled');
    	$this->data['text_disabled'] 	= lang('text_disabled');
		
		$this->data['entry_name'] 		= lang('entry_name');
		$this->data['entry_code'] 		= lang('entry_code');
		$this->data['entry_locale'] 	= lang('entry_locale');
		$this->data['entry_image'] 		= lang('entry_image');
		$this->data['entry_directory'] 	= lang('entry_directory');
		$this->data['entry_filename'] 	= lang('entry_filename');
		$this->data['entry_sort_order'] = lang('entry_sort_order');
		$this->data['entry_status'] 	= lang('entry_status');

		$this->data['button_save'] 		= lang('button_save');
		$this->data['button_cancel'] 	= lang('button_cancel');
		$this->data['tab_general'] 		= lang('tab_general');

 		if (isset($this->error['warning']))
 		{
			$this->data['error_warning']= $this->error['warning'];
		}
		else
		{
			$this->data['error_warning']= '';
		}

 		if (isset($this->error['name']))
 		{
			$this->data['error_name'] 	= $this->error['name'];
		}
		else
		{
			$this->data['error_name'] 	= '';
		}

 		if (isset($this->error['code']))
 		{
			$this->data['error_code'] 	= $this->error['code'];
		}
		else
		{
			$this->data['error_code'] 	= '';
		}
		
 		if (isset($this->error['locale']))
 		{
			$this->data['error_locale'] = $this->error['locale'];
		}
		else
		{
			$this->data['error_locale'] = '';
		}

 		if (isset($this->error['image']))
 		{
			$this->data['error_image'] 	= $this->error['image'];
		}
		else
		{
			$this->data['error_image'] 	= '';
		}	
		
 		if (isset($this->error['directory']))
 		{
			$this->data['error_directory'] 	= $this->error['directory'];
		}
		else
		{
			$this->data['error_directory'] 	= '';
		}	
		
 		if (isset($this->error['filename']))
 		{
			$this->data['error_filename'] 	= $this->error['filename'];
		}
		else
		{
			$this->data['error_filename'] 	= '';
		}
		
  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('text_home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('heading_title'),
			'href'      => base_url().'admin/language',
      		'separator' => ' :: '
   		);
		
		$parameters = $this->uri->uri_to_assoc(4);
		if (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
			$this->data['action'] = base_url().'admin/language/update/pid/'.$parameters['pid'];
		}
		else
		{
			$this->data['action'] = base_url().'admin/language/insert';
		}

		$this->data['cancel'] = base_url().'admin/language';

		if (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
      		$language_info = $this->language_model->getLanguage($parameters['pid']);
    	}

		if ($this->input->post('name'))
		{
			$this->data['name'] = $this->input->post('name');
		}
		else if (!empty($language_info))
		{
			$this->data['name'] = $language_info['name'];
		}
		else
		{
			$this->data['name'] = '';
		}

		if ($this->input->post('code'))
		{
			$this->data['code'] = $this->input->post('code');
		}
		else if (!empty($language_info))
		{
			$this->data['code'] = $language_info['code'];
		}
		else 
		{
			$this->data['code'] = '';
		}

		if ($this->input->post('locale'))
		{
			$this->data['locale'] = $this->input->post('locale');
		}
		else if (!empty($language_info))
		{
			$this->data['locale'] = $language_info['locale'];
		}
		else
		{
			$this->data['locale'] = '';
		}
		
		if ($this->input->post('image'))
		{
			$this->data['image']  = $this->input->post('image');
		}
		else if (!empty($language_info))
		{
			$this->data['image']  = $language_info['image'];
		}
		else 
		{
			$this->data['image']  = '';
		}

		if ($this->input->post('directory'))
		{
			$this->data['directory'] = $this->input->post('directory');
		}
		else if (!empty($language_info))
		{
			$this->data['directory'] = $language_info['directory'];
		}
		else
		{
			$this->data['directory'] = '';
		}

		if ($this->input->post('filename'))
		{
			$this->data['filename'] = $this->input->post('filename');
		}
		else if (!empty($language_info))
		{
			$this->data['filename'] = $language_info['filename'];
		}
		else
		{
			$this->data['filename'] = '';
		}

		if ($this->input->post('sort_order'))
		{
			$this->data['sort_order'] = $this->input->post('sort_order');
		}
		else if (!empty($language_info))
		{
			$this->data['sort_order'] = $language_info['sort_order'];
		}
		else
		{
			$this->data['sort_order'] = '';
		}

    	if ($this->input->post('status'))
    	{
      		$this->data['status'] = $this->input->post('status');
    	}
    	else if (!empty($language_info))
    	{
			$this->data['status'] = $language_info['status'];
		}
		else
		{
      		$this->data['status'] = 1;
    	}

    	$this->load->view('template/common/header', $this->data);
        $this->load->view('template/localisation/language_form');
        $this->load->view('template/common/footer');
	}
	
	private function validateForm()
	{
		if ((utf8_strlen($this->input->post('name')) < 3) 
		 || (utf8_strlen($this->input->post('name')) > 32))
		 {
			$this->error['name'] = lang('error_name');
		}

		if (utf8_strlen($this->input->post('code')) < 2)
		{
			$this->error['code'] = lang('error_code');
		}

		if (!$this->input->post('locale'))
		{
			$this->error['locale'] = lang('error_locale');
		}
		
		if (!$this->input->post('directory'))
		{ 
			$this->error['directory'] = lang('error_directory'); 
		}

		if (!$this->input->post('filename'))
		{
			$this->error['filename'] = lang('error_filename');
		}
		
		if ((utf8_strlen($this->input->post('image')) < 3) 
		 || (utf8_strlen($this->input->post('image')) > 32))
		{
			$this->error['image'] = lang('error_image');
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
		
		foreach ($this->input->post['selected'] as $language_id)
		{
			$language_info = $this->language_model->getLanguage($language_id);
			if ($language_info)
			{
				if ($this->config->get('config_language') == $language_info['code'])
				{
					$this->error['warning'] = lang('error_default');
				}
				
				if ($this->config->get('config_admin_language') == $language_info['code'])
				{
					$this->error['warning'] = lang('error_admin');
				}	
			
				//$store_total = $this->model_setting_store->getTotalStoresByLanguage($language_info['code']);
				//if ($store_total)
				//{
				//	$this->error['warning'] = sprintf(lang('error_store'), $store_total);
				//}
			}
			
			//$order_total = $this->model_sale_order->getTotalOrdersByLanguageId($language_id);
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