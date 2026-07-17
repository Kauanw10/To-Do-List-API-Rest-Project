<?php 
    // Token JWT
    require_once __DIR__ . "/../config/env.php";

    class JWT {
    private static $secret = SECRET_KEY;

   
    private static function base64UrlEncode($dados) {
        $b64 = base64_encode($dados);
        return str_replace(['+', '/', '='], ['-', '_', ''], $b64);
    }

    private static function base64UrlDecode($dados) {
        // Faz o inverso: devolve o '-' para '+' e o '_' para '/'
        $b64 = str_replace(['-', '_'], ['+', '/'], $dados);
        
        // Adiciona o preenchimento de '=' se necessário para o base64 ficar válido
        $padding = strlen($b64) % 4;
        if ($padding) {
            $b64 .= str_repeat('=', 4 - $padding);
        }
        
        return base64_decode($b64);
    }

    public static function gerar($payload) {

        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payloadJson = json_encode($payload);

        $headerCodificado = self::base64UrlEncode($header);
        $payloadCodificado = self::base64UrlEncode($payloadJson);

        $baseAssinatura = $headerCodificado . "." . $payloadCodificado;

        
        $signature = hash_hmac('sha256', $baseAssinatura, self::$secret, true);

        $signatureCodificado = self::base64UrlEncode($signature);

        return $headerCodificado . "." . $payloadCodificado . "." . $signatureCodificado;
    }

    public static function validar($token) {
        // Como separar o $token em $header, $payload e $signature?
        $tokenSeparado = explode(".", $token);

        // Validação inicial
        if (count($tokenSeparado) !== 3) {
            return false;
        }
            
        $headerRecebido = $tokenSeparado[0];
        $payloadRecebido = $tokenSeparado[1];
        $assinaturaRecebida = $tokenSeparado[2];

        // Reconstrução e Comparação
        $baseAssRecriada = $headerRecebido . "." . $payloadRecebido;

        $novaAssinatura = hash_hmac('sha256', $baseAssRecriada, self::$secret, true);

        $assinaturaCodificada = self::base64UrlEncode($novaAssinatura);

        $comp = $assinaturaCodificada === $assinaturaRecebida ? true : false;

        // Se a assinatura não bater, tchau!
        if ($comp !== true) {
            return false;
        }

        $payload = self::base64UrlDecode($payloadRecebido);
        $payloadJson = json_decode($payload);

        // Se o token estiver vencido, tchau!
        if ($payloadJson->exp <= time()) {
            return false;
        }

        // Se chegou até aqui, está tudo perfeito. Retorna o payload!
        return $payloadJson;

    }
}
?>