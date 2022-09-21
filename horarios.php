<?php
  include_once("conexao.php");

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset= utf-8");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: POST,GET,DELETE");

  $response_json = file_get_contents("php://input");
  $dados = json_decode($response_json, true);

  if ($dados) {

    if ($dados['method'] == 'post') {
      $query_horario = "INSERT INTO horarios (horario) VALUES(:horario)";
      $response_horario = $conn -> prepare($query_horario);
  
      $response_horario->bindParam(':horario', $dados['horario'],  PDO::PARAM_STR);
      $response_horario->execute();
  
      if ($response_horario -> rowCount()) {
        $response = [
          "erro" => false,
          "mensagem" => "Horario adicionado com sucesso",
        ];
      } else {
        $response = [
          "erro" => true,
          "mensagem" => "Horario não adicionado",
        ];
      }
    }

  } else {
    $query_horarios = "SELECT * FROM horarios";
    $resposta_horarios = $conn->prepare($query_horarios);
    $resposta_horarios->execute();

    if (($resposta_horarios) and ($resposta_horarios->rowCount() != 0)) {
      $row_horario = $resposta_horarios->fetchAll(PDO::FETCH_ASSOC);
      $response = array("horarios" => $row_horario);
    } else {
      $response = array("horarios" => []);
    }
  }


  http_response_code(200);
  echo json_encode($response);