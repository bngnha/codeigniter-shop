<?php
class User_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get($whereusername = '')
	{
		if ($whereusername != '')
		{
			$this->db->where('username', $whereusername);
			$this->db->where('del_flg', '0');
			$query = $this->db->get_where('users');
			return $query;
		}
		else
		{
			$query = $this->db->get('users');
			return $query;
		}
	}

	public function insert($user, $pass)
	{
		$result = false;
		if (isset($user) && isset($pass))
		{
			try
			{
				$this->db->set('username',$user);
				$this->db->set('password',$pass);
	
				$this->db->insert('users');
				return $this->db->insert_id();
			}
			catch(Exception $e)
			{
				$result = false;
			}
		}
		return $result;
	}

    public function addUser($data)
    {
    	try
        {
	    	foreach ($data as $field=>$value)
	    	{
	    		if ($field == 'password')
	    		{
	    		     $this->db->set($field, md5($value));	
	    		}
	    		else
	    		{
	    		     $this->db->set($field, $value);
	    		}
	    	}
	    	$this->db->set('date_added', 'NOW()', false);
	    	$this->db->set('date_modified', 'NOW()', false);
	    	$this->db->insert('users');
        }
        catch(Exception $e)
        {
        	$result = false;
        }
    }

    public function editUser($user_id, $data) 
    {
    	try
        {
	        foreach ($data as $field=>$value)
	        {
	        	if (!empty($value) || $value != '') {
		        	if ($field == 'password')
		        	{
		                $this->db->set($field, md5($value));   
		            }
		            else
		            {
		                $this->db->set($field, $value);
		            }
	        	}
	        }
	        $this->db->set('date_modified', 'NOW()', false);
            $this->db->set('user_id', $user_id);
	        $this->db->where('user_id', $user_id);
	        $this->db->update('users');
        }
        catch(Exception $e)
        {
            $result = false;
        }
    }

	public function getTotalUsers()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `users` WHERE del_flg = '0' ");
      	$row = $query->row_array();
		return $row['total'];
		
	}

    public function getUser($user_id)
    {   
    	$where['user_id'] = $user_id;
    	$where['del_flg'] = '0';
        $query = $this->db->get_where('users', $where);
        return $query->row_array();
    }

	public function getUsers($search='', $limit='', $offset='', $sort='', $direction='')
	{
		$where = array();
		$where['secret'] = 0;
		$where['del_flg'] = 0;
		$where = array_merge($where, $search);
		
		$this->db->order_by($sort, $direction);
		$query = $this->db->get_where('users', $where, $limit, $offset);
		return $query;
	}

    public function getUserByUsername($username)
    {
        $where['username'] = $username;
        $where['del_flg'] = '0';
        $query = $this->db->get_where('users', $where);
        return $query->row_array();
    }

    public function getUserByCode($code)
    {
    	$where['code'] = $code;
    	$where['del_flg'] = '0';
        $query = $this->db->get_where('users', $where);
        return $query->row_array();
    }

    public function deleteUser($user_id)
    {
        $this->db->query("
            UPDATE users SET del_flg = '1', date_modified = NOW(), 
            user_modified ='" .(int)$user_id . "' WHERE user_id = '" . (int)$user_id . "'");
    }
    
	public function editCode($email, $code)
	{
		$this->db->query("UPDATE `users` SET code = " . $this->db->escape($code) . " WHERE email = " . $this->db->escape($email). " AND del_flg = '0' ");
	}
	
	public function getTotalUsersByEmail($email)
	{
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `users` WHERE email = " . $this->db->escape($email) . " AND del_flg = '0' ");
      	$row = $query->row_array();
		return $row['total'];
	}
	
    public function editPassword($user_id, $password)
    {
        $this->db->query("UPDATE `user` SET password = " . $this->db->escape(md5($password)) . " WHERE user_id = '" . (int)$user_id . "' AND del_flg = '0'");
    }

	public function getTotalUsersByGroupId($user_group_id)
	{
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `users` WHERE user_group_id = '" . (int)$user_group_id . "' AND del_flg = '0'");
		$row = $query->row_array();
		return $row['total'];
	}
}