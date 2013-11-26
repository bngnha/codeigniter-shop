<?php
class Country_Model extends CI_Model
{
	public function addCountry($data)
	{
		try
        {
			foreach ($data as $field=>$value)
	        {
	            $this->db->set($field, $value);
	        }
	        $this->db->insert('country');
			//$this->cache->delete('country');
			return $this->db->insert_id();
        }
        catch(Exception $e)
        {
        	$result = false;
        }
	}
	
	public function editCountry($country_id, $data)
	{
		try
        {
			foreach ($data as $field=>$value)
	        {
	            $this->db->set($field, $value);
	        }
	        $this->db->where('country_id', $country_id);
		    $this->db->update('country');
			//$this->cache->delete('country');
			return true;
		}
        catch(Exception $e)
        {
        	$result = false;
        }
	}
	
	public function deleteCountry($country_id)
	{
		try
        {
			$this->db->where('country_id', $country_id);
			$this->db->delete('country'); 
			//$this->cache->delete('country');
			return true;
        }
        catch(Exception $e)
        {
        	$result = false;
        }
	}
	
	public function getCountry($country_id)
	{
		$where['country_id'] = $country_id;
        $query = $this->db->get_where('country', $where);
        return $query->row_array();
	}
		
	public function getCountries($search='', $limit='', $offset='', $sort='', $direction='')
	{
		$where['status'] = 1;
		if ($search != null)
		{
			$where = array_merge($where, $search);
			if ($sort != null && $direction != null)
			{
				$this->db->order_by($sort, $direction);
			}
			$query = $this->db->get_where('country', $where, $limit, $offset);
		}
		else
		{
			if ($limit != null && $offset != null)
			{
				if ($sort != null && $direction != null)
				{
					$this->db->order_by($sort, $direction);
				}
				$query = $this->db->get_where('country', $where, $limit, $offset);
			}
			else
			{
				if ($sort != null && $direction != null)
				{
					$this->db->order_by($sort, $direction);
				}
				$query = $this->db->get_where('country', $where);
			}
		}
		return $query->result_array();
	}
	
	public function getTotalCountries()
	{
		$query = $this->db->count_all('country');
		return $query;
	}	
}
?>