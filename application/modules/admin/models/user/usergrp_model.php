<?php
class Usergrp_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function addUserGroup($data)
	{
		$this->db->query("INSERT INTO users_group SET name = " . $this->db->escape($data['name']) . ", permission = " . (isset($data['permission']) ? $this->db->escape(serialize($data['permission'])) : $this->db->escape('')));
	}
	
	public function editUserGroup($user_group_id, $data)
	{
		$this->db->query("UPDATE users_group SET name = " . $this->db->escape($data['name']) . ", permission = " . (isset($data['permission']) ? $this->db->escape(serialize($data['permission'])) : $this->db->escape('')) . " WHERE user_group_id = " . (int)$user_group_id);
	}
	
	public function deleteUserGroup($user_group_id)
	{
		$this->db->query("DELETE FROM users_group WHERE user_group_id = " . (int)$user_group_id);
	}

	public function addPermission($user_id, $type, $page)
	{
		$user_query = $this->db->query("SELECT DISTINCT user_group_id FROM users WHERE user_id = '" . (int)$user_id . "'");
		
		if ($user_query->num_rows() > 0)
		{
			$row_user = $user_query->row_array();
			$user_group_query = $this->db->query("SELECT DISTINCT * FROM users_group WHERE user_group_id = '" . (int)$row_user['user_group_id'] . "'");
		
			if ($user_group_query->num_rows() > 0)
			{
				$row_grp = $user_group_query->row_array();
				$data = unserialize($row_grp['permission']);
		
				$data[$type][] = $page;
				$this->db->query("UPDATE users_group SET permission = '" . serialize($data) . "' WHERE user_group_id = '" . (int)$row_user['user_group_id'] . "'");
			}
		}
	}

	public function getUserGroup($user_group_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM users_group WHERE user_group_id = '" . (int)$user_group_id . "'");
		$row_grp = $query->row_array();
		$user_group = array(
			'name'       => $row_grp['name'],
			'permission' => unserialize($row_grp['permission'])
		);

		return $user_group;
	}
	
	public function getUserGroups($data = array())
	{
		$sql = "SELECT * FROM users_group";
		$sql .= " ORDER BY name";	

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
	
	public function getTotalUserGroups()
	{
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM users_group");
      	$row = $query->row_array();
		return $row['total'];
	}
}
?>