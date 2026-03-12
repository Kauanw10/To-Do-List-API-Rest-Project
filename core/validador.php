<?php 
    class Validador{
        private $erros = [];

        public function adicionarErro($campo,$mensagem){
           $this->erros[$campo] = $mensagem;
        }

        public function temErros(){
           return count($this->erros) > 0;
        }

        public function retornarErros($statusPadrao = "Alerta", $tituloPadrao = "Campo(s) Inválido(s)."){
            return[
                "sucesso" => false,
                "status" => $statusPadrao,
                "titulo" => $tituloPadrao,
                "erros" => $this->erros
            ];
        }
    }
?>