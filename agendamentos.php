<?php
  include_once("conexao.php");

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset= utf-8");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: POST,GET,DELETE");

  $response_agendamentos = file_get_contents('php://input');
  $dados = json_decode($response_agendamentos);

  if ($dados) {

    if ($dados['method'] === 'post') {
    //   // $query_agendamentos = "INSERT INTO agendamentos (ID_HORARIO, ID_PET, ID_USUARIO_AG) VALUES(:id_horario, :id_pet, :id_usuario_ag)";
    //   // $response_agendamento = $conn->prepare($query_agendamentos);

    //   // $response_agendamento -> bindParam(':id_horario', $dados['agendamento']['id_horario']);
    //   // $response_agendamento -> bindParam(':id_pet', $dados['agendamento']['id_pet']);
    //   // $response_agendamento -> bindParam(':id_usuario_ag', $dados['agendamento']['id_usuario_ag']);

    //   //$response_agendamento->execute();

    //   // if ($response_agendamento -> rowCount()) {
    //   //   $response = [
    //   //     "erro" => false,
    //   //     "mensagem" => "Agendamento marcado com sucesso."
    //   //   ];
    //   // }
    }

  } else {
    $query_agendamentos = "SELECT * FROM agendamentos";
    $resposta_agendamentos = $conn -> prepare($query_agendamentos);
    $resposta_agendamentos -> execute();
    
    if (($resposta_agendamentos) and ($resposta_agendamentos->rowCount() != 0)) {
      $row_agendamentos = $resposta_agendamentos->fetchAll(PDO::FETCH_ASSOC);
      $response = array("agendamentos" => $row_agendamentos);
    } else {
      $response = array("agendamentos" => []);
    }
  }

  http_response_code(200);
  echo json_encode($dados);
