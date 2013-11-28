<?php
class Product_Model extends CI_Model
{
	public function updateViewed($product_id)
	{
		$this->db->query("UPDATE product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}

	public function getProduct($product_id)
	{
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM product_special ps WHERE ps.product_id = p.product_id AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT ss.name FROM stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->session->userdata('config_language_id') . "') AS stock_status, (SELECT AVG(rating) AS total FROM review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->session->userdata('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW()");
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			$row['rating'] = (int)$row['rating'];
			return $row;
		}
		else
		{
			return false;
		}
	}

	public function getProducts($data = array())
	{
		//$cache = md5(http_build_query($data));
		
		$product_data = array(); //$this->cache->get('product.' . (int)$this->config->item('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache);

		if (!$product_data)
		{
			$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) "; 
			if (!empty($data['filter_tag']))
			{
				$sql .= " LEFT JOIN product_tag pt ON (p.product_id = pt.product_id)";			
			}
						
			if (!empty($data['filter_category_id']))
			{
				$sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";			
			}
			
			$sql .= " WHERE pd.language_id = '" . (int)$this->session->userdata('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() "; 
			
			if (!empty($data['filter_name']) || !empty($data['filter_tag']))
			{
				$sql .= " AND (";
											
				if (!empty($data['filter_name']))
				{
					$implode = array();
					$words = explode(' ', $data['filter_name']);
					
					foreach ($words as $word)
					{
						if (!empty($data['filter_description']))
						{
							$implode[] = "LCASE(pd.name) LIKE '%" . utf8_strtolower($word) . "%' OR LCASE(pd.description) LIKE '%" . utf8_strtolower($word) . "%'";
						}
						else
						{
							$implode[] = "LCASE(pd.name) LIKE '%" . utf8_strtolower($word) . "%'";
						}				
					}
					
					if ($implode)
					{
						$sql .= " " . implode(" OR ", $implode) . "";
					}
				}

				if (!empty($data['filter_name']) && !empty($data['filter_tag']))
				{
					$sql .= " OR ";
				}

				if (!empty($data['filter_tag']))
				{
					$implode = array();
					$words = explode(' ', $data['filter_tag']);

					foreach ($words as $word)
					{
						$implode[] = "LCASE(pt.tag) LIKE '%" . utf8_strtolower($data['filter_tag']) . "%' AND pt.language_id = '" . (int)$this->session->userdata('config_language_id') . "'";
					}

					if ($implode)
					{
						$sql .= " " . implode(" OR ", $implode) . "";
					}
				}

				$sql .= ")";
			}
			
			if (!empty($data['filter_category_id']))
			{
				if (!empty($data['filter_sub_category']))
				{
					$implode_data = array();
					$implode_data[] = "p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
					
					$this->load->model('category_model');
					
					$categories = $this->category_model->getCategoriesByParentId($data['filter_category_id']);

					foreach ($categories as $category_id)
					{
						$implode_data[] = "p2c.category_id = '" . (int)$category_id . "'";
					}

					$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
				}
				else
				{
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
			}		

			if (!empty($data['filter_manufacturer_id']))
			{
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
			}

			$sql .= " GROUP BY p.product_id";

			$sort_data = array(
				'pd.name',
				'p.model',
				'p.quantity',
				'p.price',
				'rating',
				'p.sort_order',
				'p.date_added'
			);	

			if (isset($data['sort']) && in_array($data['sort'], $sort_data))
			{
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model')
				{
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				}
				else
				{
					$sql .= " ORDER BY " . $data['sort'];
				}
			}
			else
			{
				$sql .= " ORDER BY p.sort_order";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC'))
			{
				$sql .= " DESC";
			}
			else
			{
				$sql .= " ASC";
			}
		
			if (isset($data['start']) || isset($data['limit']))
			{
				if ($data['start'] < 0)
				{
					$data['start'] = 0;
				}				
	
				if ($data['limit'] < 1)
				{
					$data['limit'] = 20;
				}	

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$product_data = array();
			$query = $this->db->query($sql);
		
			foreach ($query->result_array() as $result)
			{
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			//$this->cache->set('product.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache, $product_data);
		}
		return $product_data;
	}
	
	public function getProductSpecials($data = array())
	{
		if ($this->customer->isLogged())
		{
			$customer_group_id = $this->customer->getCustomerGroupId();
		}
		else
		{
			$customer_group_id = $this->config->item('config_customer_group_id');
		}	

		$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM product_special ps LEFT JOIN product p ON (ps.product_id = p.product_id) LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data))
		{
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model')
			{
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			}
			else
			{
				$sql .= " ORDER BY " . $data['sort'];
			}
		}
		else
		{
			$sql .= " ORDER BY p.sort_order";	
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC'))
		{
			$sql .= " DESC";
		}
		else
		{
			$sql .= " ASC";
		}
	
		if (isset($data['start']) || isset($data['limit']))
		{
			if ($data['start'] < 0)
			{
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1)
			{
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();
		
		$query = $this->db->query($sql);

		foreach ($query->rows as $result)
		{ 		
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}
		
	public function getLatestProducts($limit)
	{
		$product_data = array(); //$this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$limit);

		if (!$product_data)
		{ 
			$query = $this->db->query("SELECT p.product_id FROM product p WHERE p.status = '1' AND p.date_available <= NOW() ORDER BY p.date_added DESC LIMIT " . (int)$limit);
			foreach ($query->result_array() as $result) 
			{
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			//$this->cache->set('product.latest.' . (int)$this->config->item('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$limit, $product_data);
		}
		return $product_data;
	}
	
	public function getPopularProducts($limit)
	{
		$product_data = array();
		$query = $this->db->query("SELECT p.product_id FROM product p WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed, p.date_added DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result)
		{
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getBestSellerProducts($limit)
	{
		$product_data = array(); //$this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$limit);

		if (!$product_data)
		{ 
			$product_data = array();
			$query = $this->db->query("SELECT op.product_id, COUNT(*) AS total FROM order_product op LEFT JOIN product` p ON (op.product_id = p.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result)
			{ 		
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			//$this->cache->set('product.bestseller.' . (int)$this->config->item('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$limit, $product_data);
		}
		
		return $product_data;
	}
	
	public function getProductImages($product_id)
	{
		$query = $this->db->query("SELECT * FROM product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");
		return $query->result_array();
	}
	
	public function getProductRelated($product_id)
	{
		$product_data = array();

		$query = $this->db->query("SELECT * FROM product_related pr LEFT JOIN product p ON (pr.related_id = p.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW()");
		
		foreach ($query->result_array() as $result)
		{ 
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}
		return $product_data;
	}
		
	public function getProductTags($product_id)
	{
		$query = $this->db->query("SELECT * FROM product_tag WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$this->session->userdata('config_language_id') . "'");
		return $query->rows;
	}
	
	public function getCategories($product_id)
	{
		$query = $this->db->query("SELECT * FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
		return $query->result_array();
	}	
		
	public function getTotalProducts($data = array())
	{
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) ";

		if (!empty($data['filter_category_id']))
		{
			$sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";			
		}
		
		if (!empty($data['filter_tag']))
		{
			$sql .= " LEFT JOIN product_tag pt ON (p.product_id = pt.product_id)";			
		}
					
		$sql .= " WHERE pd.language_id = '" . (int)$this->session->userdata('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() ";
		
		if (!empty($data['filter_name']) || !empty($data['filter_tag']))
		{
			$sql .= " AND (";
								
			if (!empty($data['filter_name']))
			{
				$implode = array();
				
				$words = explode(' ', $data['filter_name']);
				foreach ($words as $word)
				{
					if (!empty($data['filter_description']))
					{
						$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					}
					else
					{
						$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					}				
				}
				
				if ($implode)
				{
					$sql .= " " . implode(" OR ", $implode) . "";
				}
			}
			
			if (!empty($data['filter_name']) 
			&& !empty($data['filter_tag']))
			{
				$sql .= " OR ";
			}
			
			if (!empty($data['filter_tag']))
			{
				$implode = array();
				$words = explode(' ', $data['filter_tag']);
				
				foreach ($words as $word)
				{
					$implode[] = "LCASE(pt.tag) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%' AND pt.language_id = '" . (int)$this->session->userdata('config_language_id') . "'";
				}
				
				if ($implode)
				{
					$sql .= " " . implode(" OR ", $implode) . "";
				}
			}

			$sql .= ")";
		}
		
		if (!empty($data['filter_category_id']))
		{
			if (!empty($data['filter_sub_category']))
			{
				$implode_data = array();
				$implode_data[] = "p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				
				$this->load->model('catalog/category_model');
				$categories = $this->category_model->getCategoriesByParentId($data['filter_category_id']);
					
				foreach ($categories as $category_id)
				{
					$implode_data[] = "p2c.category_id = '" . (int)$category_id . "'";
				}
							
				$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
			}
			else
			{
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}
		}		
		
		if (!empty($data['filter_manufacturer_id']))
		{
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		
		$query = $this->db->query($sql);
		$row = $query->row_array();
		return $row['total'];
	}
			
	public function getTotalProductSpecials()
	{
		if ($this->customer->isLogged())
		{
			$customer_group_id = $this->customer->getCustomerGroupId();
		}
		else
		{
			$customer_group_id = $this->config->item('config_customer_group_id');
		}		

		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM product_special ps LEFT JOIN product p ON (ps.product_id = p.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

		$row = $query->row_array();
		if (isset($row['total']))
		{
			return $row['total'];
		}
		else
		{
			return 0;	
		}
	}	
}
?>