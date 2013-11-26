<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Logout extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->switch_to($this->session->userdata('config_admin_language'));
	}

	public function index()
	{
		// Detroy all session
        $this->session->sess_destroy();

        redirect('admin/login');
	}
}