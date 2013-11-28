<?php
class News_Model extends CI_Model {
	public function addNews($data) {
		var_dump($data);
		$this->db->query("INSERT INTO " . DB_PREFIX . "news SET status = '" . (int)$data['status'] . "', date_added = now()");
		$news_id = $this->db->insert_id();
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "news SET image = '" . $this->db->escape_str($data['image']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}
		foreach ($data['news_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "news_description SET news_id = '" . (int)$news_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape_str($value['title']) . "', meta_description = '" . $this->db->escape_str($value['meta_description']) . "', description = '" . $this->db->escape_str($value['description']) . "'");
		}
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'news_id=" . (int)$news_id . "', keyword = '" . $this->db->escape_str($data['keyword']) . "'");
		}
		//		if (isset($data['news_store'])) {
		//			foreach ($data['news_store'] as $store_id) {
		//				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_store SET news_id = '" . (int)$news_id . "', store_id = '" . (int)$store_id . "'");
		//			}
		//		}
		//$this->cache->delete('news');
	}

	public function editNews($news_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "news SET status = '" . (int)$data['status'] . "' WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "news SET image = '" . $this->db->escape_str($data['image']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}
		foreach ($data['news_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "news_description SET news_id = '" . (int)$news_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape_str($value['title']) . "', meta_description = '" . $this->db->escape_str($value['meta_description']) . "', description = '" . $this->db->escape_str($value['description']) . "'");
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id. "'");
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'news_id=" . (int)$news_id . "', keyword = '" . $this->db->escape_str($data['keyword']) . "'");
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_store WHERE news_id = '" . (int)$news_id . "'");
		if (isset($data['news_store'])) {
			foreach ($data['news_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_store SET news_id = '" . (int)$news_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		//$this->cache->delete('news');
	}

	public function deleteNews($news_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "news WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_store WHERE news_id = '" . (int)$news_id . "'");
		//$this->cache->delete('news');
	}

	public function getNewsStory($news_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id . "') AS keyword FROM " . DB_PREFIX . "news WHERE news_id = '" . (int)$news_id . "'");
		return  $query->row_array();
	}

	public function getNewsDescriptions($news_id) {
		$news_description_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");
		foreach ($query->result_array() as $result) {
			$news_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}
		return $news_description_data;
	}

	public function getNewsStores($news_id) {
		$newspage_store_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_store WHERE news_id = '" . (int)$news_id . "'");
		foreach ($query->result_array() as $result) {
			$newspage_store_data[] = $result['store_id'];
		}
		return $newspage_store_data;
	}

	public function getNews($lang_id,$data = array()) {
		$category_data = array();
		$sql ="SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE nd.language_id = '" . $lang_id . "' ORDER BY n.date_added";
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

	public function getTotalNews() {
		$this->checkNews();
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news");
		return $query->row()->total;
	}

	public function checkNews() {
		$create_news = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "news` (`news_id` int(11) NOT NULL auto_increment, `status` int(1) NOT NULL default '0', `image` varchar(255) collate utf8_bin default NULL, `image_size` int(1) NOT NULL default '0', `date_added` datetime default NULL, PRIMARY KEY  (`news_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($create_news);
		$create_news_descriptions = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "news_description` (`news_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` varchar(64) collate utf8_bin NOT NULL default '', `meta_description` varchar(255) collate utf8_bin NOT NULL, `description` text collate utf8_bin NOT NULL, PRIMARY KEY  (`news_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($create_news_descriptions);
		$create_news_to_store = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "news_to_store` (`news_id` int(11) NOT NULL, `store_id` int(11) NOT NULL, PRIMARY KEY  (`news_id`, `store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($create_news_to_store);
	}
}
?>