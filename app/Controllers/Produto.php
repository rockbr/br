<?php

namespace App\Controllers;

use App\Models\PadraoModel;

class Produto extends BaseController
{
	protected $tabela = 'produtos';
	protected $session;

	function __construct()
	{
		$this->session = \Config\Services::session();
	}

	public function list()
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
		$sucesso = $this->session->get($this->tabela.'_cadastrado');
		$this->session->set($this->tabela.'_cadastrado', null);		

		$data_header = array(
			'titulo' => 'Consulta',
			'subtitulo' => 'CONSULTA PRODUTOS',
			'session' => $this->session
		);

		$params = array(
			'fields' => array('id', 'codigo', 'nome'), //OK

			'from' =>  $this->tabela, //OK
			'where' => array('id_empresa' =>  $this->session->get('id_empresa')),
			'order_by' => 'nome', //OK
			'order_by_direction' => 'ASC', //OK
			'offset' => $offset,
			'limit' => $limit,
		);
		
		$table_tbody = $model->getQuery($params);
		$table_count = $model->getQuery($params, true);

		$data = array(
			'table_thead' =>  array('Código','Nome'),
			'table_tbody' => $table_tbody,
			'sucesso' => $sucesso,
			'acoes_novo' => 'novoprodutos',
			'acoes_editar' => 'editaprodutos',
			'acoes_deletar' => 'deletaprodutos',
			'pagina' => $pagina,
			'paginacao_de' => ($offset + 1),
			'paginacao_ate' => ($offset == 0 ? count($table_tbody) : ($offset + count($table_tbody))),
			'paginacao_total' => $table_count[0]['count'],
			'limite' => $limit,
			'url' => site_url('consultaprodutos'),
		);

		echo view('admin/main_header', $data_header);
		echo view('admin/main_list',  $data);
		echo view('admin/main_footer');
	}

	public function cadastro($id = null)
	{
		helper('form', 'url');

		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();
		$util = new Util();
		$dados = $this->session->get($this->tabela.'_dados');
		$erro = $this->session->get($this->tabela.'_erro');
		$this->session->set($this->tabela.'_dados', null);
		$this->session->set($this->tabela.'_erro', null);

		#region Dados

		if ($id != null && $id > 0) {
			$params = array(
				'fields' => array('id', 'codigo', 'nome', 'id_grupo_produto'),
				'from' =>  $this->tabela,
				'where' => array('id' => $id)
			);

			$dados = array();
			$dados = $model->getQuery($params)[0];
		}

		#endregion
	
		$data_header = array(
			'session' => $this->session
		);

		$data = array(
			'titulo' => 'Cadastro Produtos',
			'grupo_produto' => $util->comboGrupoProduto(),
			'url_salvar' => 'salvaprodutos',
			'dados' => $dados,
			'erro' => $erro,			
		);

		echo view('admin/main_header',$data_header);
		echo view('admin/produto/cadastro_produtos',  $data);
		echo view('admin/main_footer');
	}

	public function delete()
	{
		helper('form', 'url');
		
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$security = \Config\Services::security();

		$model = new PadraoModel();
		$id = $this->request->getVar('query');		

		$where = array('where' => array('id' => $id));
		$model->setDeleteSoft($this->tabela, $where);

		$reponse = array(
			'csrf_name' => $security->getCSRFTokenName(),
			'csrf_hash' => $security->getCSRFHash()
		);

		echo json_encode($reponse);
		die;
	}

	public function salva()
	{
		helper(['form', 'url']);
		
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();
		$validacao = self::valida();

		$id = $this->request->getVar('id');

		//Create Data Array
		$dados = array(
			'codigo' => $this->request->getVar('codigo'),
			'nome' => $this->request->getVar('nome'),	
			'id_empresa' => $this->session->get('id_empresa'),
			'id_grupo_produto' => $this->request->getVar('id_grupo_produto'),
		);

		if ($validacao) {

			if ($id == null || $id <= 0) {
				$model->setInsert($this->tabela, $dados);
				$this->session->set($this->tabela.'_cadastrado', '1');
			} else {
				//Remove o id_empresa pois não pode fazer update desse campo
				unset($dados['id_empresa']);

				$where = array('where' => array('id' => $id));
				$model->setUpdate($this->tabela, $dados, $where);
				$this->session->set($this->tabela.'_cadastrado', '2');
			}

			return redirect()->to('consultaprodutos');
		} else {
			$this->session->set($this->tabela.'_dados', $dados);
			$this->session->set($this->tabela.'_erro', \Config\Services::validation()->listErrors());
			return redirect()->to('novoprodutos');
		}
	}

	private function valida()
	{
		helper(['form', 'url']);
		$valida = \Config\Services::validation();
		$id = $this->request->getVar('id');

		$valida->setRules([
			'codigo' => ['label' => 'Código', 'rules' => 'required|min_length[1]|max_length[50]'],
			'nome' => ['label' => 'Nome', 'rules' => 'required|min_length[3]|max_length[255]'],		
			'id_grupo_produto' => ['label' => 'Grupo', 'rules' => 'required'],	
		]);

		if ($valida->withRequest($this->request)->run()) {
			return true;
		} else {
			return false;
		}
	}
	//--------------------------------------------------------------------
}