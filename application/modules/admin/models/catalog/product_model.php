<?php
class Product_Model extends CI_Model
{
	public function addProduct($data)
	{
		$this->db->trans_begin();
		try
		{
			// insert product
			$this->db->set('model',$data['model']);
			$this->db->set('location', $data['location']);
			$this->db->set('quantity', (int)$data['quantity']);
			$this->db->set('minimum', (int)$data['minimum']);
			$this->db->set('stock_status_id', (int)$data['stock_status_id']);
			$this->db->set('date_available', $data['date_available']);
			$this->db->set('manufacturer_id', (int)$data['manufacturer_id']);
			$this->db->set('price', (float)$data['price']);
			$this->db->set('size', $data['size']);
			$this->db->set('color', $data['color']);
			$this->db->set('status', (int)$data['status']);
			$this->db->set('sort_order', (int)$data['sort_order']);
			$this->db->set('date_added', 'NOW()', false);
			$this->db->insert('product');
			$product_id = $this->db->insert_id();
			
			// update image product
			if (isset($data['image']))
            {
			    $this->db->set('image', $data['image']);
			    $this->db->where('product_id', (int)$product_id);
			    $this->db->update('product');
            }
			
            // insert desciption
            foreach ($data['product_description'] as $language_id => $value)
            {
            	$this->db->set('product_id', (int)$product_id);
            	$this->db->set('language_id', (int)$language_id);
            	$this->db->set('name', $value['name']);
            	$this->db->set('meta_keyword', $value['meta_keyword']);
            	$this->db->set('meta_description', $value['meta_description']);
            	$this->db->set('description', $value['description']);
            	$this->db->set('meta_keyword', $value['meta_keyword']);
            	$this->db->insert('product_description');
            }
            
            // insert special
			if (isset($data['product_special']))
	        {
	            foreach ($data['product_special'] as $product_special)
	            {
	            	$this->db->set('product_id', (int)$product_id);
	            	$this->db->set('priority', (int)$product_special['priority']);
	            	$this->db->set('price', (float)$product_special['price']);
	            	$this->db->set('date_start', $product_special['date_start']);
	            	$this->db->set('date_end', $product_special['date_end']);
	            	$this->db->insert('product_special');
	            }
	        }
	        
	        // insert image infor
			if (isset($data['product_image'])) 
	        {
	            foreach ($data['product_image'] as $product_image)
	            {
	            	$this->db->set('product_id', (int)$product_id);
	            	$this->db->set('image', $product_image['image']);
	            	$this->db->set('sort_order', (int)$product_image['sort_order']);
	            	$this->db->insert('product_image');
	            }
	        }
	        
	        // insert category of product information
			if (isset($data['product_category']))
	        {
	            foreach ($data['product_category'] as $category_id)
	            {
	            	$this->db->set('product_id', (int)$product_id);
	            	$this->db->set('category_id', (int)$category_id );
	            	$this->db->insert('product_to_category');
	            }
	        }
            
	        // insert related product
			if (isset($data['product_related']))
	        {
	            foreach ($data['product_related'] as $related_id)
	            {
	            	$this->db->where('product_id', (int)$product_id);
                    $this->db->where('related_id', (int)$related_id);
                    $this->db->delete('product_related');
            
                    $this->db->set('product_id', (int)$product_id);
                    $this->db->set('related_id', (int)$related_id);
                    $this->db->insert('product_related');
                    
                    $this->db->where('product_id', (int)$related_id);
                    $this->db->where('related_id', (int)$product_id);
                    $this->db->delete('product_related');
            
                    $this->db->set('product_id', (int)$related_id);
                    $this->db->set('related_id', (int)$product_id);
                    $this->db->insert('product_related');
	            }
	        }
	        
	        // insert tag
			foreach ($data['product_tag'] as $language_id => $value)
	        {
	            if ($value)
	            {
	                $tags = explode(',', $value);
	                foreach ($tags as $tag)
	                {
	                	$this->db->set('product_id', (int)$product_id);
	                	$this->db->set('language_id', (int)$language_id);
	                	$this->db->set('tag', trim($tag));
	                	$this->db->insert('product_tag');
	                }
	            }
	        }
	        
	        // insert keyword
			if ($data['keyword'])
	        {
	        	$this->db->set('query', 'product_id='.(int)$product_id);
	        	$this->db->set('keyword', $data['keyword']);
	        	$this->db->insert('url_alias');
	        }
	        
	        //$this->cache->delete('product');
	        
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
	
	public function editProduct($product_id, $data)
	{
		$this->db->trans_begin();
        try
        {
        	// update product
        	$this->db->set('model', $data['model']);
        	$this->db->set('quantity', (int)$data['quantity']);
        	$this->db->set('minimum', (int)$data['minimum']);
        	$this->db->set('stock_status_id', (int)$data['stock_status_id']);
        	$this->db->set('date_available', $data['date_available']);
        	$this->db->set('manufacturer_id', (int)$data['manufacturer_id']);
        	$this->db->set('price', (float)$data['price']);
        	$this->db->set('size', $data['size']);
			$this->db->set('color', $data['color']);
            // update image of product
            if (isset($data['image']))
            {
                $this->db->set('image', $data['image']);
            }
        	$this->db->set('sort_order', (int)$data['sort_order']);
        	$this->db->set('date_modified', 'NOW()', false);
        	$this->db->where('product_id', (int)$product_id );
        	$this->db->update('product');
			
			// update product description
			$this->db->query("DELETE FROM product_description WHERE product_id = '" . (int)$product_id . "'");
			foreach ($data['product_description'] as $language_id => $value)
			{
				$this->db->set('product_id', (int)$product_id );
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('name', $value['name']);
				$this->db->set('meta_keyword', $value['meta_keyword']);
				$this->db->set('meta_description', $value['meta_description']);
				$this->db->set('description', $value['description']);
				$this->db->insert('product_description');
			}
	
			// update special product
			$this->db->query("DELETE FROM product_special WHERE product_id = '" . (int)$product_id . "'");
			if (isset($data['product_special']))
			{
				foreach ($data['product_special'] as $product_special)
				{
					$this->db->set('product_id', (int)$product_id);
					$this->db->set('priority', (int)$product_special['priority']);
					$this->db->set('price', (float)$product_special['price']);
					$this->db->set('date_start', $product_special['date_start']);
					$this->db->set('date_end', $product_special['date_end']);
					$this->db->insert('product_special');
				}
			}
	
			// update image
			$this->db->query("DELETE FROM product_image WHERE product_id = '" . (int)$product_id . "'");
			if (isset($data['product_image']))
			{
				foreach ($data['product_image'] as $product_image)
				{
					$this->db->set('product_id', (int)$product_id);
					$this->db->set('image', $product_image['image']);
                    $this->db->set('sort_order', (int)$product_image['sort_order']);
                    $this->db->insert('product_image');
				}
			}
	
			// update category of product
			$this->db->query("DELETE FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
			if (isset($data['product_category']))
			{
				foreach ($data['product_category'] as $category_id)
				{
					$this->db->set('product_id', (int)$product_id);
					$this->db->set('product_id', (int)$product_id);
					$this->db->set('category_id', (int)$category_id);
					$this->db->insert('product_to_category');
				}		
			}
	
			// udpate related
			$this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_related WHERE related_id = '" . (int)$product_id . "'");
			if (isset($data['product_related']))
			{
				foreach ($data['product_related'] as $related_id)
				{
					$this->db->where('product_id', (int)$product_id);
                    $this->db->where('related_id', (int)$related_id);
                    $this->db->delete('product_related');
					
                    $this->db->set('product_id', (int)$product_id);
                    $this->db->set('related_id', (int)$related_id);
                    $this->db->insert('product_related');
                    
                    $this->db->where('product_id', (int)$related_id);
                    $this->db->where('related_id', (int)$product_id);
                    $this->db->delete('product_related');
            
                    $this->db->set('product_id', (int)$related_id);
                    $this->db->set('related_id', (int)$product_id);
                    $this->db->insert('product_related');
				}
			}
	
			// update tag
			$this->db->query("DELETE FROM product_tag WHERE product_id = '" . (int)$product_id. "'");
			foreach ($data['product_tag'] as $language_id => $value)
			{
				if ($value)
				{
					$tags = explode(',', $value);
					foreach ($tags as $tag)
					{
						$this->db->set('product_id', (int)$product_id);
                        $this->db->set('language_id', (int)$language_id);
                        $this->db->set('tag', trim($tag));
                        $this->db->insert('product_tag');
					}
				}
			}

			// update keyword
			$this->db->query("DELETE FROM url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
			if ($data['keyword'])
			{
				$this->db->set('query', 'product_id='.(int)$product_id);
                $this->db->set('keyword', $data['keyword']);
                $this->db->insert('url_alias');
			}

	        //$this->cache->delete('product');
            
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
	
	public function copyProduct($product_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->session->userdata('config_admin_language_id') . "'");
		
		if ($query->num_rows)
		{
			$data = array();
			$data = $query->row_array();
			
			$data['keyword'] = '';
			$data['status'] = '0';
						
			$data = array_merge($data, array('product_description' => $this->getProductDescriptions($product_id)));			
			$data = array_merge($data, array('product_image' => $this->getProductImages($product_id)));
			
			$data['product_image'] = array();
			
			$results = $this->getProductImages($product_id);
			foreach ($results as $result)
			{
				$data['product_image'][] = $result['image'];
			}

			$data = array_merge($data, array('product_related' => $this->getProductRelated($product_id)));
			$data = array_merge($data, array('product_special' => $this->getProductSpecials($product_id)));
			$data = array_merge($data, array('product_tag' => $this->getProductTags($product_id)));
			$data = array_merge($data, array('product_category' => $this->getProductCategories($product_id)));
			
			$this->addProduct($data);
		}
	}
	
	public function deleteProduct($product_id)
	{
		$this->db->trans_begin();
        try
        {
			$this->db->query("DELETE FROM product WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_description WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_image WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_related WHERE related_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_special WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_tag WHERE product_id='" . (int)$product_id. "'");
			$this->db->query("DELETE FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM review WHERE product_id = '" . (int)$product_id . "'");
			
			$this->db->query("DELETE FROM url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
			
    		//$this->cache->delete('product');
            
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
	
	public function getProduct($product_id)
	{
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->session->userdata('config_admin_language_id') . "'");
		return $query->row_array();
	}
	
	public function getProducts($data = array())
	{
		if ($data)
		{
			$sql = "SELECT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";
			
			if (!empty($data['filter_category_id']))
			{
				$sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";			
			}
					
			$sql .= " WHERE pd.language_id = " . $this->db->escape((int)$this->session->userdata('config_admin_language_id')); 
			if (!empty($data['filter_name']))
			{
				$sql .= " AND LCASE(pd.name) LIKE " . $this->db->escape(utf8_strtolower($data['filter_name'])."%");
			}

			if (!empty($data['filter_model']))
			{
				$sql .= " AND LCASE(p.model) LIKE " . $this->db->escape(utf8_strtolower($data['filter_model'])."%");
			}
			
			if (!empty($data['filter_price']))
			{
				$sql .= " AND p.price LIKE " . $this->db->escape($data['filter_price']."%");
			}
			
			if (isset($data['filter_quantity']) && !is_null($data['filter_quantity']))
			{
				$sql .= " AND p.quantity = " . $this->db->escape($data['filter_quantity']);
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status']))
			{
				$sql .= " AND p.status = " . $this->db->escape((int)$data['filter_status']);
			}
					
			if (!empty($data['filter_category_id']))
			{
				if (!empty($data['filter_sub_category']))
				{
					$implode_data = array();
					$implode_data[] = "category_id = " . $this->db->escape((int)$data['filter_category_id']);

					$this->load->model('catalog/category_model');
					
					$categories = $this->category_model->getCategories($data['filter_category_id']);
					foreach ($categories as $category)
					{
						$implode_data[] = "p2c.category_id = " . $this->db->escape((int)$category['category_id']);
					}
					
					$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
				}
				else
				{
					$sql .= " AND p2c.category_id = " . $this->db->escape((int)$data['filter_category_id']);
				}
			}
			$sql .= " GROUP BY p.product_id";
						
			$sort_data = array(
				'pd.name',
				'p.model',
				'p.price',
				'p.quantity',
				'p.status',
				'p.sort_order'
			);	
			if (isset($data['sort']) && in_array($data['sort'], $sort_data))
			{
				$sql .= " ORDER BY " . $data['sort'];	
			}
			else
			{
				$sql .= " ORDER BY pd.name";	
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
			$query = $this->db->query($sql);
			return $query->result_array();
		}
		else
		{
			$product_data = ""; //$this->cache->get('product.' . (int)$this->config->item('config_admin_language_id'));
		
			if (!$product_data)
			{
				$query = $this->db->query("SELECT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->session->userdata('config_admin_language_id') . "' ORDER BY pd.name ASC");
				$product_data = $query->row_array();
			
				//$this->cache->set('product.' . (int)$this->config->item('config_admin_language_id'), $product_data);
			}	
			return $product_data;
		}
	}
	
	public function getProductsByCategoryId($category_id)
	{
		$query = $this->db->query("SELECT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_admin_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");
        return $query->result_array();
	} 
	
	public function getProductDescriptions($product_id)
	{
		$product_description_data = array();
		$query = $this->db->query("SELECT * FROM product_description WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->result_array() as $result)
		{
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description']
			);
		}
		return $product_description_data;
	}

	public function getProductImages($product_id)
	{
		$product_image_data = array();
		$query = $this->db->query("SELECT * FROM product_image WHERE product_id = '" . (int)$product_id . "'");
	    foreach ($query->result_array() as $key=>$result)
        {
            $product_image_data[] = array(
                'product_image_id' => $result['product_image_id'],
                'image'            => $result['image'],
                'sort_order'       => $result['sort_order']
            );
        }
		return $product_image_data;
	}

	public function getProductSpecials($product_id)
	{
		$product_special_data = array();
		$query = $this->db->query("SELECT * FROM product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");
	    foreach ($query->result_array() as $result)
        {
            $product_special_data[] = array(
                'product_special_id' => $result['product_special_id'],
                'priority'      => $result['priority'],
                'price'         => $result['price'],
                'date_start'    => $result['date_start'],
                'date_end'      => $result['date_end'],
            );
        }
		return $product_special_data;
	}

	public function getProductCategories($product_id)
	{
		$product_category_data = array();
		$query = $this->db->query("SELECT * FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->result_array() as $result)
		{
			$product_category_data[] = $result['category_id'];
		}
		return $product_category_data;
	}

	public function getProductRelated($product_id)
	{
		$product_related_data = array();
		$query = $this->db->query("SELECT * FROM product_related WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->result_array() as $result)
		{
			$product_related_data[] = $result['related_id'];
		}
		return $product_related_data;
	}
	
	public function getProductTags($product_id)
	{
		$product_tag_data = array();
		$query = $this->db->query("SELECT * FROM product_tag WHERE product_id = '" . (int)$product_id . "'");
		
		$tag_data = array();
		foreach ($query->result_array() as $result)
		{
			$tag_data[$result['language_id']][] = $result['tag'];
		}
		
		foreach ($tag_data as $language => $tags)
		{
			$product_tag_data[$language] = implode(',', $tags);
		}
		
		return $product_tag_data;
	}
	
	public function getTotalProducts($data = array())
	{
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";
		if (!empty($data['filter_category_id']))
		{
			$sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";			
		}
		 
		$sql .= " WHERE pd.language_id = ".$this->session->userdata('config_admin_language_id');
		 			
		if (!empty($data['filter_name']))
		{
			$sql .= " AND LCASE(pd.name) LIKE " . $this->db->escape(utf8_strtolower($data['filter_name']).'%');
		}

		if (!empty($data['filter_model']))
		{
			$sql .= " AND LCASE(p.model) LIKE " . $this->db->escape(utf8_strtolower($data['filter_model']).'%');
		}
		
		if (!empty($data['filter_price']))
		{
			$sql .= " AND p.price LIKE " . $this->db->escape($data['filter_price']);
		}
		
		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity']))
		{
			$sql .= " AND p.quantity = " . $this->db->escape($data['filter_quantity']);
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status']))
		{
			$sql .= " AND p.status = " . $this->db->escape((int)$data['filter_status']);
		}

		if (!empty($data['filter_category_id']))
		{
			if (!empty($data['filter_sub_category']))
			{
				$implode_data = array();
				
				$implode_data[] = "p2c.category_id = " . $this->db->escape((int)$data['filter_category_id']);
				$this->load->model('catalog/category_model');
				
				$categories = $this->category_model->getCategories($data['filter_category_id']);
				
				foreach ($categories as $category)
				{
					$implode_data[] = "p2c.category_id = " . $this->db->escape((int)$category['category_id']);
				}
				
				$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
			}
			else
			{
				$sql .= " AND p2c.category_id = " . $this->db->escape((int)$data['filter_category_id']);
			}
		}
		
		$query = $this->db->query($sql);
		$row = $query->row_array();
		return $row['total'];
	}	
		
	public function getTotalProductsByStockStatusId($stock_status_id)
	{
		$this->db->where('stock_status_id', $stock_status_id);
		$query = $this->db->count_all('product');
		return $query;
	}

	public function getTotalProductsByManufacturerId($manufacturer_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
        $row = $query->row();
        return $row->total;
	}
}
?>