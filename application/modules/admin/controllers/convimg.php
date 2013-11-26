<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Convimg extends MX_Controller
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

	        // Product
	        $this->lang->load('admin/catalog/product');
	        $this->load->model('catalog/product_model');

            // Product
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
		$this->load->model('tool/image_model');
		$img_path = 'data/triumph/';
		
		$img_arr = array(
						'Maxi-025-White.JPG',
						'Maxi-025-Skin.JPG',
						'Maxi-025-Black.jpg'
						);
		foreach ($img_arr as $img_name) {

			if (file_exists(DIR_IMAGE . $img_path.$img_name)){
			$image = $this->image_model->resize($img_path.$img_name, 400, 400);
			} else {
				$image = $this->image_model->resize('no_image.jpg', 40, 40);
			}

		}
	}
}
?>