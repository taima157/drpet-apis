<?php
  include_once("conexao.php");

  function resposta($codigo, $ok, $msg){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Content-Type: application/json");

    http_response_code($codigo);

    echo (json_encode([
      'ok' => $ok,
      'msg' => $msg
    ]));
    die;
  }
  
  $body = file_get_contents('php://input');
  $body = json_decode($body);

  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    resposta(200, true, "");
  }

  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    resposta(400, false, "Metodo invalido");
  }
  // echo json_encode(["resposta" => "Ok"]);


  if (!$body) {
    resposta(400, false, "Corpo da requisicao n encontrado");
  }


  $body -> horario = filter_var($body ->horario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $body -> horario = filter_var($body ->horario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  
  if (!$body->horario) {
    resposta(400, false, "Dados invalidos");
  }



  $query_horario = $conn->prepare("INSERT INTO horarios_disponivel (horario) VALUES(:horario)");

  $query_horario->bindParam('horario', $body->horario);

  $query_horario->execute();

  resposta(200, true, "Mensagem salva com sucesso");
?>