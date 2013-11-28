<?php
class Manufacturer_Model extends CI_Model
{
	public function getManufacturer($manufacturer_id)
	{
		$query = $this->db->query("SELECT * FROM manufacturer m WHERE m.manufacturer_id = '" . (int)$manufacturer_id . "'");
		return $query->row_array();	
	}
	
	public function getManufacturers($data = array())
	{
		if ($data)
		{
			$sql = "SELECT * FROM manufacturer m ";
			$sort_data = array
			(
				'name',
				'sort_order'
			);	
			if (isset($data['sort']) && in_array($data['sort'], $sort_data))
			{
				$sql .= " ORDER BY " . $data['sort'];	
			}
			else
			{
				$sql .= " ORDER BY name";	
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
			//$manufacturer_data = $this->cache->get('manufacturer.' . (int)$this->config->get('config_store_id'));
		
			if (!$manufacturer_data)
			{
				$query = $this->db->query("SELECT * FROM manufacturer m ORDER BY name");
				$manufacturer_data = $query->result_array();

				//$this->cache->set('manufacturer.' . (int)$this->config->get('config_store_id'), $manufacturer_data);
			}
			return $manufacturer_data;
		}	
	} 
}
?>