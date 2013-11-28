<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends MX_Controller
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

	        // User
	        $this->lang->load('admin/user/user');
	        $this->load->model('user/user_model');

            // User
            $this->data['title']        	= lang('admin.users.title');
            $this->data['heading_title']   	= lang('admin.users.header');
            
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
    		$data['username']     = $this->input->post('username');
            $data['password']     = $this->input->post('password');
            $data['firstname']    = $this->input->post('firstname');
            $data['lastname']     = $this->input->post('lastname');
            $data['email']        = $this->input->post('email');
            $data['user_group_id']= $this->input->post('user_group_id');
            $data['status']       = $this->input->post('status');
            $data['ip']           = $this->input->ip_address();

			$this->user_model->addUser($data);
			$this->session->set_userdata('success',lang('admin.user.text_success'));

			redirect('admin/user');
    	}
    	$this->getForm();
  	}

  	public function update()
  	{
    	if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
    	{
    		$data['username']     = $this->input->post('username');
    		$data['password']     = $this->input->post('password');
    		$data['firstname']    = $this->input->post('firstname');
    		$data['lastname']     = $this->input->post('lastname');
    		$data['email']        = $this->input->post('email');
    		$data['user_group_id']= $this->input->post('user_group_id');
    		$data['status']       = $this->input->post('status');
    		$data['ip']           = $this->input->ip_address();

    		$parameters = $this->uri->uri_to_assoc(4);
			$this->user_model->editUser($parameters['pid'], $data);
			$this->session->set_userdata('success',lang('admin.user.text_success'));

			redirect('admin/user');
    	}

    	$this->getForm();
  	}
 
  	public function delete()
  	{
    	if ($this->input->post('selected') && $this->validateDelete())
    	{
      		foreach ($this->input->post('selected') as $user_id) {
				$this->user_model->deleteUser($user_id);
			}
			$this->session->set_userdata('success',lang('admin.user.text_success'));

			redirect('admin/user');
    	}
    	$this->getList();
  	}

  	private function getList()
  	{
  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('admin.users.home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('admin.users.header'),
			'href'      => base_url().'admin/user',
      		'separator' => ' :: '
   		);

		$this->data['insert'] 				= base_url().'admin/user/insert';
		$this->data['delete'] 				= base_url().'admin/user/delete';
		$this->data['column_username'] 		= lang('admin.users.column_username');
		$this->data['column_status'] 		= lang('admin.users.column_status');
		$this->data['column_date_added'] 	= lang('admin.users.column_date_added');
		$this->data['column_action'] 		= lang('admin.users.column_action');
		$this->data['button_insert'] 		= lang('admin.users.button_insert');
		$this->data['button_delete'] 		= lang('admin.users.button_delete');

		// limit and offset
        $limit = $this->session->userdata('config_admin_limit');
        $offset = 0;
  		$parameters = $this->uri->uri_to_assoc(4);
  		if (isset($parameters['page'])) {
  			$offset = $parameters['page'];
  		} else {
  			$offset = '0';	
  		}

  		if (isset($parameters['sort'])) {
			$this->data['sort'] = $parameters['sort'];
		} else {
			$this->data['sort'] = 'username';
		}
		if (isset($parameters['order'])) {
			$this->data['order'] = $parameters['order'];
		} else {
			$this->data['order'] = 'asc';
		}		
		
		// link sort
    	$order_name = 'asc';
    	if ($this->data['sort'] == 'username'
    	  	&& $this->data['order'] == 'asc') {
    		$order_name = 'desc';
    	}
    	$order_status = 'asc';
    	if ($this->data['sort'] == 'status'
    		&& $this->data['order'] == 'asc') {
    		$order_status = 'desc';		
    	}
    	$order_date = 'asc';
    	if ($this->data['sort'] == 'date_added'
    		&& $this->data['order'] == 'asc') {
    		$order_date = 'desc';		
    	}
    	$this->data['sort_username'] 	= base_url().'admin/user/req/sort/username/order/'.$order_name;
		$this->data['sort_status'] 		= base_url().'admin/user/req/sort/status/order/'.$order_status;
		$this->data['sort_date_added'] 	= base_url().'admin/user/req/sort/date_added/order/'.$order_date;

    	$search = array();
		$user_total = $this->user_model->getTotalUsers();
		$query 	= $this->user_model->getUsers($search, $limit, $offset, $this->data['sort'], $this->data['order']);

		$this->data['users'] = array();
    	if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $result) {
				$action = array();

				$action[] = array(
					'text' => lang('admin.users.edit'),
					'href' => base_url().'admin/user/update/pid/'.$result['user_id']
				);
						
	      		$this->data['users'][] = array(
					'user_id'    => $result['user_id'],
					'username'   => $result['username'],
					'status'     => ($result['status'] ? lang('admin.users.enable') : lang('admin.users.disable')),
					'date_added' => date(lang('admin.users.date_format'), strtotime($result['date_added'])),
					'selected'   => ($this->input->get('selected')) && in_array($result['user_id'], $this->input->get('selected')),
					'action'     => $action
				);
			}
    	} else {
    		$this->data['text_no_results']  = lang('admin.users.text_no_results');
    	}

		$config['base_url'] 		= base_url().'admin/user/req/page/';
		$config['total_rows'] 		= $user_total;
		$config['cur_page']			= $offset;

		$this->data['pagination'] = create_pagination($config);

  		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->userdata['success'])) {
			$this->data['success'] = $this->session->userdata['success'];
			$this->session->unset_userdata('success');
		} else {
			$this->data['success'] = '';
		}

        $this->load->view('template/common/header', $this->data);
        $this->load->view('template/user/user_list');
        $this->load->view('template/common/footer');
  	}

	private function getForm()
	{
    	$this->data['entry_username'] 	= lang('admin.user.entry_username');
    	$this->data['entry_password'] 	= lang('admin.user.entry_password');
    	$this->data['entry_confirm'] 	= lang('admin.user.entry_confirm');
    	$this->data['entry_firstname'] 	= lang('admin.user.entry_firstname');
    	$this->data['entry_lastname'] 	= lang('admin.user.entry_lastname');
    	$this->data['entry_email'] 		= lang('admin.user.entry_email');
    	$this->data['entry_user_group'] = lang('admin.user.entry_user_group');
		$this->data['entry_status'] 	= lang('admin.user.entry_status');
		$this->data['entry_captcha'] 	= lang('admin.user.entry_captcha');
        $this->data['text_enabled']     = lang('admin.user.text_enabled');
        $this->data['text_disabled']    = lang('admin.user.text_disabled');
        
    	$this->data['button_save'] 		= lang('admin.user.button_save');
    	$this->data['button_cancel'] 	= lang('admin.user.button_cancel');
    	$this->data['tab_general'] 		= lang('admin.user.tab_general');
    
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
		  $this->data['error_warning'] = '';
		}
 		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
		  $this->data['error_username'] = '';
		}

 		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
 		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		
	 	if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}
		
	 	if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
		  $this->data['error_lastname'] = '';
		}

		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('admin.users.home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('admin.users.header'),
			'href'      => base_url().'admin/user',
      		'separator' => ' :: '
   		);

   		$parameters = $this->uri->uri_to_assoc(4);
		if (sizeof($parameters) > 0 && isset($parameters['pid'])) {
			$this->data['action'] = base_url().'admin/user/update/pid/'.$parameters['pid'];
		} else {
			$this->data['action'] = base_url().'admin/user/insert';
		}

    	$this->data['cancel'] = base_url().'admin/user';

    	if (sizeof($parameters) > 0 && isset($parameters['pid'])) {
      		$user_info = $this->user_model->getUser($parameters['pid']);
    	}

    	if ($this->input->post('username')) {
      		$this->data['username'] = $this->input->post('username');
    	} elseif (!empty($user_info)) {
			$this->data['username'] = $user_info['username'];
		} else {
      		$this->data['username'] = '';
    	}

  		if ($this->input->post('password')) {
    		$this->data['password'] = $this->input->post('password');
		} else {
			$this->data['password'] = '';
		}

  		if ($this->input->post('confirm')) {
    		$this->data['confirm'] = $this->input->post('confirm');
		} else {
			$this->data['confirm'] = '';
		}
  
    	if ($this->input->post('firstname')) {
      		$this->data['firstname'] = $this->input->post('firstname');
    	} elseif (!empty($user_info)) {
			$this->data['firstname'] = $user_info['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if ($this->input->post('lastname')) {
      		$this->data['lastname'] = $this->input->post('lastname');
    	} elseif (!empty($user_info)) {
			$this->data['lastname'] = $user_info['lastname'];
		} else {
      		$this->data['lastname'] = '';
   		}

    	if ($this->input->post('email')) {
      		$this->data['email'] = $this->input->post('email');
    	} elseif (!empty($user_info)) {
			$this->data['email'] = $user_info['email'];
		} else {
      		$this->data['email'] = '';
    	}

    	if ($this->input->post('user_group_id')) {
      		$this->data['user_group_id'] = $this->input->post('user_group_id');
    	} elseif (!empty($user_info)) {
			$this->data['user_group_id'] = $user_info['user_group_id'];
		} else {
      		$this->data['user_group_id'] = '';
    	}

		$this->load->model('user/usergrp_model');
    	$this->data['user_groups'] = $this->usergrp_model->getUserGroups();
 
     	if ($this->input->post('status')) {
      		$this->data['status'] = $this->input->post('status');
    	} elseif (!empty($user_info)) {
			$this->data['status'] = $user_info['status'];
		} else {
      		$this->data['status'] = 0;
    	}

    	$this->load->view('template/common/header', $this->data);
        $this->load->view('template/user/user_form');
        $this->load->view('template/common/footer');
  	}
  	
  	private function validateForm()
  	{
    	if ((utf8_strlen($this->input->post('username')) < 3) 
    	|| (utf8_strlen($this->input->post('username')) > 20))
    	{
      		$this->error['username'] = lang('admin.user.error_username');
    	}
		
		$user_info = $this->user_model->getUserByUsername($this->input->post('username'));
		$parameters = $this->uri->uri_to_assoc(4);
  	    if (sizeof($parameters) > 0 && isset($parameters['pid']))
  	    {
            // update a user, check that user is exist
            if ($parameters['pid'] != $user_info['user_id']) {
            	if (sizeof($user_info) > 0 && $user_info['username'] == $this->input->post('username'))
	            {
	                $this->error['warning'] = lang('admin.user.error_exists');
	            }
            }
        }
        else
        {
            // insert a new user
            if (sizeof($user_info) > 0)
            {
                $this->error['warning'] = lang('admin.user.error_exists');
            }
        }

    	if ((utf8_strlen($this->input->post('firstname')) < 1) 
    	|| (utf8_strlen($this->input->post('firstname')) > 32))
    	{
			$this->error['firstname'] = lang('admin.user.error_firstname');
    	}

    	if ((utf8_strlen($this->input->post('lastname')) < 1) 
    	|| (utf8_strlen($this->input->post('lastname')) > 32))
    	{
      		$this->error['lastname'] = lang('admin.user.error_lastname');
    	}

    	if ($this->input->post('password') || ($this->input->post('user_id')))
    	{
      		if ((utf8_strlen($this->input->post('password')) < 4) 
      		|| (utf8_strlen($this->input->post('password')) > 20))
      		{
        		$this->error['password'] = lang('admin.user.error_password');
      		}
	
	  		if ($this->input->post('password') != $this->input->post('confirm'))
	  		{
	    		$this->error['confirm'] = lang('admin.user.error_confirm');
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
  		$validate_flg = true;
		foreach ($this->input->post('selected') as $user_id){
			if ($this->session->userdata['user_id'] == $user_id){
				$this->error['warning'] = lang('admin.user.error_account');
				$validate_flg = false;
				break;
			}
		}
		return $validate_flg;
  	}
}
?>