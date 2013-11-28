<?php
class Language_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function addLanguage($data)
	{
		$this->db->trans_begin();
		try
		{
			// Add language
			$this->db->set('name', $data['name']);
			$this->db->set('code', $data['code']);
			$this->db->set('locale', $data['locale']);
			$this->db->set('directory', $data['directory']);
			$this->db->set('filename', $data['filename']);
			$this->db->set('image', $data['image']);
			$this->db->set('sort_order', $data['sort_order']);
			$this->db->set('status', (int)$data['status']);
			$this->db->insert('language');
			//$this->cache->delete('language');

			$language_id = $this->db->insert_id();
							
			// Category
			$query = $this->db->query("SELECT * FROM category_description WHERE language_id = '" . (int)$this->session->userdata('config_admin_language_id') . "'");
			foreach ($query->result_array() as $category)
			{
				$this->db->set('category_id', (int)$category['category_id']);
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('name', $category['name']);
				$this->db->set('meta_description', $category['meta_description']);
				$this->db->set('description', $category['description']);

				$this->db->insert('category_description');
			}
			//$this->cache->delete('category');
			
			// Information
			$query = $this->db->query("SELECT * FROM information_description WHERE language_id = '" . (int)$this->session->userdata('config_admin_language_id') . "'");
			foreach ($query->result_array() as $information)
			{
				$this->db->set('information_id', (int)$information['information_id']);
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('title', $information['title']);
				$this->db->set('description', $information['description']);
				
				$this->db->insert('information_description');
			}		
			//$this->cache->delete('information');
	
			// Product
			$query = $this->db->query("SELECT * FROM product_description WHERE language_id = '" . (int)$this->session->userdata('config_admin_language_id') . "'");
			foreach ($query->result_array() as $product)
			{
				$this->db->set('product_id', (int)$product['product_id']);
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('name', $product['name']);
				$this->db->set('meta_description', $product['meta_description']);
				$this->db->set('description', $product['description']);
				
				$this->db->insert('product_description');
			}
			//$this->cache->delete('product');
			
			// Product Tag 
			$query = $this->db->query("SELECT * FROM product_tag WHERE language_id = '" . (int)$this->session->userdata('config_admin_language_id') . "'");
			foreach ($query->result_array() as $product_tag)
			{
				$this->db->set('product_id', (int)$product_tag['product_id']);
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('tag', $product_tag['tag']);
				
				$this->db->insert('product_tag');
			}
			//$this->cache->delete('product_tag');
			
			// Stock Status
			$query = $this->db->query("SELECT * FROM stock_status WHERE language_id = '" . (int)$this->session->userdata('config_admin_language_id') . "'");
			foreach ($query->result_array() as $stock_status)
			{
				$this->db->set('stock_status_id', (int)$stock_status['stock_status_id']);
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('name', $stock_status['name']);
				
				$this->db->insert('stock_status');
			}
			//$this->cache->delete('stock_status');

			if ($this->db->trans_status() === FALSE)
			{
			    $this->db->trans_rollback();
			    return false;
			}
			else
			{
			    $this->db->trans_commit();
			    return true;
			}
		}
	    catch(Exception $e)
        {
        	$this->db->trans_rollback();
            return false;
        }
	}
	
	public function editLanguage($language_id, $data)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->set('name', $data['name']);
	        $this->db->set('code', $data['code']);
	        $this->db->set('locale', $data['locale']);
	        $this->db->set('directory', $data['directory']);
	        $this->db->set('filename', $data['filename']);
	        $this->db->set('image', $data['image']);
	        $this->db->set('status', $data['status']);
	        $this->db->set('sort_order', $data['sort_order']);
	        $this->db->where('language_id', $language_id);
	
	        $this->db->update('language');
        
		 	//$this->cache->delete('language');
		    if ($this->db->trans_status() === FALSE)
			{
			    $this->db->trans_rollback();
			    return false;
			}
			else
			{
			    $this->db->trans_commit();
			    return true;
			}
		}
	    catch(Exception $e)
        {
        	$this->db->trans_rollback();
            return false;
        }
	}
	
	public function deleteLanguage($language_id)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->query("DELETE FROM language WHERE language_id = '" . (int)$language_id . "'");
			//$this->cache->delete('language');
			
			$this->db->query("DELETE FROM category_description WHERE language_id = '" . (int)$language_id . "'");
			//$this->cache->delete('category');
			
			$this->db->query("DELETE FROM information_description WHERE language_id = '" . (int)$language_id . "'");
			//$this->cache->delete('information');
			
			$this->db->query("DELETE FROM product_description WHERE language_id = '" . (int)$language_id . "'");
			$this->db->query("DELETE FROM product_tag WHERE language_id = '" . (int)$language_id . "'");
			//$this->cache->delete('product');
									
			$this->db->query("DELETE FROM stock_status WHERE language_id = '" . (int)$language_id . "'");
			//$this->cache->delete('stock_status');
			
		 	if ($this->db->trans_status() === FALSE)
			{
			    $this->db->trans_rollback();
			    return false;
			}
			else
			{
			    $this->db->trans_commit();
			    return true;
			}
		}
	    catch(Exception $e)
        {
        	$this->db->trans_rollback();
            return false;
        }
	}

 	public function getLanguage($language_id)
    {   
    	$where['language_id'] = $language_id;
        $query = $this->db->get_where('language', $where);
        return $query->row_array();
    }

    public function getLanguages($data = array())
    {
		if ($data) {
			$sql = "SELECT * FROM language";
	
			$sort_data = array(
				'name',
				'code',
				'sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY sort_order, name";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}					

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
	
			return $query->rows;
		} else {
			//$language_data = $this->cache->get('language');
			$language_data = null;
			if (!$language_data) {
				$language_data = array();
				
				$query = $this->db->query("SELECT * FROM language ORDER BY sort_order, name");
	
    			foreach ($query->result_array() as $result) {
      				$language_data[$result['code']] = array(
        				'language_id' => $result['language_id'],
        				'name'        => $result['name'],
        				'code'        => $result['code'],
						'locale'      => $result['locale'],
						'image'       => $result['image'],
						'directory'   => $result['directory'],
						'filename'    => $result['filename'],
						'sort_order'  => $result['sort_order'],
						'status'      => $result['status']
      				);
    			}	
				//$this->cache->set('language', $language_data);
			}
		
			return $language_data;
		}
	}

    public function getLanguagesBySearch($search='', $limit='', $offset='', $sort='', $direction='')
    {
        $where['status'] = 1;
        if ($search != null)
        {
            $where = array_merge($where, $search);
            if ($sort != null && $direction != null)
            {
                $this->db->order_by($sort, $direction);
            }
            $query = $this->db->get_where('language', $where, $limit, $offset);
        }
        else
        {
            if ($limit != null && $offset != null)
            {
                if ($sort != null && $direction != null)
                {
                    $this->db->order_by($sort, $direction);
                }
                $query = $this->db->get_where('language', $where, $limit, $offset);
            }
            else
            {
                if ($sort != null && $direction != null)
                {
                    $this->db->order_by($sort, $direction);
                }
                $query = $this->db->get_where('language', $where);
            }
        }
        return $query->result_array();
    }
	
	public function getTotalLanguages()
	{
      	$query = $this->db->count_all('language');
		return $query;
	}
	
    public function getLanguageIdByCode($code)
    {
        $where = array();
        $where['status'] = 1;
        $where['code']   = $code;
        $this->db->select('language_id');
        $query = $this->db->get_where('language', $where);
        return $query->row_array();
    }
}
?>