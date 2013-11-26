<?php
class Manufacturer_Model extends CI_Model
{
	public function addManufacturer($data)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->set('name', $data['name']);
			$this->db->set('sort_order', (int)$data['sort_order']);
			$this->db->insert('manufacturer');
			$manufacturer_id = $this->db->getLastId();
	
			if (isset($data['image']))
			{
				$this->db->set('image', $data['image']);
				$this->db->where('manufacturer_id', (int)$manufacturer_id);
				$this->db->update('manufacturer');
			}
					
			if ($data['keyword'])
			{
				$this->db->set('query', 'manufacturer_id=' . (int)$manufacturer_id);
				$this->db->set('keyword', $data['keyword']);
				$this->db->insert('url_alias');
			}
			
			//$this->cache->delete('manufacturer');
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
	
	public function editManufacturer($manufacturer_id, $data)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->set('name', $data['name']);
			$this->db->set('sort_order', (int)$data['sort_order']);
			$this->db->where('manufacturer_id', (int)$manufacturer_id);
			$this->db->update('manufacturer');
	
			if (isset($data['image']))
			{
				$this->db->set('image', $data['image']);
				$this->db->where('manufacturer_id', (int)$manufacturer_id);
				$this->db->update('manufacturer');
			}
				
			$this->db->query("DELETE FROM url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id. "'");
			if ($data['keyword'])
			{
				$this->db->set('query', 'manufacturer_id=' . (int)$manufacturer_id);
				$this->db->set('keyword', $data['keyword']);
				$this->db->insert('url_alias');
			}
		
			//$this->cache->delete('manufacturer');
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
	
	public function deleteManufacturer($manufacturer_id)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->query("DELETE FROM manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			$this->db->query("DELETE FROM url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");
				
			//$this->cache->delete('manufacturer');
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
	
	public function getManufacturer($manufacturer_id)
	{
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "') AS keyword FROM manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		return $query->row_array();
	}
	
	public function getManufacturers($data = array())
	{
		if ($data) {
			$sql = "SELECT * FROM manufacturer";
			
			$sort_data = array(
				'name',
				'sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY name";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'desc')) {
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
		}
		else
		{
			$manufacturer_data = "";//$this->cache->get('manufacturer');
		
			if (!$manufacturer_data) {
				$query = $this->db->query("SELECT * FROM manufacturer ORDER BY name");
	
				$manufacturer_data = $query->result_array();
			
				//$this->cache->set('manufacturer', $manufacturer_data);
			}
		 
			return $manufacturer_data;
		}
	}
	
	public function getTotalManufacturersByImageId($image_id)
	{
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM manufacturer WHERE image_id = '" . (int)$image_id . "'");
		$row = $query->row_array();
		return $row['total'];
	}

	public function getTotalManufacturers()
	{
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM manufacturer");
      	$row = $query->row_array();
		return $row['total'];
	}	
}
?>