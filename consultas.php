<?php
  include_once('conexao.php');
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset= utf-8");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");


  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response_consultas = file_get_contents("php://input");
    $dados = json_decode($response_consultas, true);

    $query_consultas = "INSERT INTO consultas (id_agendamento, id_usuario_co, id_pet_co, procedimento) VALUES (:id_agendamento, :id_usuario_co, :id_pet_co, :procedimento)";
    $response_consultas = $conn -> prepare($query_consultas);

    $response_consultas -> bindParam(':id_agendamento', $dados['id_agendamento'], PDO::PARAM_INT);
    $response_consultas -> bindParam(':id_usuario_co', $dados['id_usuario_co'], PDO::PARAM_INT);
    $response_consultas -> bindParam(':id_pet_co', $dados['id_pet_co'], PDO::PARAM_INT);
    $response_consultas -> bindParam(':procedimento', $dados['procedimento'], PDO::PARAM_STR);

    $response_consultas -> execute();

    if ($response_consultas -> rowCount()) {
      $response = [
        "erro" => false,
        "mensagem" => "Consulta marcado com sucesso.",
      ];
    } else {
      $response = [
        "erro" => true,
        "mensagem" => "Não foi possivel salvar a consulta.",
      ];
    }
  }

  http_response_code(200);
  echo json_encode($response);
?>