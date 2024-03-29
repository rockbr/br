<?php

namespace App\Controllers;

use App\Models\PadraoModel;

class Util extends BaseController
{

	protected $session;

	function __construct()
	{
		$this->session = \Config\Services::session();
	}

	public function comboReceberMesAno()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$sql = 'SELECT to_char(data_vencimento,\'MM/YYYY\') AS mes_ano
        FROM fin_movimento_financeiro
        WHERE baixada=FALSE AND id_tipo_movimento_financeiro=\'CR\'
        GROUP BY mes_ano
        ORDER BY mes_ano';


		return $model->getQueryCustomTrio($sql);
	}

	public function comboPagarMesAno()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$sql = 'SELECT to_char(data_vencimento,\'MM/YYYY\') AS mes_ano
        FROM fin_movimento_financeiro
        WHERE baixada=FALSE AND id_tipo_movimento_financeiro=\'CP\'
        GROUP BY mes_ano
        ORDER BY mes_ano';


		return $model->getQueryCustomTrio($sql);
	}

	public function comboSituacaoTicket()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$paramsGrupo = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'tipos', //OK	
			'where' => array('id_subtipo' =>  4),
			'order_by' => 'id', //OK
			'order_by_direction' => 'ASC', //OK

		);


		return $model->getQuery($paramsGrupo);
	}

	public function comboPrioridadeTicket()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$paramsGrupo = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'tipos', //OK	
			'where' => array('id_subtipo' =>  5),
			'order_by' => 'id', //OK
			'order_by_direction' => 'ASC', //OK

		);


		return $model->getQuery($paramsGrupo);
	}

	public function comboCategoriaTicket()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$paramsGrupo = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'tipos', //OK	
			'where' => array('id_subtipo' =>  6),
			'order_by' => 'nome', //OK
			'order_by_direction' => 'ASC', //OK

		);


		return $model->getQuery($paramsGrupo);
	}

	public function comboCategoriaTicketFiltro($categorias)
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$sql = 'SELECT id, nome FROM tipos WHERE id IN (' . $categorias . ') AND  id_subtipo = 6 AND data_delete IS NULL ORDER BY nome';

		return $model->getQueryCustom($sql);
	}

	public function comboFormaPagamento()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$paramsGrupo = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'formas_pagamentos', //OK				
			'order_by' => 'nome', //OK
			'order_by_direction' => 'ASC', //OK

		);


		return $model->getQuery($paramsGrupo);
	}

	public function comboSituacaoCompra()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$paramsGrupo = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'tipos', //OK	
			'where' => array('id_subtipo' =>  7),
			'order_by' => 'id', //OK
			'order_by_direction' => 'ASC', //OK

		);


		return $model->getQuery($paramsGrupo);
	}

	public function comboPrioridadeCompra()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$paramsGrupo = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'tipos', //OK	
			'where' => array('id_subtipo' =>  8),
			'order_by' => 'id', //OK
			'order_by_direction' => 'ASC', //OK

		);


		return $model->getQuery($paramsGrupo);
	}

	public function comboCategoriaCompra()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$paramsGrupo = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'tipos', //OK	
			'where' => array('id_subtipo' =>  9),
			'order_by' => 'nome', //OK
			'order_by_direction' => 'ASC', //OK

		);


		return $model->getQuery($paramsGrupo);
	}

	public function comboGrupoUsuario()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$id = $this->session->get('id_grupo_usuario');

		$paramsGrupo = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'grupos_usuarios', //OK	
			'where' => array('id_empresa' =>  $this->session->get('id_empresa')),
			'order_by' => 'nome', //OK
			'order_by_direction' => 'ASC', //OK

		);


		return $model->getQuery($paramsGrupo);
	}

	public function comboGrupoPessoa()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$paramsGrupoPessoa = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'grupos_pessoas', //OK	
			'where' => array('id_empresa' =>  $this->session->get('id_empresa')),
			'order_by' => 'nome', //OK
			'order_by_direction' => 'ASC', //OK

		);
		return $model->getQuery($paramsGrupoPessoa);
	}

	public function comboGrupoProduto()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$paramsGrupoProduto = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'grupos_produtos', //OK		
			'where' => array('id_empresa' =>  $this->session->get('id_empresa')),
			'order_by' => 'nome', //OK
			'order_by_direction' => 'ASC', //OK

		);
		return $model->getQuery($paramsGrupoProduto);
	}

	public function comboEstado()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$paramsEstados = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'estados', //OK			
			'order_by' => 'nome', //OK
			'order_by_direction' => 'ASC', //OK

		);
		return $model->getQuery($paramsEstados);
	}

	public function comboUsuario()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$paramsClientes = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'usuarios', //OK	
			'where' => array('id_empresa' =>  $this->session->get('id_empresa')),
			'order_by' => 'nome', //OK
			'order_by_direction' => 'ASC', //OK

		);
		return $model->getQuery($paramsClientes);
	}

	public function comboCliente()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$paramsClientes = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'pessoas', //OK	
			'where' => array('id_empresa' =>  $this->session->get('id_empresa')),
			'order_by' => 'nome', //OK
			'order_by_direction' => 'ASC', //OK

		);
		return $model->getQuery($paramsClientes);
	}

	public function comboFornecedor()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$paramsClientes = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'pessoas', //OK	
			'where' => array('id_empresa' =>  $this->session->get('id_empresa')),
			'order_by' => 'nome', //OK
			'order_by_direction' => 'ASC', //OK

		);
		return $model->getQuery($paramsClientes);
	}

	public function comboProduto()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();

		$paramsProdutos = array(
			'fields' => array('id', 'nome'), //OK
			'from' =>  'produtos', //OK	
			'where' => array('id_empresa' =>  $this->session->get('id_empresa')),
			'order_by' => 'nome', //OK
			'order_by_direction' => 'ASC', //OK

		);
		return $model->getQuery($paramsProdutos);
	}

	public function acao($id, $valor)
	{
		if ($id == null && $valor != null)
			return 'I';
		else if ($id != null && $valor == null)
			return 'D';
		else if ($id != null && $valor != null)
			return 'U';
	}

	public function likeMatch($valor)
	{
		if ($valor == null || $valor == '')
			return '';
		else
			return strtoupper(strtr(utf8_decode($valor), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'));
	}
}
