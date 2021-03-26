<?php

namespace App\Controllers;

use App\Models\PadraoModel;

class Home extends BaseController
{
	protected $tabela = 'tickets';
	protected $session;

	function __construct()
	{
		$this->session = \Config\Services::session();
	}

	public function index()
	{
		helper('form');

		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$pagina = 0;

		if ($this->request->getVar('paginacao') != null) {
			if ($this->request->getVar('paginacao')  == 'Anterior') {
				$pagina = $this->request->getVar('pagina_anterior') == null ? 0 : $this->request->getVar('pagina_anterior');
			} else if ($this->request->getVar('paginacao')  == 'Próximo') {
				$pagina = $this->request->getVar('pagina_proximo') == null ? 0 : $this->request->getVar('pagina_proximo');
			}
		}

		$limit = $this->request->getVar('pagina_limite') == null ? 100 : $this->request->getVar('pagina_limite');
		$offset = $pagina != null ? ($pagina * $limit) : 0;

		$model = new PadraoModel();
		$sucesso = $this->session->get($this->tabela . '_cadastrado');
		$this->session->set($this->tabela . '_cadastrado', null);

		$data_header = array(
			'titulo' => 'Consulta',
			'subtitulo' => 'CONSULTA TICKETS',
			'session' => $this->session
		);
		$categorias = str_replace('{', '', str_replace('}', '', $this->session->get('id_categoria_lista')));

		$sql = 'SELECT t.id, to_char(t.data_insert,\'DD/MM/YYYY HH24:MI:SS\') AS data_insert, t.assunto, c.nome AS departamento, s.nome AS situacao 
		FROM tickets t
		LEFT JOIN tipos s ON s.id = t.id_situacao_ticket 
		LEFT JOIN tipos c ON c.id = t.id_categoria_ticket
		LEFT JOIN tipos p ON p.id = t.id_prioridade_ticket
		WHERE t.id_categoria_ticket IN (' . $categorias . ') AND t.id_empresa = ' . $this->session->get('id_empresa') . ' AND t.data_delete IS NULL ORDER BY t.data_insert DESC';

		$sqlCount = 'SELECT count(*) 
		FROM tickets t
		LEFT JOIN tipos s ON s.id = t.id_situacao_ticket 
		LEFT JOIN tipos c ON c.id = t.id_categoria_ticket
		LEFT JOIN tipos p ON p.id = t.id_prioridade_ticket
		WHERE t.id_categoria_ticket IN (' . $categorias . ') AND t.id_empresa = ' . $this->session->get('id_empresa') . ' AND t.data_delete IS NULL';


		$table_tbody = $model->getQueryCustom($sql);
		$table_count = $model->getQueryCustom($sqlCount);

		$data = array(
			'table_thead' =>  array('Data', 'Assunto', 'Departamento', 'Situação'),
			'table_tbody' => $table_tbody,
			'sucesso' => $sucesso,
			'acoes_novo' => 'novotickets',
			'acoes_editar' => 'editatickets',
			'pagina' => $pagina,
			'paginacao_de' => ($offset + 1),
			'paginacao_ate' => ($offset == 0 ? count($table_tbody) : ($offset + count($table_tbody))),
			'paginacao_total' => $table_count[0]['count'],
			'limite' => $limit,
			'url' => site_url('consultatickets'),

		);

		echo view('admin/main_header', $data_header);
		echo view('admin/main_home',  $data);
		echo view('admin/main_footer');
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
