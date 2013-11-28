<?php
class Review_Model extends CI_Model
{		
	public function addReview($product_id, $data)
	{
		$this->db->query("INSERT INTO review SET author = " . $this->db->escape($data['author']) . ", product_id = '" . (int)$product_id . "', text = " . $this->db->escape(strip_tags($data['text'])) . ", rating = '" . (int)$data['rating'] . "', date_added = NOW()");
	}
		
	public function getReviewsByProductId($product_id, $start = 0, $limit = 20)
	{
		$query = $this->db->query("SELECT r.review_id, r.author, r.rating, r.text, p.product_id, pd.name, p.price, p.image, r.date_added FROM review r LEFT JOIN product p ON (r.product_id = p.product_id) LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->session->userdata('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
		return $query->result_array();
	}
	
	public function getAverageRating($product_id)
	{
		$query = $this->db->query("SELECT AVG(rating) AS total FROM review WHERE status = '1' AND product_id = '" . (int)$product_id . "' GROUP BY product_id");
		$row = $query->row_array();
		if (isset($row['total']))
		{
			return (int)$row['total'];
		}
		else
		{
			return 0;
		}
	}	
	
	public function getTotalReviews()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM review r LEFT JOIN product p ON (r.product_id = p.product_id) WHERE p.date_available <= NOW() AND p.status = '1' AND r.status = '1'");
		$row = $query->row_array();
		return $row['total'];
	}

	public function getTotalReviewsByProductId($product_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM review r LEFT JOIN product p ON (r.product_id = p.product_id) LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->session->userdata('config_language_id') . "'");
		$row = $query->row_array();
		return $row['total'];
	}
}
?>