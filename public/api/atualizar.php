<?php 
    header('Content-Type: application/json');
    
    try {
        require_once __DIR__ . "/../../middleware/auth.php";  
        require_once __DIR__ . "/../../core/conexao.php";  
        require_once __DIR__ . "/../../controllers/httpResponse.php";    
        require_once __DIR__ . "/../../repositories/TarefaRepository.php";
        require_once __DIR__ . "/../../services/TarefaService.php";

        $json = file_get_contents('php://input');
        $conteudo = json_decode($json, true);

        // Validação mínima: o ID da tarefa precisa estar presente no JSON
        if (empty($conteudo['id'])) {
            http_response_code(400);
            echo json_encode(["sucesso" => false, "erro" => "O ID da tarefa é obrigatório para edição."]);
            exit;
        }

        $tarefaRepository = new TarefaRepository($pdo);
        $tarefaService = new TarefaService($tarefaRepository); 
        $idTarefa = $conteudo['id'];

        // Repassamos os dados do JSON e o ID do usuário extraído do Token JWT
        $atualizou = $tarefaService->atualizar($usuarioLogado->user_id, $idTarefa, $conteudo);

        if ($atualizou) {
        http_response_code(200);
        echo json_encode([
            "sucesso" => true, 
            "mensagem" => "Tarefa atualizada com sucesso!"
        ]);
        } else {
            http_response_code(403); // Forbidden
            echo json_encode([
                "sucesso" => false, 
                "erro" => "Operação não permitida. Tarefa não encontrada, sem alterações ou pertence a outro usuário."
            ]);
        }
    
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