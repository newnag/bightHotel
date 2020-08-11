<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

class DBconnect
{
  public  $conn;
  public function __construct()
  {
    if (!$this->conn) {
      $database = Database::getInstance();
      $this->conn = $database->dbConnection();
    }
  }

  // function Query
  public function query($sql)
  {
    try {
      $query = $this->conn->prepare($sql);
      $query->execute();
      $data = $query->fetchAll(PDO::FETCH_ASSOC);

      return !empty($data) ? $data : false;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function fetch($sql)
  {
    $query = $this->conn->prepare($sql);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
  }


  public function fetch_assoc($sql)
  {
    $query = $this->conn->prepare($sql);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    foreach ($row as $value) {
      $output = $value;
    }
    return $output;
  }

  public function runQuery($sql)
  {
    try {
      $data = $this->conn->prepare($sql);
      return !empty($data) ? $data : false;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  // function Insert
  public function insert($table, $field, $value)
  {
    try {
      $sql = " INSERT INTO $table ($field) VALUES ($value) ";
      $query = $this->conn->prepare($sql);
      $query->execute();
      $output = array(
        'insert_id' => $this->conn->lastInsertId(),
        'event' => 'insert',
        'status' => '200',
        'message' => 'OK'
      );
      return $output;
    } catch (PDOException $e) {
      $output = array(
        'event' => 'insert',
        'status' => '400',
        'message' => $e->getMessage()
      );
      return $output;
    }
  }

  // function Multi Insert
  public function multiInsert($tableName, $data)
  {
    try {
      $rowsSQL = array();
      $toBind = array();
      $columnNames = array_keys($data[0]);
      foreach ($data as $arrayIndex => $row) {
        $params = array();
        foreach ($row as $columnName => $columnValue) {
          $param = ":" . $columnName . $arrayIndex;
          $params[] = $param;
          $toBind[$param] = $columnValue;
        }
        $rowsSQL[] = "(" . implode(", ", $params) . ")";
      }
      $sql = "INSERT INTO `$tableName` (" . implode(", ", $columnNames) . ") VALUES " . implode(", ", $rowsSQL);
      $pdoStatement = $this->conn->prepare($sql);
      foreach ($toBind as $param => $val) {
        $pdoStatement->bindValue($param, $val);
      }
      $pdoStatement->execute();
      $output = array(
        'event' => 'multi_insert',
        'status' => '200',
        'message' => 'OK'
      );
      return $output;
    } catch (PDOException $e) {
      $output = array(
        'event' => 'multi_insert',
        'status' => '400',
        'message' => $e->getMessage()
      );
      return $output;
    }
  }

  // function Update
  public function update($table, $set, $where)
  {
    try {
      $sql = " UPDATE $table SET $set WHERE $where ";
      $query = $this->conn->prepare($sql);
      $query->execute();
      $output = array(
        'event' => 'update',
        'status' => '200',
        'message' => 'OK'
      );
      return $output;
    } catch (PDOException $e) {
      $output = array(
        'event' => 'update',
        'status' => '400',
        'message' => 'error_request'
      );
      return $output;
    }
  }

  // function Delete
  public function delete($table, $where)
  {
    try {
      $sql = " DELETE FROM $table WHERE $where ";
      $query = $this->conn->prepare($sql);
      $query->execute();
      $output = array(
        'event' => 'delete',
        'status' => '200',
        'message' => 'OK'
      );
      return $output;
    } catch (PDOException $e) {
      $output = array(
        'event' => 'delete',
        'status' => '400',
        'message' => 'error_request'
      );
      return $output;
    }
  }


  // function Query
  public function fetchAll($sql, $val)
  {
    try {
      $query = $this->conn->prepare($sql);
      $query->execute($val);
      $data = $query->fetchAll(PDO::FETCH_ASSOC);

      return !empty($data) ? $data : false;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function fetchPrepare($sql, $value)
  {
    $query = $this->conn->prepare($sql);
    $query->execute($value);
    return $query->fetch(PDO::FETCH_ASSOC);
  }

  public function fetchObject($sql, $value)
  {
    $query = $this->conn->prepare($sql);
    $query->execute($value);
    return $query->fetchObject();
  }

  public function fetchColumnPrepare($sql, $value)
  {

    $query = $this->conn->prepare($sql);
    $query->execute($value);
    return $query->fetchColumn();
  }

  // function Insert Width Prepare
  public function insertPrepare($table, $field, $key, $value)
  {
    try {
      $this->conn->beginTransaction();
      $sql = " INSERT INTO $table ($field) VALUES ($key) ";
      $query = $this->conn->prepare($sql);
      $query->execute($value);

      $output = array(
        'insert_id' => $this->conn->lastInsertId(),
        'event' => 'insert',
        'status' => '200',
        'message' => 'OK'
      );
      $this->conn->commit();
      return $output;
    } catch (PDOException $e) {
      $this->conn->rollBack();
      $output = array(
        'event' => 'insert',
        'status' => '400',
        'message' => $e->getMessage()
      );
      return $output;
    }
  }

  // function Insert String
  public function insertString($sql)
  {
    try {
      $this->conn->beginTransaction();
      $query = $this->conn->prepare($sql);
      $query->execute();
      $output = array(
        'event' => 'insert',
        'status' => '200',
        'message' => 'OK'
      );
      $this->conn->commit();
      return $output;
    } catch (PDOException $e) {
      $this->conn->rollBack();
      $output = array(
        'event' => 'insert',
        'status' => '400',
        'message' => $e->getMessage()
      );
      return $output;
    }
  }

  // function Insert Value
  public function insertValue($sql, $val)
  {
    try {
      $this->conn->beginTransaction();
      $query = $this->conn->prepare($sql);
      $query->execute($val);
      $output = array(
        'last_id' => $this->conn->lastInsertId(),
        'event' => 'insert',
        'status' => '200',
        'message' => 'OK'
      );
      $this->conn->commit();
      return $output;
    } catch (PDOException $e) {
      $this->conn->rollBack();
      $output = array(
        'event' => 'insert',
        'status' => '400',
        'message' => $e->getMessage()
      );
      return $output;
    }
  }


  public function updateValue($sql, $value)
  {
    try {
      $this->conn->beginTransaction();
      $query = $this->conn->prepare($sql);
      $query->execute($value);
      $output = array(
        'event' => 'update',
        'status' => '200',
        'message' => 'OK'
      );
      $this->conn->commit();
      return $output;
    } catch (PDOException $e) {
      $this->conn->rollBack();
      $output = array(
        'event' => 'update',
        'status' => '400',
        'message' => $e->getMessage() //'error_request'
      );
      return $output;
    }
  }

  public function update_prepare($table, $set, $where, $value)
  {
    try {

      $sql = " UPDATE $table SET $set WHERE $where ";
      $query = $this->conn->prepare($sql);
      $query->execute($value);
      $output = array(
        'event' => 'update',
        'status' => '200',
        'message' => 'OK'
      );
      return $output;
    } catch (PDOException $e) {
      $output = array(
        'event' => 'update',
        'status' => '400',
        'message' => $e->getMessage() //'error_request'
      );
      return $output;
    }
  }

  public function deletePrepare($table, $where, $val)
  {
    try {
      $this->conn->beginTransaction();
      $sql = " DELETE FROM $table WHERE $where ";
      $query = $this->conn->prepare($sql);
      $query->execute($val);
      $output = array(
        'event' => 'delete',
        'status' => '200',
        'message' => 'OK'
      );
      $this->conn->commit();
      return $output;
    } catch (PDOException $e) {
      $this->conn->rollBack();
      $output = array(
        'event' => 'delete',
        'status' => '400',
        'message' => 'error_request'
      );
      return $output;
    }
  }
}
