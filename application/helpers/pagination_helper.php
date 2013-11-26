<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( !function_exists('create_pagination'))
{
	function create_pagination($config='')
	{
		$ci =& get_instance();
		$ci->load->library('pagination');
		$ci->load->language('admin/common/pagination');

		$cfg['first_link']		= '|&lt';
		$cfg['last_link']		= '&gt;|';

		$cfg['next_link'] 		= '&gt;';
  		$cfg['prev_link'] 		= '&lt;';

  		$cfg['cur_tag_open']	= '&nbsp;<b>';
		$cfg['cur_tag_close']	= '</b>';

		$cfg['full_tag_open'] 	= "<div class='links'>";
		$cfg['full_tag_close'] 	= "</div>";

		$cfg = array_merge($cfg, $config);
		$ci->pagination->initialize($cfg);
		
		$pagination = $ci->pagination->create_links();
		if ($pagination != '')
		{
			$showing_beging = (($ci->pagination->cur_page-1) * $ci->pagination->per_page) + 1;
			$showing_end = $ci->pagination->cur_page * $ci->pagination->per_page;
			if ($showing_end > $ci->pagination->total_rows)
			{
				$showing_end = $ci->pagination->total_rows;
			}
			$pagination .= '<div class="results">'.sprintf(lang('admin.pagination.showing'), $showing_beging, $showing_end, $ci->pagination->total_rows, ceil($ci->pagination->total_rows / $ci->pagination->per_page)).'</div>';
		}
		return $pagination;
	}
}
?>