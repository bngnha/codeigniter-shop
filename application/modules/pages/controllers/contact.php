<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contact extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template->append_css('module::contact.css');
        
        $this->lang->load('pages/contact/contact');
        $this->load->helper('jcaptcha');
    }

    public function index()
    {
    	$this->data['title'] = lang('heading_title');

    	$this->load->model('tool/image_model');
    	$this->data['banners'][] = $this->image_model->resize('data/banner/banner3.jpg', 940, 333);
    	$this->data['banners'][] = $this->image_model->resize('data/banner/banner2.jpg', 940, 333);
    	$this->data['banners'][] = $this->image_model->resize('data/banner/banner1.jpg', 940, 333);
    	
    	// Owner information
    	$this->data['config_name'] = $this->session->userdata('config_name');
    	$this->data['config_address'] = $this->session->userdata('config_address');
    	$this->data['config_email'] = $this->session->userdata('config_email');
    	$this->data['config_telephone'] = $this->session->userdata('config_telephone');
    	
    	// Generator captcha
    	if (!$this->error) {
    		$this->data['captcha'] = createCaptcha('captcha_contact');
    		$this->session->set_userdata('captcha_img', $this->data['captcha']);
    	} else {
    		$this->data['captcha'] = $this->session->userdata('captcha_img');
    	}

    	// Action
    	$this->data['action'] = base_url().'pages/contact/register';
    	
    	if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
		  $this->data['error_name'] = '';
		}
    	
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
		  $this->data['error_email'] = '';
		}
    	
		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
		  $this->data['error_title'] = '';
		}
    	
		if (isset($this->error['content'])) {
			$this->data['error_content'] = $this->error['content'];
		} else {
		  $this->data['error_content'] = '';
		}
		
    	if (isset($this->error['captcha'])) {
			$this->data['error_captcha'] = $this->error['captcha'];
		} else {
		  	$this->data['error_captcha'] = '';
		}

		$this->template->build('template/contact', $this->data);
  	}

  	public function register()
  	{
  		if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
    	{
  			$data = array();
  			$data['name']     = $this->input->post('name');
    		$data['email']     = $this->input->post('email');
    		$data['title']    = $this->input->post('title');
    		$data['enquiry']     = $this->input->post('enquiry');

    		$this->load->model('contact/contact_model');
     		
    		$this->contact_model->addContact($data);
    		$this->session->set_userdata('success',lang('infor_success'));
    		
  			redirect();
    	}
    	$this->index();
  	}
  	
	private function validateForm()
  	{
  		$this->data['field_name'] = $this->input->post('name');
    	if ((utf8_strlen($this->input->post('name')) < 3) 
    	|| (utf8_strlen($this->input->post('name')) > 20))
    	{
      		$this->error['name'] = lang('error_name');
    	}

    	$this->load->helper('email');
    	$this->data['field_email'] = $this->input->post('email');
    	if (!valid_email($this->input->post('email')))
    	{
			$this->error['email'] = lang('error_email');
    	}

    	$this->data['field_title'] = $this->input->post('title');
    	if ((utf8_strlen($this->input->post('title')) <= 0))
    	{
      		$this->error['title'] = lang('error_title');
    	}

    	$this->data['field_enquiry'] = $this->input->post('enquiry');
  		if ((utf8_strlen($this->input->post('enquiry')) <= 0))
    	{
      		$this->error['content'] = lang('error_content');
    	}
    	if (!checkCaptcha($this->input->post('captcha'), 'captcha_contact'))
    	{
    		$this->error['captcha'] = lang('error_captcha');
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