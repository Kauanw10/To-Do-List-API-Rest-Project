<?php 
    require_once __DIR__ .  "/../core/conexao.php";
    require_once __DIR__ .  "/httpResponse.php";


    // Refatorar esta Arquivo
    function formatResponse($tipoFuncao, $dados){
        switch ($tipoFuncao){
            
            case 'Registrar':
                
                if ($dados === false) {
                    $statusRegistrar = "Erro";
                    $tituloRegistrar = "Não foi possivel criar o Cadastro.";
                    
                    return statusTarefa(false, $statusRegistrar, $tituloRegistrar, 400);
                }
                
                $statusRegistrar = "Sucesso";
                $tituloRegistrar = "Registro Criado.";
                
                return statusTarefa(true, $statusRegistrar, $tituloRegistrar, 201);
                    
            break;

            case 'Login':
                if ($dados === false) {
                    $statusLogin = "Erro";
                    $tituloLogin = "Não foi possivel Logar.";
                    
                    return statusTarefa(false, $statusLogin, $tituloLogin, 401);
                }

                $statusLogin = "Sucesso";
                $tituloLogin = "Login Realizado.";
                
                return statusTarefa(true, $statusLogin, $tituloLogin, 201, $dados);

            break;

            case 'Criar':
                if ($dados === false) {
                    $statusCriar = "Erro";
                    $tituloCriar = "Não foi possivel Criar uma nova Tarefa.";
                    
                    return statusTarefa(false, $statusCriar, $tituloCriar, 401);
                }

                $statusCriar = "Sucesso";
                $tituloCriar = "Tarefa Criada.";
                
                return statusTarefa(true, $statusCriar, $tituloCriar, 201, $dados);

            break;

            case 'Listar':

                $statusListar = "Sucesso";
                $tituloListar = "Tarefas Listadas.";
                
                return statusTarefa(true, $statusListar, $tituloListar, 200, $dados);
            break;

            case 'Atualizar':
                if ($dados === false) {
                    $statusAtualizar = "Erro";
                    $tituloAtualizar = "Não foi possivel Atualizar a nova Tarefa.";
                    
                    return statusTarefa(false, $statusAtualizar, $tituloAtualizar, 400);
                }

                $statusAtualizar = "Sucesso";
                $tituloAtualizar = "Tarefa Atualizada.";
                
                return statusTarefa(true, $statusAtualizar, $tituloAtualizar, 200, $dados);

            break;
            
            break;

            case 'Excluir':
                if ($dados === false) {
                    $statusExcluir = "Erro";
                    $tituloExcluir = "Não foi possivel Excluir a Tarefa.";
                    
                    return statusTarefa(false, $statusExcluir, $tituloExcluir, 400);
                }

                $statusExcluir = "Sucesso";
                $tituloExcluir = "Tarefa Excluida.";
                
                return statusTarefa(true, $statusExcluir, $tituloExcluir, 200);

            break;
        }
    }
?>