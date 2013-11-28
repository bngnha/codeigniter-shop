<?php
class Zone_Model extends CI_Model
{
	public function addZone($data)
	{
	    try
        {
            foreach ($data as $field=>$value)
            {
                $this->db->set($field, $value);
            }
            $this->db->insert('zone');
            //$this->cache->delete('country');
            return $this->db->insert_id();
        }
        catch(Exception $e)
        {
            $result = false;
        }
	}

	public function editZone($zone_id, $data)
	{
	    try
        {
            foreach ($data as $field=>$value)
            {
                $this->db->set($field, $value);
            }
            $this->db->where('zone_id', $zone_id);
            $this->db->update('zone');
            //$this->cache->delete('country');
            return true;
        }
        catch(Exception $e)
        {
            $result = false;
        }
	}

	public function deleteZone($zone_id)
	{
	    try
        {
            $this->db->where('zone_id', $zone_id);
            $this->db->delete('zone'); 
            //$this->cache->delete('country');
            return true;
        }
        catch(Exception $e)
        {
            $result = false;
        }
	}

	public function getZone($zone_id)
	{
		$where['zone_id'] = $zone_id;
		$this->db->distinct('*');
        $query = $this->db->get_where('zone', $where);
        return $query->row_array();
	}

	public function getZones($search='', $limit='', $offset='', $sort='', $direction='')
	{
	    $where = array();
        if ($search != null)
        {
            $where = array_merge($where, $search);
            if ($sort != null && $direction != null)
            {
                $this->db->order_by($sort, $direction);
            }
            $selects = array(
                        'z.zone_id',
                        'z.code AS code',
                        'z.name AS name',
                        'c.name AS country'
                        );
            $this->db->select($selects);
	        $this->db->from('zone AS z');
	        $this->db->join('country c','z.country_id = c.country_id','left');
	        $this->db->where($where);
	        $this->db->limit($limit, $offset);
	        $query = $this->db->get();
        }
        else
        {
            if ($limit != null && $offset != null)
            {
                if ($sort != null && $direction != null)
                {
                    $this->db->order_by($sort, $direction);
                }
                $selects = array(
                        'z.zone_id',
                        'z.code AS code',
                        'z.name AS name',
                        'c.name AS country'
                        );
                $this->db->select($selects);
                $this->db->from('zone AS z');
                $this->db->join('country c','z.country_id = c.country_id','left');
                $this->db->where($where);
                $this->db->limit($limit, $offset);
                $query = $this->db->get();
            }
            else
            {
                if ($sort != null && $direction != null)
                {
                    $this->db->order_by($sort, $direction);
                }
                $selects = array(
                        'z.zone_id',
                        'z.code AS code',
                        'z.name AS name',
                        'c.name AS country'
                        );
                $this->db->select($selects);
                $this->db->from('zone AS z');
                $this->db->join('country c','z.country_id = c.country_id','left');
                $this->db->where($where);
                $query = $this->db->get();
            }
        }
        return $query->result_array();
	}

	public function getTotalZones()
	{
		$query = $this->db->count_all('zone');
        return $query;
	}

	public function getZonesByCountryId($country_id)
	{
		//$zone_data = $this->cache->get('zone.' . (int)$country_id);
		//if (!$zone_data)
		//{
		    $selects = array('zone_id',
		                     'name');
		    $this->db->select($selects);
			$where['country_id'] = $country_id;
			$this->db->order_by('name');
			$query = $this->db->get_where('zone', $where);
			//$this->cache->set('zone.' . (int)$country_id, $zone_data);
		//}
		return $query->result_array();
	}

	public function getTotalZonesByCountryId($country_id)
	{
		$query = $this->db->query("SELECT count(*) AS total FROM zone WHERE country_id = '" . (int)$country_id . "'");
		$row = $query->row();
		return $row->total;
	}
}
?>