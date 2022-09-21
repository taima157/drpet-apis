<?php
  session_start();
  include_once("conexao.php");

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset= utf-8");
  
  $query_usuarios = "SELECT * FROM usuarios";
  $resposta_usuarios = $conn -> prepare($query_usuarios);
  $resposta_usuarios -> execute();
  
  if (($resposta_usuarios) AND ($resposta_usuarios -> rowCount() != 0)) {
    $row_usuario = $resposta_usuarios -> fetchAll(PDO::FETCH_ASSOC);
    $lista_usuarios = array("users" => $row_usuario);
  }else {
    $lista_usuarios = array("users" => []);
  }

  http_response_code(200);
  echo json_encode($lista_usuarios);
?>