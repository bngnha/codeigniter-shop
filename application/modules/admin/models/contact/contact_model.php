<?php
class Contact_Model extends CI_Model
{

	public function getContact($contact_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM contact WHERE contact_id = '" . (int)$contact_id . "'");
		return $query->row_array();
	}

	public function getContacts($data)
	{
		if (!empty($data))
		{
			$this->db->select('*');
			$this->db->from('contact');
				
			if (isset($data['sort']) && isset($data['order']))
			{
				$this->db->order_by($data['sort'], $data['order']);
			}
			else
			{
				$this->db->order_by('date_added');
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
			$query = $this->db->query("SELECT * FROM contact order by date_added desc");
		}
		return $query->result_array();
	}

	public function getTotalContacts()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM contact");
		$row = $query->row_array();
		return $row['total'];
	}
	public function deleteContact($faq_id)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->query("DELETE FROM contact WHERE contact_id = '" . (int)$faq_id . "'");
				
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
	public function updateContact($faq_id, $data)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->set('status', (int)$data['status']);
			$this->db->set('date_modified', 'NOW()', false);
			$this->db->where('contact_id', (int)$faq_id);
				
			$this->db->update('contact');
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
}
?>
