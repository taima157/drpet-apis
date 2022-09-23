<?php
  include_once("conexao.php");

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset= utf-8");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");

  $response_json = file_get_contents("php://input");
  $dados = json_decode($response_json, true);

  if ($dados) {
    $query_senha = "SELECT CAST(AES_DECRYPT(senha, 'techninja') AS CHAR) AS senha FROM usuarios WHERE (email=:email)";
    $response_senha = $conn->prepare($query_senha);
    $response_senha -> bindParam(':email', $dados['email'], PDO::PARAM_STR);
    
    $response_senha -> execute();
    $row_senha = $response_senha -> fetch(PDO::FETCH_ASSOC);
    
    if ($row_senha['senha'] === $dados['senha']) {
      $query_login = "SELECT nome, email, idusuario FROM usuarios WHERE (email=:email)";
      $cad_login = $conn->prepare($query_login);

      $cad_login -> bindParam(':email', $dados['email'], PDO::PARAM_STR);
      $cad_login->execute();
      
      if ($cad_login->rowCount() > 0) {
        $row_login = $cad_login->fetchAll(PDO::FETCH_ASSOC);

        $response = [
          "erro" => false,
          "mensagem" => "Login efetuado com sucesso.",
          "user" => $row_login,
        ];
      }
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