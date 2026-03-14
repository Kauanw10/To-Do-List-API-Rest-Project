<?php 
    require_once __DIR__ . "/../../core/conexao.php";
    require_once __DIR__ . "/../../services/TarefaService.php";  
    require_once __DIR__ . "/../../core/helpers.php";  
    
   $dados = getJson();
   $resultadoExcluir = TarefaService::excluir($dados['id']);

    response($resultadoExcluir);
?>