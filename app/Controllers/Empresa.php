<?php

namespace App\Controllers;

use App\Models\PadraoModel;

class Empresa extends BaseController
{
	protected $tabela = 'empresas';
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
			'subtitulo' => 'CONSULTA EMPRESAS',
			'session' => $this->session,
			'super' => $this->session->get('super')
		);

		$params = array(
			'fields' => array('id', 'nome', 'cnpj'), //OK

			'from' =>  $this->tabela, //OK

			'order_by' => 'nome', //OK
			'order_by_direction' => 'ASC', //OK
			'offset' => $offset,
			'limit' => $limit,
		);
		
		$table_tbody = $model->getQuery($params);
		$table_count = $model->getQuery($params, true);

		$data = array(
			'table_thead' =>  array('Nome', 'CNPJ'),
			'table_tbody' => $table_tbody,
			'sucesso' => $sucesso,
			'acoes_novo' => 'novoempresas',
			'acoes_editar' => 'editaempresas',
			'acoes_deletar' => 'deletaempresas',
			'pagina' => $pagina,
			'paginacao_de' => ($offset + 1),
			'paginacao_ate' => ($offset == 0 ? count($table_tbody) : ($offset + count($table_tbody))),
			'paginacao_total' => $table_count[0]['count'],
			'limite' => $limit,
			'url' => site_url('consultaempresas'),
		);

		echo view('admin/padrao/main_header', $data_header);
		echo view('admin/padrao/main_list',  $data);
		echo view('admin/padrao/main_footer');
	}

	public function cadastro($id = null)
	{
		helper('form', 'url');

		if (!$this->session->get('logado')) {
			return redirect()->to('/admin');
		}

		$model = new PadraoModel();
		$util = new Util();
		$dados = array();

		$dados = $this->session->get($this->tabela . '_dados');
		$erro = $this->session->get($this->tabela . '_erro');
		$this->session->set($this->tabela . '_dados', null);
		$this->session->set($this->tabela . '_erro', null);

		#region Dados

		self::limpaDadosSessao();

		if ($id != null && $id > 0) {

			$this->session->set($this->tabela . '_id', $id);

			$paramsEmpresa = array(
				'fields' => array('id', 'nome', 'cnpj', 'ie', 'observacao'),
				'from' =>  $this->tabela,
				'where' => array('id' => $id)
			);
			$dados = $model->getQuery($paramsEmpresa);

			if ($dados != null) {
				$dados = $dados[0];
			}

			#region Telefones

			$paramsTelefones = array(
				'fields' => array('id', 'numero', 'id_tipo'),
				'from' =>  $this->tabela . '_telefones',
				'where' => array('id_empresa' => $id),
			);

			$dadosTelefones = $model->getQuery($paramsTelefones);

			if ($dadosTelefones != null) {
				foreach ($dadosTelefones as $value) {
					if ($value['id_tipo'] == 19) {
						$dados = array_merge($dados, array('telefone_celular' => $value['numero'])); //adiciona o id
						$this->session->set($this->tabela . '_id_telefone_celular', $value['id']);
					} else if ($value['id_tipo'] == 20) {
						$dados = array_merge($dados, array('telefone_fixo' => $value['numero'])); //adiciona o id
						$this->session->set($this->tabela . '_id_telefone_fixo', $value['id']);
					}
				}
			}

			#endregion

			#region Contatos

			$paramsContatos = array(
				'fields' => array('id', 'contato', 'id_tipo'),
				'from' =>  $this->tabela . '_contatos',
				'where' => array('id_empresa' => $id),
			);

			$dadosContatos = $model->getQuery($paramsContatos);

			if ($dadosContatos != null) {
				foreach ($dadosContatos as $value) {
					if ($value['id_tipo'] == 1) {
						$dados = array_merge($dados, array('email' => $value['contato']));
						$this->session->set($this->tabela . '_id_email', $value['id']);
					} else if ($value['id_tipo'] == 5) {
						$dados = array_merge($dados, array('twitter' => $value['contato']));
						$this->session->set($this->tabela . '_id_twitter', $value['id']);
					} else if ($value['id_tipo'] == 6) {
						$dados = array_merge($dados, array('facebook' => $value['contato']));
						$this->session->set($this->tabela . '_id_facebook', $value['id']);
					} else if ($value['id_tipo'] == 8) {
						$dados = array_merge($dados, array('instagram' => $value['contato']));
						$this->session->set($this->tabela . '_id_instagram', $value['id']);
					}
				}
			}

			#endregion

			#region Endereços

			$paramsEnderecos = array(
				'fields' => array('id', 'cep', 'logradouro', 'numero', 'bairro', 'complemento', 'cidade', 'id_estado'),
				'from' =>  $this->tabela . '_enderecos',
				'where' => array('id_empresa' => $id),
				'limit' => 1
			);

			$dadosEnderecos = $model->getQuery($paramsEnderecos);

			if ($dadosEnderecos != null) {
				$dados = array_merge($dados, array('auto_cep' => $dadosEnderecos[0]['cep']));
				$dados = array_merge($dados, array('logradouro' => $dadosEnderecos[0]['logradouro']));
				$dados = array_merge($dados, array('numero' => $dadosEnderecos[0]['numero']));
				$dados = array_merge($dados, array('bairro' => $dadosEnderecos[0]['bairro']));
				$dados = array_merge($dados, array('complemento' => $dadosEnderecos[0]['complemento']));
				$dados = array_merge($dados, array('auto_cidade' => $dadosEnderecos[0]['cidade']));
				$dados = array_merge($dados, array('id_estado' => $dadosEnderecos[0]['id_estado']));
				$this->session->set($this->tabela . '_id_endereco', $dadosEnderecos[0]['id']);
			}

			#endregion
		}

		#endregion

		$data_header = array(
			'session' => $this->session
		);

		$data = array(
			'titulo' => 'Cadastro de Empresas',
			'url_salvar' => 'salvaempresas',
			'dados' => $dados,
			'erro' => $erro,
			'estados' => $util->comboEstado(),
		);

		echo view('admin/padrao/main_header', $data_header);
		echo view('admin/padrao/empresa/cadastro_' . $this->tabela,  $data);
		echo view('admin/padrao/main_footer');
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

		$util = new Util();
		$model = new PadraoModel();
		$validacao = self::valida();

		//Pega o ID na sessão e depois a apaga ele
		$id = $this->session->get($this->tabela . '_id');
		$idEndereco = $this->session->get($this->tabela . '_id_endereco');
		$idTelefoneCelular = $this->session->get($this->tabela . '_id_telefone_celular');
		$idTelefoneFixo = $this->session->get($this->tabela . '_id_telefone_fixo');
		$idEmail = $this->session->get($this->tabela . '_id_email');
		$idTwitter = $this->session->get($this->tabela . '_id_twitter');
		$idFacebook = $this->session->get($this->tabela . '_id_facebook');
		$idInstagram = $this->session->get($this->tabela . '_id_instagram');

		self::limpaDadosSessao();

		//Create Data Array
		$dados = array(
			'nome' => $this->request->getVar('nome'),
			'cnpj' => $this->request->getVar('cnpj'),
			'ie' => $this->request->getVar('ie'),
			'observacao' => $this->request->getVar('observacao')
		);

		#region Endereco

		$endereco = array(
			'acao' => $util->acao($idEndereco, $this->request->getVar('logradouro')),
			'id' => $idEndereco,
			'id_tipo' => 15,
			'cep' => $this->request->getVar('auto_cep'),
			'logradouro' => $this->request->getVar('logradouro'),
			'numero' => $this->request->getVar('numero'),
			'bairro' => $this->request->getVar('bairro'),
			'complemento' => $this->request->getVar('complemento'),
			'cidade' => $this->request->getVar('auto_cidade'),
			'id_estado' => $this->request->getVar('id_estado'),
		);

		#endregion

		#region Telefone

		$telefoneCelular = array('acao' => $util->acao($idTelefoneCelular, $this->request->getVar('telefone_celular')), 'id' => $idTelefoneCelular, 'numero' => $this->request->getVar('telefone_celular'), 'id_tipo' => 19);
		$telefoneFixo = array('acao' => $util->acao($idTelefoneFixo, $this->request->getVar('telefone_fixo')), 'id' => $idTelefoneFixo, 'numero' => $this->request->getVar('telefone_fixo'), 'id_tipo' => 20);

		#endregion

		#region Contato

		$contatoEmail = array('acao' => $util->acao($idEmail, $this->request->getVar('email')), 'id' => $idEmail, 'contato' => $this->request->getVar('email'), 'id_tipo' => 1);
		$contatoTwitter = array('acao' => $util->acao($idTwitter, $this->request->getVar('twitter')), 'id' => $idTwitter, 'contato' => $this->request->getVar('twitter'), 'id_tipo' => 5);
		$contatofacebook = array('acao' => $util->acao($idFacebook, $this->request->getVar('facebook')), 'id' => $idFacebook, 'contato' => $this->request->getVar('facebook'), 'id_tipo' => 6);
		$contatoInstagram = array('acao' => $util->acao($idInstagram, $this->request->getVar('instagram')), 'id' => $idInstagram, 'contato' => $this->request->getVar('instagram'), 'id_tipo' => 8);

		#endregion

		$relationships = array(
			'empresas_telefones' => array($telefoneCelular, $telefoneFixo),
			'empresas_contatos' => array($contatoEmail, $contatoTwitter, $contatofacebook, $contatoInstagram),
			'empresas_enderecos' => array($endereco)
		);

		if ($validacao) {

			$model->setInsertUpdateTransactions($this->tabela, $dados, $id, 'id_empresa', $relationships);

			if ($id == null || $id <= 0) {
				$this->session->set($this->tabela . '_cadastrado', '1');
			} else {
				$this->session->set($this->tabela . '_cadastrado', '2');
			}
			return redirect()->to('consultaempresas');
		} else {
			$this->session->set($this->tabela . '_dados', $dados);
			$this->session->set($this->tabela . '_erro', \Config\Services::validation()->listErrors());
			return redirect()->to('novoempresas');
		}
	}

	private function limpaDadosSessao()
	{
		//Limpa os dados na sessão
		$this->session->set($this->tabela . '_id', null);
		$this->session->set($this->tabela . '_id_endereco', null);
		$this->session->set($this->tabela . '_id_telefone_celular', null);
		$this->session->set($this->tabela . '_id_telefone_fixo', null);
		$this->session->set($this->tabela . '_id_email', null);
		$this->session->set($this->tabela . '_id_twitter', null);
		$this->session->set($this->tabela . '_id_facebook', null);
		$this->session->set($this->tabela . '_id_instagram ', null);
	}

	private function valida()
	{
		helper(['form', 'url']);
		$valida = \Config\Services::validation();
		$id = $this->request->getVar('id');

		$valida->setRules([
			'nome' => ['label' => 'Nome', 'rules' => 'required|min_length[3]|max_length[255]'],
		]);

		if ($valida->withRequest($this->request)->run()) {
			return true;
		} else {
			return false;
		}
	}
	//--------------------------------------------------------------------
}
