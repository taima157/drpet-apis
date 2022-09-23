<?php
  include_once("conexao.php");

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset= utf-8");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");

  if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $url = explode("/", $_SERVER["REQUEST_URI"]);


    if (count($url) > 3) {
      if ($url[3] != " ") {
        $query_pet = "SELECT * FROM pets WHERE idpet=:idpet";
        $pet = $conn->prepare($query_pet);
    
        $pet -> bindParam(':idpet', $url[3], PDO::PARAM_INT);
        $pet -> execute();
    
        if ($pet -> rowCount()) {
          $response = $pet->fetch(PDO::FETCH_ASSOC);
        } else {
          $response = [
            "erro" => true,
            "mensagem" => "Pet não encontrado.",
          ];
        }
  
      }

    } else {
      $query_pet = "SELECT * FROM pets";
      $pets = $conn->prepare($query_pet);

      $pets->execute();
  
      if ($pets->rowCount() > 0) {
        $response = $pets->fetchAll(PDO::FETCH_ASSOC);
      } else {
        $response = [
          "erro" => true,
          "mensagem" => "Pet não encontrado.",
        ];
      }
    }
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response_pets = file_get_contents("php://input");
    $dados = json_decode($response_pets, true);

    $query_cadastroPet = "INSERT INTO pets (nome, raca, especie, cor, sexo, id_usuario) VALUES (:nome, :raca, :especie, :cor, :sexo, :id_usuario)";
    $cad_pet = $conn->prepare($query_cadastroPet);

    $cad_pet->bindParam(':nome', $dados['pet']['nome'], PDO::PARAM_STR);
    $cad_pet->bindParam(':raca', $dados['pet']['raca'], PDO::PARAM_STR);
    $cad_pet->bindParam(':especie', $dados['pet']['especie'], PDO::PARAM_STR);
    $cad_pet->bindParam(':cor', $dados['pet']['cor'], PDO::PARAM_STR);
    $cad_pet->bindParam(':sexo', $dados['pet']['sexo'], PDO::PARAM_STR);
    $cad_pet->bindParam(':id_usuario', $dados['pet']['id_usuario'], PDO::PARAM_STR);

    $cad_pet->execute();

    if ($cad_pet->rowCount()) {
      $response = [
        "erro" => false,
        "mensagem" => "Pet cadastrado com sucesso."
      ];
    } else {
      $response = [
        "erro" => true,
        "mensagem" => "Pet não cadastrado."
      ];
    }
  }

  if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    $response_meusPets = file_get_contents("php://input");
    $dados = json_decode($response_meusPets, true);

    $query_editarPet = "UPDATE pets SET nome=:nome, raca=:raca, especie=:especie, cor=:cor, sexo=:sexo WHERE idpet=:idpet";

    $edit_pet = $conn->prepare($query_editarPet);

    $edit_pet->bindParam(':idpet', $dados['pet']['idpet'], PDO::PARAM_INT);
    $edit_pet->bindParam(':nome', $dados['pet']['nome'], PDO::PARAM_STR);
    $edit_pet->bindParam(':raca', $dados['pet']['raca'], PDO::PARAM_STR);
    $edit_pet->bindParam(':especie', $dados['pet']['especie'], PDO::PARAM_STR);
    $edit_pet->bindParam(':cor', $dados['pet']['cor'], PDO::PARAM_STR);
    $edit_pet->bindParam(':sexo', $dados['pet']['sexo'], PDO::PARAM_STR);

    $edit_pet->execute();

    if ($edit_pet->rowCount()) {
      $response = [
        "erro" => false,
        "mensagem" => "Pet editado com sucesso."
      ];
    } else {
      $response = [
        "erro" => true,
        "mensagem" => "Não foi possivel editar o pet.",
      ];
    }
  }
  
  if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    $url = explode("/", $_SERVER["REQUEST_URI"]);

    if ($url[3]) {
      $query_deletePet = "DELETE FROM pets WHERE idpet=:idpet";
      $deletePet = $conn->prepare($query_deletePet);
  
      $deletePet->bindParam(':idpet', $url[3], PDO::PARAM_INT);
      $deletePet->execute();
  
      if ($deletePet->rowCount()) {
        $response = [
          "erro" => false,
          "mensagem" => "Pet foi deletado com sucesso.",
        ];
      } else {
        $response = [
          "erro" => true,
          "mensagem" => "Pet não foi deletado.",
        ];
      }
    } else {
      $response = [
        "erro" => true,
        "mensagem" => "Pet não selecionado.",
      ];
    }
  }

  http_response_code(200);
  echo json_encode($response);
