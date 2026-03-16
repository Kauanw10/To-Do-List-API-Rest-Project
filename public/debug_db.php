<?php
require_once __DIR__ . '/../config/env.php';

header('Content-Type: text/plain');

echo "--- Diagnóstico de Conexão ---\n";
echo "Host: " . DB_HOST . "\n";
echo "User: " . DB_USER . "\n";
echo "Port: " . DB_PORT . "\n";
echo "DB Name: " . DB_NAME . "\n";
echo "Senha configurada: " . (empty(DB_PASS) ? "NÃO (Vazia)" : "SIM") . "\n";

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5 // Se não conectar em 5s, para.
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "\n✅ SUCESSO: O PHP conectou ao MySQL do Aiven!";
} catch (PDOException $e) {
    echo "\n❌ ERRO DE CONEXÃO:\n";
    echo $e->getMessage();
}