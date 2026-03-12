<?php
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'tarefas_list_api');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('ENV', 'dev');

    if (ENV === 'dev') {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        ini_set('log_errors', 0);
    } else {
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        ini_set('error_log', __DIR__ . '/../logs/erros.log');
        ini_set('display_startup_errors', 0);
    }
?>