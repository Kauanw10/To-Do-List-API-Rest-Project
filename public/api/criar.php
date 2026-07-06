<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . "/../../core/conexao.php";  
require_once __DIR__ . "/../../core/httpResponse.php";    
require_once __DIR__ . "/../../repositories/TarefaRepository.php";
require_once __DIR__ . "/../../services/TarefaService.php";

$json = file_get_contents('php://input');
$dados = json_decode($json, true);

if ($dados) {
    $dados["usuario_id"] = 1;
    $_POST = $dados;
}

header('Content-Type: application/json');

try {
    $tarefaRepository = new TarefaRepository($pdo);
    $tarefaService = new TarefaService($tarefaRepository); 

    $resultado = $tarefaService->criar($_POST);

     if (isset($resultado) && $resultado['sucesso'] === true) {
            http_response_code(200);
            echo json_encode($resultado);
        }else {
            http_response_code(400);
            echo json_encode($resultado);
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