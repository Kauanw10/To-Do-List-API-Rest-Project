<?php 
    require_once __DIR__ . "/../core/conexao.php";

function statusTarefa($stmt, $status, $titulo, $codigoHttp){
    try {
    if ($stmt->execute()) {
        responder(true, $status, $titulo, $codigoHttp);
    }
    } catch (Exception $e) {
    if (ENV === "dev") {
        $status = "Erro";
        $titulo = $e->getMessage() . " Arquivo: " . $e->getFile() . " Linha: " . $e->getLine();

        responder(false, $status, $titulo, 500);

    } else {
        registrarErro($e);

        $status = "Erro";
        $titulo = "Tente novamente mais tarde.";
        responder(false, $status, $titulo, 500);
    }
    }
}

function responder($sucesso, $status, $titulo, $codigoHttp){
    http_response_code($codigoHttp);

    header('Content-Type: application/json');

    $resposta = [
        "sucesso" => $sucesso,
        "status" => $status,
        "titulo" => $titulo
    ];

    echo json_encode($resposta);
    exit;
}

function registrarErro($e) {
    $caminhoPastaLogs = __DIR__ . '/../logs'; 
    $arquivoLog = __DIR__ . '/../logs/erros.log';

    if (!is_dir($caminhoPasta)) {
        mkdir($caminhoPasta, 0777, true);
    }

    $mensagem = "[" . date('Y-m-d H:i:s') . "]" . PHP_EOL;
    $mensagem .= "Mensagem: " . $e->getMessage() . PHP_EOL;
    $mensagem .= "Arquivo: " . $e->getFile() . PHP_EOL;
    $mensagem .= "Linha: " . $e->getLine() . PHP_EOL;
    $mensagem .= "---------------------------------------" . PHP_EOL;
  
    if (!error_log($mensagem, 3, $arquivoLog)) {
        error_log("Erro no PHP: " . $e->getMessage()); 
    }
}
?>