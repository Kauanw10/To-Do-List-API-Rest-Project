<?php 
    require_once "../services/TarefaService.php";
    require_once "../core/helpers.php";
   
    $dados = getJson();
    $resultadoCriar = TarefaService::criar($dados);

    $statusHttp = ($resultadoCriar['sucesso']) ? 201 : 400;
    response($resultadoCriar, $statusHttp);

?>