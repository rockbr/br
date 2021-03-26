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

		$data_header = array(
			'titulo' => 'Fluxo',
			'subtitulo' => 'FLUXO',
			'session' => $this->session
		);

		$sql = 'SELECT 0 AS id, to_char((f.data_vencimento + INTERVAL \'1 day\'),\'DD/MM/YYYY\') AS data, 
        to_char(0.0,\'"R$ " 999G999G990D99\') AS caixa,
        (SELECT to_char(SUM(r.valor),\'"R$ " 999G999G990D99\') FROM fin_movimento_financeiro r WHERE r.data_vencimento=f.data_vencimento AND r.baixada=FALSE AND r.id_tipo_movimento_financeiro=\'CR\') AS receb_total,
        (SELECT to_char(SUM(p.valor),\'"R$ " 999G999G990D99\') FROM fin_movimento_financeiro p WHERE p.data_vencimento=f.data_vencimento AND p.baixada=FALSE AND p.id_tipo_movimento_financeiro=\'CP\') AS pgto,
        to_char(0.0,\'"R$ " 999G999G990D99\')  AS adiantamento
        FROM fin_movimento_financeiro f
        WHERE f.baixada=FALSE AND f.data_vencimento >=\'2021-03-01\' AND f.data_vencimento <=\'2021-03-31\'
        GROUP BY f.data_vencimento
        ORDER BY f.data_vencimento DESC';

		$sqlCount = 'SELECT count(*) 
        FROM fin_movimento_financeiro f
        WHERE f.baixada=FALSE AND f.data_vencimento >=\'2021-03-01\' AND f.data_vencimento <=\'2021-03-31\'
        GROUP BY f.data_vencimento
        ORDER BY f.data_vencimento DESC';

		$table_tbody = $model->getQueryCustomTrio($sql);
		$table_count = $model->getQueryCustomTrio($sqlCount);

		$data = array(
			'table_thead' =>  array('Data', 'Caixa', 'Receb. Total', 'Pgto', 'Adiantamento'),
			'table_tbody' => $table_tbody,
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
        GROUP BY data_vencimento';

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
        GROUP BY data_vencimento';

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
}
