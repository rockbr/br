<?php

namespace App\Controllers;

use App\Models\PadraoModel;

class Ticket extends BaseController
{
	protected $tabela = 'tickets';
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
		$dados = $this->session->get($this->tabela . '_dados');
		$erro = $this->session->get($this->tabela . '_erro');
		$this->session->set($this->tabela . '_dados', null);
		$this->session->set($this->tabela . '_erro', null);

		$dadosTabela = array();
		$dados = array(
			'id_situacao_ticket' => 24,
			'id_prioridade_ticket' => 29,
		);

		#region Dados

		if ($id != null && $id > 0) {
			$params = array(
				'fields' => array('id', 'assunto', 'data_insert', 'id_situacao_ticket', 'id_categoria_ticket', 'id_prioridade_ticket'),
				'from' =>  $this->tabela,
				'where' => array('id' => $id)
			);

			$dados = array();
			$dados = $model->getQuery($params)[0];


			//Historico
			$paramsHistorico = array(
				'fields' => array('tickets_historicos.id', 'to_char(tickets_historicos.data_insert,\'DD/MM/YYYY HH24:MI:SS\') AS data', 'usuarios.nome AS usuario', 'tickets_historicos.descricao', 'tickets_historicos.anexo'),
				'from' =>  'tickets_historicos',
				'join' => array('usuarios' => array('usuarios.id = tickets_historicos.id_usuario', 'left')),
				'where' => array('id_ticket' => $id),
				'order_by' => 'tickets_historicos.data_insert',
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
			'titulo' => 'Cadastro Ticket',
			'url_salvar' => 'salvatickets',			
			'situacao_ticket' => $util->comboSituacaoTicket(),
			'prioridade_ticket' => $util->comboPrioridadeTicket(),
			'categoria_ticket' => $util->comboCategoriaTicketFiltro($categorias),
			'dados' => $dados,
			'table_historico_tbody' => $dadosTabela,
			'erro' => $erro,
		);

		echo view('admin/main_header', $data_header);
		echo view('admin/ticket/cadastro_ticket',  $data);
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
		$situacao = $this->request->getVar('id_situacao_ticket');
		//Create Data Array
		$dados = array(
			'assunto' => $this->request->getVar('assunto'),
			'id_empresa' => $this->session->get('id_empresa'),
			'id_usuario' => $this->session->get('id_usuario'),
			'id_situacao_ticket' => ($id != null && $id > 0 && $situacao != 26 && $situacao != 27) ? 25 : $situacao,
			'id_categoria_ticket' => $this->request->getVar('id_categoria_ticket'),
			'id_prioridade_ticket' => $this->request->getVar('id_prioridade_ticket'),
		);

		#region Historico

		$file = $this->request->getFile('caminho_anexo');		
		$nomeImagem = '';

		if ($file->isValid()){
			$nomeImagem = $file->getRandomName();
			$file->store('../../httpdocs/ticket/', $nomeImagem);
		}


		$historico = array(
			'acao' => 'I',
			'id_usuario' => $this->session->get('id_usuario'),
			'descricao' => $this->request->getVar('descricao'),
			'anexo' => $nomeImagem
		);

		$relationships = array(
			'tickets_historicos' => array($historico),
		);

		#endregion

		if ($validacao) {

			//Remove o login pois não pode fazer update desse campo
			if ($id != null && $id > 0) {
				unset($dados['id_empresa']);
				unset($dados['id_categoria_ticket']);
				unset($dados['id_prioridade_ticket']);
			}

			$model->setInsertUpdateTransactions($this->tabela, $dados, $id, 'id_ticket', $relationships);

			if ($id == null || $id <= 0) {
				$this->session->set($this->tabela . '_cadastrado', '1');
				return redirect()->to('consultatickets');
			} else {
				$this->session->set($this->tabela . '_cadastrado', '2');
				return redirect()->to('editatickets/' . $id);
			}			
		} else {
			$this->session->set($this->tabela . '_dados', $dados);
			$this->session->set($this->tabela . '_erro', \Config\Services::validation()->listErrors());

			if ($id == null || $id <= 0) {
				return redirect()->to('novotickets');
			} else {
				return redirect()->to('editatickets/' . $id);
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
			$url_file = base_url('ticket/' . $name);

			return redirect()->to($url_file);
			//return $this->response->download($file,null);
		} else {
			redirect(base_url());
		}
	}
}
