<?php 
    require_once "/var/www/html/core/conexao.php";
    require_once "/var/www/html/services/TarefaService.php";  
    require_once "/var/www/html/core/helpers.php";  
    
   $dados = getJson();
   $resultadoExcluir = TarefaService::excluir($dados['id']);

    response($resultadoExcluir);
?>