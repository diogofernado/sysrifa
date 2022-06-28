<?php

   // obter a conexão com o banco de dados
   include('../../conexao/conn.php');

   // obter os dados enviados do formulário via $_REQUEST
   $requestData = $_REQUEST;

   // verificação de campos obrigatórios
   if(empty($requestData['NOME']) || empty($requestData['CELULAR'])){
       // caso a variável venha vazia do formulário, devolver/retornar um erro
       $dados = array(
           "tipo" => 'error',
           "mensagem" => 'Existe campos obrigatórios não preenchidos.'
       );
   } else {
       // caso os campos obrigatórios estejam preenchidos, iremos realizar o cadastro
       $ID = isset($requestData['ID']) ? $requestData['ID'] : '';
       $operacao = isset($requestData['operacao']) ? $requestData['operacao'] : '';


       // verificação para cadastro ou avaliação de registro
       if($operacao == 'insert') {
           // comandos para o INSERT no banco de dados ocorrerem
           try{
               $stmt = $pdo->prepare('INSERT INTO COMPRADOR (NOME, CELULAR) VALUES (:a, :b)');
               $stmt->execute(array(
                   ':a' => utf8_decode($requestData['NOME']),
                   ':b' => utf8_decode($requestData['CELULAR'])
               ));
               $dados = array(
                "tipo" => 'success',
                "mensagem" => 'Registro salvo com sucesso'
            );
           } catch(PDOException $e) {
            $dados = array(
                "tipo" => 'error',
                "mensagem" => 'Não foi possível salvar o registro: '.$e
            );
           }
       } else {
           // se a operação vir vazia, então iremos realizar um UPDATE
           try{
            $stmt = $pdo->prepare('UPDATE COMPRADOR SET NOME = :a WHERE ID = :id, CELULAR = :b WHERE ID = :id');
            $stmt->execute(array(
                ':id' => $ID,
                ':a' => utf8_decode($requestData['NOME']),
                ':b' => utf8_decode($requestData['CELULAR'])
            ));
            $dados = array(
             "tipo" => 'success',
             "mensagem" => 'Registro atualizado com sucesso'
         );
        } catch(PDOException $e) {
         $dados = array(
             "tipo" => 'error',
             "mensagem" => 'Não foi possível atualizar o registro: '.$e
         );
       }
   }
}


   // converter o array de retorno em uma representação JSON
   echo json_encode($dados);