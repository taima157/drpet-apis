<?php
  include_once("conexao.php");

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset= utf-8");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: GET");

  if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $url = explode("/", $_SERVER["REQUEST_URI"]);

    if(count($url) > 3) {
      if ($url[3]) {
        $query_meuspets = "SELECT * FROM pets WHERE id_usuario=:id_usuario";
        $meuspets = $conn -> prepare($query_meuspets);
  
        $meuspets -> bindParam(":id_usuario", $url[3], PDO::PARAM_INT);
        $meuspets -> execute();
  
        if ($meuspets -> rowCount()) {
          $response = $meuspets -> fetchAll(PDO::FETCH_ASSOC);
        } else {
          $response = [
            "erro" => true,
            "mensagem" => "Você não possui pets registrados",
          ];
        }
      }
    } else {
      $response = [
        "erro" => true,
        "mensagem" => "Nenhum usuário selecionado",
      ];
    }
  }

  http_response_code(200);
  echo json_encode($response);
