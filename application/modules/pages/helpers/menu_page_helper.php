<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( !function_exists('menu_page'))
{
	function menu_page(&$data='')
	{
		$ci =& get_instance();

		$ci->load->model('catalog/category_model');
		$ci->load->model('catalog/product_model');

		$menu['categories'] = array();			
		$categories = $ci->category_model->getCategories(0);

		foreach ($categories as $category)
		{
			if ($category['top'])
			{
				$children_data = array();
				$children = $ci->category_model->getCategories($category['category_id']);
				
				foreach ($children as $child)
				{
					$data_search = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true	
					);		

					$product_total = $ci->product_model->getTotalProducts($data_search);
									
					$children_data[] = array(
						'name'  => $child['name'] . ' (' . $product_total . ')',
						'href'  => base_url() . 'pages/category/detail/category_id/' . $category['category_id'] . '/child_id/' . $child['category_id']
					);				
				}

				// Level 1
				$menu['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => base_url(). 'pages/category/detail/category_id/' . $category['category_id']
				);
			}
		}
		$data = array_merge($data, $menu);
	}
}