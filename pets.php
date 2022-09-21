<?php
  include_once("conexao.php");

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset= utf-8");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: POST,GET,DELETE");

  $response_meusPets = file_get_contents("php://input");
  $dados = json_decode($response_meusPets, true);

  if ($dados) {
    if ($dados['method'] === 'get') {
      $query_pet = "SELECT * FROM pets WHERE idpet=:idpet";
      $pets = $conn->prepare($query_pet);
  
      $pets->bindParam(':idpet', $dados['idpet'], PDO::PARAM_STR);
  
      $pets->execute();
  
      if ($pets->rowCount() > 0) {
        $row_pet = $pets->fetchAll(PDO::FETCH_ASSOC);
  
        $response = [
          "erro" => false,
          "mensagem" => "Pet encontrado.",
          "pet" => $row_pet,
        ];
      } else {
        $response = [
          "erro" => true,
          "mensagem" => "Pet não encontrado.",
          "pet" => [],
        ];
      }
    }
  } else {
    $query_animais = "SELECT * FROM pets";
    $resposta_animais = $conn -> prepare($query_animais);
    $resposta_animais -> execute();
      
    
    if (($resposta_animais) and ($resposta_animais->rowCount() != 0)) {
      $row_animais = $resposta_animais->fetchAll(PDO::FETCH_ASSOC);
      $response = array("pets" => $row_animais);
    } else {
      $response = array("pets" => []);
    }
  }

  http_response_code(200);
  echo json_encode($response);
?>