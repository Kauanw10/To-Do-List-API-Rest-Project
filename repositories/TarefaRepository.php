<?php 
    require_once __DIR__ .  "/../core/conexao.php";

    class TarefaRepository{

        private $db;

        public function __construct($conexaoDB){
            $this->db = $conexaoDB;
        }

        // Proximo Passo: Desenvolver as Funcionalidades dos Métodos abaixo

        public function criar($dados){
            
        }

        public function listar($id_Usuario){
            
        }

        public function atualizar($id_Usuario, $id_Tarefa, $conteudo){
            
        }

        public function excluir($id_Usuario, $id_Tarefa){
            
        }
    }
?>