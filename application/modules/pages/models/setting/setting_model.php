<?php 
class Setting_Model extends CI_Model
{
	public function getSetting($group='', $key='')
	{
		$data = array();
		$where = array();
		if (isset($group) && $group != '')
		{
            $where['group'] = $group;
		}
		if (isset($key) && $key != '')
		{
			$where['key'] = $key;
		}
		$query = $this->db->get_where('setting', $where);
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $result)
			{
				if (!$result['serialized'])
				{
					$data[$result['key']] = $result['value'];
				}
				else
				{
					$data[$result['key']] = unserialize($result['value']);
				}
			}
		}
		return $data;
	}

	public function editSetting($group, $data)
	{
		try
        {
        	$where['group'] = $group;
			$this->db->delete('setting', $where);
	
			foreach ($data as $field => $value)
			{
				if (!is_array($value))
				{
					$this->db->set('group', $group);
                    $this->db->set('key', $field);
                    $this->db->set('value', $value);
                    $this->db->insert('setting');
				}
				else
				{
					$this->db->set('group', $group);
                    $this->db->set('key', $field);
                    $this->db->set('value', serialize($value));
                    $this->db->set('serialized', '1');
                    $this->db->insert('setting');
				}
			}
        }
        catch(Exception $e)
        {
            $result = false;
        }
	}
	
	public function deleteSetting($group, $store_id = 0)
	{
		$where['store_id']= $store_id;
		$where['group']   = $group;
		$this->db->delete('setting', $where);
	}
}
?>