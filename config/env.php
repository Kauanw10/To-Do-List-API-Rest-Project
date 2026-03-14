<?php

    define('DB_HOST', getenv('DB_HOST') ?: 'mysql-1303todolist-kauanw10-db1.f.aivencloud.com');
    define('DB_NAME', getenv('DB_NAME') ?: 'defaultdb');
    define('DB_USER', getenv('DB_USER') ?: 'avnadmin');
    define('DB_PASS', getenv('DB_PASS') ?: '');
    define('DB_PORT', getenv('DB_PORT') ?: '10745');
    
    define('ENV', getenv('RENDER') ? 'prod' : 'dev');

    if (ENV === 'dev') {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        ini_set('display_startup_errors', 0);
    }
?>