<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registro - To-Do List</title>
    <link rel="stylesheet" href="style.css">
    
    <!-- Redireciona se o usuário JÁ estiver logado -->
    <script>
        if (localStorage.getItem('token_jwt')) {
            window.location.href = 'dashboard.php';
        }
    </script>
</head>
<body>

    <main class="auth-container">
        <!-- ÁREA DE MENSAGENS / ERROS -->
        <p id="msg"></p>

        <!-- FORMULÁRIO DE LOGIN -->
        <section id="sec-login">
            <h2>Entrar na sua conta</h2>
            <form id="formLogin">
                <div class="campo">
                    <label for="login_email">E-mail:</label>
                    <input type="email" id="login_email" name="email" required>
                    <span class="erro_msg" id="erro-email"></span>
                </div>

                <div class="campo">
                    <label for="login_senha">Senha:</label>
                    <input type="password" id="login_senha" name="senha" required>
                    <span class="erro_msg" id="erro-senha"></span>
                </div>

                <button type="submit">Entrar</button>
            </form>
            <p>Ainda não tem conta? <a href="#" id="linkCadastro">Cadastre-se</a></p>
        </section>

        <!-- FORMULÁRIO DE REGISTRO (Inicialmente escondido ou alternável) -->
        <section id="sec-registro" style="display: none;">
            <h2>Criar nova conta</h2>
            <form id="formRegistro">
                <div class="campo">
                    <label for="reg_nome">Nome:</label>
                    <input type="text" id="reg_nome" name="nome" required>
                    <span class="erro_msg" id="erro-nome"></span>
                </div>

                <div class="campo">
                    <label for="reg_email">E-mail:</label>
                    <input type="email" id="reg_email" name="email" required>
                    <span class="erro_msg" id="erro-email-reg"></span>
                </div>

                <div class="campo">
                    <label for="reg_senha">Senha:</label>
                    <input type="password" id="reg_senha" name="senha" required>
                    <span class="erro_msg" id="erro-senha-reg"></span>
                </div>

                <button type="submit">Cadastrar</button>
            </form>
            <p>Já possui conta? <a href="#" id="linkLogin">Faça Login</a></p>
        </section>
    </main>

    <script src="auth.js"></script>
</body>
</html>