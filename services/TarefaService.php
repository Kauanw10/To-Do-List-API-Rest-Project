<?php 
    require_once __DIR__ .  "/../core/validador.php";
    require_once __DIR__ .  "/../controllers/funcoes.php";
    require_once __DIR__ .  "/../core/conexao.php";

    class TarefaService{
        public static function criar($dados){
            global $pdo;
            $verif = new Validador();

            if (empty($dados['titulo'])) {
                $verif->adicionarErro('titulo', 'O título não pode estar vázio!');
            }

            if (strlen($dados['desc']) < 5) {
                $verif->adicionarErro('desc', 'A descrição deve ter pelo menos 5 caracteres!');
            }

            if ($verif->temErros()) {
                return $verif->retornarErros();
            }

            $stmt = $pdo->prepare('INSERT INTO tarefas (titulo, descricao, situacao) VALUES (:titulo, :descricao, :situacao)');
            $stmt->bindValue('titulo', $dados['titulo'], PDO::PARAM_STR);
            $stmt->bindValue('descricao', $dados['desc'], PDO::PARAM_STR);
            $stmt->bindValue('situacao', $dados['status'], PDO::PARAM_STR);

            $statusPOST = "Sucesso";
            $tituloPOST = "Tarefa Criada.";
        
            $retornoCriar = statusTarefa($stmt, $statusPOST, $tituloPOST, 201);

            return $retornoCriar;
        }

        public static function listar(){
            global $pdo;
           
            try {
                if (!$pdo) {
                throw new \Exception("Conexão com o banco de dados não encontrada.");
                }

                $stmt = $pdo->prepare("SELECT * FROM tarefas ORDER BY id ASC");
                $stmt->execute();
                
                $lista = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                return $lista ?: [];

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
            global $pdo;
            $verif = new Validador();

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
                $stmt = $pdo->prepare('UPDATE `tarefas` SET `titulo` = :titulo, `descricao` = :descricao, `situacao` = :situacao  WHERE `id` = :id');

                $stmt->bindValue('id', $dados['id_Tarefa'], PDO::PARAM_INT);
                $stmt->bindValue('titulo', $dados['tituloModal'], PDO::PARAM_STR);
                $stmt->bindValue('descricao', $dados['descModal'], PDO::PARAM_STR);
                $stmt->bindValue('situacao', $dados['statusModal'], PDO::PARAM_STR);


                $statusPUT = "Sucesso";
                $tituloPUT = "Tarefa Atualizada.";

                return statusTarefa($stmt, $statusPUT, $tituloPUT, 200);
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
            global $pdo;
                $stmt = $pdo->prepare('DELETE FROM `tarefas` WHERE id = :id');
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
                $statusDEL = "Sucesso";
                $tituloDEL = "Tarefa Excluida.";
            
                $retornoExcluir = statusTarefa($stmt, $statusDEL, $tituloDEL, 200);

                return $retornoExcluir;
        }
    }
?>