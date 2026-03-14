<?php
    define('DB_HOST', 'mysql-1303todolist-kauanw10-db1.f.aivencloud.com');
    define('DB_NAME', 'defaultdb');
    define('DB_USER', 'avnadmin');
    define('DB_PASS', '');
    define('DB_PORT', '10745');
    define('ENV', 'prod');

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