<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Setting extends MX_Controller
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

	        // Setting
	        $this->lang->load('admin/setting/setting');
	        $this->load->model('setting/setting_model');

            // Setting
            $this->data['title']            = lang('heading_title');
            $this->data['heading_title']    = lang('heading_title');
            
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
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validate())
		{
		 	$this->output->enable_profiler(TRUE);
		 	
		 	// Get language_id
		 	
			$this->setting_model->editSetting('config', $this->input->post());

			if ($this->config->item('config_currency_auto'))
			{
				$this->load->model('localisation/currency_model');
				$this->currency_model->updateCurrencies();
			}

			$this->session->set_userdata('success', lang('text_success'));
			$this->session->set_userdata($this->input->post());

			redirect('admin/setting');
		}

		$configs = $this->session->all_userdata();
		if (is_array($configs) && sizeof($configs) > 0)
		{
			foreach ($configs as $key=>$val)
			{
				$this->config->set_item($key, $val);
			}
		}

		$this->data['text_none']      = lang('text_none');
		$this->data['text_yes']       = lang('text_yes');
		$this->data['text_no']        = lang('text_no');
		$this->data['text_image_manager'] = lang('text_image_manager');
 		$this->data['text_browse']    = lang('text_browse');
		$this->data['text_clear']     = lang('text_clear');	
		$this->data['text_mail']      = lang('text_mail');
		$this->data['text_smtp']      = lang('text_smtp');

		// General
		$this->data['entry_name']     = lang('entry_name');
		$this->data['entry_owner']    = lang('entry_owner');
		$this->data['entry_address']  = lang('entry_address');
		$this->data['entry_email']    = lang('entry_email');
		$this->data['entry_telephone']= lang('entry_telephone');
		$this->data['entry_fax']      = lang('entry_fax');

		// Store
		$this->data['entry_title']    = lang('entry_title');
		$this->data['entry_meta_description'] = lang('entry_meta_description');

		// Local
		$this->data['entry_country']  = lang('entry_country');
		$this->data['entry_zone']     = lang('entry_zone');
		$this->data['entry_language'] = lang('entry_language');
		$this->data['entry_admin_language']   = lang('entry_admin_language');
		$this->data['entry_currency'] = lang('entry_currency');
		$this->data['entry_currency_auto']    = lang('entry_currency_auto');

		// Option
		$this->data['entry_catalog_limit']    = lang('entry_catalog_limit');
		$this->data['entry_admin_limit']      = lang('entry_admin_limit');
		$this->data['entry_account'] = lang('entry_account');
		$this->data['entry_checkout']         = lang('entry_checkout');
		$this->data['entry_stock_display']    = lang('entry_stock_display');
		$this->data['entry_stock_warning']    = lang('entry_stock_warning');
		$this->data['entry_order_status']     = lang('entry_order_status');
		$this->data['entry_complete_status']  = lang('entry_complete_status');	
		$this->data['entry_return_status']    = lang('entry_return_status');
		$this->data['entry_stock_status']     = lang('entry_stock_status');
		$this->data['entry_review']   = lang('entry_review');
		$this->data['entry_download'] = lang('entry_download');
		$this->data['entry_upload_allowed']   = lang('entry_upload_allowed');

		// Image
		$this->data['entry_logo']     = lang('entry_logo');
		$this->data['entry_icon']     = lang('entry_icon');
		$this->data['entry_image_category']   = lang('entry_image_category');
		$this->data['entry_image_thumb']      = lang('entry_image_thumb');
		$this->data['entry_image_popup']      = lang('entry_image_popup');
		$this->data['entry_image_product']    = lang('entry_image_product');
		$this->data['entry_image_additional'] = lang('entry_image_additional');
		$this->data['entry_image_related']    = lang('entry_image_related');
		$this->data['entry_image_compare']    = lang('entry_image_compare');
		$this->data['entry_image_wishlist']   = lang('entry_image_wishlist');
		$this->data['entry_image_cart']       = lang('entry_image_cart');

		// Mail
		$this->data['entry_mail_protocol']    = lang('entry_mail_protocol');
		$this->data['entry_mail_parameter']   = lang('entry_mail_parameter');
		$this->data['entry_smtp_host'] = lang('entry_smtp_host');
		$this->data['entry_smtp_username']    = lang('entry_smtp_username');
		$this->data['entry_smtp_password']    = lang('entry_smtp_password');
		$this->data['entry_smtp_port'] = lang('entry_smtp_port');
		$this->data['entry_smtp_timeout']     = lang('entry_smtp_timeout');
		$this->data['entry_alert_mail']       = lang('entry_alert_mail');
		$this->data['entry_account_mail']     = lang('entry_account_mail');
		$this->data['entry_alert_emails']     = lang('entry_alert_emails');
		
		// Server
		$this->data['entry_use_ssl']  = lang('entry_use_ssl');
		$this->data['entry_maintenance']      = lang('entry_maintenance');
		$this->data['entry_encryption']       = lang('entry_encryption');
		$this->data['entry_seo_url']  = lang('entry_seo_url');
		$this->data['entry_compression']      = lang('entry_compression');
		$this->data['entry_error_display']    = lang('entry_error_display');
		$this->data['entry_error_log']        = lang('entry_error_log');
		$this->data['entry_error_filename']   = lang('entry_error_filename');
		$this->data['entry_google_analytics'] = lang('entry_google_analytics');

		$this->data['button_save']    = lang('button_save');
		$this->data['button_cancel']  = lang('button_cancel');
		$this->data['tab_general']    = lang('tab_general');
		$this->data['tab_store']      = lang('tab_store');
		$this->data['tab_local']      = lang('tab_local');
		$this->data['tab_option']     = lang('tab_option');
		$this->data['tab_image']      = lang('tab_image');
		$this->data['tab_mail']       = lang('tab_mail');
		$this->data['tab_server']     = lang('tab_server');

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

 		if (isset($this->error['owner']))
 		{
			$this->data['error_owner'] = $this->error['owner'];
		}
		else
		{
			$this->data['error_owner'] = '';
		}

 		if (isset($this->error['address']))
 		{
			$this->data['error_address'] = $this->error['address'];
		}
		else
		{
			$this->data['error_address'] = '';
		}

 		if (isset($this->error['email']))
 		{
			$this->data['error_email'] = $this->error['email'];
		}
		else
		{
			$this->data['error_email'] = '';
		}

		if (isset($this->error['telephone']))
		{
			$this->data['error_telephone'] = $this->error['telephone'];
		}
		else
		{
			$this->data['error_telephone'] = '';
		}
 
  		if (isset($this->error['title']))
  		{
			$this->data['error_title'] = $this->error['title'];
		}
		else
		{
			$this->data['error_title'] = '';
		}

 		if (isset($this->error['image_category']))
 		{
			$this->data['error_image_category'] = $this->error['image_category'];
		}
		else
		{
			$this->data['error_image_category'] = '';
		}

 		if (isset($this->error['image_thumb']))
 		{
			$this->data['error_image_thumb'] = $this->error['image_thumb'];
		}
		else
		{
			$this->data['error_image_thumb'] = '';
		}

 		if (isset($this->error['image_popup']))
 		{
			$this->data['error_image_popup'] = $this->error['image_popup'];
		}
		else
		{
			$this->data['error_image_popup'] = '';
		}

 		if (isset($this->error['image_product']))
 		{
			$this->data['error_image_product'] = $this->error['image_product'];
		}
		else
		{
			$this->data['error_image_product'] = '';
		}

 		if (isset($this->error['image_additional']))
 		{
			$this->data['error_image_additional'] = $this->error['image_additional'];
		}
		else
		{
			$this->data['error_image_additional'] = '';
		}	

 		if (isset($this->error['image_related']))
 		{
			$this->data['error_image_related'] = $this->error['image_related'];
		}
		else
		{
			$this->data['error_image_related'] = '';
		}

 		if (isset($this->error['image_compare']))
 		{
			$this->data['error_image_compare'] = $this->error['image_compare'];
		}
		else
		{
			$this->data['error_image_compare'] = '';
		}

  		if (isset($this->error['image_wishlist']))
  		{
			$this->data['error_image_wishlist'] = $this->error['image_wishlist'];
		}
		else
		{
			$this->data['error_image_wishlist'] = '';
		}

		if (isset($this->error['image_cart']))
		{
			$this->data['error_image_cart'] = $this->error['image_cart'];
		}
		else
		{
			$this->data['error_image_cart'] = '';
		}

		if (isset($this->error['error_filename']))
		{
			$this->data['error_error_filename'] = $this->error['error_filename'];
		}
		else
		{
			$this->data['error_error_filename'] = '';
		}

		if (isset($this->error['catalog_limit']))
		{
			$this->data['error_catalog_limit'] = $this->error['catalog_limit'];
		}
		else
		{
			$this->data['error_catalog_limit'] = '';
		}

		if (isset($this->error['admin_limit']))
		{
			$this->data['error_admin_limit'] = $this->error['admin_limit'];
		}
		else
		{
			$this->data['error_admin_limit'] = '';
		}

  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('text_home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('heading_title'),
			'href'      => base_url().'admin/setting',
      		'separator' => ' :: '
   		);

		if ($this->session->userdata('success'))
		{
			$this->data['success'] = $this->session->userdata('success');
			$this->session->unset_userdata('success');
		}
		else
		{
			$this->data['success'] = '';
		}

		$this->data['action'] = base_url().'admin/setting';
		$this->data['cancel'] = base_url().'admin/dashboard';

		// General
		if ($this->input->post('config_name'))
		{
			$this->data['config_name'] = $this->input->post('config_name');
		}
		else
		{
			$this->data['config_name'] = $this->config->item('config_name');
		}

		if ($this->input->post('config_owner'))
		{
			$this->data['config_owner'] = $this->input->post('config_owner');
		}
		else
		{
			$this->data['config_owner'] = $this->config->item('config_owner');
		}

		if ($this->input->post('config_address'))
		{
			$this->data['config_address'] = $this->input->post('config_address');
		}
		else
		{
			$this->data['config_address'] = $this->config->item('config_address');
		}

		if ($this->input->post('config_email'))
		{
			$this->data['config_email'] = $this->input->post('config_email');
		}
		else
		{
			$this->data['config_email'] = $this->config->item('config_email');
		}

		if ($this->input->post('config_telephone'))
		{
			$this->data['config_telephone'] = $this->input->post('config_telephone');
		}
		else
		{
			$this->data['config_telephone'] = $this->config->item('config_telephone');
		}

		if ($this->input->post('config_fax'))
		{
			$this->data['config_fax'] = $this->input->post('config_fax');
		}
		else
		{
			$this->data['config_fax'] = $this->config->item('config_fax');
		}

		// Store
		if ($this->input->post('config_title'))
		{
			$this->data['config_title'] = $this->input->post('config_title');
		}
		else
		{
			$this->data['config_title'] = $this->config->item('config_title');
		}

		if ($this->input->post('config_meta_description'))
		{
			$this->data['config_meta_description'] = $this->input->post('config_meta_description');
		}
		else
		{
			$this->data['config_meta_description'] = $this->config->item('config_meta_description');
		}

		// Local
		if ($this->input->post('config_country_id'))
		{
			$this->data['config_country_id'] = $this->input->post('config_country_id');
		}
		else
		{
			$this->data['config_country_id'] = $this->config->item('config_country_id');
		}

		$this->load->model('localisation/country_model');
		$this->data['countries'] = $this->country_model->getCountries();

		if ($this->input->post('config_zone_id'))
		{
			$this->data['config_zone_id'] = $this->input->post('config_zone_id');
		}
		else
		{
			$this->data['config_zone_id'] = $this->config->item('config_zone_id');
		}

		if ($this->input->post('config_language'))
		{
			$this->data['config_language'] = $this->input->post('config_language');
		}
		else
		{
			$this->data['config_language'] = $this->config->item('config_language');
		}

		$this->load->model('localisation/language_model');
		$this->data['languages'] = $this->language_model->getLanguages();

		if ($this->input->post('config_admin_language'))
		{
			$this->data['config_admin_language'] = $this->input->post('config_admin_language');
		}
		else
		{
			$this->data['config_admin_language'] = $this->config->item('config_admin_language');
		}

		if ($this->input->post('config_currency'))
		{
			$this->data['config_currency'] = $this->input->post('config_currency');
		}
		else
		{
			$this->data['config_currency'] = $this->config->item('config_currency');
		}
		$this->load->model('localisation/currency_model');
        $this->data['currencies'] = $this->currency_model->getCurrencies();

		if ($this->input->post('config_currency_auto'))
		{
			$this->data['config_currency_auto'] = $this->input->post('config_currency_auto');
		}
		else
		{
			$this->data['config_currency_auto'] = $this->config->item('config_currency_auto');
		}

		// Option
		if ($this->input->post('config_catalog_limit'))
		{
			$this->data['config_catalog_limit'] = $this->input->post('config_catalog_limit');
		}
		else
		{
			$this->data['config_catalog_limit'] = $this->config->item('config_catalog_limit');
		}

		if ($this->input->post('config_admin_limit'))
		{
			$this->data['config_admin_limit'] = $this->input->post('config_admin_limit');
		}
		else
		{
			$this->data['config_admin_limit'] = $this->config->item('config_admin_limit');
		}

		$this->load->model('catalog/information_model');
		$this->data['informations'] = $this->information_model->getInformations();

	    if ($this->input->post('config_stock_display'))
	    {
            $this->data['config_stock_display'] = $this->input->post('config_stock_display');
        }
        else
        {
            $this->data['config_stock_display'] = $this->config->item('config_stock_display');
        }

        if ($this->input->post('config_stock_warning'))
        {
            $this->data['config_stock_warning'] = $this->input->post('config_stock_warning');
        }
        else
        {
            $this->data['config_stock_warning'] = $this->config->item('config_stock_warning');
        }
		
		$this->load->model('localisation/stock_model');
		$this->data['stock_statuses'] = $this->stock_model->getStockStatuses();

		if ($this->input->post('config_review_status'))
		{
			$this->data['config_review_status'] = $this->input->post('config_review_status');
		}
		else
		{
			$this->data['config_review_status'] = $this->config->item('config_review_status');
		}

		if ($this->input->post('config_download'))
		{
			$this->data['config_download'] = $this->input->post('config_download');
		}
		else
		{
			$this->data['config_download'] = $this->config->item('config_download');
		}

		if ($this->input->post('config_upload_allowed'))
		{
			$this->data['config_upload_allowed'] = $this->input->post('config_upload_allowed');
		}
		else
		{
			$this->data['config_upload_allowed'] = $this->config->item('config_upload_allowed');
		}

		// Image
		$this->load->model('tool/image_model');
		if ($this->input->post('config_logo'))
		{
			$this->data['config_logo'] = $this->input->post('config_logo');
		}
		else
		{
			$this->data['config_logo'] = $this->config->item('config_logo');
		}

		if ($this->config->item('config_logo')
		  && file_exists(DIR_IMAGE . $this->config->item('config_logo'))
		  && is_file(DIR_IMAGE . $this->config->item('config_logo')))
		{
			$this->data['logo'] = $this->image_model->resize($this->config->item('config_logo'), 100, 100);		
		}
		else
		{
			$this->data['logo'] = $this->image_model->resize('no_image.jpg', 100, 100);
		}

		if ($this->input->post('config_icon'))
		{
			$this->data['config_icon'] = $this->input->post('config_icon');
		}
		else
		{
			$this->data['config_icon'] = $this->config->item('config_icon');
		}

		if ($this->config->item('config_icon') 
		  && file_exists(DIR_IMAGE . $this->config->item('config_icon'))
		  && is_file(DIR_IMAGE . $this->config->item('config_icon')))
		{
			$this->data['icon'] = $this->image_model->resize($this->config->item('config_icon'), 100, 100);		
		}
		else
		{
			$this->data['icon'] = $this->image_model->resize('no_image.jpg', 100, 100);
		}

		$this->data['no_image'] = $this->image_model->resize('no_image.jpg', 100, 100);

		if ($this->input->post('config_image_category_width'))
		{
			$this->data['config_image_category_width'] = $this->input->post('config_image_category_width');
		}
		else
		{
			$this->data['config_image_category_width'] = $this->config->item('config_image_category_width');
		}

		if ($this->input->post('config_image_category_height'))
		{
			$this->data['config_image_category_height'] = $this->input->post('config_image_category_height');
		}
		else
		{
			$this->data['config_image_category_height'] = $this->config->item('config_image_category_height');
		}

		if ($this->input->post('config_image_thumb_width'))
		{
			$this->data['config_image_thumb_width'] = $this->input->post('config_image_thumb_width');
		}
		else
		{
			$this->data['config_image_thumb_width'] = $this->config->item('config_image_thumb_width');
		}

		if ($this->input->post('config_image_thumb_height'))
		{
			$this->data['config_image_thumb_height'] = $this->input->post('config_image_thumb_height');
		}
		else
		{
			$this->data['config_image_thumb_height'] = $this->config->item('config_image_thumb_height');
		}

		if ($this->input->post('config_image_popup_width'))
		{
			$this->data['config_image_popup_width'] = $this->input->post('config_image_popup_width');
		}
		else
		{
			$this->data['config_image_popup_width'] = $this->config->item('config_image_popup_width');
		}

		if ($this->input->post('config_image_popup_height'))
		{
			$this->data['config_image_popup_height'] = $this->input->post('config_image_popup_height');
		}
		else
		{
			$this->data['config_image_popup_height'] = $this->config->item('config_image_popup_height');
		}

		if ($this->input->post('config_image_product_width'))
		{
			$this->data['config_image_product_width'] = $this->input->post('config_image_product_width');
		}
		else
		{
			$this->data['config_image_product_width'] = $this->config->item('config_image_product_width');
		}

		if ($this->input->post('config_image_product_height'))
		{
			$this->data['config_image_product_height'] = $this->input->post('config_image_product_height');
		}
		else
		{
			$this->data['config_image_product_height'] = $this->config->item('config_image_product_height');
		}

		if ($this->input->post('config_image_additional_width'))
		{
			$this->data['config_image_additional_width'] = $this->input->post('config_image_additional_width');
		}
		else
		{
			$this->data['config_image_additional_width'] = $this->config->item('config_image_additional_width');
		}

		if ($this->input->post('config_image_additional_height'))
		{
			$this->data['config_image_additional_height'] = $this->input->post('config_image_additional_height');
		}
		else
		{
			$this->data['config_image_additional_height'] = $this->config->item('config_image_additional_height');
		}

		if ($this->input->post('config_image_related_width'))
		{
			$this->data['config_image_related_width'] = $this->input->post('config_image_related_width');
		}
		else
		{
			$this->data['config_image_related_width'] = $this->config->item('config_image_related_width');
		}

		if ($this->input->post('config_image_related_height'))
		{
			$this->data['config_image_related_height'] = $this->input->post('config_image_related_height');
		}
		else
		{
			$this->data['config_image_related_height'] = $this->config->item('config_image_related_height');
		}

		if ($this->input->post('config_image_compare_width'))
		{
			$this->data['config_image_compare_width'] = $this->input->post('config_image_compare_width');
		}
		else
		{
			$this->data['config_image_compare_width'] = $this->config->item('config_image_compare_width');
		}

		if ($this->input->post('config_image_compare_height'))
		{
			$this->data['config_image_compare_height'] = $this->input->post('config_image_compare_height');
		}
		else
		{
			$this->data['config_image_compare_height'] = $this->config->item('config_image_compare_height');
		}	

		if ($this->input->post('config_image_wishlist_width'))
		{
			$this->data['config_image_wishlist_width'] = $this->input->post('config_image_wishlist_width');
		}
		else
		{
			$this->data['config_image_wishlist_width'] = $this->config->item('config_image_wishlist_width');
		}

		if ($this->input->post('config_image_wishlist_height'))
		{
			$this->data['config_image_wishlist_height'] = $this->input->post('config_image_wishlist_height');
		}
		else
		{
			$this->data['config_image_wishlist_height'] = $this->config->item('config_image_wishlist_height');
		}

		if ($this->input->post('config_image_cart_width'))
		{
			$this->data['config_image_cart_width'] = $this->input->post('config_image_cart_width');
		}
		else
		{
			$this->data['config_image_cart_width'] = $this->config->item('config_image_cart_width');
		}

		if ($this->input->post('config_image_cart_height'))
		{
			$this->data['config_image_cart_height'] = $this->input->post('config_image_cart_height');
		}
		else
		{
			$this->data['config_image_cart_height'] = $this->config->item('config_image_cart_height');
		}

		// Email
		if ($this->input->post('config_mail_protocol'))
		{
			$this->data['config_mail_protocol'] = $this->input->post('config_mail_protocol');
		}
		else
		{
			$this->data['config_mail_protocol'] = $this->config->item('config_mail_protocol');
		}

		if ($this->input->post('config_mail_parameter'))
		{
			$this->data['config_mail_parameter'] = $this->input->post('config_mail_parameter');
		}
		else
		{
			$this->data['config_mail_parameter'] = $this->config->item('config_mail_parameter');
		}

		if ($this->input->post('config_smtp_host'))
		{
			$this->data['config_smtp_host'] = $this->input->post('config_smtp_host');
		}
		else
		{
			$this->data['config_smtp_host'] = $this->config->item('config_smtp_host');
		}

		if ($this->input->post('config_smtp_username'))
		{
			$this->data['config_smtp_username'] = $this->input->post('config_smtp_username');
		}
		else
		{
			$this->data['config_smtp_username'] = $this->config->item('config_smtp_username');
		}

		if ($this->input->post('config_smtp_password'))
		{
			$this->data['config_smtp_password'] = $this->input->post('config_smtp_password');
		}
		else
		{
			$this->data['config_smtp_password'] = $this->config->item('config_smtp_password');
		}

		if ($this->input->post('config_smtp_port'))
		{
			$this->data['config_smtp_port'] = $this->input->post('config_smtp_port');
		}
		else if ($this->config->item('config_smtp_port'))
		{
			$this->data['config_smtp_port'] = $this->config->item('config_smtp_port');
		}
		else
		{
			$this->data['config_smtp_port'] = 25;
		}

		if ($this->input->post('config_smtp_timeout'))
		{
			$this->data['config_smtp_timeout'] = $this->input->post('config_smtp_timeout');
		}
		else if ($this->config->item('config_smtp_timeout'))
		{
			$this->data['config_smtp_timeout'] = $this->config->item('config_smtp_timeout');
		}
		else
		{
			$this->data['config_smtp_timeout'] = 5;	
		}

		if ($this->input->post('config_alert_mail'))
		{
			$this->data['config_alert_mail'] = $this->input->post('config_alert_mail');
		}
		else
		{
			$this->data['config_alert_mail'] = $this->config->item('config_alert_mail');
		}

		if ($this->input->post('config_account_mail'))
		{
			$this->data['config_account_mail'] = $this->input->post('config_account_mail');
		}
		else
		{
			$this->data['config_account_mail'] = $this->config->item('config_account_mail');
		}

		if ($this->input->post('config_alert_emails'))
		{
			$this->data['config_alert_emails'] = $this->input->post('config_alert_emails');
		}
		else
		{
			$this->data['config_alert_emails'] = $this->config->item('config_alert_emails');
		}

		// Server
		if ($this->input->post('config_use_ssl'))
		{
			$this->data['config_use_ssl'] = $this->input->post('config_use_ssl');
		}
		else
		{
			$this->data['config_use_ssl'] = $this->config->item('config_use_ssl');
		}

		if ($this->input->post('config_seo_url'))
		{
			$this->data['config_seo_url'] = $this->input->post('config_seo_url');
		}
		else
		{
			$this->data['config_seo_url'] = $this->config->item('config_seo_url');
		}

		if ($this->input->post('config_maintenance'))
		{
			$this->data['config_maintenance'] = $this->input->post('config_maintenance');
		}
		else
		{
			$this->data['config_maintenance'] = $this->config->item('config_maintenance');
		}

		if ($this->input->post('config_encryption'))
		{
			$this->data['config_encryption'] = $this->input->post('config_encryption');
		}
		else
		{
			$this->data['config_encryption'] = $this->config->item('config_encryption');
		}

		if ($this->input->post('config_compression'))
		{
			$this->data['config_compression'] = $this->input->post('config_compression'); 
		}
		else
		{
			$this->data['config_compression'] = $this->config->item('config_compression');
		}

		if ($this->input->post('config_error_display'))
		{
			$this->data['config_error_display'] = $this->input->post('config_error_display'); 
		}
		else
		{
			$this->data['config_error_display'] = $this->config->item('config_error_display');
		}

		if ($this->input->post('config_error_log'))
		{
			$this->data['config_error_log'] = $this->input->post('config_error_log'); 
		}
		else
		{
			$this->data['config_error_log'] = $this->config->item('config_error_log');
		}

		if ($this->input->post('config_error_filename'))
		{
			$this->data['config_error_filename'] = $this->input->post('config_error_filename'); 
		}
		else
		{
			$this->data['config_error_filename'] = $this->config->item('config_error_filename');
		}

		if ($this->input->post('config_google_analytics'))
		{
			$this->data['config_google_analytics'] = $this->input->post('config_google_analytics'); 
		}
		else
		{
			$this->data['config_google_analytics'] = $this->config->item('config_google_analytics');
		}

		$this->load->view('template/common/header', $this->data);
        $this->load->view('template/setting/setting');
        $this->load->view('template/common/footer');
	}

	private function validate()
	{
		if (!$this->input->post('config_name'))
		{
			$this->error['name'] = lang('error_name');
		}

		if ((utf8_strlen($this->input->post('config_owner')) < 3) 
		 || (utf8_strlen($this->input->post('config_owner')) > 64))
		{
			$this->error['owner'] = lang('error_owner');
		}

		if ((utf8_strlen($this->input->post('config_address')) < 3) 
		 || (utf8_strlen($this->input->post('config_address')) > 256))
		{
			$this->error['address'] = lang('error_address');
		}

    	if ((utf8_strlen($this->input->post('config_email')) > 96)
    	 || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->input->post('config_email')))
    	{
      		$this->error['email'] = lang('error_email');
    	}

    	if ((utf8_strlen($this->input->post('config_telephone')) < 3)
    	 || (utf8_strlen($this->input->post('config_telephone')) > 32))
    	{
      		$this->error['telephone'] = lang('error_telephone');
    	}
		
    	if (!$this->input->post('config_title'))
		{
			$this->error['title'] = lang('error_title');
		}	

		if (!$this->input->post('config_image_category_width')
		 || !$this->input->post('config_image_category_height'))
		{
			$this->error['image_category'] = lang('error_image_category');
		} 

		if (!$this->input->post('config_image_thumb_width')
		 || !$this->input->post('config_image_thumb_height'))
		{
			$this->error['image_thumb'] = lang('error_image_thumb');
		}	

		if (!$this->input->post('config_image_popup_width')
		 || !$this->input->post('config_image_popup_height'))
		{
			$this->error['image_popup'] = lang('error_image_popup');
		}	

		if (!$this->input->post('config_image_product_width')
		 || !$this->input->post('config_image_product_height'))
		{
			$this->error['image_product'] = lang('error_image_product');
		}

		if (!$this->input->post('config_image_additional_width')
		 || !$this->input->post('config_image_additional_height'))
		{
			$this->error['image_additional'] = lang('error_image_additional');
		}

		if (!$this->input->post('config_image_related_width')
		 || !$this->input->post('config_image_related_height'))
		{
			$this->error['image_related'] = lang('error_image_related');
		}

		/*
		if (!$this->input->post('config_image_compare_width')
		 || !$this->input->post('config_image_compare_height'))
		{
			$this->error['image_compare'] = lang('error_image_compare');
		}

		if (!$this->input->post('config_image_wishlist_width')
		 || !$this->input->post('config_image_wishlist_height'))
		{
			$this->error['image_wishlist'] = lang('error_image_wishlist');
		}
		*/

		if (!$this->input->post('config_image_cart_width')
		 || !$this->input->post('config_image_cart_height'))
		{
			$this->error['image_cart'] = lang('error_image_cart');
		}

		/*
		if (!$this->input->post('config_error_filename'))
		{
			$this->error['error_filename'] = lang('error_error_filename');
		}

		if (!$this->input->post('config_admin_limit'))
		{
			$this->error['admin_limit'] = lang('error_limit');
		}

		if (!$this->input->post('config_catalog_limit'))
		{
			$this->error['catalog_limit'] = lang('error_limit');
		}
		*/

		if ($this->error && !isset($this->error['warning']))
		{
			$this->error['warning'] = lang('error_warning');
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

	public function template()
	{
		$template = basename($this->input->get('template'));
		if (file_exists(DIR_IMAGE . 'templates/' . $template . '.png'))
		{
			$image = HTTPS_IMAGE . 'templates/' . $template . '.png';
		}
		else
		{
			$image = HTTPS_IMAGE . 'no_image.jpg';
		}
		echo '<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />';
	}

	public function zone()
	{
		$output = '';
		$this->load->model('localisation/zone_model');
		$results = $this->zone_model->getZonesByCountryId($this->input->get('country_id'));
		foreach ($results as $result)
		{
			$output .= '<option value="' . $result['zone_id'] . '"';
			if ($this->input->get('zone_id') && $this->input->get('zone_id') == $result['zone_id'])
			{
				$output .= ' selected="selected"';
			}
			$output .= '>' . $result['name'] . '</option>';
		}
		if (!$results)
		{
			$output .= '<option value="0">' . lang('text_none') . '</option>';
		}
		echo $output;
	}
}
?>