<?php

namespace App\Controllers;

class Download extends BaseController
{
	function __construct()
	{
		$this->session = \Config\Services::session();
	}

	public function index()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$data_header = array('session' => $this->session);

		echo view('admin/main_header', $data_header);
		echo view('admin/main_download');
		echo view('admin/main_footer');
	}

	public function download($name)
	{
		helper('form', 'url');
		if (!empty($name)) {
			$url_file = base_url("upload/" . $name);
			//return $this->response->download($file,null);
			return redirect()->to($url_file); 
		} else {
			redirect(base_url());
		}
	}
}
