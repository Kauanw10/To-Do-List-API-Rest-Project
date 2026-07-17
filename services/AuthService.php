<?php

    // Autenticador
    require_once __DIR__ .  "/../core/validador.php";
    require_once __DIR__ .  "/../core/jwt.php";
    require_once __DIR__ .  "/../controllers/buildResponse.php";
    require_once __DIR__ .  "/../repositories/UserRepository.php";

    class AuthService{
        public static function registrar($dados){
        // Cuida do cadastro, gera hash, valida duplicidade.
        
            $verif = new Validador();
            $tipoFuncao = "Registrar";

            // Validação Básica dos Campos
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
            
            // Buscando pelo E-mail dado pelo Usuário
            $existeEmail = UserRepository::buscarPorEmail($dados['email']);

            if ($existeEmail != false) {
                $verif->adicionarErro('email', 'E-mail já está cadastrado!');
            }

            if ($verif->temErros()) {
                return $verif->retornarErros();
            }

            // Criar senha hash
            $senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);
            $dados['senha'] = $senhaHash;

            // Criar Registro de Usuário
            $usuarioRegistrado = UserRepository::criarUser($dados);
            
            if ($usuarioRegistrado === false) {
                $resultado = formatResponse($tipoFuncao, false);
            }else {
                $resultado = formatResponse($tipoFuncao, true);
            }

            return $resultado;
        }

        public static function login($dados){
            // Cuida da entrada, verifica se a senha bate com o hash do banco.

            $verif = new Validador();
            $tipoFuncao = "Login";

            // Validação Básica dos Campos
            if (empty($dados['email'])) {
                $verif->adicionarErro('email', 'E-mail está vazio!');
            }

            if (empty($dados['senha'])) {
                $verif->adicionarErro('senha', 'Senha está vazia!');
            }

            if ($verif->temErros()) {
                return $verif->retornarErros();
            }

            // Buscar Usuário pelo e-mail fornecido
            $usuario = UserRepository::buscarPorEmail($dados['email']);

            if ($usuario === false) {
                $verif->adicionarErro('email', 'E-mail ou senha inválidos!');
            }          

            if ($verif->temErros()) {
                return $verif->retornarErros();
            }
           
            if (!password_verify($dados['senha'], $usuario['senha'])) {
                $verif->adicionarErro('senha', 'E-mail ou senha inválidos!');
            }

            if ($verif->temErros()) {
                return $verif->retornarErros();
            }

            $payload = [
                'user_id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'exp' => time() + 3600 // Expira em 1 hora
            ];

            $token = JWT::gerar($payload);

            return formatResponse($tipoFuncao, ['token' => $token]);

        }

    }

?>

