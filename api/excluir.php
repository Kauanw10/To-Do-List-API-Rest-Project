<?php 
    require_once "../core/conexao.php";
    require_once "../services/TarefaService.php";  
    require_once "../core/helpers.php";  
    
   $dados = getJson();
   $resultadoExcluir = TarefaService::excluir($dados['id']);

    response($resultadoExcluir);
?>