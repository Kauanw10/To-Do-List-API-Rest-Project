const formLogin = document.getElementById('formLogin');
const formRegistro = document.getElementById('formRegistro');
const secLogin = document.getElementById('sec-login');
const secRegistro = document.getElementById('sec-registro');
const pMsg = document.getElementById('msg');

// Alternar entre Login e Cadastro
document.getElementById('linkCadastro').addEventListener('click', (e) => {
    e.preventDefault();
    secLogin.style.display = 'none';
    secRegistro.style.display = 'block';
    pMsg.innerText = '';
});

document.getElementById('linkLogin').addEventListener('click', (e) => {
    e.preventDefault();
    secRegistro.style.display = 'none';
    secLogin.style.display = 'block';
    pMsg.innerText = '';
});

// SUBMIT DO LOGIN
formLogin.addEventListener('submit', async (e) => {
    e.preventDefault();
    pMsg.innerText = '';

    const dados = Object.fromEntries(new FormData(formLogin));

    try {
        const resposta = await fetch('/api/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dados)
        });

        const resultado = await resposta.json();

       // 🟢 Acesso seguro ao token:
        const token = resultado.dados?.token;

        // Só entra aqui se a resposta for 200 OK E o token existir de verdade
        if (resposta.ok && token) {
            localStorage.setItem('token_jwt', token);
            window.location.href = 'dashboard.php';
        } else {
            pMsg.style.color = 'red';
            pMsg.innerText = resultado.titulo || resultado.erro || 'Falha ao realizar login.';
        }
        
    } catch (erro) {
        console.error("Erro no catch:", erro);
        pMsg.style.color = 'red';
        pMsg.innerText = 'Erro ao conectar com o servidor.';
    }
});

// SUBMIT DO REGISTRO
formRegistro.addEventListener('submit', async (e) => {
    e.preventDefault();
    pMsg.innerText = '';

    const dados = Object.fromEntries(new FormData(formRegistro));

    try {
        const resposta = await fetch('/api/register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dados)
        });

        const resultado = await resposta.json();

        if (resposta.ok) {
            pMsg.style.color = 'green';
            pMsg.innerText = 'Conta criada com sucesso! Faça login.';
            
            // Volta para a tela de login
            secRegistro.style.display = 'none';
            secLogin.style.display = 'block';
            formRegistro.reset();
        } else {
            pMsg.style.color = 'red';
            pMsg.innerText = resultado.erro || 'Erro ao registrar usuário.';
        }
    // Exemplo no catch do Login e do Registro:
    } catch (erro) {
        console.error("Erro detalhado do Fetch/JSON:", erro); // <--- Adicione esta linha
        pMsg.style.color = 'red';
        pMsg.innerText = 'Erro ao conectar com o servidor.';
    }
});