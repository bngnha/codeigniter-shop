<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Forgotten extends MX_Controller
{
	public function __construct()
    {
        parent::__construct();
        $this->data = array();
        $this->error= array();
 
	    // Common
	    $this->lang->switch_to($this->session->userdata('config_admin_language'));
	    $this->lang->load('admin/common/banner');
	    $this->lang->load('admin/common/menu');

	    // User model
	    $this->load->model('user/user_model');
	    
	    // Forgotten
	    $this->lang->load('admin/common/forgotten');

        // Forgotten
        $this->data['title']        	= lang('heading_title');
        $this->data['heading_title']   	= lang('heading_title');
    }

	public function index()
	{
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validate())
		{
			$this->lang->load('admin/mail/forgotten');
			$code = md5(rand());

			$this->user_model->editCode($this->input->post('email'), $code);
			$this->config->load('mail');
			
			$subject = sprintf(lang('text_subject'), $this->config->item('config_name'));
			$message  = sprintf(lang('text_greeting'), $this->config->item('config_name')) . "\n\n";
			$message .= sprintf(lang('text_change'), $this->config->item('config_name')) . "\n\n";
			$message .= base_url().'admin/reset';
			$message .= sprintf(lang('text_ip'), $this->input->ip_address()) . "\n\n";
			
			$config = Array(
			  'protocol' 		=> $this->config->item('config_mail_protocol'),
			  'smtp_host' 		=> $this->config->item('config_smtp_host'),
			  'mail_path'		=> $this->config->item('config_smtp_host'),
			  'smtp_port' 		=> 465,
			  'smtp_user' 		=> $this->config->item('config_smtp_username'),
			  'smtp_pass' 		=> $this->config->item('config_smtp_password'),
			  'mailtype' 		=> 'html',
			  'charset' 		=> 'utf-8',
			  'validate'		=> TRUE,
			  'wordwrap' 		=> TRUE,
			  'crlf'			=> '\r\n',
			  'newline'			=> '\n\n',
			  'smtp_timeout'	=>$this->config->item('config_smtp_timeout')
			);
			$this->load->library('email', $config);

			$this->email->from($this->config->item('config_email'), 'Admin');
			$this->email->to($this->input->post('email'));
			//$this->email->cc('another@another-example.com');
			//$this->email->bcc('them@their-example.com');

			$this->email->subject('Email Test');
			$this->email->message(html_entity_decode('Testing the email class.', ENT_QUOTES, 'UTF-8'));
			$this->email->send();
			
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
        	'text'      => lang('text_forgotten'),
			'href'      => base_url().'admin/forgotten',
        	'separator' => lang('text_separator')
      	);
		
		$this->data['heading_title'] 	= lang('heading_title');
		$this->data['text_your_email'] 	= lang('text_your_email');
		$this->data['text_email'] 		= lang('text_email');
		$this->data['entry_email'] 		= lang('entry_email');
		$this->data['button_reset'] 	= lang('button_reset');
		$this->data['button_cancel'] 	= lang('button_cancel');

		if (isset($this->error['warning']))
		{
			$this->data['error_warning'] = $this->error['warning'];
		}
		else
		{
			$this->data['error_warning'] = '';
		}
		
		$this->data['action'] = base_url().'admin/forgotten';
		$this->data['cancel'] = base_url().'admin/login';
    	
		if ($this->input->post('email'))
		{
      		$this->data['email'] = $this->input->post('email');
		}
		else
		{
      		$this->data['email'] = '';
    	}
		
    	$this->load->view('template/common/header', $this->data);
        $this->load->view('template/common/forgotten');
        $this->load->view('template/common/footer');
	}

	private function validate()
	{
		if (!$this->input->post('email'))
		{
			$this->error['warning'] = lang('error_email');
		}
		elseif (!$this->user_model->getTotalUsersByEmail($this->input->post('email')))
		{
			$this->error['warning'] = lang('error_email');
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