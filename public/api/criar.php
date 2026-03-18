<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../services/TarefaService.php';
require_once __DIR__ . '/../../controllers/funcoes.php';

$json = file_get_contents('php://input');
$dados = json_decode($json, true);

if ($dados) {
    $_POST = $dados;
}

header('Content-Type: application/json');

try {
    $service = new TarefaService();
    $resultado = $service->criar($_POST); 
    
    echo json_encode($resultado);
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