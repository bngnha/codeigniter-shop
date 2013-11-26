<?php
class Information_Model extends CI_Model
{
	public function addInformation($data)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->query("INSERT INTO information SET sort_order = '" . (int)$this->request->post['sort_order'] . "', status = '" . (int)$data['status'] . "'");
	
			$information_id = $this->db->getLastId(); 
				
			foreach ($data['information_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO information_description SET information_id = '" . (int)$information_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
			}
			
			if (isset($data['information_store'])) {
				foreach ($data['information_store'] as $store_id) {
					$this->db->query("INSERT INTO information_to_store SET information_id = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "'");
				}
			}
	
			if (isset($data['information_layout'])) {
				foreach ($data['information_layout'] as $store_id => $layout) {
					if ($layout) {
						$this->db->query("INSERT INTO information_to_layout SET information_id = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
					}
				}
			}
					
			if ($data['keyword']) {
				$this->db->query("INSERT INTO url_alias SET query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			}
		
			//$this->cache->delete('information');
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
	
	public function editInformation($information_id, $data)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->set('sort_order', (int)$data['sort_order']);
			$this->db->set('status', (int)$data['status']);
			$this->db->where('information_id', (int)$information_id);
			$this->db->update('information');

			$this->db->query("DELETE FROM information_description WHERE information_id = '" . (int)$information_id . "'");
			foreach ($data['information_description'] as $language_id => $value)
			{
				$this->db->set('information_id', (int)$information_id);
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('title', $value['title']);
				$this->db->set('description', $value['description']);
								
				$this->db->insert('information_description');
			}
					
			$this->db->query("DELETE FROM url_alias WHERE query = 'information_id=" . (int)$information_id. "'");
			if ($data['keyword'])
			{
				$this->db->set('query', 'information_id=' . (int)$information_id);
				$this->db->set('keyword', $data['keyword']);
				$this->db->insert('url_alias');
			}
		
			//$this->cache->delete('information');
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
	
	public function deleteInformation($information_id)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->query("DELETE FROM information WHERE information_id = '" . (int)$information_id . "'");
			$this->db->query("DELETE FROM information_description WHERE information_id = '" . (int)$information_id . "'");
			$this->db->query("DELETE FROM information_to_store WHERE information_id = '" . (int)$information_id . "'");
			$this->db->query("DELETE FROM information_to_layout WHERE information_id = '" . (int)$information_id . "'");
			$this->db->query("DELETE FROM url_alias WHERE query = 'information_id=" . (int)$information_id . "'");
	
			//$this->cache->delete('information');
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

	public function getInformation($information_id)
	{
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM url_alias WHERE query = 'information_id=" . (int)$information_id . "') AS keyword FROM information WHERE information_id = '" . (int)$information_id . "'");
		return $query->row_array();
	}
		
	public function getInformations($data = array())
	{
		if ($data) {
			$sql = "SELECT * FROM information i LEFT JOIN information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int)$this->session->userdata('config_admin_language_id') . "'";
		
			$sort_data = array(
				'id.title',
				'i.sort_order'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY id.title";	
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
			
			return $query->result_array();
		} else {
			$information_data = '';//$this->cache->get('information.' . (int)$this->config->get('config_admin_language_id'));
		
			if (!$information_data) {
				$query = $this->db->query("SELECT * FROM information i LEFT JOIN information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int)$this->session->userdata('config_admin_language_id') . "' ORDER BY id.title");
	
				$information_data = $query->result_array();
			
				//$this->cache->set('information.' . (int)$this->config->get('config_admin_language_id'), $information_data);
			}	
	
			return $information_data;			
		}
	}

	public function getInformationDescriptions($information_id)
	{
		$information_description_data = array();
		$query = $this->db->query("SELECT * FROM information_description WHERE information_id = '" . (int)$information_id . "'");

		foreach ($query->result_array() as $result)
		{
			$information_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'description' => $result['description']
			);
		}
		
		return $information_description_data;
	}

	public function getTotalInformations()
	{
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM information");
		$row = $query->row_array();
		return $row['total'];
	}	

	public function getTotalInformationsByLayoutId($layout_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM information_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
		$row = $query->row_array();
		return $row['total'];
	}	
}
?>