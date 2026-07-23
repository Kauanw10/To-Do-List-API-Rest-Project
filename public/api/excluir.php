<?php 
    header('Content-Type: application/json');
    
    try {
        require_once __DIR__ . "/../../middleware/auth.php";  
        require_once __DIR__ . "/../../core/conexao.php";  
        require_once __DIR__ . "/../../controllers/httpResponse.php";  
        require_once __DIR__ . "/../../repositories/TarefaRepository.php";
        require_once __DIR__ . "/../../services/TarefaService.php";

        $json = file_get_contents('php://input');
        $dados = json_decode($json, true);

        // $userId = 1;
        $tarefaId = $dados["id"] ?? null;

        if (!$tarefaId) {
            http_response_code(400);
            echo json_encode(["sucesso" => false, "erro" => "O ID da tarefa é obrigatório."]);
            exit;
        }
    
        $tarefaRepository = new TarefaRepository($pdo);
        $tarefaService = new TarefaService($tarefaRepository); 

        // Passamos o ID da tarefa E o ID que o Token extraiu com segurança
        $deletou = $tarefaService->excluir($usuarioLogado->user_id, $tarefaId);


        if ($deletou) {
            http_response_code(200);
            echo json_encode([
                "sucesso" => true, 
                "mensagem" => "Tarefa excluída com sucesso!"
            ]);
        } else {
            http_response_code(403); // 403 Forbidden
            echo json_encode([
                "sucesso" => false, 
                "erro" => "Operação não permitida. Tarefa não encontrada ou não pertence a este usuário."
            ]);
        }

        // if (isset($resultado) && $resultado['sucesso'] === true) {
        //         http_response_code(200);
        //         echo json_encode($resultado);
        //     }else {
        //         http_response_code(400);
        //         echo json_encode($resultado);
        //     }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'sucesso' => false, 
            'status' => 'Erro', 
            'titulo' => 'Erro no servidor', 
            'erroTecnico' => $e->getMessage()
        ]);
    }

?>