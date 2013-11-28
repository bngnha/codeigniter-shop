<?php
class Currency_Model extends CI_Model
{
	public function addCurrency($data)
	{
		try
        {
			foreach ($data as $field=>$value)
	        {
	            $this->db->set($field, $value);
	        }
	        $this->db->set('date_modified', 'NOW()', false);
	        $this->db->insert('currency');
			//$this->cache->delete('currency');
        	return $this->db->insert_id();
        }
        catch(Exception $e)
        {
        	$result = false;
        }
	}
	
	public function editCurrency($currency_id, $data)
	{
		try
        {
			foreach ($data as $field=>$value)
	        {
	            $this->db->set($field, $value);
	        }
	        $this->db->set('date_modified', 'NOW()', false);
	        $this->db->where('currency_id', $currency_id);
		    $this->db->update('currency');
			//$this->cache->delete('currency');
			return true;
        }
        catch(Exception $e)
        {
        	$result = false;
        }
	}
	
	public function deleteCurrency($currency_id)
	{
		try
        {
			$this->db->where('currency_id', $currency_id);
			$this->db->delete('currency');
			//$this->cache->delete('currency');
			return true;
        }
        catch(Exception $e)
        {
        	$result = false;
        }
	}

	public function getCurrency($currency_id)
	{
		$where['currency_id'] = $currency_id;
        $query = $this->db->get_where('currency', $where);
        return $query->row_array();
	}
	
	public function getCurrencyByCode($currency)
	{
		$where['code'] = $currency;
        $query = $this->db->get_where('currency', $where);
        return $query;
	}
		
	public function getCurrencies($search='', $limit='', $offset='', $sort='', $direction='')
	{
        $where['status'] = 1;
		if ($search != null)
		{
			$where = array_merge($where, $search);
			if ($sort != null && $direction != null)
			{
				$this->db->order_by($sort, $direction);
			}
			$query = $this->db->get_where('currency', $where, $limit, $offset);
		}
		else
		{
			if ($limit != null && $offset != null)
			{
				if ($sort != null && $direction != null)
				{
					$this->db->order_by($sort, $direction);
				}
				$query = $this->db->get_where('currency', $where, $limit, $offset);
			}
			else
			{
				if ($sort != null && $direction != null)
				{
					$this->db->order_by($sort, $direction);
				}
				$query = $this->db->get_where('currency', $where);
			}
		}
		return $query->result_array();
	}	

	public function updateCurrencies()
	{
		if (extension_loaded('curl'))
		{
			$data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code != '" . $this->db->escape($this->config->get('config_currency')) . "' AND date_modified < '" .  $this->db->escape(date('Y-m-d H:i:s', strtotime('-1 day'))) . "'");

			foreach ($query->rows as $result) {
				$data[] = $this->config->get('config_currency') . $result['code'] . '=X';
			}	
			
			$curl = curl_init();
			
			curl_setopt($curl, CURLOPT_URL, 'http://download.finance.yahoo.com/d/quotes.csv?s=' . implode(',', $data) . '&f=sl1&e=.csv');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			
			$content = curl_exec($curl);
			
			curl_close($curl);
			
			$lines = explode("\n", trim($content));
				
			foreach ($lines as $line)
			{
				$currency = utf8_substr($line, 4, 3);
				$value = utf8_substr($line, 11, 6);
				if ((float)$value)
				{
					$this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '" . (float)$value . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($currency) . "'");
				}
			}
			
			$this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '1.00000', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($this->config->get('config_currency')) . "'");

			$this->cache->delete('currency');
		}
	}
	
	public function getTotalCurrencies()
	{
		$query = $this->db->count_all('currency');
        return $query;
	}
}
?>