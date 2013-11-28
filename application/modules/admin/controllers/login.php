<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
        $this->error= array();

		// Get language that set in db
		$lang_cd = 'en';          // default language
		$lang_name = 'english'; 

		$this->load->model('setting/setting_model');
		$row = $this->setting_model->getSetting('config', 'config_admin_language');
		if (isset($row))
		{
			$lang_cd = isset($row['config_admin_language']) ? $row['config_admin_language'] : 'en';
			if ($lang_cd === 'vi')
			{
				$lang_name = 'vietnamese';
			}
		}
		$this->session->set_userdata('config_admin_language', $lang_cd);

		$this->lang->switch_to($lang_cd);
        $this->lang->load('admin/common/login');
		$this->load->model('user/user_model');
	}

	public function index()
	{
		$logged = false;
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if($this->input->post('username', true) != false &&
				$this->input->post('password', true) != false )
			{
				$username = $this->input->post('username', true);
				$password = $this->input->post('password', true);

				$query = $this->user_model->get($username); 
        		if($query->num_rows()> 0){
        			$row = $query->row_array();
        			if (md5($password) != $row['password'])
        			{
        				$this->data['error_message'] = lang('admin.login.error');
        			}
        			else
        			{
        				$this->session->set_userdata('username', $username);
        				$this->session->set_userdata('user_id', $row['user_id']);
        				$this->session->set_userdata('logged_in', true);
						$logged = true;	
        			}
        		}
        		else
        		{
        			$this->data['error_message'] = lang('admin.login.error');
        		}
			}
			else
			{
				$this->data['error_message'] = lang('admin.login.error');
			}
		}
		if ($logged)
		{
			redirect('admin/dashboard');
		}

        $this->data['title']  			= lang('admin.login.title');
        $this->data['heading_title'] 	= lang('admin.login.header');
        $this->data['user_name']		= lang('admin.login.username');
        $this->data['password']			= lang('admin.login.password');
        $this->data['forgotpasswd']		= lang('admin.login.forgotpass');
        $this->data['submit']			= lang('admin.login.submit');
        $this->data['forgotpasswd_url']	= base_url().'admin/forgotten';

		$this->template->build('template/common/login', $this->data);

	}

	public function register()
	{
		$result = false;
		// Delete old session
		$this->session->sess_destroy();

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if($this->input->post('username', true) != false &&
				$this->input->post('password',true) != false )
			{
				$username = $this->input->post('username', true);
				$password = $this->input->post('password', true);
				$query = $this->user_model->get($username);
				if($query->num_rows()<= 0)
				{
					if($username != '' && $password != '')
					{
						$hashpass = md5($password);
						$result = $this->user_model->insert($username, $hashpass);
						if ($result)
						{
							$result = true;
						}
					}
				}
			}
		}
		if ($result)
		{
			$this->session->set_userdata('logged_in', true);
		}
		else
		{
			redirect('admin/login');
		}
	}
}