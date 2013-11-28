<?php 
class Stock_Model extends CI_Model
{
	public function addStockStatus($data)
	{
		try
        {
			foreach ($data['stock_status'] as $language_id => $value)
			{
				if (isset($stock_status_id))
				{
					$this->db->set('stock_status_id', $stock_status_id);
					$this->db->set('language_id', $language_id);
					$this->db->set('name', $value['name']);
					$this->db->insert('stock_status');
				}
				else
				{
					$this->db->set('language_id', $language_id);
					$this->db->set('name', $value['name']);
					$this->db->insert('stock_status');
					$stock_status_id = $this->db->insert_id();
				}
			}
			return $stock_status_id;
        }
        catch(Exception $e)
        {
        	$result = false;
        }
		//$this->cache->delete('stock_status');
	}

	public function editStockStatus($stock_status_id, $data)
	{
		try
        {
        	$this->db->where('stock_status_id', $stock_status_id);
            $this->db->delete('stock_status');

			foreach ($data['stock_status'] as $language_id => $value)
			{
			    if (isset($stock_status_id))
                {
                    $this->db->set('stock_status_id', $stock_status_id);
                    $this->db->set('language_id', $language_id);
                    $this->db->set('name', $value['name']);
                    $this->db->insert('stock_status');
                }
                else
                {
                    $this->db->set('language_id', $language_id);
                    $this->db->set('name', $value['name']);
                    $this->db->insert('stock_status');
                    $stock_status_id = $this->db->insert_id();
                }
			}
        }
        catch(Exception $e)
        {
        	$result = false;
        }	
		//$this->cache->delete('stock_status');
	}

	public function deleteStockStatus($stock_status_id)
	{
		try
        {
        	$this->db->where('stock_status_id', $stock_status_id);
            $this->db->delete('stock_status');
        }
        catch(Exception $e)
        {
        	$result = false;
        }
		//$this->cache->delete('stock_status');
	}

	public function getStockStatus($stock_status_id)
	{
		$where['stock_status_id'] = $stock_status_id;
		$where['language_id'] = $this->session->userdata('config_admin_language_id');
        $query = $this->db->get_where('stock_status', $where);
		return $query->row_array();
	}

	public function getStockStatuses($search='', $limit='', $offset='', $sort='', $direction='')
	{
		$where = array();
		$where['language_id'] = $this->session->userdata('config_admin_language_id');
		if ($search != null)
		{
			$where = array_merge($where, $search);
			if ($sort != null && $direction != null)
			{
				$this->db->order_by($sort, $direction);
			}
			$query = $this->db->get_where('stock_status', $where, $limit, $offset);
		}
		else
		{
			if ($limit != null && $offset != null)
			{
				if ($sort != null && $direction != null)
				{
					$this->db->order_by($sort, $direction);
				}
				$query = $this->db->get_where('stock_status', $where, $limit, $offset);
			}
			else
			{
				if ($sort != null && $direction != null)
				{
					$this->db->order_by($sort, $direction);
				}
				$query = $this->db->get_where('stock_status', $where);
			}
		}
		return $query->result_array();
	}

	public function getStockStatusDescriptions($stock_status_id)
	{
		$stock_status_data = array();
		$where['stock_status_id'] = $stock_status_id;
		$query = $this->db->get_where('stock_status', $where);

		foreach ($query->result_array() as $result)
		{
			$stock_status_data[$result['language_id']] = array('name' => $result['name']);
		}
		return $stock_status_data;
	}

	public function getTotalStockStatuses()
	{
		$query = $this->db->get_where('stock_status', array('language_id'=>(int)$this->session->userdata('config_admin_language_id')));
		return $query->num_rows();
	}
}
?>