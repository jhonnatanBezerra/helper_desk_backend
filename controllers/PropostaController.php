<?php

use database\Database;

class PropostaController
{
  private $dataBase;

  public function __construct()
  {
    $this->dataBase = new Database();
  }

  public function createProposta()
  {

    try {
      $proposta_delta = $this->dataBase->select('proposta_modelo', ['delta_quill'], ['id', getPostParam('propostaModelId'),]);
      $proposta_delta = $proposta_delta[0]['delta_quill'];
    } catch (\Exception $e) {
      return 'erro ' . $e->getMessage();
    }

    $data = [
      getPostParam('nomeEmpresa'),
      getPostParam('cnpj'),
      getPostParam('cidade'),
      getPostParam('responsavel'),
      getPostParam('telefone'),
      getPostParam('tipoClienteId'),
      $proposta_delta,
      date('Y-m-d H:i:s'),
    ];

    try {
      $response = $this->dataBase->runQuery('INSERT INTO `proposta` (`nome_empresa`, `cnpj`, `cidade`, `responsavel`, `telefone`, `cliente_tipo_id`, `proposta_delta`, `created_at`) VALUES (?,?,?,?,?,?,?,?) ', $data);
      return $response;
    } catch (\Exception $e) {
      return 'erro ' . $e->getMessage();
    }
  }

  public function listAllPropostaOpen()
  {
    try {
      // $response = $this->dataBase->select('proposta', ['*'], ['status_id', 1]);
      $response = $this->dataBase->runQuery('SELECT proposta.*, status.nome AS status_descricao from proposta inner join status on (proposta.status_id = status.id) where status.id = 1');


      return json_encode($response);
    } catch (\Exception $e) {
      return 'erro ' . $e->getMessage();
    }
  }

  public function listPropostaById()
  {
    try {

      $response = $this->dataBase->select('proposta', ['*'], ['id', getParam('id')])[0];
      return json_encode($response);
    } catch (\Exception $e) {
      return 'erro ' . $e->getMessage();
    }
  }
}
