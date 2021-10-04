<?php

use database\Database;

class PlanosController
{

  private $dataBase;

  public function __construct()
  {
    $this->dataBase = new Database();
  }


  public function listPlans()
  {
    $response = $this->dataBase->select('proposta_modelo', ['*']);
    return json_encode($response);
  }

  public function listTypesClients()
  {
    $response = $this->dataBase->select('type_client', ['*']);
    return json_encode($response);
  }

  public function createPlan()
  {
    $data = [
      getPostParam('nomePlano'),
      getPostParam('contentDelta'),
      date('Y-m-d H:i:s')
    ];

    try {
      $response = $this->dataBase->runQuery('INSERT INTO `proposta_modelo` (`nome_plano`, `delta_quill`, `created_at`) VALUES (?,?,?) ', $data);
      return $response;
    } catch (\Exception $e) {
      return 'erro ' . $e->getMessage();
    }
  }

  public function updatePlan()
  {
    $data = [
      getPostParam('nomePlano'),
      getPostParam('contentDelta'),
      date('Y-m-d H:i:s'),
      getPostParam('id')
    ];

    try {
      $response = $this->dataBase->runQuery('UPDATE proposta_modelo SET `nome_plano` = ?, `delta_quill`= ?, `updated_at`= ? WHERE `id`= ?', $data);
      return $response;
    } catch (\Exception $e) {
      return 'erro ' . $e->getMessage();
    }
  }
}
