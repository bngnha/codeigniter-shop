<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class MY_Session extends CI_Session
{
    public function __construct()
    {
        parent::__construct();
    }
    public function userdata($item)
    {
        if ($this->sess_use_database === FALSE)
        {
            parent::userdata($item);
        } 
        else
        {
            if (is_array($this->userdata) && count($this->userdata)> 0)
            {
            	if (array_key_exists($item, $this->userdata))
            	{
            		return $this->userdata[$item];
            	}
            	else
            	{
            		return FALSE;
            	}
            }
            else
            {
            	return FALSE;
            }
        }
    }

    private function _userdbdata()
    {
    	$this->CI->db->where('session_id', $this->userdata['session_id']);
    	$query = $this->CI->db->get($this->sess_table_name);
    	if ($query->num_rows() >0)
    	{
    	   return $this->_unserialize($query->row(0)->user_data);
    	}
        else
        {
            return FALSE;
        }
    }
}
/* End of file MY_Session.php */