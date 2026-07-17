<?php 
header('Content-Type: application/json');

try {
    require_once __DIR__ . "/../../middleware/auth.php";  
    require_once __DIR__ . "/../../core/conexao.php";  
    require_once __DIR__ . "/../../controllers/httpResponse.php";    
    require_once __DIR__ . "/../../repositories/TarefaRepository.php";
    require_once __DIR__ . "/../../services/TarefaService.php";

    if (!isset($pdo)) {
        echo json_encode(["erro" => "Variavel PDO nao definida. Verifique o database.php"]);
        exit;
    }

    $tarefaRepository = new TarefaRepository($pdo);
    $tarefaService = new TarefaService($tarefaRepository);

    $result = $tarefaService->listar($usuarioLogado->user_id);
    echo json_encode($result);

} catch (Throwable $e) {
    echo json_encode([
        "sucesso" => false,
        "titulo" => "Erro Fatal de Sistema",
        "erroTecnico" => $e->getMessage(),
        "arquivo" => $e->getFile(),
        "linha" => $e->getLine()
    ]);
}
?>