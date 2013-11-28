<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('createCaptcha'))
{
	function createCaptcha($sess_name)
	{
		$CI =& get_instance();

		// load captcha helper of system
		$CI->load->helper('captcha');
		$confs = array(
					'img_path' 	=>'./captcha/',
					'img_url'	=>site_url().'captcha/',
					'font_path' =>site_url().'captcha/fonts/',
					'img_width'	=> 144,
		            'img_height'=> 35,
		            'expiration'=> 7200
				);
		$cap = create_captcha($confs);
		$data = array (
			'captcha_id' => '',
			'captcha_time' => $cap['time'],
			'captcha_ip_address' => $CI->input->ip_address(),
			'captcha_word' => $cap['word']
		);

		$CI->session->set_userdata($sess_name, $data);

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			echo $cap['image'];
		}
		else
		{ 
        	return $cap['image'];
		}
	}	
}
if (!function_exists('checkCaptcha'))
{
	function checkCaptcha($captcha_word, $sess_name)
	{
		$CI =& get_instance();

		$ip_address = $CI->input->ip_address();
		$expiration = time() - 7200;
		
		$data = $CI->session->userdata($sess_name);
		if ($data['captcha_word'] = $captcha_word && 
			$data['captcha_ip_address'] = $ip_address &&
			$data['captcha_time'] > $expiration)
		{
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
			{
				echo 'true';
			}
			else
			{
				return true;
			}
		}
		else
		{
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
			{
				echo 'false';
			}
			else
			{
				return false;
			}
		}
	}
}