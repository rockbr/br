<?php

namespace App\Controllers;

use App\Models\PadraoModel;

class Compra extends BaseController
{
	protected $tabela = 'compras';
	protected $session;

	function __construct()
	{
		$this->session = \Config\Services::session();
	}

	public function list()
	{
		helper('form');

		if (!$this->session->get('logado')) {
			return redirect()->to('/admin');
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
			'subtitulo' => 'CONSULTA COMPRAS',
			'session' => $this->session
		);
		$categorias = str_replace('{', '', str_replace('}', '', $this->session->get('id_categoria_lista')));

		$sql = 'SELECT t.id, to_char(t.data_insert,\'DD/MM/YYYY HH24:MI:SS\') AS data_insert, t.descricao, c.nome AS departamento, s.nome AS situacao 
		FROM compras t
		LEFT JOIN tipos s ON s.id = t.id_situacao_compra 
		LEFT JOIN tipos c ON c.id = t.id_categoria_compra
		LEFT JOIN tipos p ON p.id = t.id_prioridade_compra
		WHERE t.id_categoria_compra IN (' . $categorias . ') AND t.id_empresa = ' . $this->session->get('id_empresa') . ' AND t.data_delete IS NULL ORDER BY t.data_insert DESC';

		$sqlCount = 'SELECT count(*) 
		FROM compras t
		LEFT JOIN tipos s ON s.id = t.id_situacao_compra 
		LEFT JOIN tipos c ON c.id = t.id_categoria_compra
		LEFT JOIN tipos p ON p.id = t.id_prioridade_compra
		WHERE t.id_categoria_compra IN (' . $categorias . ') AND t.id_empresa = ' . $this->session->get('id_empresa') . ' AND t.data_delete IS NULL';


		$table_tbody = $model->getQueryCustom($sql);
		$table_count = $model->getQueryCustom($sqlCount);

		$data = array(
			'table_thead' =>  array('Data', 'Descrição', 'Departamento', 'Situação'),
			'table_tbody' => $table_tbody,
			'sucesso' => $sucesso,
			'acoes_novo' => 'novocompras',
			'acoes_editar' => 'editacompras',
			'pagina' => $pagina,
			'paginacao_de' => ($offset + 1),
			'paginacao_ate' => ($offset == 0 ? count($table_tbody) : ($offset + count($table_tbody))),
			'paginacao_total' => $table_count[0]['count'],
			'limite' => $limit,
			'url' => site_url('consultacompras'),

		);

		echo view('admin/main_header', $data_header);
		echo view('admin/main_list',  $data);
		echo view('admin/main_footer');
	}

	public function cadastro($id = null)
	{
		helper('form', 'url');

		if (!$this->session->get('logado')) {
			return redirect()->to('/admin');
		}

		$model = new PadraoModel();
		$util = new Util();
		$dados = $this->session->get($this->tabela . '_dados');
		$erro = $this->session->get($this->tabela . '_erro');
		$this->session->set($this->tabela . '_dados', null);
		$this->session->set($this->tabela . '_erro', null);

		$dadosTabela = array();
		$dados = array(
			'id_situacao_compra' => 24,
			'id_prioridade_compra' => 29,
		);

		#region Dados

		if ($id != null && $id > 0) {
			$params = array(
				'fields' => array('id', 'descricao', 'data_insert', 'id_situacao_compra', 'id_categoria_compra', 'id_prioridade_compra'),
				'from' =>  $this->tabela,
				'where' => array('id' => $id)
			);

			$dados = array();
			$dados = $model->getQuery($params)[0];


			//Historico
			$paramsHistorico = array(
				'fields' => array('compras_historicos.id', 'to_char(compras_historicos.data_insert,\'DD/MM/YYYY HH24:MI:SS\') AS data', 'usuarios.nome AS usuario', 'compras_historicos.descricao', 'compras_historicos.anexo'),
				'from' =>  'compras_historicos',
				'join' => array('usuarios' => array('usuarios.id = compras_historicos.id_usuario', 'left')),
				'where' => array('id_compra' => $id),
				'order_by' => 'compras_historicos.data_insert',
				'order_by_direction' => 'DESC', //OKn
			);

			$dadosTabela = array();
			$dadosTabela = $model->getQuery($paramsHistorico);
		}

		#endregion

		$data_header = array(
			'session' => $this->session
		);
		$categorias = str_replace('{', '', str_replace('}', '', $this->session->get('id_categoria_lista')));

		$data = array(
			'titulo' => 'Cadastro Compra',
			'url_salvar' => 'salvacompras',			
			'fornecedores' => $util->comboFornecedor(),
			'produtos' => $util->comboProduto(),
			'forma_pagamento' => $util->comboFormaPagamento(),
			'situacao_compra' => $util->comboSituacaoCompra(),
			'prioridade_compra' => $util->comboPrioridadeCompra(),
			'categoria_compra' => $util->comboCategoriaCompra(),
			'dados' => $dados,
			'table_historico_tbody' => $dadosTabela,
			'acoes_delete' => 'deleteprodutos',
			'acoes_editar' => 'editaprodutos',
			'erro' => $erro,
			'url_salvar_produtos' => 'salvaprodutos'
		);

		echo view('admin/main_header', $data_header);
		echo view('admin/compra/cadastro_compra',  $data);
		echo view('admin/main_footer');
	}

	public function delete()
	{
		helper('form', 'url');

		if (!$this->session->get('logado')) {
			return redirect()->to('/admin');
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
			return redirect()->to('/admin');
		}

		$model = new PadraoModel();
		$validacao = self::valida();

		$id = $this->request->getVar('id');
		$situacao = $this->request->getVar('id_situacao_compra');
		//Create Data Array
		$dados = array(
			'descricao' => $this->request->getVar('descricao'),
			'id_empresa' => $this->session->get('id_empresa'),
			'id_usuario' => $this->session->get('id_usuario'),
			'id_situacao_compra' => ($id != null && $id > 0 && $situacao != 26 && $situacao != 27) ? 25 : $situacao,
			'id_categoria_compra' => $this->request->getVar('id_categoria_compra'),
			'id_prioridade_compra' => $this->request->getVar('id_prioridade_compra'),
		);

		#region Historico

		$file = $this->request->getFile('caminho_anexo');
		//$nomeImagem = md5(uniqid(time())) . ".jpg";
		$nomeImagem = $file->getRandomName();

		if ($file->isValid()){
			$file->store('../../httpdocs/compra/', $nomeImagem);
		}


		$historico = array(
			'acao' => 'I',
			'id_usuario' => $this->session->get('id_usuario'),
			'descricao' => $this->request->getVar('descricao'),
			'anexo' => $nomeImagem
		);

		$relationships = array(
			'compras_historicos' => array($historico),
		);

		#endregion

		if ($validacao) {

			//Remove o login pois não pode fazer update desse campo
			if ($id != null && $id > 0) {
				unset($dados['id_empresa']);
				unset($dados['id_categoria_compra']);
				unset($dados['id_prioridade_compra']);
			}

			$model->setInsertUpdateTransactions($this->tabela, $dados, $id, 'id_compra', $relationships);

			if ($id == null || $id <= 0) {
				$this->session->set($this->tabela . '_cadastrado', '1');
				return redirect()->to('consultacompras');
			} else {
				$this->session->set($this->tabela . '_cadastrado', '2');
				return redirect()->to('editacompras/' . $id);
			}			
		} else {
			$this->session->set($this->tabela . '_dados', $dados);
			$this->session->set($this->tabela . '_erro', \Config\Services::validation()->listErrors());

			if ($id == null || $id <= 0) {
				return redirect()->to('novocompras');
			} else {
				return redirect()->to('editacompras/' . $id);
			}
		}
	}
	private function valida()
	{
		helper(['form', 'url']);
		$valida = \Config\Services::validation();
		$id = $this->request->getVar('id');

		$valida->setRules([
			'descricao' => ['label' => 'Descrição', 'rules' => 'required|min_length[3]|max_length[255]'],
		]);

		if ($valida->withRequest($this->request)->run()) {
			return true;
		} else {
			return false;
		}
	}
	//--------------------------------------------------------------------

	public function anexo($name)
	{
		helper(['url']);

		if (!empty($name)) {
			$url_file = base_url('compra/' . $name);

			return redirect()->to($url_file);
			//return $this->response->download($file,null);
		} else {
			redirect(base_url());
		}
	}
}
