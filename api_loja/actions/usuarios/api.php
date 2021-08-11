<?php
// Habilitar erros php.
ini_set('display_errors', true);
error_reporting(E_ALL);

// Cabeçalhos obrigatórios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");

$metodo = $_SERVER['REQUEST_METHOD'];

// Importações.
require_once('../../models/Usuarios.php');

// Instacias.
$usuariosModel = new Usuarios('usuarios');

if ($metodo === 'GET') {

  // Listar Usuários
  $usuarios = $usuariosModel->buscar();

  die(json_encode($usuarios));
} elseif ($metodo === 'POST') {

  // Cadastrar usuário.
  $data_json = file_get_contents("php://input");
  $usuario = json_decode($data_json, true);
  // die($usuario['nome']);

  unset($usuario['confirmarSenha']);

  $usuario['senha'] = password_hash($usuario['senha'], PASSWORD_DEFAULT);

  $cadastro = $usuariosModel->cadastrar($usuario);
  die("Usuário cadastrado com sucesso!");
} elseif ($metodo === 'PATCH') {

  $data_json = file_get_contents("php://input");
  $data = json_decode($data_json, true);
  $id = $_GET['id'];
  $userDB = $usuariosModel->buscar("id = $id")[0];

  if (isset($data['newsenha']) and $data['newsenha']) {
    if (password_verify($data['senha'], $userDB['senha'])) {
      $data['senha'] = password_hash($data['newsenha'], PASSWORD_DEFAULT);
      unset($data['newsenha']);
      $usuariosModel->atualizar($data, $id);
      die("Usuário atualizado com sucesso!");
    } else {
      die("Senha inválida");
    }
  } else {
    unset($data['senha']);
    unset($data['newsenha']);
    $usuariosModel->atualizar($data, $id);
    die("Usuário atualizado com sucesso!");
  }
} elseif ($metodo === 'DELETE') {

  $id = $_GET['id'];
  $usuariosModel->deletar($id);

  die("Usuário deletado com sucesso!");
} else {
  die("Requisição inválida");
}
