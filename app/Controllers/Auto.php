<?php

namespace App\Controllers;

use App\Models\PadraoModel;

class Auto extends BaseController
{

	function __construct()
	{
		$this->session = \Config\Services::session();
	}

	public function cep()
	{
		helper('form', 'url');

		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();
		$security = \Config\Services::security();
		$query = $this->request->getVar('query');
		$reponse = array();
		$retorno = array();

		if ($query != null) {
			$params = array(
				'fields' => array('enderecos.id AS cep', 'enderecos.tipo_logradouro', 'enderecos.logradouro', 'enderecos.complemento', 'enderecos.local', 'bairros.nome AS bairro', 'cidades.id AS id_cidade', 'cidades.nome AS cidade', 'estados.id AS uf', 'estados.nome AS estado'), //OK
				'from' =>  'enderecos', //OK	
				'join' => array(
					'bairros' => array('bairros.id=enderecos.id_bairro', 'left'),
					'cidades' => array('cidades.id=enderecos.id_cidade', 'left'),
					'estados' => array('estados.id=cidades.id_estado', 'left')
				),
				'where' => array('enderecos.id' => $query),
				'order_by' => 'cep', //OK
				'order_by_direction' => 'ASC', //OK

			);
			$retorno = $model->getQuery($params);
		}

		if ($retorno != null) {
			$reponse = array(
				'csrf_name' => $security->getCSRFTokenName(),
				'csrf_hash' => $security->getCSRFHash(),
				'logradouro' => $retorno[0]['tipo_logradouro'] . ' ' . $retorno[0]['logradouro'],
				'id_cidade' => $retorno[0]['id_cidade'],
				'cidade' => $retorno[0]['cidade'],
				'id_estado' => $retorno[0]['uf'],
				'bairro' => $retorno[0]['bairro'],
				'complemento' => $retorno[0]['complemento'],
			);
		} else {
			$reponse = array(
				'csrf_name' => $security->getCSRFTokenName(),
				'csrf_hash' => $security->getCSRFHash(),
				'logradouro' => '',
				'id_cidade' => '',
				'cidade' => '',
				'id_estado' => '',
				'bairro' => '',
				'complemento' => '',
			);
		}

		echo json_encode($reponse);
		die;
	}

	public function autocomplete()
	{
		helper('form', 'url');

		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();
		$security = \Config\Services::security();
		$query = $this->request->getVar('query');
		$like = $this->request->getVar('like');
		$like_match = $this->request->getVar('like_match');
		$like_side = $this->request->getVar('like_side');

		$tabela = $this->request->getVar('tabela');

		$term = strtr(utf8_decode($query["term"]), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');

		$params = array(
			'fields' => array('id', 'nome AS value'),
			'from' =>  $tabela,

			'like' => ($like != null ? (array('nome', $like)) : array('nome')),
			'like_match' => ($like_match != null ? (array(strtoupper($term), $like_match)) : array(strtoupper($term))),
			'like_side' =>  ($like_side != null ? array('both', $like_side) : array('both', $like_side)),

			'or_like' => ($like != null ? (array('nome', $like)) : array('nome')),
			'or_like_match' => ($like_match != null ? (array(strtolower($term), $like_match)) : array(strtolower($term))),
			'or_like_side' =>  ($like_side != null ? array('both', $like_side) : array('both', $like_side)),

			'order_by' => 'nome',
			'order_by_direction' => 'ASC',

		);


		$retorno = $model->getQuery($params);

		$reponse = array(
			'csrf_name' => $security->getCSRFTokenName(),
			'csrf_hash' => $security->getCSRFHash(),
			'dados' => $retorno,
		);

		// Return results as json encoded array
		echo json_encode($reponse);
		die;
	}
}
