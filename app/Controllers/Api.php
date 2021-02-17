<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PadraoModel;

class Api extends ResourceController
{
    protected $format    = 'json';

    #region GET

    public function getProdutos()
    {
        try {
            $model = new PadraoModel();
            $erro = array();

            $idUsuario = $this->request->getVar('id_usuario');


            if (!isset($idUsuario) || empty($idUsuario)) {
                array_push($erro, array('id_usuario' => 'nao informado'));
            }

            if ($erro != null) {
                return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode($erro));
            } else {
                $sql = 'SELECT id, codigo, nome FROM produtos WHERE data_delete is null AND (SELECT id_produto_lista FROM usuarios WHERE data_delete is null AND id = ' . $idUsuario . ') @> ARRAY[id] ORDER BY nome';
                $retorno = $model->getQueryCustom($sql);

                return $this->response->setStatusCode(200)->setContentType('json')->setBody(json_encode($retorno));
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao consultar produtos"}'));
            //return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode($e . '{"erro":"Erro ao consultar produtos"}'));
        }
    }

    public function getClientes()
    {
        try {
            $model = new PadraoModel();
            $erro = array();

            $idUsuario = $this->request->getVar('id_usuario');


            if (!isset($idUsuario) || empty($idUsuario)) {
                array_push($erro, array('id_usuario' => 'nao informado'));
            }

            if ($erro != null) {
                return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode($erro));
            } else {
                $sql = 'SELECT id, codigo, nome FROM pessoas WHERE data_delete is null AND tipo LIKE \'%C%\' AND (SELECT id_pessoa_lista FROM usuarios WHERE data_delete is null AND id = ' . $idUsuario . ') @> ARRAY[id] ORDER BY nome';
                $retorno = $model->getQueryCustom($sql);

                return $this->response->setStatusCode(200)->setContentType('json')->setBody(json_encode($retorno));
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao consultar clientes"}'));
            //return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode($e . '{"erro":"Erro ao consultar clientes"}'));
        }
    }

    public function getLogin()
    {
        try {
            $model = new PadraoModel();
            $erro = array();

            $login = $this->request->getVar('login');
            $senha = $this->request->getVar('senha');


            if (!isset($login) || empty($login)) {
                array_push($erro, array('login' => 'nao informado'));
            }
            if (!isset($senha) || empty($senha)) {
                array_push($erro, array('senha' => 'nao informada'));
            }

            if ($erro != null) {
                return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode($erro));
            } else {
                $params = array(
                    'fields' => array('id', 'nome', 'login', 'mostrar_ponto', 'mostrar_intervalo', 'mostrar_almoco', 'mostrar_foto', 'mostrar_vendas', 'mostrar_precos', 'mostrar_km', 'tempo_intervalo', 'tempo_almoco', 'bloqueado'),
                    'from' =>  'usuarios',
                    'where' => array('login' => $login, 'senha' => $senha, 'bloqueado' => false, 'acesso_app' => true),
                    'order_by' => 'nome',
                    'order_by_direction' => 'ASC', //OKn
                );
                $retorno = $model->getQuery($params);

                if (count($retorno) >= 1) {
                    return $this->response->setStatusCode(200)->setContentType('json')->setBody(json_encode($retorno));
                } else {
                    return $this->response->setStatusCode(200)->setContentType('text/plain')->setBody('Erro');
                }
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao fazer login"}'));
        }
    }

    public function getLoginPorId()
    {
        try {
            $model = new PadraoModel();
            $erro = array();

            $login = $this->request->getVar('login');
            $id = $this->request->getVar('id');


            if (!isset($login) || empty($login)) {
                array_push($erro, array('login' => 'nao informado'));
            }
            if (!isset($id) || empty($id)) {
                array_push($erro, array('id' => 'nao informada'));
            }

            if ($erro != null) {
                return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode($erro));
            } else {
                $params = array(
                    'fields' => array('id', 'nome', 'login', 'mostrar_ponto', 'mostrar_intervalo', 'mostrar_almoco', 'mostrar_foto', 'mostrar_vendas', 'mostrar_precos', 'mostrar_km', 'tempo_intervalo', 'tempo_almoco', 'bloqueado'),
                    'from' =>  'usuarios',
                    'where' => array('login' => $login, 'id' => $id, 'bloqueado' => false, 'acesso_app' => true),
                    'order_by' => 'nome',
                    'order_by_direction' => 'ASC', //OKn
                );
                $retorno = $model->getQuery($params);

                if (count($retorno) >= 1) {
                    return $this->response->setStatusCode(200)->setContentType('json')->setBody(json_encode($retorno));
                } else {
                    return $this->response->setStatusCode(200)->setContentType('text/plain')->setBody('Erro');
                }
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao fazer login"}'));
        }
    }

    #endregion

    #region Save

    public function saveKm()
    {
        try {

            $model = new PadraoModel();
            $erro = array();

            $dataApp = $this->request->getVar('data_app');
            $dataHoraApp = $this->request->getVar('data_hora_app');
            $idUsuario = $this->request->getVar('id_usuario');
            $idPessoa = $this->request->getVar('id_pessoa');
            $tipo = $this->request->getVar('tipo');
            $km = $this->request->getVar('km');

            if (!isset($dataApp) || empty($dataApp)) {
                array_push($erro, array('data_app' => 'nao informada'));
            }
            if (!isset($dataHoraApp) || empty($dataHoraApp)) {
                array_push($erro, array('data_hora_app' => 'nao informada'));
            }
            if (!isset($idUsuario) || empty($idUsuario)) {
                array_push($erro, array('id_usuario' => 'nao informado'));
            }
            if (!isset($idPessoa) || empty($idPessoa)) {
                array_push($erro, array('id_pessoa' => 'nao informado'));
            }
            if (!isset($tipo) || empty($tipo)) {
                array_push($erro, array('tipo' => 'nao informado'));
            }
            if (!isset($km) || empty($km)) {
                array_push($erro, array('km' => 'nao informado'));
            }

            if ($erro != null) {
                return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode($erro));
            } else {

                $dados = array(
                    'data_app' => $dataApp,
                    'data_hora_app' => $dataHoraApp,
                    'id_usuario' => $idUsuario,
                    'id_pessoa' => $idPessoa,
                    'tipo' => $tipo,
                    'km' => $km,
                );

                $retorno = $model->setInsert('app_km', $dados);

                if ($retorno <= 0) {
                    return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao inserir registro no banco"}'));
                } else {
                    return $this->response->setStatusCode(201)->setContentType('json')->setBody(json_encode('{"sucesso":"' . $retorno . '"}'));
                }
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao inserir registro"}'));
        }
    }

    public function saveOcorrencias()
    {
        try {

            $model = new PadraoModel();
            $erro = array();

            $dataApp = $this->request->getVar('data_app');
            $dataHoraApp = $this->request->getVar('data_hora_app');
            $idUsuario = $this->request->getVar('id_usuario');
            $idPessoa = $this->request->getVar('id_pessoa');
            $ocorrencia = $this->request->getVar('ocorrencia');

            if (!isset($dataApp) || empty($dataApp)) {
                array_push($erro, array('data_app' => 'nao informada'));
            }
            if (!isset($dataHoraApp) || empty($dataHoraApp)) {
                array_push($erro, array('data_hora_app' => 'nao informada'));
            }
            if (!isset($idUsuario) || empty($idUsuario)) {
                array_push($erro, array('id_usuario' => 'nao informado'));
            }
            if (!isset($idPessoa) || empty($idPessoa)) {
                array_push($erro, array('id_pessoa' => 'nao informado'));
            }
            if (!isset($ocorrencia) || empty($ocorrencia)) {
                array_push($erro, array('ocorrencia' => 'nao informada'));
            }

            if ($erro != null) {
                return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode($erro));
            } else {

                $dados = array(
                    'data_app' => $dataApp,
                    'data_hora_app' => $dataHoraApp,
                    'id_usuario' => $idUsuario,
                    'id_pessoa' => $idPessoa,
                    'ocorrencia' => $ocorrencia,
                );

                $retorno = $model->setInsert('app_ocorrencias', $dados);

                if ($retorno <= 0) {
                    return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao inserir registro no banco"}'));
                } else {
                    return $this->response->setStatusCode(201)->setContentType('json')->setBody(json_encode('{"sucesso":"' . $retorno . '"}'));
                }
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao inserir registro"}'));
        }
    }

    public function savePontos()
    {
        try {

            $model = new PadraoModel();
            $erro = array();

            $dataApp = $this->request->getVar('data_app');
            $dataHoraApp = $this->request->getVar('data_hora_app');
            $idUsuario = $this->request->getVar('id_usuario');
            $idPessoa = $this->request->getVar('id_pessoa');
            $latitude = $this->request->getVar('latitude');
            $longitude = $this->request->getVar('longitude');
            $tipo = $this->request->getVar('tipo');
            $observacao = $this->request->getVar('observacao');
            $endereco = $this->request->getVar('endereco');

            if (!isset($dataApp) || empty($dataApp)) {
                array_push($erro, array('data_app' => 'nao informada'));
            }
            if (!isset($dataHoraApp) || empty($dataHoraApp)) {
                array_push($erro, array('data_hora_app' => 'nao informada'));
            }
            if (!isset($idUsuario) || empty($idUsuario)) {
                array_push($erro, array('id_usuario' => 'nao informado'));
            }
            if (!isset($idPessoa) || empty($idPessoa)) {
                array_push($erro, array('id_pessoa' => 'nao informado'));
            }
            if (!isset($tipo) || empty($tipo)) {
                array_push($erro, array('tipo' => 'nao informado'));
            }

            if ($erro != null) {
                return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode($erro));
            } else {

                $dados = array(
                    'data_app' => $dataApp,
                    'data_hora_app' => $dataHoraApp,
                    'id_usuario' => $idUsuario,
                    'id_pessoa' => $idPessoa,
                    'latitude' => ((!isset($latitude) || empty($latitude)) ? null : $latitude),
                    'longitude' => ((!isset($longitude) || empty($longitude)) ? null : $longitude),
                    'tipo' => $tipo,
                    'observacao' => $observacao,
                    'endereco' => $endereco
                );

                $retorno = $model->setInsert('app_pontos', $dados);

                if ($retorno <= 0) {
                    return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao inserir registro no banco"}'));
                } else {
                    return $this->response->setStatusCode(201)->setContentType('json')->setBody(json_encode('{"sucesso":"' . $retorno . '"}'));
                }
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao inserir registro"}'));
        }
    }

    public function saveProdutoValores()
    {
        try {

            $model = new PadraoModel();
            $erro = array();

            $dataApp = $this->request->getVar('data_app');
            $dataHoraApp = $this->request->getVar('data_hora_app');
            $idUsuario = $this->request->getVar('id_usuario');
            $idPessoa = $this->request->getVar('id_pessoa');
            $idProduto = $this->request->getVar('id_produto');
            $valor = $this->request->getVar('valor');

            if (!isset($dataApp) || empty($dataApp)) {
                array_push($erro, array('data_app' => 'nao informada'));
            }
            if (!isset($dataHoraApp) || empty($dataHoraApp)) {
                array_push($erro, array('data_hora_app' => 'nao informada'));
            }
            if (!isset($idUsuario) || empty($idUsuario)) {
                array_push($erro, array('id_usuario' => 'nao informado'));
            }
            if (!isset($idPessoa) || empty($idPessoa)) {
                array_push($erro, array('id_pessoa' => 'nao informado'));
            }
            if (!isset($idProduto) || empty($idProduto)) {
                array_push($erro, array('id_produto' => 'nao informado'));
            }
            if ($valor != 0 && (!isset($valor) || empty($valor))) {
                array_push($erro, array('valor' => 'nao informado'));
            }

            if ($erro != null) {
                return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode($erro));
            } else {

                $dados = array(
                    'data_app' => $dataApp,
                    'data_hora_app' => $dataHoraApp,
                    'id_usuario' => $idUsuario,
                    'id_pessoa' => $idPessoa,
                    'id_produto' => $idProduto,
                    'valor' => floatval(str_replace(',', '.', $valor))
                );

                $where = array('data_app' => $dataApp, 'id_usuario' => $idUsuario, 'id_pessoa' => $idPessoa, 'id_produto' => $idProduto);
                $retorno = $model->setInsertOrUpdate('app_produto_valores', $dados, $where);

                if ($retorno <= 0) {
                    return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao inserir registro no banco"}'));
                } else {
                    return $this->response->setStatusCode(201)->setContentType('json')->setBody(json_encode('{"sucesso":"' . $retorno . '"}'));
                }
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao inserir registro"}'));
            //return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode($e));
        }
    }

    public function saveProdutoVendas()
    {
        try {

            $model = new PadraoModel();
            $erro = array();

            $dataApp = $this->request->getVar('data_app');
            $dataHoraApp = $this->request->getVar('data_hora_app');
            $idUsuario = $this->request->getVar('id_usuario');
            $idPessoa = $this->request->getVar('id_pessoa');
            $idProduto = $this->request->getVar('id_produto');
            $quantidade = $this->request->getVar('quantidade');

            if (!isset($dataApp) || empty($dataApp)) {
                array_push($erro, array('data_app' => 'nao informada'));
            }
            if (!isset($dataHoraApp) || empty($dataHoraApp)) {
                array_push($erro, array('data_hora_app' => 'nao informada'));
            }
            if (!isset($idUsuario) || empty($idUsuario)) {
                array_push($erro, array('id_usuario' => 'nao informado'));
            }
            if (!isset($idPessoa) || empty($idPessoa)) {
                array_push($erro, array('id_pessoa' => 'nao informado'));
            }
            if (!isset($idProduto) || empty($idProduto)) {
                array_push($erro, array('id_produto' => 'nao informado'));
            }
            if ($quantidade != 0 && (!isset($quantidade) || empty($quantidade))) {
                array_push($erro, array('quantidade' => 'nao informada'));
            }

            if ($erro != null) {
                return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode($erro));
            } else {

                $dados = array(
                    'data_app' => $dataApp,
                    'data_hora_app' => $dataHoraApp,
                    'id_usuario' => $idUsuario,
                    'id_pessoa' => $idPessoa,
                    'id_produto' => $idProduto,
                    'quantidade' => floatval(str_replace('.', ',', $quantidade)),
                );

                $where = array('where' => array('data_app' => $dataApp, 'id_usuario' => $idUsuario, 'id_pessoa' => $idPessoa, 'id_produto' => $idProduto));
                $retorno = $model->setInsertOrUpdate('app_produto_vendas', $dados, $where);

                if ($retorno <= 0) {
                    return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao inserir registro no banco"}'));
                } else {
                    return $this->response->setStatusCode(201)->setContentType('json')->setBody(json_encode('{"sucesso":"' . $retorno . '"}'));
                }
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao inserir registro"}'));
            //return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode($e));
        }
    }

    public function saveFotos()
    {
        try {

            $model = new PadraoModel();
            $erro = array();

            $dataApp = $this->request->getVar('data_app');
            $dataHoraApp = $this->request->getVar('data_hora_app');
            $idUsuario = $this->request->getVar('id_usuario');
            $idPessoa = $this->request->getVar('id_pessoa');
            $imgBase64 = $this->request->getVar('img_base64');
            $caminho = $idPessoa . "/" . date('Y') . "/" . date('m') . "/";
            $nomeImagem = md5(uniqid(time())) . ".jpg";

            if (!isset($dataApp) || empty($dataApp)) {
                array_push($erro, array('data_app' => 'nao informada'));
            }
            if (!isset($dataHoraApp) || empty($dataHoraApp)) {
                array_push($erro, array('data_hora_app' => 'nao informada'));
            }
            if (!isset($idUsuario) || empty($idUsuario)) {
                array_push($erro, array('id_usuario' => 'nao informado'));
            }
            if (!isset($idPessoa) || empty($idPessoa)) {
                array_push($erro, array('id_pessoa' => 'nao informado'));
            }
            if (!isset($imgBase64) || empty($imgBase64)) {
                array_push($erro, array('img_base64' => 'nao informado'));
            }

            if ($erro != null) {
                return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode($erro));
            } else {

                $dados = array(
                    'data_app' => $dataApp,
                    'data_hora_app' => $dataHoraApp,
                    'id_usuario' => $idUsuario,
                    'id_pessoa' => $idPessoa,
                    'img_base64' => $imgBase64,
                    'caminho_imagem' => $caminho . $nomeImagem
                );

                //Tenta gravar a foto primeiro se não der erro pode gravar no banco
                self::gravarImagem($imgBase64, $caminho, $nomeImagem);
                $retorno = $model->setInsert('app_fotos', $dados);

                if ($retorno <= 0) {
                    return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao inserir registro no banco"}'));
                } else {                    
                    return $this->response->setStatusCode(201)->setContentType('json')->setBody(json_encode('{"sucesso":"' . $retorno . '"}'));
                }
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setContentType('json')->setBody(json_encode('{"erro":"Erro ao inserir registro"}'));
        }
    }

    private function gravarImagem($imgBase64, $caminho, $nomeImg)
    {
        helper(['form', 'url', 'path']);

        $image = base64_decode($imgBase64);
        //rename file name with random number

        try {
            mkdir('assets/img/' . $caminho, 0777, true); // Cria uma pasta dentro da outra (que também e nova) - Criação Recursiva
        } catch (\Exception $e) {
        }

        $path = realpath('assets/img/' . $caminho);
        //image uploading folder path
        file_put_contents($path . '/' . $nomeImg, $image);
        // image is bind and upload to respective folder
    }

    #endregion
}
