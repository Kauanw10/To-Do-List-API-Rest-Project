<?php
// Força o PHP a mostrar erros na tela para o JS capturar
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../services/TarefaService.php';
require_once __DIR__ . '/../../controllers/funcoes.php';

// CAPTURA O JSON DO JAVASCRIPT
$json = file_get_contents('php://input');
$dados = json_decode($json, true);

// Se o JSON existir, preenche o $_POST para não quebrar sua lógica
if ($dados) {
    $_POST = $dados;
}

header('Content-Type: application/json');

try {
    $service = new TarefaService();
    // Use os dados do JSON aqui
    $resultado = $service->criar($_POST); 
    
    if ($resultado) {
        echo json_encode(['status' => 'sucesso']);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao inserir']);
    }
} catch (Exception $e) {
    // Se der erro no SQL, ele vai retornar o erro real para o Console
    http_response_code(500);
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

//     require_once __DIR__ . "/../../services/TarefaService.php";
//     require_once __DIR__ . "/../../core/helpers.php"; 
   
//     $dados = getJson();
//     $resultadoCriar = TarefaService::criar($dados);

//     $statusHttp = ($resultadoCriar['sucesso']) ? 201 : 400;
//     response($resultadoCriar, $statusHttp);

?> 