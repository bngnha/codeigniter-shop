<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Faq extends MX_Controller
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

	        // Faq
	        $this->lang->load('admin/faq/faq');
	        $this->load->model('faq/faq_model');

            // Faq
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
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && ($this->validateForm()))
		{
			$this->faq_model->addFaq($this->input->post());
			$this->session->set_userdata('success', lang('text_success'));
			
			redirect('admin/faq');
		}
		$this->getForm();
	}

	public function update()
	{
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
		{
			$parameters = $this->uri->uri_to_assoc(4);
			$this->faq_model->editFaq($parameters['pid'], $this->input->post());
			$this->session->set_userdata('success', lang('text_success'));
			
			redirect('admin/faq');
		}
		$this->getForm();
	}

	public function delete()
	{
		if ($this->input->post('delete') && $this->validateDelete())
		{
			foreach ($this->input->post('delete') as $faq_id)
			{
				$this->faq_model->deleteFaq($faq_id);
			}
			$this->session->set_userdata('success', lang('text_success'));
			
			redirect('admin/faq');
		}
		$this->getList();
	}

	private function getList()
	{
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
  			if ($parameters['sort'] == 'title')
  			{
				$this->data['sort'] = 'fd.title';
  			}
  			if ($parameters['sort'] == 'sort_order')
  			{
  				$this->data['sort'] = 'f.sort_order';
  			}
		}
		else
		{
			$this->data['sort'] = 'fd.title';
		}

		if (isset($parameters['order']))
		{
			$this->data['order'] = $parameters['order'];
		}
		else
		{
			$this->data['order'] = 'asc';
		}
  		
		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
     		'href'      => base_url().'admin/dashboard',
     		'text'      => lang('text_home'),
     		'separator' => FALSE
  		);
  		$this->data['breadcrumbs'][] = array(
     		'href'      => base_url().'admin/faq',
     		'text'      => lang('heading_title'),
     		'separator' => ' :: '
  		);
  		
		$this->data['insert'] = base_url().'admin/faq/insert';
		$this->data['delete'] = base_url().'admin/faq/delete';	
		
		$this->data['faqs'] = array();
		$data = array(
			'sort'  => $this->data['sort'],
			'order' => $this->data['order'],
			'start' => $offset,
			'limit' => $limit
		);
		
		$faq_total = $this->faq_model->getTotalFaqs();
		$results = $this->faq_model->getFaqs($data);
    	foreach ($results as $result)
    	{
			$action = array();
			$action[] = array(
				'text' => lang('text_edit'),
				'href' => base_url().'admin/faq/update/pid/' . $result['faq_id']
			);
			$this->data['faqs'][] = array(
				'faq_id' => $result['faq_id'],
				'title'       => $result['title'],
				'status'	  => ($result['status'] ? lang('text_enabled') : lang('text_disabled')),
				'sort_order'  => $result['sort_order'],
				'delete'      => in_array($result['faq_id'], (array)@$this->input->post('delete')),
				'action'      => $action
			);
		}	
		$this->data['text_enabled'] 	= lang('text_enabled');
		$this->data['text_disabled'] 	= lang('text_disabled');
		$this->data['text_no_results'] 	= lang('text_no_results');
		$this->data['column_title'] 	= lang('column_title');
		$this->data['column_status'] 	= lang('column_status');
		$this->data['column_sort_order']= lang('column_sort_order');
		$this->data['column_action'] = lang('column_action');		
		$this->data['button_insert'] = lang('button_insert');
		$this->data['button_delete'] = lang('button_delete');
		$this->data['error_warning'] = @$this->error['warning'];
	
		if ($this->session->userdata('success'))
		{
			$this->data['success'] = $this->session->userdata('success');
			$this->session->unset_userdata('success');
		}
		else
		{
			$this->data['success'] = '';
		}
		
		$order_title = 'asc';
		if ($this->data['sort'] == 'fd.title' && $this->data['order'] == 'asc')
		{
			$order_title = 'desc';
		}

		$order_sort_order = 'asc';
		if ($this->data['sort'] == 'f.sort_order' && $this->data['order'] == 'asc')
		{
			$order_sort_order = 'desc';
		}

		$this->data['sort_title'] 		= base_url().'admin/faq/req/sort/title/order/' . $order_title;
		$this->data['sort_sort_order'] 	= base_url().'admin/faq/req/sort/sort_order/order/' . $order_sort_order;

		$config['base_url'] 	  = base_url().'admin/faq/req/page/';
		$config['total_rows'] 	  = $faq_total;
		$config['cur_page']		  = $offset;
		$this->data['pagination'] = create_pagination($config);

		$this->load->view('template/common/header', $this->data);
        $this->load->view('template/faq/faq_list');
        $this->load->view('template/common/footer');
	}

	private function getForm()
	{
    	$this->data['text_enabled'] = lang('text_enabled');
    	$this->data['text_disabled']= lang('text_disabled');
		$this->data['text_upload'] 	= lang('text_upload');
		$this->data['entry_title'] 	= lang('entry_title');
		$this->data['entry_keyword']= lang('entry_keyword');
		$this->data['entry_description'] = lang('entry_description');
		$this->data['entry_status'] = lang('entry_status');
		$this->data['entry_sort_order'] = lang('entry_sort_order');
		$this->data['button_save'] 	= lang('button_save');
		$this->data['button_cancel']= lang('button_cancel');
		$this->data['tab_general'] 	= lang('tab_general');
		$this->data['tab_data'] 	= lang('tab_data');
		$this->data['error_warning']= @$this->error['warning'];
		$this->data['error_title'] 	= @$this->error['title'];
		$this->data['error_description'] = @$this->error['description'];

  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
     		'href'      => base_url().'admin/dashboard',
     		'text'      => lang('text_home'),
     		'separator' => ''
  		);
  		
  		$this->data['breadcrumbs'][] = array(
     		'href'      => base_url().'admin/faq',
     		'text'      => lang('heading_title'),
     		'separator' => ' :: '
  		);
  		
  		$parameters = $this->uri->uri_to_assoc(4);
   		if (sizeof($parameters) > 0 && isset($parameters['pid']))
   		{
   			$this->data['action'] = base_url().'admin/faq/update/pid/' . $parameters['pid'];
   		}
   		else
   		{
   			$this->data['action'] = base_url().'admin/faq/insert';
   		}
  		
		$this->data['cancel'] = base_url().'admin/faq';
		
		if (isset($parameters['pid']))
		{
			$faq_info = $this->faq_model->getFaq($parameters['pid']);
		}

		$this->load->model('localisation/language_model');
		$this->data['languages'] = $this->language_model->getLanguages();
		if ($this->input->post('faq_description'))
		{
			$this->data['faq_description'] = $this->input->post('faq_description');
		}
		elseif (isset($parameters['pid']))
		{
			$this->data['faq_description'] = $this->faq_model->getFaqDescriptions($parameters['pid']);
		}
		else
		{
			$this->data['faq_description'] = array();
		}
		
		if ($this->input->post('status'))
		{
			$this->data['status'] = $this->input->post('status');
		}
		else
		{
			$this->data['status'] = @$faq_info['status'];
		}
		
		if ($this->input->post('sort_order'))
		{
			$this->data['sort_order'] = $this->input->post('sort_order');
		}
		else
		{
			$this->data['sort_order'] = @$faq_info['sort_order'];
		}
		
		$this->id       = 'content';
 		$this->load->view('template/common/header', $this->data);
        $this->load->view('template/faq/faq_form');
        $this->load->view('template/common/footer');
	}

	private function validateForm()
	{
		//if (!$this->user->hasPermission('modify', 'catalog/faq')) {
		//	$this->error['warning'] = lang('error_permission');
		//}
		foreach ($this->input->post('faq_description') as $language_id => $value)
		{
			if ((strlen($value['title']) < 3) || (strlen($value['title']) > 32))
			{
				$this->error['title'][$language_id] = lang('error_title');
			}
			if (strlen($value['description']) < 3)
			{
				$this->error['description'][$language_id] = lang('error_description');
			}
		}
		
		if (!$this->error)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	private function validateDelete()
	{
		//if (!$this->user->hasPermission('modify', 'catalog/faq')) {
		//	$this->error['warning'] = lang('error_permission');
		//}
		if (!$this->error)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}
?>
