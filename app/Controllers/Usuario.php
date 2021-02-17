<?php

namespace App\Controllers;

use App\Models\PadraoModel;

class Usuario extends BaseController
{
	protected $tabela = 'usuarios';
	protected $session;

	function __construct()
	{
		$this->session = \Config\Services::session();
		$this->session->start();
	}

	public function index()
	{
		helper('cookie');

		$lembrete = strtolower(base64_decode(get_cookie('cookie_0')));
		$login = base64_decode(get_cookie('cookie_1'));
		$senha = base64_decode(get_cookie('cookie_2'));
		$lembreteChecked = ($lembrete == 'on') ? 'checked' : '';

		$data = array(
			'login' => $login,
			'senha' => $senha,
			'lembrete' => $lembreteChecked,
			'erro' => null
		);

		echo view('admin/usuario/login', $data);
	}

	public function login()
	{
		helper('form', 'url', 'cookie');
		$model = new PadraoModel();

		$validacao = self::validaLogin();

		if ($validacao) {
			$usuario = $this->request->getVar('login');
			$senha = $this->request->getVar('senha');
			$lembrete = strtolower($this->request->getVar('lembrete'));
			$expira = time() + 60 * 60 * 24 * 30;			

			setCookie('cookie_0', base64_encode($lembrete == 'on' ? $lembrete : 'off'), $expira, site_url());
			setCookie('cookie_1', ($lembrete == 'on' ? base64_encode($usuario) : ''), $expira, site_url());
			setCookie('cookie_2', ($lembrete == 'on' ? base64_encode($senha) : ''), $expira, site_url());

			$params = array(
				'fields' => array('usuarios.id', 'usuarios.super', 'usuarios.nome AS nome', 'usuarios.login AS login', 'usuarios.id_empresa AS id_empresa', 'grupos_usuarios.nome AS grupo', 'grupos_usuarios.id AS id_grupo', 'id_categoria_lista'), //OK
				'from' =>  $this->tabela, //OK
				'where' => array('login' => $usuario, 'senha' => md5($senha), 'bloqueado' => false, 'acesso_admin ' => true),
				'join' => array('grupos_usuarios' => array('grupos_usuarios.id = usuarios.id_grupo_usuario', 'left')), //Option: left | right | outer | inner | left outer | right outer			
			);
			$table_tbody = $model->getQuery($params);

			if (empty($table_tbody)) {
				$data = array('erro' => 'Acesso negado');
				echo view('admin/usuario/login', $data);
			} else {
				$this->session->set('id_usuario', $table_tbody[0]['id']);
				$this->session->set('nome_usuario', $table_tbody[0]['nome']);
				$this->session->set('login_usuario', $table_tbody[0]['login']);
				$this->session->set('grupo_usuario', $table_tbody[0]['grupo']);
				$this->session->set('id_grupo_usuario', $table_tbody[0]['id_grupo']);
				$this->session->set('id_categoria_lista', $table_tbody[0]['id_categoria_lista']);				
				$this->session->set('id_empresa', $table_tbody[0]['id_empresa']);
				$this->session->set('super', $table_tbody[0]['super']);
				$this->session->set('logado', true);

				//Grava a data do ultimo login
				$dados = array(
					'ultimo_login' => date('Y-m-d H:i:s'),
				);
				$where = array('where' => array('id' => $table_tbody[0]['id']));
				$model->setUpdate($this->tabela, $dados, $where);

				return redirect()->to(base_url('home'));
			}
		} else {
			$data = array('erro' => \Config\Services::validation()->listErrors());
			echo view('admin/usuario/login', $data);
		}
	}

	public function logout()
	{
		$this->session->destroy();
		return redirect()->to('/');
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
			'subtitulo' => 'CONSULTA USUÁRIOS',
			'session' => $this->session
		);

		$params = array(
			'fields' => array($this->tabela . '.id', $this->tabela . '.nome AS Nome', $this->tabela . '.login AS Login', 'grupos_usuarios.nome AS Grupo'), //OK

			'from' =>  $this->tabela, //OK
			'where' => array('usuarios.id_empresa' =>  $this->session->get('id_empresa')),
			'join' => array('grupos_usuarios' => array('grupos_usuarios.id = usuarios.id_grupo_usuario', 'left')), //Option: left | right | outer | inner | left outer | right outer			

			'order_by' => 'login', //OK
			'order_by_direction' => 'ASC', //OK
			'offset' => $offset,
			'limit' => $limit,
		);
		
		$table_tbody = $model->getQuery($params);
		$table_count = $model->getQuery($params, true);

		$data = array(
			'table_thead' =>  array('Nome', 'Login', 'Grupo'),
			'table_tbody' => $table_tbody,
			'sucesso' => $sucesso,
			'acoes_novo' => 'novousuarios',
			'acoes_editar' => 'editausuarios',
			'acoes_deletar' => 'deletausuarios',
			'pagina' => $pagina,
			'paginacao_de' => ($offset + 1),
			'paginacao_ate' => ($offset == 0 ? count($table_tbody) : ($offset + count($table_tbody))),
			'paginacao_total' => $table_count[0]['count'],
			'limite' => $limit,
			'url' => site_url('consultausuarios'),
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

		#region Dados

		if ($id != null && $id > 0) {
			$paramsUsuario = array(
				'fields' => array('id', 'nome', 'login', 'id_grupo_usuario', 'bloqueado', 'acesso_admin', 'id_categoria_lista'),
				'from' =>  $this->tabela,
				'where' => array('id' => $id)
			);

			$dados = array();
			$dados = $model->getQuery($paramsUsuario)[0];
		}

		#endregion

		$data_header = array(
			'session' => $this->session
		);

		$data = array(
			'titulo' => 'Cadastro de Usuários',
			'url_salvar' => 'salvausuarios',
			'dados' => $dados,
			'grupo_usuario' => $util->comboGrupoUsuario(),		
			'categorias' => $util->comboCategoriaTicket(),			
			'erro' => $erro,
		);

		echo view('admin/main_header', $data_header);
		echo view('admin/usuario/cadastro_' . $this->tabela,  $data);
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
		$senha = $this->request->getVar('senha');		
		$id_categoria_lista = $this->request->getVar('id_categoria_lista');

		//Create Data Array
		$dados = array(
			'login' => $this->request->getVar('email'),
			'nome' => $this->request->getVar('nome'),
			'bloqueado' => $this->request->getVar('bloqueado') != "on" ? '0' : '1',
			'id_grupo_usuario' => $this->request->getVar('id_grupo_usuario'),
			'id_empresa' => $this->session->get('id_empresa'),
			'senha' => md5($senha),			
			'acesso_admin' => $this->request->getVar('acesso_admin') != "on" ? '0' : '1',	
			'id_categoria_lista' => ($id_categoria_lista != null ? '{' . implode(',', $id_categoria_lista) . '}' : null),		
		);


		if ($validacao) {

			if ($id == null || $id <= 0) {
				$model->setInsert($this->tabela, $dados);
				$this->session->set($this->tabela . '_cadastrado', '1');
			} else {

				//Se não foi digitado a senha remove do array
				if ($senha == null || $senha == "") {
					unset($dados['senha']);
				}

				//Remove o login pois não pode fazer update desse campo
				unset($dados['login']);
				unset($dados['id_empresa']);

				$where = array('where' => array('id' => $id));
				$model->setUpdate($this->tabela, $dados, $where);
				$this->session->set($this->tabela . '_cadastrado', '2');
			}

			return redirect()->to('consultausuarios');
		} else {
			$this->session->set($this->tabela . '_dados', $dados);
			$this->session->set($this->tabela . '_erro', \Config\Services::validation()->listErrors());
			return redirect()->to('novousuarios');
		}
	}

	public function resetSenha()
	{
		$sucesso = $this->session->get('usuarios_hashs_sucesso');
		$this->session->set('usuarios_hashs_sucesso', null);

		$data = array(
			'sucesso' => $sucesso
		);

		echo view('admin/usuario/recupera_senha', $data);
	}

	public function emailResetSenha()
	{
		helper(['form', 'url']);

		$model = new PadraoModel();
		$email = \Config\Services::email();

		$usuario = $this->request->getVar('login');
		$params = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  $this->tabela, //OK
			'where' => array('login' => $usuario, 'bloqueado' => false),
			'limit' => '1',
		);
		$results = $model->getQuery($params);

		if (count($results) > 0) {
			$token = md5($results[0]['id'] . uniqid(time()));

			//Deleta links anteriores
			$where = array('where' => array('id_usuario' => $results[0]['id']));
			$model->setDeleteSoft('usuarios_hashs', $where);

			//E-mail			
			$email->setTo($usuario);			
			$email->setSubject('Acesso ao Reset de sua senha FeTx');
			$email->setMessage('Olá, Você solicitou uma nova senha no site da FeTx. Para concluir esta operação, basta clicar no link abaixo.<br/><a href="' . base_url('/novasenhatoken//' . $token) . '">Refinir nova senha</a><br/><br/>Atenção, link é válido por apenas 2 horas. Após esse período, você deverá fazer uma nova solicitação.');
			$email->send();

			//Grava novo Link
			$dados = array(
				'id_usuario' => $results[0]['id'],
				'hash ' => $token,
				'data_validade' =>  date('Y-m-d H:i:s', strtotime('+2 Hours'))
			);
			$model->setInsert('usuarios_hashs', $dados);
			$this->session->set('usuarios_hashs_sucesso', '1');
		} else {
			$this->session->set('usuarios_hashs_sucesso', '0');
		}

		return redirect()->to('resetsenha');
	}

	public function novaSenhaToken($token)
	{
		helper(['form', 'url']);
		$model = new PadraoModel();

		$params = array(
			'fields' => array('id'), //OK
			'from' =>  'usuarios_hashs', //OK
			'where' => array('hash' => $token, 'utilizado' => false, 'data_validade >=' => date('Y-m-d H:i:s'), 'data_insert <=' => date('Y-m-d H:i:s')),
			'limit' => '1',
		);
		$results = $model->getQuery($params);

		if (count($results) > 0) {
			$sucesso = $this->session->get('usuarios_nova_senha_sucesso');
			$erro = $this->session->get('usuarios_nova_senha_erro');

			//Passa o token para ser inativado depois que usar
			$this->session->set('usuarios_nova_senha_token', $token);
			//Limmpa o dado da sessão
			$this->session->set('usuarios_nova_senha_sucesso', null);

			$data = array(
				'sucesso' => $sucesso,
				'erro' => $erro
			);

			echo view('admin/usuario/nova_senha', $data);
		} else {
			return redirect()->to('/');
		}
	}

	public function novaSenha()
	{
		helper(['form', 'url']);
		$model = new PadraoModel();
		$email = \Config\Services::email();

		$validacao = self::validaNovaSenha();
		$token = $this->session->get('usuarios_nova_senha_token');
		$this->session->set('usuarios_hashs_token', null);

		if ($validacao) {
			$params = array(
				'fields' => array('id', 'id_usuario'), //OK
				'from' =>  'usuarios_hashs', //OK
				'where' => array('hash' => $token, 'utilizado' => false, 'data_validade >=' => date('Y-m-d H:i:s'), 'data_insert <=' => date('Y-m-d H:i:s')),
				'limit' => '1',
			);
			$results = $model->getQuery($params);

			if (count($results) > 0) {
				//Grava nova senha
				$senha = $this->request->getVar('senha');
				$dadosNovaSenha = array('senha' => md5($senha));
				$whereNovaSenha = array('where' => array('id' => $results[0]['id_usuario']));
				$model->setUpdate('usuarios', $dadosNovaSenha, $whereNovaSenha);

				//Grava token utilizado
				$dados = array('utilizado' => true);
				$where = array('where' => array('hash' => $token, 'utilizado' => false, 'data_validade >=' => date('Y-m-d H:i:s'), 'data_insert <=' => date('Y-m-d H:i:s')));
				$model->setUpdate('usuarios_hashs', $dados, $where);

				//E-mail Senha Alterada			
				//$email->setTo($usuario);
				//$email->setSubject('Acesso ao Reset de sua senha FeTx');
				//$email->setMessage('Olá, Você solicitou uma nova senha no site da FeTx. Para concluir esta operação, basta clicar no link abaixo.<br/><a href="' . base_url('/novasenhatoken//' . $token) . '">Refinir nova senha</a><br/><br/>Atenção, link é válido por apenas 2 horas. Após esse período, você deverá fazer uma nova solicitação.');
				//$email->send();

				$this->session->set('usuarios_nova_senha_sucesso', '1');
				return redirect()->to('/novasenhatoken//' . $token);
			} else {
				$this->session->set('usuarios_nova_senha_erro', 'Token já foi utilizado');
				$this->session->set('usuarios_nova_senha_sucesso', '0');
				return redirect()->to('/novasenhatoken//' . $token);
			}
		} else {
			$this->session->set('usuarios_nova_senha_erro', \Config\Services::validation()->listErrors());
			$this->session->set('usuarios_nova_senha_sucesso', '0');
			return redirect()->to('/novasenhatoken//' . $token);
		}
	}

	private function validaLogin()
	{
		helper(['form', 'url']);
		$valida = \Config\Services::validation();

		$valida->setRules([
			'login' => ['label' => 'Login', 'rules' => 'required|min_length[3]|max_length[255]'],
			'senha' => ['label' => 'Senha', 'rules' => 'required'],
		]);

		if ($valida->withRequest($this->request)->run()) {
			return true;
		} else {
			return false;
		}
	}
	private function validaNovaSenha()
	{
		helper(['form', 'url']);
		$valida = \Config\Services::validation();

		$valida->setRules([
			'senha' => ['label' => 'Senha', 'rules' => 'required|min_length[6]'],
			'senhaConfirmacao' => ['label' => 'Confirmação de Senha', 'rules' => 'required|matches[senha]'],
		]);


		if ($valida->withRequest($this->request)->run()) {
			return true;
		} else {
			return false;
		}
	}

	private function valida()
	{
		helper(['form', 'url']);
		$valida = \Config\Services::validation();
		$id = $this->request->getVar('id');
		$senha = $this->request->getVar('senha');

		if ($id != null && $id > 0) {
			//Verifica se foi digitado a senhase foi faz a validação
			if ($senha == null && $senha == "") {
				$valida->setRules([
					'nome' => ['label' => 'Nome', 'rules' => 'required|min_length[3]|max_length[255]'],
					'id_grupo_usuario' => ['label' => 'Grupo', 'rules' => 'required'],
					'email' => ['label' => 'E-mail', 'rules' => 'required|valid_email|is_unique[usuarios.login,id,' . $id . ']'],
				]);
			} else {
				$valida->setRules([
					'nome' => ['label' => 'Nome', 'rules' => 'required|min_length[3]|max_length[255]'],
					'id_grupo_usuario' => ['label' => 'Grupo', 'rules' => 'required'],
					'email' => ['label' => 'E-mail', 'rules' => 'required|valid_email|is_unique[usuarios.login,id,' . $id . ']'],
					'senha' => ['label' => 'Senha', 'rules' => 'required|min_length[6]'],
					'senhaConfirmacao' => ['label' => 'Confirmação de Senha', 'rules' => 'required|matches[senha]'],
				]);
			}
		} else {
			$valida->setRules([
				'nome' => ['label' => 'Nome', 'rules' => 'required|min_length[3]|max_length[255]'],
				'id_grupo_usuario' => ['label' => 'Grupo', 'rules' => 'required'],
				'email' => ['label' => 'E-mail', 'rules' => 'required|valid_email|is_unique[usuarios.login,id,' . $id . ']'],
				'senha' => ['label' => 'Senha', 'rules' => 'required|min_length[6]'],
				'senhaConfirmacao' => ['label' => 'Confirmação de Senha', 'rules' => 'required|matches[senha]'],
			]);
		}

		if ($valida->withRequest($this->request)->run()) {
			return true;
		} else {
			return false;
		}
	}
	//--------------------------------------------------------------------
}
