<?php
class Category_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function addCategory($data)
	{
		$this->db->query("INSERT INTO category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '0', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");
	
		$category_id = $this->db->insert_id();
		
		if (isset($data['image']))
		{
			$this->db->query("UPDATE category SET image = " . $this->db->escape($data['image']) . " WHERE category_id = '" . (int)$category_id . "'");
		}
		
		foreach ($data['category_description'] as $language_id => $value)
		{
			$this->db->query("INSERT INTO category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = " . $this->db->escape($value['name']) . ", meta_keyword = " . $this->db->escape($value['meta_keyword']) . ", meta_description = " . $this->db->escape($value['meta_description']) . ", description = " . $this->db->escape($value['description']));
		}
								
		if ($data['keyword']) {
			$this->db->query("INSERT INTO url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = " . $this->db->escape($data['keyword']));
		}
		
		//$this->cache->delete('category');
	}
	
	public function editCategory($category_id, $data)
	{
		$this->db->query("UPDATE category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = " . (isset($data['column']) ? (int)$data['column'] : 0) . ", sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['image']))
		{
			$this->db->query("UPDATE category SET image = " . $this->db->escape($data['image']) . " WHERE category_id = '" . (int)$category_id . "'");
		}

		$this->db->query("DELETE FROM category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($data['category_description'] as $language_id => $value)
		{
			$this->db->query("INSERT INTO category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = " . $this->db->escape($value['name']) . ", meta_keyword = " . $this->db->escape($value['meta_keyword']) . ", meta_description = " . $this->db->escape($value['meta_description']) . ", description = " . $this->db->escape($value['description']));
		}

		$this->db->query("DELETE FROM url_alias WHERE query = 'category_id=" . (int)$category_id. "'");
		if ($data['keyword'])
		{
			$this->db->query("INSERT INTO url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = " . $this->db->escape($data['keyword']));
		}
		//$this->cache->delete('category');
	}
	
	public function deleteCategory($category_id)
	{
		$this->db->query("DELETE FROM category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM category_description WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM url_alias WHERE query = 'category_id=" . (int)$category_id . "'");
		
		$query = $this->db->query("SELECT category_id FROM category WHERE parent_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result)
		{
			$this->deleteCategory($result['category_id']);
		}

		//$this->cache->delete('category');
	} 

	public function getCategory($category_id)
	{
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword FROM category WHERE category_id = '" . (int)$category_id . "'");
		
		return $query->row_array();
	} 
	
	public function getCategories($parent_id = 0)
	{
		$category_data = array(); //$this->cache->get('category.' . (int)$this->config->get('config_language_id') . '.' . (int)$parent_id);
	
		if (!$category_data) {
			$category_data = array();
		
			$query = $this->db->query("SELECT * FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->session->userdata('config_admin_language_id'). "' ORDER BY c.sort_order, cd.name ASC");
		
			foreach ($query->result_array() as $result)
			{
				$category_data[] = array(
					'category_id' => $result['category_id'],
					'name'        => $this->getPath($result['category_id'], (int)$this->session->userdata('config_admin_language_id')),
					'status'  	  => $result['status'],
					'sort_order'  => $result['sort_order']
				);
			
				$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
			}	
	
			//$this->cache->set('category.' . (int)$this->config->get('config_language_id') . '.' . (int)$parent_id, $category_data);
		}
		
		return $category_data;
	}
	
	public function getPath($category_id)
	{
		$query = $this->db->query("SELECT name, parent_id FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->session->userdata('config_admin_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		$row = $query->row_array();
		if ($row['parent_id'])
		{
			return $this->getPath($row['parent_id'], (int)$this->session->userdata('config_admin_language_id')) . lang('text_separator') . $row['name'];
		}
		else
		{
			return $row['name'];
		}
	}
	
	public function getCategoryDescriptions($category_id)
	{
		$category_description_data = array();
		
		$query = $this->db->query("SELECT * FROM category_description WHERE category_id = '" . (int)$category_id . "'");
		
		foreach ($query->result_array() as $result)
		{
			$category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}
		
		return $category_description_data;
	}	

	public function getTotalCategories()
	{
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM category");
		$row = $query->row_array();
		return $row['total'];
	}	
		
	public function getTotalCategoriesByImageId($image_id)
	{
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM category WHERE image_id = '" . (int)$image_id . "'");
		$row = $query->row_array();
		return $row['total'];
	}
}
?>