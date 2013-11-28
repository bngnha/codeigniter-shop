<?php
class Faq_Model extends CI_Model
{
	public function addFaq($data)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->set('status', $data['status']);
			$this->db->set('sort_order', $data['sort_order']);
			$this->db->set('date_added', 'NOW()', false);
			$this->db->set('date_modified', 'NOW()', false);

			$this->db->insert('faq');
			$faq_id = $this->db->insert_id();

			foreach (@$data['faq_description'] as $language_id => $value)
			{
				$this->db->set('faq_id', (int)$faq_id);
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('title', $value['title']);
				if (isset($data['faq_keyword']))
				{
					foreach (@$data['faq_keyword'] as $key => $val)
					{
						if ($key == $language_id)
						{
							$this->db->set('seo_keyword', $val['keyword']);
							break;						
						}
					}
				}
				$this->db->set('description', $value['description']);
				
				$this->db->insert('faq_description');
			}
			
			//$this->cache->delete('faq');
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

	public function editFaq($faq_id, $data)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->set('status', (int)$data['status']);
			$this->db->set('sort_order', (int)$data['sort_order']);
			$this->db->set('date_modified', 'NOW()', false);
			$this->db->where('faq_id', (int)$faq_id);
			
			$this->db->update('faq');
			
			$this->db->query("DELETE FROM faq_description WHERE faq_id = '" . (int)$faq_id . "'");
			foreach (@$data['faq_description'] as $language_id => $value)
			{
				$this->db->set('faq_id', (int)$faq_id);
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('title', $value['title']);
				if (isset($data['faq_keyword']))
				{
					foreach (@$data['faq_keyword'] as $key => $val)
					{
						if ($key == $language_id)
						{
							$this->db->set('seo_keyword', $val['keyword']);
							break;						
						}
					}
				}
				$this->db->set('description', $value['description']);
				
				$this->db->insert('faq_description');
			}
			
			//$this->cache->delete('faq');
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

	public function deleteFaq($faq_id)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->query("DELETE FROM faq WHERE faq_id = '" . (int)$faq_id . "'");
			$this->db->query("DELETE FROM faq_description WHERE faq_id = '" . (int)$faq_id . "'");
			
			//$this->cache->delete('faq');
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

	public function getFaq($faq_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM faq WHERE faq_id = '" . (int)$faq_id . "'");
		return $query->row_array();
	}

	public function getFaqDescriptions($faq_id)
	{
		$faq_description_data = array();
		$query = $this->db->query("SELECT * FROM faq_description WHERE faq_id = '" . (int)$faq_id . "'");
		foreach ($query->result_array() as $result)
		{
			$faq_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'keyword'     => $result['seo_keyword'],
				'description' => $result['description']
			);
		}
		return $faq_description_data;
	}

	public function getFaqs($data)
	{
		if (!empty($data))
		{
			$this->db->select('*');
			$this->db->from('faq AS f');
			$this->db->join('faq_description AS fd', 'f.faq_id = fd.faq_id', 'left');
			$this->db->where('fd.language_id', (int)$this->session->userdata('config_admin_language_id'));
			
			
			if (isset($data['sort']) && isset($data['order']))
			{
				$this->db->order_by($data['sort'], $data['order']);
			}
			else
			{
				$this->db->order_by('fd.title');	
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
				$this->db->limit($data['limit'], $data['start']);
			}
			$query = $this->db->get();
		}
		else
		{
			$query = $this->db->query("SELECT * FROM faq f LEFT JOIN faq_description fd ON (f.faq_id = fd.faq_id) WHERE fd.language_id = '" . (int)$this->session->userdata('config_admin_language_id') . "' ORDER BY fd.title");			
		}
		return $query->result_array();
	}

	public function getTotalFaqs()
	{
     	$query = $this->db->query("SELECT COUNT(*) AS total FROM faq");
     	$row = $query->row_array();
		return $row['total'];
	}	
}
?>
