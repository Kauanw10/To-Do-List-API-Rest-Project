<?php 
    require_once __DIR__ .  "/../core/conexao.php";

    class TarefaRepository{

        private $db;

        public function __construct($conexaoDB){
            $this->db = $conexaoDB;
        }

        // Proximo Passo: Desenvolver as Funcionalidades dos Métodos abaixo

        public function criar($conteudo){
            $stmt = $this->db->prepare('INSERT INTO tarefas (titulo, descricao, situacao, usuario_id) VALUES (:titulo, :descricao, :situacao, :usuario_id)');

            $stmt->bindValue(':titulo', $conteudo['titulo'], PDO::PARAM_STR);
            $stmt->bindValue(':descricao', $conteudo['desc'], PDO::PARAM_STR);
            $stmt->bindValue(':situacao', $conteudo['status'], PDO::PARAM_STR);
            $stmt->bindValue(':usuario_id', $conteudo['usuario_id'], PDO::PARAM_INT);

            return $stmt->execute();
        }

        public function listar($id_Usuario){
            $stmt = $this->db->prepare("SELECT * FROM tarefas WHERE usuario_id = :usuario_id ORDER BY id ASC");
            $stmt->bindValue(':usuario_id', $id_Usuario, PDO::PARAM_INT);
            $stmt->execute();
            
            $lista = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $lista ?: [];
        }

        public function atualizar($id_Usuario, $id_Tarefa, $conteudo){
            $stmt = $this->db->prepare("UPDATE tarefas SET titulo = :titulo, descricao = :descricao, situacao = :situacao WHERE id = :id_tarefa AND usuario_id = :usuario_id");

            $stmt->bindValue(':id_tarefa', $id_Tarefa, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $id_Usuario, PDO::PARAM_INT);
            $stmt->bindValue(':titulo', $conteudo['tituloModal'], PDO::PARAM_STR);
            $stmt->bindValue(':descricao', $conteudo['descModal'], PDO::PARAM_STR);
            $stmt->bindValue(':situacao', $conteudo['statusModal'], PDO::PARAM_STR);

            $stmt->execute();

            // Retorna true se alguma linha foi modificada
            return $stmt->execute();
        }

        public function excluir($id_Usuario, $id_Tarefa){
            $stmt = $this->db->prepare("DELETE FROM tarefas WHERE id = :id_tarefa AND usuario_id = :usuario_id");
            $stmt->bindValue(':id_tarefa', $id_Tarefa, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $id_Usuario, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        }
    }
?>