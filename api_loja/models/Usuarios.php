<?php
// Importações
require_once('MySql.php');

class Usuarios{
  private $db;
  public function __construct($tabela)
  {
    $this->db = new MySqlModel();
    $this->db->_tabela = $tabela;
  }

  public function cadastrar(array $array)
  {
    return $this->db->inserir($array);
  }

  public function buscar($where = null)
  {
   return $this->db->buscar($where);
  }

  public function deletar($id)
  {
    $where = "id = $id";
    $this->db->deletar($where);
  }

  public function atualizar(array $dados, $id)
  {
   $where = "id = $id";

   $this->db->atualizar($dados, $where);
  }
}