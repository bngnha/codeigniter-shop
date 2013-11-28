<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contact extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->error= array();
		$this->data['menu_id']='system';
		if ($this->session->userdata('logged_in'))
		{
			// Switch language
			$this->lang->switch_to($this->session->userdata('config_admin_language'));

			// Faq
			$this->lang->load('admin/contact/contact.php');
			$this->load->model('contact/contact_model');

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

	public function delete()
	{
		if ($this->input->post('delete'))
		{
			foreach ($this->input->post('delete') as $faq_id)
			{
				$this->contact_model->deleteContact($faq_id);
			}
			$this->session->set_userdata('success', lang('text_success'));

			redirect('admin/contact');
		}
		$this->getList();
	}
	public function detail()
	{
		$this->getForm();
	}
	public function update()
	{
		if (($_SERVER['REQUEST_METHOD'] === 'POST') )
		{
			$this->contact_model->updateContact($this->input->get('id'),$this->input->post());
			$this->session->set_userdata('success', lang('text_success'));
			
			redirect('admin/contact');
		}
		$this->getForm();
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


		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
     		'href'      => base_url().'admin/dashboard',
     		'text'      => lang('text_home'),
     		'separator' => FALSE
		);
		$this->data['breadcrumbs'][] = array(
     		'href'      => base_url().'admin/contact',
     		'text'      => lang('heading_title'),
     		'separator' => ' :: '
     		);

     		$this->data['detail'] = base_url().'admin/contact/detail';
     		$this->data['delete'] = base_url().'admin/contact/delete';
     		$data = array(
			'start' => $offset,
			'limit' => $limit
     		);

     		$faq_total = $this->contact_model->getTotalContacts();
     		$results = $this->contact_model->getContacts($data);
     		$this->data['contacts'] = array();
     		foreach ($results as $result)
     		{
     			$action = array();
     			$action[] = array(
				'text' => lang('text_detail'),
				'href' => base_url().'admin/contact/detail?id=' . $result['contact_id']
     			);
     			$this->data['contacts'][] = array(
				'contact_id' => $result['contact_id'],
				'title'       => $result['title'],
				'content'       => $result['content'],
				'status'	  => ($result['status'] ? lang('text_read') : lang('text_unread')),
				'full_name'  => $result['full_name'],
				'phone'  => $result['phone'],
				'email'  => $result['email'],
				'delete'      => in_array($result['contact_id'], (array)@$this->input->post('delete')),
				'action'      => $action
     			);
     		}
     		$this->data['text_no_results'] 	= lang('text_no_results');
     		$this->data['column_name'] 	= lang('column_name');
     		$this->data['column_status'] 	= lang('column_status');
     		$this->data['column_phone']= lang('column_phone');
     		$this->data['column_email']= lang('column_email');
     		$this->data['column_subject']= lang('column_subject');
     		$this->data['column_content']= lang('column_content');
     		$this->data['column_action'] = lang('column_action');
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


     		$config['base_url'] 	  = base_url().'admin/contact/contact/page/';
     		$config['total_rows'] 	  = $faq_total;
     		$config['cur_page']		  = $offset;
     		$this->data['pagination'] = create_pagination($config);

     		$this->load->view('template/common/header', $this->data);
     		$this->load->view('template/contact/contact_list');
     		$this->load->view('template/common/footer');
	}

	private function getForm()
	{
		$this->data['text_unread'] = lang('text_unread');
		$this->data['text_read']= lang('text_read');
		$this->data['entry_name'] 	= lang('entry_name');
		$this->data['entry_action'] 	= lang('entry_action');
		$this->data['entry_phone']= lang('entry_phone');
		$this->data['entry_email'] = lang('entry_email');
		$this->data['entry_subject'] = lang('entry_subject');
		$this->data['entry_content'] = lang('entry_content');
		$this->data['entry_status'] = lang('entry_status');
		$this->data['button_save'] 	= lang('button_save');
		$this->data['button_cancel']= lang('button_cancel');
		$this->data['button_delete'] = lang('button_delete');

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
     		'href'      => base_url().'admin/dashboard',
     		'text'      => lang('text_home'),
     		'separator' => ''
     		);

     		$this->data['breadcrumbs'][] = array(
     		'href'      => base_url().'admin/contact',
     		'text'      => lang('heading_title'),
     		'separator' => ' :: '
     		);

     		if ($this->input->get('id'))
     		{
     			$this->data['action'] = base_url().'admin/contact/update?id=' . $this->input->get('id');
     		}
     		else
     		{
     			$this->data['action'] = base_url().'admin/contact/update';
     		}

     		$this->data['cancel'] = base_url().'admin/contact';

     		if ($this->input->get('id'))
     		{
     			$this->data['faq_info'] = $this->contact_model->getContact($this->input->get('id'));
     		}

     		$this->load->view('template/common/header', $this->data);
     		$this->load->view('template/contact/contact_form');
     		$this->load->view('template/common/footer');
	}

}
?>
