<?php 
    require_once "/var/www/html/core/conexao.php";
    require_once "/var/www/html/services/TarefaService.php";  
    require_once "/var/www/html/core/helpers.php"; 

    $dados = getJson();
    $resultadoEditar = TarefaService::atualizar($dados);

    response($resultadoEditar);
?>