<?php

namespace database;

use Exception;
use PDO;

class Database
{
  private $database;

  public function __construct()
  {
    $host = 'localhost';
    $database = 'testes';
    $username = 'root';
    $password = '';
    $dsn = 'mysql:host=' . $host . ';dbname=' . $database;
    $user = $username;
    $pass = $password;
    try {
      $this->database = new PDO($dsn, $user, $pass, [
        PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      ]);
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * @return PDO
   */
  public function getDatabase(): PDO
  {
    return $this->database;
  }

  /**
   * @param string $sql
   * @param array $parameters
   * @return array
   */
  public function runQuery(string $sql, array $parameters = [], bool $debugOnly = false): array
  {
    if ($debugOnly) {
      die($this->interpolateQuery($sql, $parameters));
    }
    try {
      $statement = $this->database->prepare($sql);
      $statement->execute($parameters);
      return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (\PDOException $exception) {
      // 
      throw new Exception($exception->getMessage());
    }
  }

  /**
   * @param string $tableName
   * @param array $fields
   * @param array $where
   * @return array
   */
  public function select(string $tableName, array $fields = [], array $where = []): array
  {
    if (count($fields) === 0) {
      $sql = "select * from {$tableName}";
    } else {
      $fields = implode(', ', $fields);
      $sql = "select {$fields} from {$tableName}";
    }
    if (count($where) > 0) {
      if (count($where) === 2) {
        $sql .= " where " . $where[0] . " = " . $where[1];
      } else {
        $sql .= " where " . $where[0] . $where[1] . '"' . $where[2] . '"';
      }
    }
    return $this->runQuery($sql, []);
  }

  /**
   * @param string $table
   * @param array $fieldsAndValues
   * @return array
   */
  public function create(string $table, array $fieldsAndValues = []): array
  {
    $keys = "";
    $values = "";
    foreach ($fieldsAndValues as $key => $value) {
      $keys .= $key . ", ";
    }
    foreach ($fieldsAndValues as $key => $value) {
      $values .= '"' . $value . '", ';
    }
    $keys = substr($keys, 0, -2);
    $values = substr($values, 0, -2);
    $sql = "insert into {$table} ($keys) values ($values)";
    try {
      return $this->runQuery($sql);
    } catch (\PDOException $e) {
      throw new Exception($e->getMessage());
      return $e->getMessage();
    }
  }

  /**
   * Replaces any parameter placeholders in a query with the value of that
   * parameter. Useful for debugging. Assumes anonymous parameters from
   * $params are are in the same order as specified in $query
   *
   * @param string $query The sql query with parameter placeholders
   * @param array $params The array of substitution parameters
   * @return string The interpolated query
   */
  private static function interpolateQuery($query, $params)
  {
    $keys = array();

    # build a regular expression for each parameter
    foreach ($params as $key => $value) {
      if (is_string($key)) {
        $keys[] = '/:' . $key . '/';
      } else {
        $keys[] = '/[?]/';
      }
    }

    $query = preg_replace($keys, $params, $query, 1, $count);

    #trigger_error('replaced '.$count.' keys');

    return $query;
  }

  public static function jsonBeautify($content)
  {
    if (is_array($content)) {
      $content = json_encode($content);
    }
    $json = json_decode(utf8_encode($content));
    return trim(json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
  }
}
