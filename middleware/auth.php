<?php 
    // Autenticador de Token
    require_once __DIR__ . "/../core/jwt.php";

    // 1. Captura todos os cabeçalhos da requisição
    $headers = getallheaders();

    // Buscamos o cabeçalho Authorization (tratando maiúsculas e minúsculas)
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

    // Se o cabeçalho não existir, barramos o acesso imediatamente
    if (!$authHeader) {
        http_response_code(401);
        echo json_encode(["erro" => "Token não fornecido."]);
        exit;
    }

    // 2. Aplicação da sua lógica da pizza!
    $fatias = explode(" ", $authHeader);

    // E agora? Como fazer as validações de segurança nas fatias?
    if ($fatias[0] !== "Bearer" || empty($fatias[1])) {
        http_response_code(401);
        echo json_encode(["erro" => "Token Inválido"]);
        exit;
    }

    // 3. Finalizando a Autenticação
    $usuarioLogado = JWT::validar($fatias[1]);

    if ($usuarioLogado === false) {
        http_response_code(401);
        echo json_encode(["erro" => "Token Inválido"]);
        exit;
    }

    return $usuarioLogado;

    
?>