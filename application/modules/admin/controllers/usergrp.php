<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Usergrp extends MX_Controller
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

	        // Usergrp
	        $this->lang->load('admin/user/usergrp');
	        $this->load->model('user/usergrp_model');

            // Usergrp
            $this->data['title']        = lang('heading_title');
            $this->data['heading_title']= lang('heading_title');
            
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
			$this->usergrp_model->addUserGroup($this->input->post());	

			$this->session->set_userdata('success',lang('text_success'));
			redirect('admin/usergrp');
		}

		$this->getForm();
	}

	public function update() 
	{
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
		{
			$parameters = $this->uri->uri_to_assoc(4);
			$this->usergrp_model->editUserGroup($parameters['pid'], $this->input->post());
			
			$this->session->set_userdata('success',lang('text_success'));
			
			redirect('admin/usergrp');
		}

		$this->getForm();
	}

	public function delete()
	{ 
		if ($this->input->post('selected') && $this->validateDelete())
		{
      		foreach ($this->input->post('selected') as $user_group_id)
      		{
				$this->usergrp_model->deleteUserGroup($user_group_id);	
			}

			$this->session->set_userdata('success',lang('text_success'));
			redirect('admin/usergrp');
		}

		$this->getList();
	}

	private function getList()
	{			
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
        $this->data['sort_name'] = base_url().'admin/usergrp/req/sort/name/order/'.$order_name;
		
  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('text_home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('heading_title'),
			'href'      => base_url().'admin/usergrp',
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = base_url().'admin/usergrp/insert';
		$this->data['delete'] = base_url().'admin/usergrp/delete';	
	
		$this->data['user_groups'] = array();
		
		$data = array(
			'sort'  => $this->data['sort'],
			'order' => $this->data['order'],
            'start'           => $offset,
            'limit'           => $limit
		);
		
		$user_group_total = $this->usergrp_model->getTotalUserGroups();
		$results = $this->usergrp_model->getUserGroups($data);

		if (count($results) > 0)
        {
			foreach ($results as $result)
			{
				$action = array();
				$action[] = array(
					'text' => lang('text_edit'),
					'href' => base_url().'admin/usergrp/update/pid/' . $result['user_group_id'],
				);		
			
				$this->data['user_groups'][] = array(
					'user_group_id' => $result['user_group_id'],
					'name'          => $result['name'],
					'selected'      => $this->input->post('selected') && in_array($result['user_group_id'], $this->input->post('selected')),
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
		} else {
			$this->data['success'] = '';
		}

		$this->data['column_name'] 	 = lang('column_name');
		$this->data['column_action'] = lang('column_action');

		$this->data['button_insert'] = lang('button_insert');
		$this->data['button_delete'] = lang('button_delete');
			
		$config['base_url']         = base_url().'admin/usergrp/req/page/';
        $config['total_rows']       = $user_group_total;
        $config['cur_page']         = $offset;
        $this->data['pagination'] 	= create_pagination($config);

		$this->load->view('template/common/header', $this->data);
        $this->load->view('template/user/user_group_list');
        $this->load->view('template/common/footer');
 	}

	private function getForm()
	{
		$this->data['text_select_all'] 	= lang('text_select_all');
		$this->data['text_unselect_all']= lang('text_unselect_all');
				
		$this->data['entry_name'] 	= lang('entry_name');
		$this->data['entry_access'] = lang('entry_access');
		$this->data['entry_modify'] = lang('entry_modify');
		
		$this->data['button_save'] 	= lang('button_save');
		$this->data['button_cancel']= lang('button_cancel');

		$this->data['tab_general'] 	= lang('tab_general');

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

		$url = '';

		if (isset($this->input->get['sort']))
		{
			$url .= '&sort=' . $this->input->get['sort'];
		}

		if (isset($this->input->get['order'])) {
			$url .= '&order=' . $this->input->get['order'];
		}
			
		if (isset($this->input->get['page'])) {
			$url .= '&page=' . $this->input->get['page'];
		}

  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('text_home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('heading_title'),
			'href'      => base_url().'admin/usergrp',
      		'separator' => ' :: '
   		);
		
		$parameters = $this->uri->uri_to_assoc(4);
        if (sizeof($parameters) > 0 && isset($parameters['pid']))
        {
			$this->data['action'] = base_url().'admin/usergrp/update/pid/'.$parameters['pid'];
		}
		else
		{
			$this->data['action'] = base_url().'admin/usergrp/insert';
		}
    	$this->data['cancel'] = base_url().'admin/usergrp';

    	$user_group_info = array();
		if (sizeof($parameters) > 0 && isset($parameters['pid']))
        {
			$user_group_info = $this->usergrp_model->getUserGroup($parameters['pid']);
		}

		if ($this->input->post('name'))
		{
			$this->data['name'] = $this->input->post('name');
		}
		else if (!empty($user_group_info))
		{
			$this->data['name'] = $user_group_info['name'];
		}
		else
		{
			$this->data['name'] = '';
		}

		$ignore = array(
			'dashboard',
			'login',
			'logout',
			'forgotten',
			'reset',			
			'not_found',
			'permission',
			'footer',
			'header'
		);
				
		$this->data['permissions'] = array();
		$files = glob(APPPATH . 'module/admin/controllers/*.php');

		foreach ($files as $file)
		{
			$data = explode('/', dirname($file));
			$permission = end($data) . '/' . basename($file, '.php');
			
			if (!in_array($permission, $ignore))
			{
				$this->data['permissions'][] = $permission;
			}
		}

		if ($this->input->post('permission'))
		{
			$this->data['access'] = $this->input->post('permission');
		}
		else if ( !empty($user_group_info) && $user_group_info['permission']['access'])
		{
			$this->data['access'] = $user_group_info['permission']['access'];
		}
		else
		{ 
			$this->data['access'] = array();
		}

		if ($this->input->post('permission'))
		{
			//$this->data['modify'] = $this->input->post('permission')['modify'];
		}
		else if (!empty($user_group_info) && $user_group_info['permission']['modify'])
		{
			$this->data['modify'] = $user_group_info['permission']['modify'];
		}
		else
		{ 
			$this->data['modify'] = array();
		}
	
		$this->load->view('template/common/header', $this->data);
        $this->load->view('template/user/user_group_form');
        $this->load->view('template/common/footer');
        
	}

	private function validateForm()
	{
		//if (!$this->user->hasPermission('modify', 'user/user_permission')) {
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
		//if (!$this->user->hasPermission('modify', 'user/user_permission'))
		//{
		//	$this->error['warning'] = lang('error_permission');
		//}
		$this->load->model('user/user_model');
		foreach ($this->input->post('selected') as $user_group_id)
		{
			$user_total = $this->user_model->getTotalUsersByGroupId($user_group_id);

			if ($user_total)
			{
				$this->error['warning'] = sprintf(lang('error_user'), $user_total);
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