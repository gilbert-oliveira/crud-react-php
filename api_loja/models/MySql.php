<?php

class MySqlModel
{

  protected $_db;
  private $ipHost = "localhost";
  private $nomeBanco = "loja";
  private $user = "root";
  private $password = "";
  public $_tabela;

  public function __construct()
  {
    $this->_db = new PDO('mysql:host=' . $this->ipHost . ';dbname=' . $this->nomeBanco, $this->user, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }


  public function inserir(array $dados)
  {
    $campos = implode(", ", array_keys($dados));
    $valores = "'" . implode("' , '", array_values($dados)) . "'";

    $sql = " INSERT INTO `{$this->_tabela}` ({$campos}) VALUES ({$valores}) ";
    $resultSet = $this->_db->prepare($sql);

    $retorno = false;
    try {
      $resultSet->execute();
      $retorno = true;
    } catch (PDOException $ex) {
      echo 'Erro inesperado: ' . $ex->getMessage();
      exit;
    }
    return $retorno;
  }


  public function buscar($where = null, $limit = null, $offset = null, $orderby = null)
  {
    $where = ($where != null ? "WHERE {$where}" : "");
    $limit = ($limit != null ? "LIMIT {$limit}" : "");
    $offset = ($offset != null ? "OFFSET {$offset}" : "");
    $orderby = ($orderby != null ? "ORDER BY {$orderby}" : "");

    $sql = " SELECT * FROM `{$this->_tabela}` {$where} {$orderby} {$limit} {$offset}";
    $resultSet = $this->_db->prepare($sql);

    $retorno = false;
    try {
      $resultSet->execute();
      $resultSet->setFetchMode(PDO::FETCH_ASSOC);
      $retorno = $resultSet->fetchAll();
    } catch (PDOException $ex) {
      echo 'Erro inesperado: ' . $ex->getMessage();
      exit;
    }
    return $retorno;
  }

  public function atualizar(array $dados, $where)
  {
    foreach ($dados as $indice => $val) {
      $campos[] = "{$indice} = '{$val}'";
    }
    $campos_query = implode(", ", $campos);
    $sql = " UPDATE `{$this->_tabela}` SET {$campos_query} WHERE {$where}";
    $resultSet = $this->_db->prepare($sql);

    //echo $sql;
    //exit;

    //Tratamento com PDO e chamada de Excessão - Usado tambem contra SQL Injection
    $retorno = false;
    try {
      $resultSet->execute();
      $retorno = true;
    } catch (PDOException $ex) {
      echo 'Erro inesperado: ' . $ex->getMessage();
      exit;
    }
    return $retorno;
  }

  public function deletar($where)
  {
    $sql = " DELETE FROM {$this->_tabela} WHERE {$where}";
    $resultSet = $this->_db->prepare($sql);

    //Tratamento com PDO e chamada de Excessão - Usado tambem contra SQL Injection
    $retorno = false;
    try {
      $resultSet->execute();
      $retorno = true;
    } catch (PDOException $ex) {
      echo 'Erro inesperado: ' . $ex->getMessage();
      exit;
    }
    return $retorno;
  }

  public function runQuery($query)
  {

    $resultSet = $this->_db->prepare($query);

    $retorno = false;
    try {
      $resultSet->execute();
      $resultSet->setFetchMode(PDO::FETCH_ASSOC);
      $retorno = $resultSet->fetchAll();
    } catch (PDOException $ex) {
      echo 'Erro inesperado: ' . $ex->getMessage();
      exit;
    }
    return $retorno;
  }
}