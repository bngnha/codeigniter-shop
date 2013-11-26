<?php
class Information_Model extends CI_Model
{
	public function getInformation($information_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM information i LEFT JOIN information_description id ON (i.information_id = id.information_id) WHERE i.information_id = '" . (int)$information_id . "' AND id.language_id = '" . (int)$this->config->item('config_language_id') . "' AND i.status = '1'");
		return $query->row_array();
	}
	
	public function getInformations()
	{
		$query = $this->db->query("SELECT * FROM information i LEFT JOIN information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int)$this->config->item('config_language_id') . "' AND i.status = '1' AND i.sort_order <> '-1' ORDER BY i.sort_order, LCASE(id.title) ASC");
		return $query->row_array();
	}	
}
?>