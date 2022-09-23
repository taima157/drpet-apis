<?php
  include_once("conexao.php");

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset= utf-8");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");

  if ($_SERVER["REQUEST_METHOD"] === "GET") { 
    $query_agendamentos = "SELECT * FROM agendamentos";
    $response_agendamentos = $conn -> prepare($query_agendamentos);

    $response_agendamentos -> execute();

    if ($response_agendamentos -> rowCount()) {
      $response = $response_agendamentos -> fetchAll(PDO::FETCH_ASSOC);
    } else {
      $response = [
        "erro" => false,
        "mensagem" => "Não há nenhum agendamento marcado."
      ];
    }
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response_pets = file_get_contents("php://input");
    $dados = json_decode($response_pets, true);

    $query_agendamentos = "INSERT INTO agendamentos (id_horario, id_usuario_ag, id_pet, status) VALUES (:id_horario, :id_usuario_ag, :id_pet, 'pendente')";
    $response_agendamentos = $conn -> prepare($query_agendamentos);

    $response_agendamentos -> bindParam(':id_horario', $dados['id_horario'], PDO::PARAM_INT);
    $response_agendamentos -> bindParam(':id_usuario_ag', $dados['id_usuario_ag'], PDO::PARAM_INT);
    $response_agendamentos -> bindParam(':id_pet', $dados['id_pet'], PDO::PARAM_INT);

    $response_agendamentos -> execute();

    if ($response_agendamentos -> rowCount()) {
      $response = [
        "erro" => false,
        "mensagem" => "Agendamento marcado com sucesso.",
      ];
    } else {
      $response = [
        "erro" => true,
        "mensagem" => "Não foi possivel marcar o agendamento.",
      ];
    }
  }

  http_response_code(200);
  echo json_encode($response);
