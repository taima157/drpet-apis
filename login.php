<?php
  include_once("conexao.php");

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset= utf-8");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: POST,GET,DELETE");

  $response_json = file_get_contents("php://input");
  $dados = json_decode($response_json, true);

  if ($dados) {
    $query_login = "SELECT idusuario, nome, email FROM usuarios WHERE (email=:email AND senha=:senha)";
    $cad_login = $conn->prepare($query_login);

    $cad_login->bindParam(':email', $dados['user']['email'], PDO::PARAM_STR);
    $cad_login->bindParam(':senha', $dados['user']['senha'], PDO::PARAM_STR);

    $cad_login->execute();
    $row_login = $cad_login->fetchAll(PDO::FETCH_ASSOC);

    if ($cad_login->rowCount() > 0) {

      $response = [
        "erro" => false,
        "mensagem" => "Login efetuado com sucesso.",
        "user" => $row_login,
      ];
    } else {
      $response = [
        "erro" => true,
        "mensagem" => "E-mail ou senha incorretos.",
        "user" => []
      ];
    }
  } else {
    $response = [
      "erro" => true,
      "mensagem" => "E-mail ou senha incorretos.",
      "user" => []
    ];
  }

  http_response_code(200);
  echo json_encode($response);
?>