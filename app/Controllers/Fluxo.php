<?php

namespace App\Controllers;

use App\Models\PadraoModel;

class Fluxo extends BaseController
{
	protected $tabela = 'empresas';
	protected $session;

	function __construct()
	{
		$this->session = \Config\Services::session();
	}

	public function dashboard()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}
		$util = new Util();
		$model = new PadraoModel();

		$data_header = array('session' => $this->session);
		$dados = array(
			'mes_ano' =>  date('m/Y')
		);

		$data = array(
			'dados' => $dados,
			'receber_mes_ano' => $util->comboReceberMesAno(),
			'pagar_mes_ano' => $util->combopagarMesAno(),
		);

		echo view('admin/main_header', $data_header);
		echo view('admin/financeiro/dashboard_contas', $data);
		echo view('admin/main_footer');
	}

	public function list()
	{
		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}
		$util = new Util();
		$model = new PadraoModel();

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

		$filtroDataInicial = $this->request->getVar('filtro_data_inicial') == null ? ('01/' . date('M/Y')) : $this->request->getVar('filtro_data_inicial');
		$filtroDataFinal = $this->request->getVar('filtro_data_final') == null ? date('d/M/Y') : $this->request->getVar('filtro_data_final');		

		$data_header = array(
			'titulo' => 'Fluxo',
			'subtitulo' => 'FLUXO',
			'session' => $this->session
		);


		$sql = 'SELECT 0 AS id, to_char(f.data_vencimento,\'DD/MM/YYYY\') AS data, 
        to_char(0.0,\'"R$ " 999G999G990D99\') AS caixa,
        (SELECT to_char(SUM(r.valor),\'"R$ " 999G999G990D99\') FROM fin_movimento_financeiro r LEFT JOIN fin_motivo_movto m ON (m.id=r.id_fin_motivo_movto) WHERE r.data_vencimento=f.data_vencimento AND r.baixada=FALSE AND r.id_tipo_movimento_financeiro=\'CR\' AND m.id_fin_tipo_motivo_movto=15) AS receber_boleto, 
        (SELECT to_char(SUM(r.valor),\'"R$ " 999G999G990D99\') FROM fin_movimento_financeiro r LEFT JOIN fin_motivo_movto m ON (m.id=r.id_fin_motivo_movto) WHERE r.data_vencimento=f.data_vencimento AND r.baixada=FALSE AND r.id_tipo_movimento_financeiro=\'CR\' AND m.id_fin_tipo_motivo_movto!=15) AS receber_outros, 
        (SELECT to_char(SUM(p.valor),\'"R$ " 999G999G990D99\') FROM fin_movimento_financeiro p WHERE p.data_vencimento=f.data_vencimento AND p.baixada=FALSE AND p.id_tipo_movimento_financeiro=\'CP\') AS pagar 
        FROM fin_movimento_financeiro f
        WHERE f.baixada=FALSE AND f.data_vencimento >=\'' . $filtroDataInicial . '\' AND f.data_vencimento <=\'' . $filtroDataFinal . '\'
        GROUP BY f.data_vencimento
        ORDER BY f.data_vencimento DESC';

		$sqlCount = 'SELECT count(*) 
        FROM fin_movimento_financeiro f
        WHERE f.baixada=FALSE AND f.data_vencimento >=\'' . $filtroDataInicial . '\' AND f.data_vencimento <=\'' . $filtroDataFinal . '\'
        GROUP BY f.data_vencimento
        ORDER BY f.data_vencimento DESC';

		$table_tbody = $model->getQueryCustomTrio($sql);
		$table_count = $model->getQueryCustomTrio($sqlCount);

		$data = array(
			'table_thead' =>  array('Data', 'Caixa', 'Receber Boleto', 'Receber Outros', 'Pagar'),
			'table_tbody' => $table_tbody,
			'acoes_filtro' => array(
				array('<', "Data Inicial", $filtroDataInicial, 'date', 'filtro_data_inicial'), //label, value, type, id
				array('>', "Data Final", $filtroDataFinal, 'date', 'filtro_data_final'), //label, value, type, id			
			),
			'sucesso' => $sucesso,
			'acoes_novo' => '',
			'acoes_editar' => '',
			'acoes_deletar' => '',
			'pagina' => $pagina,
			'paginacao_de' => ($offset + 1),
			'paginacao_ate' => ($offset == 0 ? count($table_tbody) : ($offset + count($table_tbody))),
			'paginacao_total' => $table_count[0]['count'],
			'limite' => $limit,
			'url' => site_url('consultafluxo'),
		);

		echo view('admin/main_header', $data_header);
		echo view('admin/main_list',  $data);
		echo view('admin/main_footer');
	}

	public function receberPorMes()
	{
		helper('form', 'url');

		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();
		$security = \Config\Services::security();
		$reponse = array();
		$retorno = array();

		$query = $this->request->getVar('query');

		$sql = 'SELECT to_char(data_vencimento,\'DD\') AS dia, SUM(valor) AS valor
        FROM fin_movimento_financeiro 
        WHERE baixada=FALSE AND to_char(data_vencimento,\'MM/YYYY\') =\'' . $query . '\' AND id_tipo_movimento_financeiro=\'CR\'
        GROUP BY data_vencimento ORDER BY data_vencimento';

		$retorno = $model->getQueryCustomTrio($sql);

		if ($retorno != null) {
			$reponse = array(
				'csrf_name' => $security->getCSRFTokenName(),
				'csrf_hash' => $security->getCSRFHash(),
				'labels' => array_column($retorno, 'dia'),
				'points' => array_column($retorno, 'valor'),
			);
		} else {
			$reponse = array(
				'csrf_name' => $security->getCSRFTokenName(),
				'csrf_hash' => $security->getCSRFHash(),
				'labels' =>  '',
				'points' => '',
			);
		}

		echo json_encode($reponse);
		die;
	}

	public function pagarPorMes()
	{
		helper('form', 'url');

		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();
		$security = \Config\Services::security();
		$reponse = array();
		$retorno = array();

		$query = $this->request->getVar('query');

		$sql = 'SELECT to_char(data_vencimento,\'DD\') AS dia, SUM(valor) AS valor 
        FROM fin_movimento_financeiro 
        WHERE baixada=FALSE AND to_char(data_vencimento,\'MM/YYYY\') =\'' . $query . '\' AND id_tipo_movimento_financeiro=\'CP\' 
        GROUP BY data_vencimento ORDER BY data_vencimento';

		$retorno = $model->getQueryCustomTrio($sql);


		if ($retorno != null) {
			$reponse = array(
				'csrf_name' => $security->getCSRFTokenName(),
				'csrf_hash' => $security->getCSRFHash(),
				'labels' => array_column($retorno, 'dia'),
				'points' => array_column($retorno, 'valor'),
			);
		} else {
			$reponse = array(
				'csrf_name' => $security->getCSRFTokenName(),
				'csrf_hash' => $security->getCSRFHash(),
				'labels' =>  '',
				'points' => '',
			);
		}

		echo json_encode($reponse);
		die;
	}

	public function pagarReceberPorMes()
	{
		helper('form', 'url');

		if (!$this->session->get('logado')) {
			return redirect()->to('/');
		}

		$model = new PadraoModel();
		$security = \Config\Services::security();
		$reponse = array();
		$retorno = array();

		$query = $this->request->getVar('query');
		
		$sql = 'SELECT to_char(f.data_vencimento,\'DD\') AS dia, 
		coalesce((SELECT SUM(valor) AS valor FROM fin_movimento_financeiro p WHERE p.baixada=FALSE AND p.data_vencimento=f.data_vencimento AND (p.id_tipo_movimento_financeiro=\'CP\') GROUP BY p.data_vencimento),0) as pagar, 
		coalesce((SELECT SUM(valor) AS valor FROM fin_movimento_financeiro r WHERE r.baixada=FALSE AND r.data_vencimento=f.data_vencimento AND (r.id_tipo_movimento_financeiro=\'CR\') GROUP BY r.data_vencimento),0) as receber 
		FROM fin_movimento_financeiro f 
		WHERE f.baixada=FALSE AND to_char(f.data_vencimento,\'MM/YYYY\') =\'' . $query . '\'  AND (f.id_tipo_movimento_financeiro=\'CR\' OR f.id_tipo_movimento_financeiro=\'CR\') 
		GROUP BY f.data_vencimento ORDER BY f.data_vencimento;';

		$retorno = $model->getQueryCustomTrio($sql);

		if ($retorno != null) {
		
			$reponse = array(
				'csrf_name' => $security->getCSRFTokenName(),
				'csrf_hash' => $security->getCSRFHash(),
				'labels' => array_column($retorno, 'dia'),
				'pointspagar' => array_column($retorno, 'pagar'),
				'pointsreceber' => array_column($retorno, 'receber'),
			);
		} else {
			$reponse = array(
				'csrf_name' => $security->getCSRFTokenName(),
				'csrf_hash' => $security->getCSRFHash(),
				'labels' =>  '',
				'pointspagar' => '',
				'pointsreceber' => '',
			);
		}

		echo json_encode($reponse);
		die;
	}
}
