<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class News extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->error= array();

		if ($this->session->userdata('logged_in'))
		{
			// Common
			$this->lang->switch_to($this->session->userdata('config_admin_language'));

			// Category
			$this->lang->load('admin/news/news');
			$this->load->model('news/news_model');
		
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

	public function index() {

		$this->getList();
	}

	public function insert() {

		if ($this->input->post() && $this->validateForm()){
			$this->news_model->addNews($this->input->post());

			$this->session->set_userdata('success','text_success');
			var_dump($this->session->userdata('success'));
			redirect('admin/news');
		}

		$this->getForm();
	}

	public function update() {

		if ($this->input->post() && $this->validateForm()){
			$this->news_model->editNews($this->input->get('news_id'), $this->input->post());

			$this->session->set_userdata('success','text_success');

			redirect('admin/news');
		}

		$this->getForm();
	}

	public function delete() {

		if ($this->input->post('selected') && $this->validateDelete()) {
			foreach ($this->input->post('selected') as $news_id) {
				$this->news_model->deleteNews($news_id);
			}

			$this->session->set_userdata('success','text_success');

			redirect('admin/news');
		}

		$this->getList();
	}

	private function getList() {

		$this->data['text_no_results'] = lang('text_no_results');

		$this->data['column_title'] = lang('column_title');
		$this->data['column_date_added'] = lang('column_date_added');
		$this->data['column_status'] = lang('column_status');
		$this->data['column_action'] = lang('column_action');

		$this->data['button_module'] = lang('button_module');
		$this->data['button_insert'] = lang('button_insert');
		$this->data['button_delete'] = lang('button_delete');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->userdata('success');
			$this->session->unset_userdata('success');
		} else {
			$this->data['success'] = '';
		}
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('text_home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
       		'text'      =>lang('heading_title'),
			'href'      => base_url().'admin/news',
      		'separator' => ' :: '
      		);

      		$this->data['module'] = base_url().'admin/news';
      		$this->data['insert'] = base_url().'admin/news/insert';
      		$this->data['delete'] = base_url().'admin/news/delete';

      		$this->data['news'] = array();
			$parameters = $this->uri->uri_to_assoc(4);
      		// limit and offset
      		$limit = $this->session->userdata('config_admin_limit');
      		$offset = 0;
      		if (isset($parameters['page']))
      		{
      			$offset = $parameters['page'];
      		}
      		$data = array(
            'start'           => $offset,
            'limit'           => $limit
      		);
      		
      		$total = $this->news_model->getTotalNews();

      		$results = $this->news_model->getNews($this->session->userdata('config_admin_language_id'),$data);

      		foreach ($results as $result) {
      			$action = array();

      			$action[] = array(
				'text' => lang('text_edit'),
				'href' => base_url().'admin/news/update'. '?news_id=' . $result['news_id']
      			);

      			$this->data['news'][] = array(
				'news_id'     => $result['news_id'],
				'title'       => $result['title'],
				'date_added'  => date(lang('date_format_short'), strtotime($result['date_added'])),
				'status'      => ($result['status'] ? lang('text_enabled') : lang('text_disabled')),
				'selected'    => $this->input->post('selected') && in_array($result['news_id'], $this->input->post('selected')),
				'action'      => $action
      			);
      		}
      		//phan trang
      		$config['base_url']         = base_url().'admin/news/index/page/';
	        $config['total_rows']       = $total;
	        $config['cur_page']         = $offset;
	        $this->data['pagination']   = create_pagination($config);
      		$this->load->view('template/common/header', $this->data);
	        $this->load->view('template/news/news_list');
	        $this->load->view('template/common/footer');
	}

	private function getForm() {

		$this->data['text_enabled'] = lang('text_enabled');
		$this->data['text_disabled'] = lang('text_disabled');
		$this->data['text_default'] = lang('text_default');
		$this->data['text_image_manager'] = lang('text_image_manager');
		$this->data['text_browse'] = lang('text_browse');
		$this->data['text_clear'] = lang('text_clear');

		$this->data['entry_title'] = lang('entry_title');
		$this->data['entry_keyword'] = lang('entry_keyword');
		$this->data['entry_meta_description'] = lang('entry_meta_description');
		$this->data['entry_description'] = lang('entry_description');
		$this->data['entry_store'] = lang('entry_store');
		$this->data['entry_status'] = lang('entry_status');
		$this->data['entry_image'] = lang('entry_image');

		$this->data['button_save'] = lang('button_save');
		$this->data['button_cancel'] = lang('button_cancel');

		$this->data['tab_general'] = lang('tab_general');
		$this->data['tab_data'] = lang('tab_data');


		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}

		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
       		'text'      =>lang('text_home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
       		'text'      =>lang('heading_title'),
			'href'      => base_url().'admin/news',
      		'separator' => ' :: '
      		);

      		if (!$this->input->get('news_id')) {
      			$this->data['action'] = base_url().'admin/news/insert';
      		} else {
      			$this->data['action'] = base_url().'admin/news/update' . '?news_id=' . $this->input->get('news_id');
      		}

      		$this->data['cancel'] = base_url().'admin/news';

      		if ($this->input->get('news_id')) {
      			$news_info = $this->news_model->getNewsStory($this->input->get('news_id'));
      		}

      		$this->load->model('localisation/language_model');
      		$this->data['languages'] = $this->language_model->getLanguages();

      		if ($this->input->post('news_description')) {
      			$this->data['news_description'] = $this->input->post('news_description');
      		} elseif ($this->input->get('news_id')) {
      			$this->data['news_description'] = $this->news_model->getNewsDescriptions($this->input->get('news_id'));
      		} else {
      			$this->data['news_description'] = array();
      		}


      		if ($this->input->post('keyword')) {
      			$this->data['keyword'] = $this->input->post('keyword');
      		} elseif (isset($news_info)) {
      			$this->data['keyword'] = $news_info['keyword'];
      		} else {
      			$this->data['keyword'] = '';
      		}

      		if ($this->input->post('status')) {
      			$this->data['status'] = $this->input->post('status');
      		} elseif (isset($news_info)) {
      			$this->data['status'] = $news_info['status'];
      		} else {
      			$this->data['status'] = '';
      		}

      		if ($this->input->post('image')) {
      			$this->data['image'] = $this->input->post('image');
      		} elseif (isset($news_info)) {
      			$this->data['image'] = $news_info['image'];
      		} else {
      			$this->data['image'] = '';
      		}

      		$this->load->model('tool/image_model');

      		if (!empty($news_info) && $news_info['image'] && file_exists(DIR_IMAGE . $news_info['image'])) {
      			$this->data['thumb'] = $this->image_model->resize($news_info['image'], IMG_THUMB_WIDTH, IMG_THUMB_HEIGHT);
      		} else {
      			$this->data['thumb'] = $this->image_model->resize('no_image.jpg', IMG_THUMB_WIDTH, IMG_THUMB_HEIGHT);
      		}

      		$this->data['no_image'] = $this->image_model->resize('no_image.jpg', IMG_THUMB_WIDTH, IMG_THUMB_HEIGHT);

      		$this->load->view('template/common/header', $this->data);
      		$this->load->view('template/news/news_form');
      		$this->load->view('template/common/footer');
	}

	private function validate() {
//		if (!$this->user->hasPermission('modify', 'module/news')) {
//			$this->error['warning'] = lang('error_permission');
//		}

		//if (!$this->request->post['news_headline_chars']) {
		//	$this->error['numchars'] = lang('error_numchars');
		//}

		//if (!$this->request->post['news_thumb_width'] || !$this->request->post['news_thumb_height']) {
		//	$this->error['newspage_thumb'] = lang('error_newspage_thumb');
		//}

		//if (!$this->request->post['news_popup_width'] || !$this->request->post['news_popup_height']) {
		//	$this->error['newspage_popup'] = lang('error_newspage_popup');
		//}

		//if (isset($this->request->post['news_module'])) {
		//	foreach ($this->request->post['news_module'] as $key => $value) {
		//		if (!$value['numchars']) {
		//			$this->error['module_chars'][$key] = lang('error_numchars');
		//		}
		//	}
		//}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateForm() {
		foreach ($this->input->post('news_description') as $language_id => $value) {
			if ((strlen($value['title']) < 3) || (strlen($value['title']) > 255)) {
				$this->error['title'][$language_id] = lang('error_title');
			}

			if (strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = lang('error_description');
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
//		if (!$this->user->hasPermission('modify', 'module/news')) {
//			$this->error['warning'] = lang('error_permission');
//		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
