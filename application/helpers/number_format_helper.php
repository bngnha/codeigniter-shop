<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( !function_exists('format_decimal'))
{
	function format_decimal($decimal_number='')
	{
		$result = $decimal_number;
		if ($decimal_number != '')
		{
			$arr_number = explode('.', $decimal_number);
			if(is_array($arr_number))
			{
				$integer_part = $arr_number[0];
				$decimal_number = $arr_number[1];
				if ($decimal_number < 0 || $decimal_number == 0)
				{
					$result = number_format($integer_part, 0, '', '.');
				} else {
					$result = number_format($integer_part, 0, '', '.') . ','. $decimal_number;
					
				}
			}
		}
		return $result;
	}
}