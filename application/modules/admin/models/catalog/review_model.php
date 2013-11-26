<?php
class Review_Model extends CI_Model
{
	public function addReview($data)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->set('author', $data['author']);
			$this->db->set('product_id', $data['product_id']);
			$this->db->set('text', strip_tags($data['text']));
			$this->db->set('rating', (int)$data['rating']);
			$this->db->set('status', (int)$data['status']);
			$this->db->set('date_added', 'NOW()', false);
			$this->db->set('date_modified', 'NOW()', false);

			$this->db->insert('review');
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
	
	public function editReview($review_id, $data)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->set('author', $data['author']);
			$this->db->set('product_id', $data['product_id']);
			$this->db->set('text', strip_tags($data['text']));
			$this->db->set('rating', (int)$data['rating']);
			$this->db->set('status', (int)$data['status']);
			$this->db->set('date_modified', 'NOW()', false);
			$this->db->where('review_id', (int)$review_id);

			$this->db->update('review');
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
	
	public function deleteReview($review_id)
	{
		$this->db->trans_begin();
		try
		{
			$this->db->query("DELETE FROM review WHERE review_id = '" . (int)$review_id . "'");
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
	
	public function getReview($review_id)
	{
		$query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = '" . (int)$this->session->userdata('config_admin_language_id') . "') AS product FROM review r WHERE r.review_id = '" . (int)$review_id . "'");
		return $query->row_array();
	}

	public function getReviews($data = array())
	{
		$sql = "SELECT r.review_id, pd.name, r.author, r.rating, r.status, r.date_added FROM review r LEFT JOIN product_description pd ON (r.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->session->userdata('config_admin_language_id') . "'";																																					  
		
		$sort_data = array(
			'pd.name',
			'r.author',
			'r.rating',
			'r.status',
			'r.date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY r.date_added";	
		}
			
		if (isset($data['order']) && ($data['order'] == 'desc')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}																																							  
																																							  
		$query = $this->db->query($sql);																																				
		return $query->result_array();	
	}
	
	public function getTotalReviews()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM review");
		$row = $query->row_array();
		return $row['total'];
	}
	
	public function getTotalReviewsAwaitingApproval()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM review WHERE status = '0'");
		$row = $query->row_array();
		return $row['total'];
	}	
}
?>