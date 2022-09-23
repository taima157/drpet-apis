<?php
  include_once("conexao.php");

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset= utf-8");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: PUT");

  $response_json = file_get_contents("php://input");
  $dados = json_decode($response_json, true);

  if ($dados) {
    $query_adm = "SELECT * FROM usuarios WHERE (email=:email AND adm='1')";
    $adm = $conn->prepare($query_adm);

    $adm->bindParam(':email', $dados['email'], PDO::PARAM_STR);
    $adm->execute();

    if ($adm->rowCount() > 0) {
      $response = [
        "erro" => false,
        "mensagem" => "Usuário é administrador.",
        "adm" => true,
      ];
    } else {
      $response = [
        "erro" => true,
        "mensagem" => "Usuário não é administrador.",
        "adm" => false,
      ];
    }
  } else {
    $response = [
      "erro" => true,
      "mensagem" => "Usuário não encontrado.",
    ];
  }

  http_response_code(200);
  echo json_encode($response);

?>