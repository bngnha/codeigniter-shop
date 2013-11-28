<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Filemanager extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->error= array();

		if ($this->session->userdata('logged_in'))
		{
			$this->lang->switch_to($this->session->userdata('config_admin_language'));
			$this->lang->load('admin/common/filemanager');
			// File manager
			$this->data['title']            = lang('heading_title');
			$this->data['heading_title']    = lang('heading_title');
		}
		else
		{
			redirect('admin/login');
		}
	}
	public function index()
	{
		if ($this->input->server('HTTPS')
		&& (($this->input->server('HTTPS') == 'on')
		|| ($this->input->server('HTTPS') == '1')))
		{
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['entry_folder']   = lang('entry_folder');
		$this->data['entry_move']     = lang('entry_move');
		$this->data['entry_copy']     = lang('entry_copy');
		$this->data['entry_rename']   = lang('entry_rename');

		$this->data['button_folder']  = lang('button_folder');
		$this->data['button_delete']  = lang('button_delete');
		$this->data['button_move']    = lang('button_move');
		$this->data['button_copy']    = lang('button_copy');
		$this->data['button_rename']  = lang('button_rename');
		$this->data['button_upload']  = lang('button_upload');
		$this->data['button_refresh'] = lang('button_refresh');

		$this->data['error_select']   = lang('error_select');
		$this->data['error_directory']= lang('error_directory');

		$this->data['directory'] = HTTP_IMAGE;
		if ($this->input->get('field'))
		{
			$this->data['field'] =$this->input->get('field');
		}
		else
		{
			$this->data['field'] = '';
		}

		if ($this->input->get('CKEditorFuncNum'))
		{
			$this->data['fckeditor'] = $this->input->get('CKEditorFuncNum');
		}
		else
		{
			$this->data['fckeditor'] = false;
		}
		$this->load->view('template/common/filemanager', $this->data);
	}

	public function image()
	{
		$this->load->model('tool/image_model');
		if ($this->input->get('image'))
		{
			echo $this->image_model->resize(html_entity_decode($this->input->get('image'), ENT_QUOTES, 'UTF-8'), 100, 100);
		}
	}
	//liet ke cac folder trong folder cha
	public function directory()
	{
		$json = array();
		$directories = DIR_IMAGE . 'data/' ;
		if ($this->input->post('directory')){
			$directories =DIR_IMAGE . 'data/' . str_replace('../', '', $this->input->post('directory'));

		}
		$directories = glob(rtrim($directories) . '/*', GLOB_ONLYDIR);
		if (isset($directories))
		{
			$i = 0;
			foreach ($directories as $directory)
			{
				$json[$i]['data'] = basename($directory);
				$json[$i]['attributes']['directory'] = utf8_substr($directory, strlen(DIR_IMAGE . 'data/'));
					
				$children = glob(rtrim($directory, '/') . '/*', GLOB_ONLYDIR);
					
				if ($children)  {
					$json[$i]['children'] = ' ';
				}
					
				$i++;
			}
		}
		echo json_encode($json);
	}
	//liet ke cac file co trong folder
	public function files()
	{
		$json = array();
		$this->load->model('tool/image_model');

		if ($this->input->post('directory'))
		{
			$directory = DIR_IMAGE . 'data/' . str_replace('../', '', $this->input->post('directory'));
		}
		else
		{
			$directory = DIR_IMAGE . 'data/';
		}
		$allowed = array(
			'.jpg',
			'.jpeg',
			'.png',
			'.gif'
			);

			$files = glob(rtrim($directory, '/') . '/*');
			if ($files)
			{
				foreach ($files as $file)
				{
					if (is_file($file))
					{
						$ext = strrchr($file, '.');
					}
					else
					{
						$ext = '';
					}
					if (in_array(strtolower($ext), $allowed))
					{
						$size = filesize($file);
						$i = 0;
						$suffix = array(
						'B',
						'KB',
						'MB',
						'GB',
						'TB',
						'PB',
						'EB',
						'ZB',
						'YB'
						);
						while (($size / 1024) > 1)
						{
							$size = $size / 1024;
							$i++;
						}
						$json[] = array(
						'file'     => utf8_substr($file, strlen(DIR_IMAGE . 'data/')),
						'filename' => basename($file),
						'size'     => round(utf8_substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
						'thumb'    => $this->image_model->resize(utf8_substr($file, strlen(DIR_IMAGE)), 100, 100)
						);
					}
				}
			}
			echo json_encode($json);
			//echo $json;
	}

	public function create()
	{
		//$this->lang->load('admin/common/filemanager');

		$json = array();
		if ($this->input->post('directory'))
		{
			$directory = DIR_IMAGE . 'data/' . $this->input->post('directory');
		}else{
			$directory = DIR_IMAGE . 'data/' ;
		}
		if ($this->input->post('name'))
		{

			if (!is_dir($directory))
			{
				$json['error'] = lang('error_directory');
			}
			if (file_exists($directory . '/' . str_replace('../', '', $this->input->post('name'))))
			{
				$json['error'] = lang('error_exists');
			}
		}
		else
		{
			$json['error'] = lang('error_name');
		}

		//if (!$this->user->hasPermission('modify', 'common/filemanager')) {
		//	$json['error'] = lang('error_permission');
		//}

		if (!isset($json['error']))
		{
			mkdir($directory . '/' . str_replace('../', '', $this->input->post('name')), 0777);
			$json['success'] = lang('text_create');
		}
		echo json_encode($json);
	}

	public function delete()
	{
		$this->lang->load('admin/common/filemanager');
		$json = array();

		if ($this->input->post('path'))
		{
			$path = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', html_entity_decode($this->input->post('path'), ENT_QUOTES, 'UTF-8')), '/');

			if (!file_exists($path))
			{
				$json['error'] = lang('error_select');
			}

			if ($path == rtrim(DIR_IMAGE . 'data/', '/'))
			{
				$json['error'] = lang('error_delete');
			}
		}
		else
		{
			$json['error'] = lang('error_select');
		}

		//if (!$this->user->hasPermission('modify', 'common/filemanager'))
		//{
		//	$json['error'] = lang('error_permission');
		//}

		if (!isset($json['error']))
		{
			if (is_file($path))
			{
				unlink($path);
			}
			else if (is_dir($path))
			{
				$this->recursiveDelete($path);
			}
			$json['success'] = lang('text_delete');
		}
		echo json_encode($json);
	}

	protected function recursiveDelete($directory)
	{
		if (is_dir($directory))
		{
			$handle = opendir($directory);
		}

		if (!$handle)
		{
			return false;
		}

		while (false !== ($file = readdir($handle)))
		{
			if ($file != '.' && $file != '..')
			{
				if (!is_dir($directory . '/' . $file))
				{
					unlink($directory . '/' . $file);
				}
				else
				{
					$this->recursiveDelete($directory . '/' . $file);
				}
			}
		}

		closedir($handle);
		rmdir($directory);
		return true;
	}

	public function move()
	{
		$this->lang->load('admin/common/filemanager');
		$json = array();

		if ($this->input->post('from') && $this->input->post('to'))
		{
			$from = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', html_entity_decode($this->input->post('from'), ENT_QUOTES, 'UTF-8')), '/');

			if (!file_exists($from))
			{
				$json['error'] = lang('error_missing');
			}

			if ($from == DIR_IMAGE . 'data')
			{
				$json['error'] = lang('error_default');
			}

			$to = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', html_entity_decode($this->input->post('to'), ENT_QUOTES, 'UTF-8')), '/');

			if (!file_exists($to))
			{
				$json['error'] = lang('error_move');
			}

			if (file_exists($to . '/' . basename($from)))
			{
				$json['error'] = lang('error_exists');
			}
		}
		else
		{
			$json['error'] = lang('error_directory');
		}

		//if (!$this->user->hasPermission('modify', 'common/filemanager'))
		//{
		//	$json['error'] = lang('error_permission');
		//}

		if (!isset($json['error']))
		{
			rename($from, $to . '/' . basename($from));
			$json['success'] = lang('text_move');
		}
		echo json_encode($json);
	}

	public function copy()
	{
		$this->lang->load('admin/common/filemanager');

		$json = array();
		if ($this->input->post('path') && $this->input->post('name'))
		{
			if ((utf8_strlen($this->input->post('name')) < 3)
			|| (utf8_strlen($this->input->post('name')) > 255))
			{
				$json['error'] = lang('error_filename');
			}
			$old_name = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', html_entity_decode($this->input->post('path'), ENT_QUOTES, 'UTF-8')), '/');

			if (!file_exists($old_name) || $old_name == DIR_IMAGE . 'data')
			{
				$json['error'] = lang('error_copy');
			}

			if (is_file($old_name))
			{
				$ext = strrchr($old_name, '.');
			}
			else
			{
				$ext = '';
			}

			$new_name = dirname($old_name) . '/' . str_replace('../', '', html_entity_decode($this->input->post('name'), ENT_QUOTES, 'UTF-8') . $ext);
			if (file_exists($new_name))
			{
				$json['error'] = lang('error_exists');
			}
		}
		else
		{
			$json['error'] = lang('error_select');
		}

		//if (!$this->user->hasPermission('modify', 'common/filemanager')) {
		//	$json['error'] = lang('error_permission');
		//}

		if (!isset($json['error']))
		{
			if (is_file($old_name))
			{
				copy($old_name, $new_name);
			}
			else
			{
				$this->recursiveCopy($old_name, $new_name);
			}
			$json['success'] = lang('text_copy');
		}

		echo json_encode($json);
	}

	function recursiveCopy($source, $destination)
	{
		$directory = opendir($source);
		@mkdir($destination);

		while (false !== ($file = readdir($directory)))
		{
			if (($file != '.') && ($file != '..'))
			{
				if (is_dir($source . '/' . $file))
				{
					$this->recursiveCopy($source . '/' . $file, $destination . '/' . $file);
				}
				else
				{
					copy($source . '/' . $file, $destination . '/' . $file);
				}
			}
		}
		closedir($directory);
	}

	public function folders()
	{
		echo $this->recursiveFolders(DIR_IMAGE . 'data/');
	}

	protected function recursiveFolders($directory)
	{
		$output = '';
		$output .= '<option value="' . utf8_substr($directory, strlen(DIR_IMAGE . 'data/')) . '">' . utf8_substr($directory, strlen(DIR_IMAGE . 'data/')) . '</option>';

		$directories = glob(rtrim(str_replace('../', '', $directory), '/') . '/*', GLOB_ONLYDIR);
		foreach ($directories  as $directory)
		{
			$output .= $this->recursiveFolders($directory);
		}
		return $output;
	}

	public function rename()
	{
		$this->lang->load('admin/common/filemanager');

		$json = array();
		if ($this->input->post('path') && $this->input->post('name')) {
			if ((utf8_strlen($this->input->post('name')) < 3)
			|| (utf8_strlen($this->input->post('name')) > 255))
			{
				$json['error'] = lang('error_filename');
			}
			$old_name = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', html_entity_decode($this->input->post('path'), ENT_QUOTES, 'UTF-8')), '/');
			if (!file_exists($old_name) || $old_name == DIR_IMAGE . 'data')
			{
				$json['error'] = lang('error_rename');
			}

			if (is_file($old_name))
			{
				$ext = strrchr($old_name, '.');
			}
			else
			{
				$ext = '';
			}
			$new_name = dirname($old_name) . '/' . str_replace('../', '', html_entity_decode($this->input->post('name'), ENT_QUOTES, 'UTF-8') . $ext);

			if (file_exists($new_name))
			{
				$json['error'] = lang('error_exists');
			}
		}

		//if (!$this->user->hasPermission('modify', 'common/filemanager')) {
		//	$json['error'] = lang('error_permission');
		//}

		if (!isset($json['error']))
		{
			rename($old_name, $new_name);
			$json['success'] = lang('text_rename');
		}

		echo json_encode($json);
	}

	public function upload()
	{
		//$this->lang->load('admin/common/filemanager');
		$json = array();
		$directory = DIR_IMAGE . 'data';
		if ($this->input->post('directory'))
		{
			$directory = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $this->input->post('directory')), '/');
		}
		if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
			$filename = basename(html_entity_decode($_FILES['image']['name'], ENT_QUOTES, 'UTF-8'));

			if ((strlen($filename) < 3) || (strlen($filename) > 255)) {
				$json['error'] = lang('error_filename');
			}

			$directory = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $this->input->post('directory')), '/');

			if (!is_dir($directory)) {
				$json['error'] = lang('error_directory');
			}

			if ($_FILES['image']['size'] > 3000000000) {
				$json['error'] = lang('error_file_size');
			}

			$allowed = array(
					'image/jpeg',
					'image/pjpeg',
					'image/png',
					'image/x-png',
					'image/gif',
					'application/x-shockwave-flash'
					);

					if (!in_array($_FILES['image']['type'], $allowed)) {
						$json['error'] = lang('error_file_type');
					}

					$allowed = array(
					'.jpg',
					'.jpeg',
					'.gif',
					'.png',
					'.flv'
					);

					if (!in_array(strtolower(strrchr($filename, '.')), $allowed)) {
						$json['error'] = lang('error_file_type');
					}

					if ($_FILES['image']['error'] != UPLOAD_ERR_OK) {
						$json['error'] = 'error_upload_' . $_FILES['image']['error'];
					}
		} else {
			$json['error'] = lang('error_file');
		}

		if (!isset($json['error'])) {
			if (@move_uploaded_file($_FILES['image']['tmp_name'], $directory . '/' . $filename)) {
				$json['success'] = lang('text_uploaded');
			} else {
				$json['error'] = lang('error_uploaded');
			}
		}
		echo json_encode($json);
	}
}
?>