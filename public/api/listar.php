<?php 
header('Content-Type: application/json');

try {
    require_once __DIR__ . "/../../core/conexao.php"; 
    require_once __DIR__ . "/../../services/TarefaService.php";
    require_once __DIR__ . "/../../core/helpers.php";

    if (!isset($pdo)) {
        echo json_encode(["erro" => "Variavel PDO nao definida. Verifique o database.php"]);
        exit;
    }

    $result = TarefaService::listar();
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