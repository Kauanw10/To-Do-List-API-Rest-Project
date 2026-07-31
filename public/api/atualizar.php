<?php 
    header("Content-Type: application/json; charset=UTF-8");
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
    
    try {
        $usuarioLogado = require_once __DIR__ . "/../../middleware/auth.php";

        require_once __DIR__ . "/../../core/conexao.php";  
        require_once __DIR__ . "/../../controllers/httpResponse.php";    
        require_once __DIR__ . "/../../repositories/TarefaRepository.php";
        require_once __DIR__ . "/../../services/TarefaService.php";

        $id_Usuario = is_array($usuarioLogado) 
        ? ($usuarioLogado['id'] ?? $usuarioLogado['user_id'] ?? $usuarioLogado['sub'] ?? null)
        : ($usuarioLogado->id ?? $usuarioLogado->user_id ?? $usuarioLogado->sub ?? null);

        $json = file_get_contents('php://input');
        $dados = json_decode($json, true);
        
        $id_Tarefa = $dados['id'] ?? null;

        $conteudo = [
        'titulo'    => $dados['titulo'] ?? '',
        'descricao' => $dados['descricao'] ?? '',
        'situacao'  => $dados['situacao'] ?? ''
    ];
        
        // --- VALIDAÇÃO DE SEGURANÇA ---
    if (!$id_Usuario || !$id_Tarefa) {
        http_response_code(400);
        echo json_encode([
            "sucesso" => false,
            "status" => "Erro 400",
            "titulo" => "ID do usuário ou da tarefa inválido."
        ]);
        exit;
    }

        $tarefaRepository = new TarefaRepository($pdo);
        $tarefaService = new TarefaService($tarefaRepository); 

        // Repassamos os dados do JSON e o ID do usuário extraído do Token JWT
        $atualizou = $tarefaService->atualizar($id_Usuario, $id_Tarefa, $conteudo);

        if ($atualizou) {
        http_response_code(200);
        echo json_encode([
            "sucesso" => true,
            "status" => "Sucesso", 
            "titulo" => "Tarefa atualizada com sucesso!"
        ]);
        } else {
            http_response_code(403); // Forbidden
            echo json_encode([
                "sucesso" => false, 
                "status" => "Aviso", 
                "titulo" => "Nenhuma alteração foi realizada na tarefa."
            ]);
        }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false, 
        'status' => 'Erro 500', 
        'titulo' => 'Erro no servidor ao atualizar tarefa', 
        'erroTecnico' => $e->getMessage()
    ]);
}

?>