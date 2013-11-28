<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reset extends MX_Controller 
{
	private $error = array();
	public function index()
	{
		if ($this->session->userdata('logged_in'))
        {
			redirect('admin/dashboard');
		}
				
		if (isset($this->input->get('code')))
		{
			$code = $this->input->get('code');
		} else {
			$code = '';
		}
		
		$this->load->model('user/user_model');
		$user_info = $this->user_model->getUserByCode($code);
		
		if ($user_info)
		{
			$this->lang->load('admin/common/reset');
			if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validate())
			{
				$this->user_model->editPassword($user_info['user_id'], $this->input->post('password'));
	 
				$this->session->set_userdata('success', lang('text_success'));
				redirect('admin/login');
			}
			
			$this->data['breadcrumbs'] = array();
			$this->data['breadcrumbs'][] = array(
				'text'      => lang('text_home'),
				'href'      => base_url().'admin/dashboard',        	
				'separator' => false
			); 
			
			$this->data['breadcrumbs'][] = array(
				'text'      => lang('text_reset'),
				'href'      => base_url().'admin/reset',       	
				'separator' => lang('text_separator')
			);
			
			$this->data['heading_title']     = lang('heading_title');
			$this->data['text_password']     = lang('text_password');
			$this->data['entry_password']    = lang('entry_password');
			$this->data['entry_confirm']     = lang('entry_confirm');
	
			$this->data['button_save']   = lang('button_save');
			$this->data['button_cancel'] = lang('button_cancel');
	
			if (isset($this->error['password']))
			{ 
				$this->data['error_password'] = $this->error['password'];
			}
			else
			{
				$this->data['error_password'] = '';
			}
	
			if (isset($this->error['confirm']))
			{ 
				$this->data['error_confirm'] = $this->error['confirm'];
			}
			else
			{
				$this->data['error_confirm'] = '';
			}
			
			$this->data['action'] = base_url().'admin/reset';
			$this->data['cancel'] = base_url().'admin/login';
			
			if ($this->input->post('password'))
			{
				$this->data['password'] = $this->input->post('password');
			}
			else
			{
				$this->data['password'] = '';
			}
	
			if ($this->input->post('confirm'))
			{
				$this->data['confirm'] = $this->input->post('confirm');
			}
			else
			{
				$this->data['confirm'] = '';
			}
			
			$this->load->view('template/common/header', $this->data);
            $this->load->view('template/catalog/reset');
            $this->load->view('template/common/footer');

		}
		else
		{
			return redirect('admin/login');
		}
	}

	private function validate()
	{
    	if ((utf8_strlen($this->input->post('password')) < 4) 
    	|| (utf8_strlen($this->input->post('password')) > 20))
    	{
      		$this->error['password'] = lang('error_password');
    	}

    	if ($this->input->post('confirm') != $this->input->post('password'))
    	{
      		$this->error['confirm'] = lang('error_confirm');
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