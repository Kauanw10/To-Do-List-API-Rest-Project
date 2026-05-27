<?php

    // Autenticador
    require_once __DIR__ .  "/../core/validador.php";
    require_once __DIR__ .  "/../controllers/preparaQuery.php";
    require_once __DIR__ .  "/../core/conexao.php";

    class AuthService{
        public static function registrar($dados){
        // Cuida do cadastro, gera hash, valida duplicidade.
            global $pdo;
            $verif = new Validador();
            $tipoFuncao = "Registrar";

            if (empty($dados['nome'])) {
                $verif->adicionarErro('nome', 'Nome está vazio!');
            }

           if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                $verif->adicionarErro('email', 'Formato de E-mail Inválido!');
            }

           

            if (strlen($dados['senha']) < 5) {
                $verif->adicionarErro('senha', 'Senha precisa conter no minimo 5 caracteres!');
            }



            if ($verif->temErros()) {
                return $verif->retornarErros();
            }


            $stmt = $pdo->prepare("SELECT * FROM users WHERE `email` = :email");
            $stmt->bindValue('email', $dados['email'], PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount()) {
                $verif->adicionarErro('email', 'E-mail já está cadastrado!');
            }



            if ($verif->temErros()) {
                return $verif->retornarErros();
            }

            // Preparar query
            $senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);
            $dados['senha'] = $senhaHash;

            $queryRegistrar = preparaQuery($tipoFuncao, $dados);
            return $queryRegistrar;

        }



        public static function login($dados){
            // Cuida da entrada, verifica se a senha bate com o hash do banco.
            global $pdo;
            $verif = new Validador();

            if (empty($dados['email'])) {
                $verif->adicionarErro('email', 'E-mail está vazio!');
            }

            if (empty($dados['senha'])) {
                $verif->adicionarErro('senha', 'Senha está vazia!');
            }

            if ($verif->temErros()) {
                return $verif->retornarErros();
            }

            $stmt = $pdo->prepare("SELECT id, nome, senha FROM users WHERE `email` = :email");
            $stmt->bindValue(':email', $dados['email'], PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() != 1) {
                $verif->adicionarErro('email', 'E-mail ou senha inválidos!');
            }          

            if ($verif->temErros()) {
                return $verif->retornarErros();
            }

            $dbData = $stmt->fetch(PDO::FETCH_ASSOC);
           
            if (!password_verify($dados['senha'], $dbData['senha'])) {
                $verif->adicionarErro('senha', 'E-mail ou senha inválidos!');
            }

            if ($verif->temErros()) {
                return $verif->retornarErros();
            }

           return [
                "sucesso" => true,
                "status" => "Sucesso",
                "titulo" => "Login realizado.",
                "usuario" => [
                    "id" => $dbData['id'],
                    "nome" => $dbData['nome']
                ]
            ];

        }

    }

?>

