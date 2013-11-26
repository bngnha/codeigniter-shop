<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class MY_Lang extends MX_Lang {
    public function __construct() {
        parent::__construct();
    }
    public function switch_to($idiom) {
    	$CI =& get_instance();

        // Get language id from language table and set to session
        $where = array();
        $where['status'] = 1;
        $where['code']   = $idiom;
        $CI->db->select('language_id');
        $query = $CI->db->get_where('language', $where);
        $result = $query->row_array();

        if (isset($result['language_id']))
        {
            $CI->session->set_userdata('config_admin_language_id', $result['language_id']);
        }

        // Switch to coresponding language directory 
    	if ($idiom === FALSE || $idiom === 'en') {
    		$idiom = 'english';
    	} else if ($idiom === 'vi') {
    		$idiom = 'vietnamese';
    	}
        
        if(is_string($idiom) && $idiom != $CI->config->item('language')) {
            $CI->config->set_item('language', $idiom);
            $loaded = $this->is_loaded;
            $this->is_loaded = array();
            foreach($loaded as $file) {
                $this->load(str_replace('_lang.php','',$file));
            }
        }
    }
}
/* End of file MY_Lang.php */
/* Location: ./application/core/MY_Lang.php */