<?php 
    require_once __DIR__ .  "/../core/conexao.php";

    class UserRepository{
        public static function criarUser($dados){
            global $pdo;

            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
            $stmt->bindValue(':nome', $dados['nome'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $dados['email'], PDO::PARAM_STR);
            $stmt->bindValue(':senha', $dados['senha'], PDO::PARAM_STR);

            if ($stmt->execute()) {
                return true;
            }else {
                return false;
            }
        }

        public static function buscarPorEmail($email){
            global $pdo;

            $stmt = $pdo->prepare("SELECT id, nome, senha FROM usuarios WHERE `email` = :email");
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() != 1) {
                return false;
            }
            
            $dbData = $stmt->fetch(PDO::FETCH_ASSOC);

            return $dbData;
                
        }
            
    }
?>