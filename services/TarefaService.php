<?php 
    require_once __DIR__ .  "/../core/validador.php";
    require_once __DIR__ .  "/../controllers/preparaQuery.php";
    require_once __DIR__ .  "/../core/conexao.php";

    class TarefaService{
        public static function criar($dados){
            $verif = new Validador();
            $tipoFuncao = "Criar";

            if (empty($dados['titulo'])) {
                $verif->adicionarErro('titulo', 'O título não pode estar vázio!');
            }

            if (strlen($dados['desc']) < 5) {
                $verif->adicionarErro('desc', 'A descrição deve ter pelo menos 5 caracteres!');
            }

            if ($verif->temErros()) {
                return $verif->retornarErros();
            }

            $queryCriar = preparaQuery($tipoFuncao, $dados);

            return $queryCriar;
        }

        public static function listar(){
            
            $tipoFuncao = "Listar";
           
            try {
                $queryListar = preparaQuery($tipoFuncao, null);
                return $queryListar;

            } catch (\PDOException $e) {
                return [
                "sucesso" => false,
                "status" => "Erro",
                "titulo" => "Erro ao carregar lista de tarefas.",
                "erroTecnico" => $e->getMessage()
                ];
            } catch (\Exception $e){
                return [
                    "sucesso" => false,
                    "status" => "Erro",
                    "titulo" => "Erro inesperado.",
                    "erroTecnico" => $e->getMessage()
                ];
            }
        }

        public static function atualizar($dados){
            $verif = new Validador();
            $tipoFuncao = "Atualizar";

            if (empty($dados['tituloModal'])) {
                $verif->adicionarErro('tituloModal', 'O título não pode estar vazio!');
            }

            if (strlen($dados['descModal']) < 5) {
                $verif->adicionarErro('descModal', 'A descrição deve ter pelo menos 5 caracteres!');
            }

            if ($verif->temErros()) {
                return $verif->retornarErros();
            }

            try {
                $queryAtualizar = preparaQuery($tipoFuncao, $dados);
                return $queryAtualizar;

            } catch (\PDOException $e) {
                return [
                    "sucesso" => false,
                    "status" => "Erro",
                    "titulo" => "Erro ao atualizar banco de dados.",
                    "erroTecnico" => $e->getMessage()
                ];
            }
        }

        public static function excluir($id){
            $tipoFuncao = "Excluir";
            $queryExcluir = preparaQuery($tipoFuncao, $id);

            return $queryExcluir;
        }
    }
?>