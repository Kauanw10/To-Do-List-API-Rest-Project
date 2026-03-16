<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);

    require_once __DIR__ . "/../../services/TarefaService.php";
    require_once __DIR__ . "/../../core/helpers.php"; 
   
    $dados = getJson();
    $resultadoCriar = TarefaService::criar($dados);

    $statusHttp = ($resultadoCriar['sucesso']) ? 201 : 400;
    response($resultadoCriar, $statusHttp);

?>