<?php
class Language_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function addLanguage($data) {
		$this->db->query("INSERT INTO language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', locale = '" . $this->db->escape($data['locale']) . "', directory = '" . $this->db->escape($data['directory']) . "', filename = '" . $this->db->escape($data['filename']) . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "'");
		
		$this->cache->delete('language');
		
		$language_id = $this->db->getLastId();

		// Attribute 
		$query = $this->db->query("SELECT * FROM attribute_description WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $attribute) {
			$this->db->query("INSERT INTO attribute_description SET attribute_id = '" . (int)$attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($attribute['name']) . "'");
		}

		$this->cache->delete('attribute');

		// Attribute Group
		$query = $this->db->query("SELECT * FROM attribute_group_description WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $attribute_group) {
			$this->db->query("INSERT INTO attribute_group_description SET attribute_group_id = '" . (int)$attribute_group['attribute_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($attribute['name']) . "'");
		}

		$this->cache->delete('attribute');
		
		// Banner
		$query = $this->db->query("SELECT * FROM banner_image_description WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $banner_image) {
			$this->db->query("INSERT INTO banner_image_description SET banner_image_id = '" . (int)$banner_image['banner_image_id'] . "', banner_id = '" . (int)$banner_image['banner_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($banner_image['title']) . "'");
		}

		$this->cache->delete('attribute');
						
		// Category
		$query = $this->db->query("SELECT * FROM category_description WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $category) {
			$this->db->query("INSERT INTO category_description SET category_id = '" . (int)$category['category_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($category['name']) . "', meta_description= '" . $this->db->escape($category['meta_description']) . "', description = '" . $this->db->escape($category['description']) . "'");
		}

		$this->cache->delete('category');
		
		// Download
		$query = $this->db->query("SELECT * FROM download_description WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $download) {
			$this->db->query("INSERT INTO download_description SET download_id = '" . (int)$download['download_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($download['name']) . "'");
		}
				
		// Information
		$query = $this->db->query("SELECT * FROM information_description WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $information) {
			$this->db->query("INSERT INTO information_description SET information_id = '" . (int)$information['information_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($information['title']) . "', description = '" . $this->db->escape($information['description']) . "'");
		}		

		$this->cache->delete('information');

		// Length
		$query = $this->db->query("SELECT * FROM length_class_description WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $length) {
			$this->db->query("INSERT INTO length_class_description SET length_class_id = '" . (int)$length['length_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($length['title']) . "', unit = '" . $this->db->escape($length['unit']) . "'");
		}	
		
		$this->cache->delete('length_class');

		// Option 
		$query = $this->db->query("SELECT * FROM option_description WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $option) {
			$this->db->query("INSERT INTO option_description SET option_id = '" . (int)$option['option_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($option['name']) . "'");
		}

		// Option Value
		$query = $this->db->query("SELECT * FROM option_value_description WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $option_value) {
			$this->db->query("INSERT INTO option_value_description SET option_value_id = '" . (int)$option_value['option_value_id'] . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_value['option_id'] . "', name = '" . $this->db->escape($option_value['name']) . "'");
		}
				
		// Order Status
		$query = $this->db->query("SELECT * FROM order_status WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $order_status) {
			$this->db->query("INSERT INTO order_status SET order_status_id = '" . (int)$order_status['order_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($order_status['name']) . "'");
		}	
		
		$this->cache->delete('order_status');
		
		// Product
		$query = $this->db->query("SELECT * FROM product_description WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $product) {
			$this->db->query("INSERT INTO product_description SET product_id = '" . (int)$product['product_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($product['name']) . "', meta_description= '" . $this->db->escape($product['meta_description']) . "', description = '" . $this->db->escape($product['description']) . "'");
		}

		$this->cache->delete('product');
		
		// Product Attribute 
		$query = $this->db->query("SELECT * FROM product_attribute WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $product_attribute) {
			$this->db->query("INSERT INTO product_attribute SET product_id = '" . (int)$product_attribute['product_id'] . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" . $this->db->escape($product_attribute['text']) . "'");
		}

		// Product Tag 
		$query = $this->db->query("SELECT * FROM product_tag WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $product_tag) {
			$this->db->query("INSERT INTO product_tag SET product_id = '" . (int)$product_tag['product_id'] . "', language_id = '" . (int)$language_id . "', tag = '" . $this->db->escape($product_tag['tag']) . "'");
		}
		
		// Return Action 
		$query = $this->db->query("SELECT * FROM return_action WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $return_action) {
			$this->db->query("INSERT INTO return_action SET return_action_id = '" . (int)$return_action['return_action_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_action['name']) . "'");
		}

		// Return Reason 
		$query = $this->db->query("SELECT * FROM return_reason WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $return_reason) {
			$this->db->query("INSERT INTO return_reason SET return_reason_id = '" . (int)$return_reason['return_reason_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_reason['name']) . "'");
		}
		
		// Return Status
		$query = $this->db->query("SELECT * FROM return_status WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $return_status) {
			$this->db->query("INSERT INTO return_status SET return_status_id = '" . (int)$return_status['return_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_status['name']) . "'");
		}
						
		// Stock Status
		$query = $this->db->query("SELECT * FROM stock_status WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $stock_status) {
			$this->db->query("INSERT INTO stock_status SET stock_status_id = '" . (int)$stock_status['stock_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($stock_status['name']) . "'");
		}
		
		$this->cache->delete('stock_status');
		
		// Voucher Theme
		$query = $this->db->query("SELECT * FROM voucher_theme_description WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $voucher_theme) {
			$this->db->query("INSERT INTO voucher_theme_description SET voucher_theme_id = '" . (int)$voucher_theme['voucher_theme_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($voucher_theme['name']) . "'");
		}	
				
		// Weight Class
		$query = $this->db->query("SELECT * FROM weight_class_description WHERE language_id = '" . (int)$this->config->get('config_admin_language_id') . "'");

		foreach ($query->rows as $weight_class) {
			$this->db->query("INSERT INTO weight_class_description SET weight_class_id = '" . (int)$weight_class['weight_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($weight_class['title']) . "', unit = '" . $this->db->escape($weight_class['unit']) . "'");
		}	
		
		$this->cache->delete('weight_class');
	}
	
	public function editLanguage($language_id, $data) {
		$this->db->query("UPDATE language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', locale = '" . $this->db->escape($data['locale']) . "', directory = '" . $this->db->escape($data['directory']) . "', filename = '" . $this->db->escape($data['filename']) . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "' WHERE language_id = '" . (int)$language_id . "'");
				
		$this->cache->delete('language');
	}
	
	public function deleteLanguage($language_id) {
		$this->db->query("DELETE FROM language WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('language');
		
		$this->db->query("DELETE FROM attribute_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM attribute_group_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->db->query("DELETE FROM banner_image_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->db->query("DELETE FROM category_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('category');
		
		$this->db->query("DELETE FROM download_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM information_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('information');
		
		$this->db->query("DELETE FROM length_class_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('length_class');
		
		$this->db->query("DELETE FROM option_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM option_value_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->db->query("DELETE FROM order_status WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('order_status');
		
		$this->db->query("DELETE FROM product_attribute WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM product_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM product_tag WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('product');
		
		$this->db->query("DELETE FROM return_action WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('return_action');
		
		$this->db->query("DELETE FROM return_reason WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('return_reason');
				
		$this->db->query("DELETE FROM return_status WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('return_status');
								
		$this->db->query("DELETE FROM stock_status WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('stock_status');
		
		$this->db->query("DELETE FROM voucher_theme_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('voucher_theme');
				
		$this->db->query("DELETE FROM weight_class_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('weight_class');
	}

 	public function getLanguage($language_id)
    {   
    	$where['language_id'] = $language_id;
        $query = $this->db->get_where('language', $where);
        return $query->row_array();
    }

	public function getLanguages($search='', $limit='', $offset='', $sort='', $direction='')
	{
		$where = array();
		$where['status'] = 1;
		if(!isset($search)) {
		  $where = array_merge($where, $search);
		}
		if (!isset($limit)) {
			$limit = 10;
		}
		if (!isset($sort)) {
		  $this->db->order_by($sort, $direction);
		}
		$query = $this->db->get_where('language', $where, $limit, $offset);
		return $query;
	}
	
	public function getTotalLanguages()
	{
      	$query = $this->db->count_all('language');
		return $query;
	}
}
?>