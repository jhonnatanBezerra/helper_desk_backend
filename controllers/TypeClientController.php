<?php

use database\Database;

class TypeClientController
{
  private $dataBase;

  public function __construct()
  {
    $this->dataBase = new Database();
  }
}
