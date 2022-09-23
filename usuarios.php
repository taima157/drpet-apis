<?php
  session_start();
  include_once("conexao.php");

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset= utf-8");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");

  if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $url = explode("/", $_SERVER["REQUEST_URI"]);

    if (count($url) > 3) {

      if ($url[3] != " ") {
        $query_usuarios = "SELECT * FROM usuarios WHERE idusuario=:idusuario";
        $response_usuarios = $conn -> prepare($query_usuarios);
    
        $response_usuarios -> bindParam(':idusuario', $url[3], PDO::PARAM_INT); 
        $response_usuarios -> execute();
    
        if ($response_usuarios -> rowCount()) {
          $response = $response_usuarios -> fetch(PDO::FETCH_ASSOC);
        } else {
          $response = [
            "erro" => true,
            "mensagem" => "Usuário não encontrado."
          ];
        }
    
      }
    } else {
      $query_usuarios = "SELECT * FROM usuarios";
      $resposta_usuarios = $conn -> prepare($query_usuarios);
      $resposta_usuarios -> execute();
      
      if (($resposta_usuarios) AND ($resposta_usuarios -> rowCount() != 0)) {
        $response = $resposta_usuarios -> fetchAll(PDO::FETCH_ASSOC);
      }else {
        $response = [
          "erro" => true,
          "mensagem" => "Nenhum usuario registrado"
        ];
      }
    }
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response_json = file_get_contents("php://input");
    $dados = json_decode($response_json, true);
  
    if ($dados) {
      $query_cadastro = "INSERT INTO usuarios (nome, email, senha, cpf_cnpj, adm) VALUES (:nome, :email, AES_ENCRYPT(:senha, 'techninja'), :cpf_cnpj, :adm)";
      $cad_usuario = $conn->prepare($query_cadastro);
  
      $cad_usuario->bindParam(':nome', $dados['user']['nome'], PDO::PARAM_STR);
      $cad_usuario->bindParam(':email', $dados['user']['email'], PDO::PARAM_STR);
      $cad_usuario->bindParam(':senha', $dados['user']['senha'], PDO::PARAM_STR);
      $cad_usuario->bindParam(':cpf_cnpj', $dados['user']['cpf_cnpj'], PDO::PARAM_STR);
      $cad_usuario->bindParam(':adm', $dados['user']['adm'], PDO::PARAM_BOOL);
  
      $cad_usuario->execute();
  
      if ($cad_usuario->rowCount()) {
  
        $response = [
          "erro" => false,
          "mensagem" => "Usuário cadastrado com sucesso."
        ];
      } else {
        $response = [
          "erro" => true,
          "mensagem" => "Usuário não cadastrado."
        ];
      }
    } else {
      $response = [
        "erro" => true,
        "mensagem" => "Usuário não cadastrado."
      ];
    }
  }

  if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    $response_json = file_get_contents("php://input");
    $dados = json_decode($response_json, true);
  
    if ($dados) {
      $query_editar = "UPDATE usuarios SET nome=:nome, email=:email, senha=AES_ENCRYPT(:senha, 'techninja') WHERE idusuario=:idusuario";
      $edit_usuario = $conn->prepare($query_editar);
  
      $edit_usuario->bindParam(':nome', $dados['user']['nome'], PDO::PARAM_STR);
      $edit_usuario->bindParam(':idusuario', $dados['user']['idusuario'], PDO::PARAM_STR);
      $edit_usuario->bindParam(':email', $dados['user']['email'], PDO::PARAM_STR);
      $edit_usuario->bindParam(':senha', $dados['user']['senha'], PDO::PARAM_STR);
  
      $edit_usuario->execute();
  
      if ($edit_usuario->rowCount()) {
  
        $response = [
          "erro" => false,
          "mensagem" => "Usuário editado com sucesso."
        ];
      } else {
        $response = [
          "erro" => true,
          "mensagem" => "Usuário não editado."
        ];
      }
    } else {
      $response = [
        "erro" => true,
        "mensagem" => "Usuário não editado."
      ];
    }
  }

  http_response_code(200);
  echo json_encode($response);
?>