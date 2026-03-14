<?php 
    require_once "/var/www/html/services/TarefaService.php";
    require_once "/var/www/html/core/helpers.php";
   
    $dados = getJson();
    $resultadoCriar = TarefaService::criar($dados);

    $statusHttp = ($resultadoCriar['sucesso']) ? 201 : 400;
    response($resultadoCriar, $statusHttp);

?>