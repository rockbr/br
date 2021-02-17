<?php

namespace App\Controllers;

use App\Models\PadraoModel;

class Home extends BaseController
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
		
		$util = new Util();
		$model = new PadraoModel();

		$data_header = array('session' => $this->session);

		$data = array(
		);

		echo view('admin/padrao/main_header', $data_header);
		echo view('admin/padrao/main_home', $data);
		echo view('admin/padrao/main_footer');
	}


	//Termos e Condições de Uso
	public function termos()
	{
		echo view('site/termos/termos-de-uso');
	}

	//Politica de Privacidade
	public function privacidade()
	{
		echo view('site/termos/politica-privacidade');
	}
}
