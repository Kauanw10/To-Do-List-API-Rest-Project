<?php 
    require_once __DIR__ .  "/../core/conexao.php";
    require_once __DIR__ .  "/httpResponse.php";


    function formatResponse($tipoFuncao, $dados){
        switch ($tipoFuncao){
            
            case 'Registrar':
                
                if ($dados === false) {
                    $statusRegistrar = "Erro";
                    $tituloRegistrar = "Não foi possivel criar o Cadastro.";
                    
                    return statusTarefa(false, $statusRegistrar, $tituloRegistrar, 400);
                }
                
                $statusRegistrar = "Sucesso";
                $tituloRegistrar = "Registro Criado.";
                
                return statusTarefa(true, $statusRegistrar, $tituloRegistrar, 201);
                    
            break;

            case 'Criar':
                global $pdo;
                $stmt = $pdo->prepare('INSERT INTO tarefas (titulo, descricao, situacao) VALUES (:titulo, :descricao, :situacao)');
                $stmt->bindValue('titulo', $dados['titulo'], PDO::PARAM_STR);
                $stmt->bindValue('descricao', $dados['desc'], PDO::PARAM_STR);
                $stmt->bindValue('situacao', $dados['status'], PDO::PARAM_STR);

                $statusPOST = "Sucesso";
                $tituloPOST = "Tarefa Criada.";
            
                return statusTarefa($stmt, $statusPOST, $tituloPOST, 201);

            break;

            case 'Listar':
                global $pdo;
                
                if (!$pdo) {
                    throw new \Exception("Conexão com o banco de dados não encontrada.");
                }

                $stmt = $pdo->prepare("SELECT * FROM tarefas ORDER BY id ASC");
                $stmt->execute();
                
                $lista = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                return $lista ?: [];
            break;

            case 'Atualizar':
                global $pdo;
                $stmt = $pdo->prepare('UPDATE `tarefas` SET `titulo` = :titulo, `descricao` = :descricao, `situacao` = :situacao  WHERE `id` = :id');

                $stmt->bindValue('id', $dados['id_Tarefa'], PDO::PARAM_INT);
                $stmt->bindValue('titulo', $dados['tituloModal'], PDO::PARAM_STR);
                $stmt->bindValue('descricao', $dados['descModal'], PDO::PARAM_STR);
                $stmt->bindValue('situacao', $dados['statusModal'], PDO::PARAM_STR);


                $statusPUT = "Sucesso";
                $tituloPUT = "Tarefa Atualizada.";

                return statusTarefa($stmt, $statusPUT, $tituloPUT, 200);
            
            break;

            case 'Excluir':
                global $pdo;

                $stmt = $pdo->prepare('DELETE FROM `tarefas` WHERE id = :id');
                $stmt->bindValue(':id', $dados, PDO::PARAM_INT);
    
                $statusDEL = "Sucesso";
                $tituloDEL = "Tarefa Excluida.";
            
                return statusTarefa($stmt, $statusDEL, $tituloDEL, 200);

            break;
        }
    }
?>