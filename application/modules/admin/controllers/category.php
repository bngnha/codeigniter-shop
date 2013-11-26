<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Category extends MX_Controller
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

	        // Category
			$this->lang->load('admin/catalog/category');
			$this->load->model('catalog/category_model');

            // Category
			$this->data['title']        	= lang('heading_title');
			$this->data['heading_title']   	= lang('heading_title');
            
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
		if ($this->input->post() && $this->validateForm())
		{
			$this->category_model->addCategory($this->input->post());
			$this->session->set_userdata('success',lang('text_success'));

			redirect('admin/category');
		}

		$this->getForm();
	}

	public function update()
	{
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
		{
			$parameters = $this->uri->uri_to_assoc(4);
			$this->category_model->editCategory($parameters['pid'], $this->input->post());
			$this->session->set_userdata('success',lang('text_success'));
				
			redirect('admin/category');
		}

		$this->getForm();
	}

	public function delete()
	{
		if ($this->input->post('selected') && $this->validateDelete())
		{
			foreach ($this->input->post('selected') as $category_id)
			{
				$this->category_model->deleteCategory($category_id);
			}

			$this->session->data['success'] =lang('text_success');

			redirect('admin/category');
		}

		$this->getList();
	}

	private function getList()
	{
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('text_home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
	       	'text'      =>lang('heading_title'),
			'href'      => base_url().'admin/category',
	      	'separator' => ' :: '
      	);
      			
      	$this->data['insert'] = base_url().'admin/category/insert';
      	$this->data['delete'] = base_url().'admin/category/delete';
		
      	$parameters = $this->uri->uri_to_assoc(4);
      
      	$this->data['categories'] = array();

      	$results = $this->category_model->getCategories(0);
      	$total = $this->category_model->getTotalCategories();
      	foreach ($results as $result)
      	{
      		$action = array();
      			
      		$action[] = array(
				'text' =>lang('text_edit'),
				'href' => base_url().'admin/category/update/pid/'.$result['category_id']
      		);

      		$this->data['categories'][] = array(
			'category_id' => $result['category_id'],
			'name'        => $result['name'],
			'sort_order'  => $result['sort_order'],
			'selected'    => $this->input->post('selected') && in_array($result['category_id'], $this->input->post('selected')),
			'action'      => $action
      		);
      	}

      	$this->data['heading_title'] 	=lang('heading_title');
      	$this->data['text_no_results'] 	=lang('text_no_results');

      	$this->data['column_name'] 		=lang('column_name');
      	$this->data['column_sort_order']=lang('column_sort_order');
      	$this->data['column_action'] 	=lang('column_action');

      	$this->data['button_insert'] =lang('button_insert');
      	$this->data['button_delete'] =lang('button_delete');

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
		}
		else
		{
			$this->data['success'] = '';
		}
		
      	$this->load->view('template/common/header', $this->data);
		$this->load->view('template/catalog/category_list');
		$this->load->view('template/common/footer');
	}

	private function getForm()
	{
		$this->data['text_none'] 	=lang('text_none');
		$this->data['text_default'] =lang('text_default');
		$this->data['text_image_manager'] =lang('text_image_manager');
		$this->data['text_browse'] 	=lang('text_browse');
		$this->data['text_clear'] 	=lang('text_clear');
		$this->data['text_enabled'] =lang('text_enabled');
		$this->data['text_disabled']=lang('text_disabled');
		$this->data['text_percent'] =lang('text_percent');
		$this->data['text_amount'] 	=lang('text_amount');

		$this->data['entry_name'] 	=lang('entry_name');
		$this->data['entry_meta_keyword'] =lang('entry_meta_keyword');
		$this->data['entry_meta_description'] =lang('entry_meta_description');
		$this->data['entry_description'] =lang('entry_description');
		$this->data['entry_store'] 	=lang('entry_store');
		$this->data['entry_keyword']=lang('entry_keyword');
		$this->data['entry_parent'] =lang('entry_parent');
		$this->data['entry_image'] 	=lang('entry_image');
		$this->data['entry_top'] 	=lang('entry_top');
		$this->data['entry_column'] =lang('entry_column');
		$this->data['entry_sort_order'] =lang('entry_sort_order');
		$this->data['entry_status'] =lang('entry_status');
		$this->data['entry_layout'] =lang('entry_layout');

		$this->data['button_save'] 	=lang('button_save');
		$this->data['button_cancel']=lang('button_cancel');

		$this->data['tab_general'] 	=lang('tab_general');
		$this->data['tab_data'] 	=lang('tab_data');
		$this->data['tab_design'] 	=lang('tab_design');

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
			$this->data['error_name'] = array();
		}

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
       		'text'      =>lang('text_home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
       		'text'      =>lang('heading_title'),
			'href'      => base_url().'admin/category',
      		'separator' => ' :: '
      	);
      	
		$parameters = $this->uri->uri_to_assoc(4);
   		if (sizeof($parameters) > 0 && isset($parameters['pid']))
   		{
			$this->data['action'] = base_url().'admin/category/update/pid/'.$parameters['pid'];
		}
		else
		{
			$this->data['action'] = base_url().'admin/category/insert';
		}

      	$this->data['cancel'] = base_url().'admin/category';

		if (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
			$category_info = $this->category_model->getCategory($parameters['pid']);
		}
      	
      	$this->load->model('localisation/language_model');
      	$this->data['languages'] = $this->language_model->getLanguages();

      	if ($this->input->post('category_description'))
      	{
      		$this->data['category_description'] = $this->input->post('category_description');
     	}
     	elseif (isset($parameters['pid']))
     	{
      		$this->data['category_description'] = $this->category_model->getCategoryDescriptions($parameters['pid']);
      	}
      	else
      	{
      		$this->data['category_description'] = array();
      	}

      	$categories = $this->category_model->getCategories(0);

      	// Remove own id from list
      	if (!empty($category_info))
      	{
      		foreach ($categories as $key => $category)
      		{
      			if ($category['category_id'] == $category_info['category_id'])
      			{
      				unset($categories[$key]);
      			}
      		}
      	}

      	$this->data['categories'] = $categories;

      	if ($this->input->post('parent_id'))
      	{
      		$this->data['parent_id'] = $this->input->post('parent_id');
      	}
      	elseif (!empty($category_info))
      	{
      		$this->data['parent_id'] = $category_info['parent_id'];
      	}
      	else
      	{
      		$this->data['parent_id'] = 0;
      	}

      	if ($this->input->post('keyword')) {
      		$this->data['keyword'] = $this->input->post('keyword');
      	}
      	elseif (!empty($category_info))
      	{
      		$this->data['keyword'] = $category_info['keyword'];
      	}
      	else
      	{
      		$this->data['keyword'] = '';
      	}

      	if ($this->input->post('image'))
      	{
      		$this->data['image'] = $this->input->post('image');
      	}
      	elseif (!empty($category_info))
      	{
      		$this->data['image'] = $category_info['image'];
      	}
      	else
      	{
      		$this->data['image'] = '';
      	}

      	$this->load->model('tool/image_model');

      	if (!empty($category_info) 
      	&& $category_info['image']
      	&& file_exists(DIR_IMAGE . $category_info['image']))
      	{
      		$this->data['thumb'] = $this->image_model->resize($category_info['image'], 100, 100);
      	}
      	else
      	{
      		$this->data['thumb'] = $this->image_model->resize('no_image.jpg', IMG_THUMB_WIDTH, IMG_THUMB_HEIGHT);
      	}

      	$this->data['no_image'] = $this->image_model->resize('no_image.jpg', IMG_THUMB_WIDTH, IMG_THUMB_HEIGHT);

      	if ($this->input->post('sort_order'))
      	{
      		$this->data['sort_order'] = $this->input->post('sort_order');
      	}
      	elseif (!empty($category_info))
      	{
      			$this->data['sort_order'] = $category_info['sort_order'];
      	}
      	else
      	{
      			$this->data['sort_order'] = 0;
      	}

      	if ($this->input->post('status'))
      	{
      		$this->data['status'] = $this->input->post('status');
      	}
      	elseif (!empty($category_info))
      	{
      		$this->data['status'] = $category_info['status'];
      	}
      	else
      	{
      		$this->data['status'] = 1;
      	}

      	if ($this->input->post('top'))
      	{
      		$this->data['top'] = $this->input->post('top');
      	}
      	elseif (!empty($category_info))
      	{
      		$this->data['top'] = $category_info['top'];
      	}
      	else
      	{
      		$this->data['top'] = 0;
      	}

      	$this->load->view('template/common/header', $this->data);
		$this->load->view('template/catalog/category_form');
		$this->load->view('template/common/footer');
	}

	private function validateForm()
	{
		//		if (!$this->user->hasPermission('modify', 'catalog/category')) {
		//			$this->error['warning'] =lang('error_permission');
		//		}

		foreach ($this->input->post('category_description') as $language_id => $value)
		{
			if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255))
			{
				$this->error['name'][$language_id] =lang('error_name');
			}
		}

		if ($this->error && !isset($this->error['warning']))
		{
			$this->error['warning'] =lang('error_warning');
		}
			
		if (!$this->error)
		{
			return true;
		}
		else
		{
			return false;
		}
		return  true;
	}

	private function validateDelete()
	{
		//		if (!$this->user->hasPermission('modify', 'catalog/category')) {
		//			$this->error['warning'] =lang('error_permission');
		//		}
		//
		//		if (!$this->error) {
		//			return true;
		//		} else {
		//			return false;
		//		}
		return true;
	}
}
?>