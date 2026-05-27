<?php 
    // Registrador
    require_once __DIR__ . '/../../config/env.php';
    require_once __DIR__ . '/../../services/AuthService.php';

    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);

    if ($dados) {
        $_POST = $dados;
    }

    header('Content-Type: application/json');

    try {
        $resultado = AuthService::registrar($_POST); 
        
        if (isset($resultado) && $resultado['sucesso'] === true) {
            http_response_code(201);
            echo json_encode($resultado);
        }else {
            http_response_code(400);
            echo json_encode([
                "sucesso" => false,
                "status" => "Alerta",
                "titulo" => "Erro de Validação",
                "erros" => $resultado
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