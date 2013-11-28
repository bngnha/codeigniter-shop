<?php
class Image_Model extends CI_Model
{
	public function resize($filename, $width, $height)
	{
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename))
		{
			return;
		} 

		$info = pathinfo($filename);
		$extension = $info['extension'];
		
		$old_image = $filename;
		$new_image = 'cache/' . utf8_substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;
		
		if (!file_exists(DIR_IMAGE . $new_image) 
		|| (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image)))
		{
			$path = '';
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));

			foreach ($directories as $directory)
			{
				$path = $path . '/' . $directory;
				if (!file_exists(DIR_IMAGE . $path))
				{
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}
			$config['image_library'] = 'gd2';
			$config['source_image'] = DIR_IMAGE . $old_image;
			$config['new_image'] = DIR_IMAGE . $new_image;
			$config['maintain_ratio'] = TRUE;
			$config['width'] = $width;
			$config['height'] = $height;
			$this->load->library('image_lib');
			$this->image_lib->initialize($config);  
			if ( !$this->image_lib->resize())
			{
			    echo $this->image_lib->display_errors();
			}
			$this->image_lib->clear();
		}
		return HTTP_IMAGE . $new_image;
	}
}
?>