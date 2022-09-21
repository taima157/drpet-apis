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
      $query_meusPets = "SELECT * FROM pets WHERE id_usuario=:id_usuario";
      $meusPets = $conn->prepare($query_meusPets);

      $meusPets->bindParam(':id_usuario', $dados['id_usuario'], PDO::PARAM_STR);

      $meusPets->execute();

      if ($meusPets->rowCount() > 0) {
        $row_meusPets = $meusPets->fetchAll(PDO::FETCH_ASSOC);

        $response = [
          "erro" => false,
          "mensagem" => "Você possui pets registrados.",
          "pets" => $row_meusPets,
        ];
      } else {
        $response = [
          "erro" => true,
          "mensagem" => "Você não possui pets registrados.",
          "pets" => [],
        ];
      }
    }

    if ($dados['method'] === 'post') {
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

    if ($dados['method'] === 'update') {
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
          "mensagem" => "Não foi possivel editar o pet."
        ];
      }
    }

    if ($dados['method'] === 'delete') {
      $query_deletePet = "DELETE FROM pets WHERE idpet=:idpet";
      $deletePet = $conn->prepare($query_deletePet);

      $deletePet->bindParam(':idpet', $dados['idpet'], PDO::PARAM_INT);
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
    }

  } else {
    $response = [
      "erro" => true,
      "mensagem" => "Dados não fornecidos.",
    ];
  }

  http_response_code(200);
  echo json_encode($response);
?>