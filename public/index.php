<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

date_default_timezone_set('America/Campo_Grande');

require_once '../Database.php';
require_once '../Functions.php';
require_once '../controllers/PlanosController.php';
require_once '../controllers/PropostaController.php';

// subir server no ip 10.1.1.20:8888

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  if (getParam('action') === 'list_plans') {
    header('content-type: application/json');
    echo (new PlanosController)->listPlans();
    die();
  }

  if (getParam('action') === 'list_type_clients') {
    header('content-type: application/json');
    echo (new PlanosController)->listTypesClients();
    die();
  }

  if (getParam('action') === 'list_open_proposal') {
    echo (new PropostaController)->listAllPropostaOpen();
    die();
  }

  if (getParam('action') === 'list_propsal_by_id') {
    header('content-type: application/json');
    echo (new PropostaController)->listPropostaById();
    die();
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (getParam('action') === 'create_plans') {
    (new PlanosController)->createPlan();
    die();
  }

  if (getParam('action') === 'create_proposta') {
    (new PropostaController)->createProposta();
    die();
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
  if (getParam('action') === 'update_plan') {
    (new PlanosController)->updatePlan();
    die();
  }
}
