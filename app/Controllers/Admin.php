<?php

namespace App\Controllers;

class Admin extends BaseController
{
	function __construct()
	{
		$this->session = \Config\Services::session();
	}

	public function index()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/admin');
		}

		$data_header = array('session' => $this->session);

		echo view('admin/padrao/main_header', $data_header);
		echo view('admin/padrao/main_home');
		echo view('admin/padrao/main_footer');
	}
}
