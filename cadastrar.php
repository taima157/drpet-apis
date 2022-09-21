<?php
  include_once("conexao.php");

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset= utf-8");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: POST,GET,DELETE");

  $response_json = file_get_contents("php://input");
  $dados = json_decode($response_json, true);

  if ($dados) {
    $query_cadastro = "INSERT INTO usuarios (nome, email, senha, cpf_cnpj, adm) VALUES (:nome, :email, :senha, :cpf_cnpj, :adm)";
    $cad_usuario = $conn->prepare($query_cadastro);

    $cad_usuario->bindParam(':nome', $dados['user']['nome'], PDO::PARAM_STR);
    $cad_usuario->bindParam(':email', $dados['user']['email'], PDO::PARAM_STR);
    $cad_usuario->bindParam(':senha', $dados['user']['senha'], PDO::PARAM_STR);
    $cad_usuario->bindParam(':cpf_cnpj', $dados['user']['cpf_cnpj'], PDO::PARAM_STR);
    $cad_usuario->bindParam(':adm', $dados['user']['adm'], PDO::PARAM_BOOL);

    $cad_usuario->execute();

    if ($cad_usuario->rowCount()) {

      $response = [
        "erro" => false,
        "mensagem" => "Usuário cadastrado com sucesso."
      ];
    } else {
      $response = [
        "erro" => true,
        "mensagem" => "Usuário não cadastrado."
      ];
    }
  } else {
    $response = [
      "erro" => true,
      "mensagem" => "Usuário não cadastrado."
    ];
  }

  http_response_code(200);
  echo json_encode($response);

?>