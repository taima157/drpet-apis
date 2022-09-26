<?php
  include_once("conexao.php");

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset= utf-8");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: POST,GET,DELETE");

  if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $url = explode("/", $_SERVER["REQUEST_URI"]);

    if (count($url) > 3) {
      if ($url[3]) {
        $query_horarios = "SELECT * FROM horarios WHERE idhorario=:idhorario";
        $resposta_horarios = $conn->prepare($query_horarios);
  
        $resposta_horarios -> bindParam(":idhorario", $url[3], PDO::PARAM_INT);
        $resposta_horarios->execute();
  
        if ($resposta_horarios->rowCount()) {
          $response = $resposta_horarios->fetch(PDO::FETCH_ASSOC);
        } else {
          $response = [
            "erro" => false,
            "mensagem" => "Horário não registrado."
          ];
        }
      }
    } else {
      $parametros = explode("?", $url[2]);
      
      if (count($parametros) > 1) {
        if ($parametros[1] === "disponivel") {
          $query_horarios = "SELECT * FROM horarios h WHERE NOT EXISTS (SELECT * FROM agendamentos a WHERE h.idhorario = a.id_horario)";
          $resposta_horarios = $conn->prepare($query_horarios);
          $resposta_horarios->execute();
    
          if ($resposta_horarios->rowCount()) {
            $response = $resposta_horarios->fetchAll(PDO::FETCH_ASSOC);
          } else {
            $response = [
              "erro" => false,
              "mensagem" => "Nenhum horario disponível."
            ];
          }
        } else {
          $response = [
            "erro" => false,
            "mensagem" => "Nenhum horario disponível."
          ];
        }
      }else {

        $query_horarios = "SELECT * FROM horarios";
        $resposta_horarios = $conn->prepare($query_horarios);
        $resposta_horarios->execute();

        if (($resposta_horarios) and ($resposta_horarios->rowCount() != 0)) {
          $response = $resposta_horarios->fetchAll(PDO::FETCH_ASSOC);
        } else {
          $response = [
            "erro" => false,
            "mensagem" => "Nenhum horario registrado."
          ];
        }
      }

    }
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response_json = file_get_contents("php://input");
    $dados = json_decode($response_json, true);

    $query_horario = "INSERT INTO horarios (data, hora) VALUES(:data, :hora)";
    $response_horario = $conn->prepare($query_horario);

    $response_horario->bindParam(':data', $dados['data'],  PDO::PARAM_STR);
    $response_horario->bindParam(':hora', $dados['hora'],  PDO::PARAM_STR);
    $response_horario->execute();

    if ($response_horario->rowCount()) {
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

  http_response_code(200);
  echo json_encode($response);
