<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Review extends MX_Controller
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
	        $this->lang->load('admin/catalog/review');
	        $this->load->model('catalog/review_model');

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
		$this->getList();
  	}
  
    public function req()
    {
        $this->getList();
    }

	public function insert()
	{
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
		{
			$this->review_model->addReview($this->input->post());
			$this->session->set_userdata('success', lang('text_success'));
						
			redirect('admin/review');
		}
		$this->getForm();
	}

	public function update()
	{
		if (($_SERVER['REQUEST_METHOD'] === 'POST') && $this->validateForm())
		{
			$parameters = $this->uri->uri_to_assoc(4);
			$this->review_model->editReview($parameters['pid'], $this->input->post());
			$this->session->set_userdata('success', lang('text_success'));
						
			redirect('admin/review');
		}
		$this->getForm();
	}

	public function delete()
	{ 
		if ($this->input->post('selected') && $this->validateDelete())
		{
			foreach ($this->input->post('selected') as $review_id)
			{
				$this->review_model->deleteReview($review_id);
			}

			$this->session->set_userdata('success', lang('text_success'));
			redirect('admin/review');
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
       		'text'      => lang('heading_title'),
			'href'      => base_url().'admin/review',
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = base_url().'admin/review/insert';
		$this->data['delete'] = base_url().'admin/review/delete';	
		
		$this->data['text_no_results'] 	= lang('text_no_results');
		$this->data['column_product'] 	= lang('column_product');
		$this->data['column_author'] 	= lang('column_author');
		$this->data['column_rating'] 	= lang('column_rating');
		$this->data['column_status'] 	= lang('column_status');
		$this->data['column_date_added']= lang('column_date_added');
		$this->data['column_action'] 	= lang('column_action');		
		
		$this->data['button_insert'] 	= lang('button_insert');
		$this->data['button_delete'] 	= lang('button_delete');
		
		$parameters = $this->uri->uri_to_assoc(4);
		if (isset($parameters['sort']))
  	    {
            $this->data['sort'] = isset($parameters['sort']);
        }
        else
        {
            $this->data['sort'] = 'r.date_added';
        }
        
        if (isset($parameters['order']))
        {
            $this->data['order'] = isset($parameters['order']);
        }
        else
        {
            $this->data['order'] = 'asc';
        }
				
 		// limit and offset
        $limit = $this->session->userdata('config_admin_limit');
        $offset = 0;
        if (isset($parameters['page']))
        {
            $offset = $parameters['page'];
        }
        else
        {
            $offset = '0';  
        }

        if (isset($parameters['sort']))
        {
            $this->data['sort'] = $parameters['sort'];
        }
        else
        {
            $this->data['sort'] = 'product';
        }
        if (isset($parameters['order']))
        {
            $this->data['order'] = $parameters['order'];
        }
        else
        {
            $this->data['order'] = 'asc';
        } 
        
         // link sort
        $order_product = 'asc';
        if ((isset($parameters['sort']) && $parameters['sort'] == 'product')
            && $this->data['order'] == 'asc')
        {
            $order_product = 'desc';
        }
        if ($this->data['sort'] == 'product')
        {
        	$this->data['sort'] = 'pd.name';
        }
        
        $order_author = 'asc';
        if ((isset($parameters['sort']) && $parameters['sort'] == 'author')
            && $this->data['order'] == 'asc')
        {
            $order_author = 'desc';
        }
		if ($this->data['sort'] == 'author')
		{
			$this->data['sort'] = 'r.author';
		}
        
        $order_rating = 'asc';
        if ((isset($parameters['sort']) && $parameters['sort'] == 'rating')
            && $this->data['order'] == 'asc')
        {
            $order_rating = 'desc';
        }
        if ($this->data['sort'] == 'rating')
        {
        	$this->data['sort'] = 'r.rating';
        }
        
  	    $order_status = 'asc';
        if ((isset($parameters['sort']) && $parameters['sort'] == 'status')
            && $this->data['order'] == 'asc')
        {
            $order_status = 'desc';
        }
        if ($this->data['sort'] == 'status')
        {
        	$this->data['sort'] = 'r.status';
        }
        
  	    $order_date_added = 'asc';
        if ((isset($parameters['sort']) && $parameters['sort'] == 'date_added')
            && $this->data['order'] == 'asc')
        {
            $order_date_added = 'desc';
        }
        if ($this->data['sort'] == 'date_added')
        {
        	$this->data['sort'] = 'r.date_added';
        }

		$this->data['sort_product'] = base_url().'admin/review/req/sort/product/order/' . $order_product;
		$this->data['sort_author'] = base_url().'admin/review/req/sort/author/order/' . $order_author;
		$this->data['sort_rating'] = base_url().'admin/review/req/sort/rating/order/' . $order_rating;
		$this->data['sort_status'] = base_url().'admin/review/req/sort/status/order/' . $order_status;
		$this->data['sort_date_added'] = base_url().'admin/review/req/sort/date_added/order/' . $order_date_added;
        
		$this->data['reviews'] = array();
		$data = array(
			'sort'  => $this->data['sort'],
			'order' => $this->data['order'],
			'start' => $offset,
			'limit' => $limit
		);
		
		$review_total = $this->review_model->getTotalReviews();
		$results = $this->review_model->getReviews($data);
    	foreach ($results as $result)
    	{
			$action = array();
			$action[] = array(
				'text' => lang('text_edit'),
				'href' => base_url().'admin/review/update/pid/'. $result['review_id']
			);
						
			$this->data['reviews'][] = array(
				'review_id'  => $result['review_id'],
				'name'       => $result['name'],
				'author'     => $result['author'],
				'rating'     => $result['rating'],
				'status'     => ($result['status'] ? lang('text_enabled') : lang('text_disabled')),
				'date_added' => date(lang('date_format_short'), strtotime($result['date_added'])),
				'selected'   => $this->input->post('selected') && in_array($result['review_id'], $this->input->post('selected')),
				'action'     => $action
			);
		}	
 
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

		$config['base_url']         = base_url().'admin/review/req/page/';
        $config['total_rows']       = $review_total;
        $config['cur_page']         = $offset;
        $this->data['pagination']   = create_pagination($config);

        $this->load->view('template/common/header', $this->data);
        $this->load->view('template/catalog/review_list');
        $this->load->view('template/common/footer');
	}

	private function getForm()
	{
		$this->data['text_enabled'] = lang('text_enabled');
		$this->data['text_disabled']= lang('text_disabled');
		$this->data['text_none'] 	= lang('text_none');
		$this->data['text_select'] 	= lang('text_select');

		$this->data['entry_product']= lang('entry_product');
		$this->data['entry_author'] = lang('entry_author');
		$this->data['entry_rating'] = lang('entry_rating');
		$this->data['entry_status'] = lang('entry_status');
		$this->data['entry_text'] 	= lang('entry_text');
		$this->data['entry_good'] 	= lang('entry_good');
		$this->data['entry_bad'] 	= lang('entry_bad');

		$this->data['button_save'] 	= lang('button_save');
		$this->data['button_cancel']= lang('button_cancel');

 		if (isset($this->error['warning']))
 		{
			$this->data['error_warning'] = $this->error['warning'];
		}
		else
		{
			$this->data['error_warning'] = '';
		}
 		
		if (isset($this->error['product']))
		{
			$this->data['error_product'] = $this->error['product'];
		}
		else
		{
			$this->data['error_product'] = '';
		}
		
 		if (isset($this->error['author']))
 		{
			$this->data['error_author'] = $this->error['author'];
		}
		else
		{
			$this->data['error_author'] = '';
		}
		
 		if (isset($this->error['text']))
 		{
			$this->data['error_text'] = $this->error['text'];
		}
		else
		{
			$this->data['error_text'] = '';
		}
		
 		if (isset($this->error['rating']))
 		{
			$this->data['error_rating'] = $this->error['rating'];
		}
		else
		{
			$this->data['error_rating'] = '';
		}
				
   		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('text_home'),
			'href'      => base_url().'admin/dashboard',
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => lang('heading_title'),
			'href'      => base_url().'admin/review',
      		'separator' => ' :: '
   		);

		$parameters = $this->uri->uri_to_assoc(4);
        if (sizeof($parameters) > 0 && isset($parameters['pid']))
        {
            $this->data['action'] = base_url().'admin/review/update/pid/'.$parameters['pid'];
        }
        else
        {
            $this->data['action'] = base_url().'admin/review/insert';
        }
		
		$this->data['cancel'] = base_url().'admin/review';

		if (sizeof($parameters) > 0 && isset($parameters['pid']))
		{
			$review_info = $this->review_model->getReview($parameters['pid']);
		}

		$this->load->model('catalog/product_model');
		
		if ($this->input->post('product_id'))
		{
			$this->data['product_id'] = $this->input->post('product_id');
		}
		elseif (!empty($review_info))
		{
			$this->data['product_id'] = $review_info['product_id'];
		}
		else
		{
			$this->data['product_id'] = '';
		}

		if ($this->input->post('product'))
		{
			$this->data['product'] = $this->input->post('product');
		}
		elseif (!empty($review_info))
		{
			$this->data['product'] = $review_info['product'];
		}
		else
		{
			$this->data['product'] = '';
		}
				
		if ($this->input->post('author'))
		{
			$this->data['author'] = $this->input->post('author');
		}
		elseif (!empty($review_info))
		{
			$this->data['author'] = $review_info['author'];
		}
		else
		{
			$this->data['author'] = '';
		}

		if ($this->input->post('text'))
		{
			$this->data['text'] = $this->input->post('text');
		}
		elseif (!empty($review_info))
		{
			$this->data['text'] = $review_info['text'];
		}
		else
		{
			$this->data['text'] = '';
		}

		if ($this->input->post('rating'))
		{
			$this->data['rating'] = $this->input->post('rating');
		}
		elseif (!empty($review_info))
		{
			$this->data['rating'] = $review_info['rating'];
		}
		else
		{
			$this->data['rating'] = '';
		}

		if ($this->input->post('status'))
		{
			$this->data['status'] = $this->input->post('status');
		}
		elseif (!empty($review_info))
		{
			$this->data['status'] = $review_info['status'];
		}
		else
		{
			$this->data['status'] = '';
		}

		$this->load->view('template/common/header', $this->data);
        $this->load->view('template/catalog/review_form');
        $this->load->view('template/common/footer');
	}
	
	private function validateForm()
	{
		//if (!$this->user->hasPermission('modify', 'catalog/review')) {
		//	$this->error['warning'] = lang('error_permission');
		//}

		if (!$this->input->post('product_id'))
		{
			$this->error['product'] = lang('error_product');
		}
		
		if ((utf8_strlen($this->input->post('author')) < 3)
		 || (utf8_strlen($this->input->post('author') > 64)))
		{
			$this->error['author'] = lang('error_author');
		}

		if (utf8_strlen($this->input->post('text')) < 1)
		{
			$this->error['text'] = lang('error_text');
		}
				
		if (!$this->input->post('rating'))
		{
			$this->error['rating'] = lang('error_rating');
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

	private function validateDelete()
	{
		//if (!$this->user->hasPermission('modify', 'catalog/review')) {
		//	$this->error['warning'] = lang('error_permission');
		//}

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