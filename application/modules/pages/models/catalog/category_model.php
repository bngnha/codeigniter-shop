<?php
class Category_Model extends CI_Model 
{
	public function getCategory($category_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->session->userdata('config_language_id') . "' AND c.status = '1'");
		return $query->row_array();
	}
	
	public function getCategories($parent_id = 0)
	{
		$query = $this->db->query("SELECT * FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->session->userdata('config_language_id') . "' AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
		return $query->result_array();
	}

	public function getCategoriesByParentId($category_id)
	{
		$category_data = array();
		$category_query = $this->db->query("SELECT category_id FROM category WHERE parent_id = '" . (int)$category_id . "'");
		foreach ($category_query->row_array() as $category)
		{
			$category_data[] = $category['category_id'];
			$children = $this->getCategoriesByParentId($category['category_id']);
			if ($children)
			{
				$category_data = array_merge($children, $category_data);
			}			
		}
		return $category_data;
	}

	public function getTotalCategoriesByCategoryId($parent_id = 0)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM category c WHERE c.parent_id = '" . (int)$parent_id . "' AND c.status = '1'");
		$row = $query->row_array();
		return $row['total'];
	}
}
?>