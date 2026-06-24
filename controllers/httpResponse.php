<?php 
    require_once __DIR__ . "/../core/conexao.php";

function statusTarefa($stmt, $status, $titulo, $codigoHttp, $payload = null){
    try {

        if ($stmt === true) {
            return responder(true, $status, $titulo, $codigoHttp, $payload);
        } else {
            return responder(false, $status, $titulo, $codigoHttp);
        }

    } catch (Exception $e) {
        if (ENV === "dev") {
            $status = "Erro";
            $titulo = $e->getMessage() . " Arquivo: " . $e->getFile() . " Linha: " . $e->getLine();

            return responder(false, $status, $titulo, 500);

        } else {
            registrarErro($e);

            $status = "Erro";
            $titulo = "Tente novamente mais tarde.";
            return responder(false, $status, $titulo, 500);
        }
    }
}

function responder($sucesso, $status, $titulo, $codigoHttp, $payload = null){
    http_response_code($codigoHttp);

    header('Content-Type: application/json');
    
    $resposta = [
        "sucesso" => $sucesso,
        "status" => $status,
        "titulo" => $titulo
    ];

    if ($payload != null) {
      $resposta['dados'] = $payload;
    }

    return $resposta;
}

function registrarErro($e) {
    $caminhoPastaLogs = __DIR__ . '/../logs'; 
    $arquivoLog = __DIR__ . '/../logs/erros.log';

    if (!is_dir($caminhoPastaLogs)) {
        mkdir($caminhoPastaLogs, 0777, true);
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