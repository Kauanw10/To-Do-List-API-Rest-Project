const forms = document.getElementById('forms')
const p = document.getElementById('msg')
const listaDeTarefas = document.getElementById('listaDeTarefas')
let msgErro 
let urlAtual 
let metodoAtual 


document.addEventListener('DOMContentLoaded', function() {
    console.log("Script carregado com sucesso.")
    buscarDados()
    limparAlertas()
})

forms.addEventListener('submit', async (e) => {
    e.preventDefault()
    console.log("Botão Clicado, impedindo Recarregamento e processando formulário...")

    const formDados = new FormData(e.target)
    const objetoDados = Object.fromEntries(formDados)

    criarTarefa(objetoDados)
    
})

async function criarTarefa(dados) {
    limparAlertas()

    urlAtual = '/api/criar.php'
    metodoAtual = 'POST'
    msgErro = "Erro ao criar tarefa."

    const dadosCriar = await fetchApi(urlAtual, metodoAtual, dados, msgErro)
    
    p.innerText = `${dadosCriar.status}: ${dadosCriar.titulo}`

        if (dadosCriar.sucesso) {
            buscarDados()
            forms.reset()
        }else{
            showError(dadosCriar)
        }
}

async function buscarDados() {
    urlAtual = '/api/listar.php';
    msgErro = "Falha ao carregar a lista de tarefas.";

    const resposta = await fetchApi(urlAtual, 'GET', null, msgErro);

    console.log("DEBUG resposta completa do fetchApi:", resposta);

    if (resposta.sucesso) {
        // Extrai a lista do local correto onde o PHP devolveu
        const listaExtraida = resposta.dados || resposta.lista || (Array.isArray(resposta) ? resposta : []);
        
        listarTarefas(listaExtraida);
    } else {
        showError(resposta);
    }
}

function listarTarefas(tarefas) {
    try {
        // 🟢 Busca o elemento dinamicamente para garantir que ele não esteja 'null'
        const ul = document.getElementById('listaDeTarefas');

        if (!ul) {
            console.error("ERRO: Elemento HTML 'listaDeTarefas' não foi encontrado na página!");
            return;
        }

        ul.innerHTML = ""; // Limpa a lista antes de reexibir

        // 🟢 Se a lista não for um array válido ou estiver vazia
        if (!Array.isArray(tarefas) || tarefas.length === 0) {
            ul.innerHTML = "<li>Nenhuma tarefa encontrada.</li>";
            return;
        }

        tarefas.forEach(tarefa => {
            const novaTarefa = document.createElement('li');

            // Ajuste os nomes das propriedades se o PHP retornar nomes em inglês (ex: title, description)
            const titulo = tarefa.titulo || tarefa.title || 'Sem título';
            const descricao = tarefa.descricao || tarefa.description || '';
            const situacao = tarefa.situacao || tarefa.status || 'Pendente';

            const spanTexto = document.createElement('span');   
            spanTexto.textContent = `Título: ${titulo} | Descrição: ${descricao} | Situação: ${situacao}`;

            const btnAtualizar = document.createElement('button');
            btnAtualizar.textContent = 'Editar';
            btnAtualizar.style.marginLeft = '10px';

            const btnExcluir = document.createElement('button');
            btnExcluir.textContent = 'Excluir';
            btnExcluir.style.marginLeft = '10px';

            btnAtualizar.onclick = () => {
                sessionStorage.setItem("id", tarefa.id);
                sessionStorage.setItem("titulo", titulo);
                sessionStorage.setItem("desc", descricao);
                sessionStorage.setItem("stts", situacao);
                atualizarTarefa();
            };
            
            btnExcluir.onclick = () => {
                excluirTarefa(tarefa.id);
            };

            novaTarefa.appendChild(spanTexto);
            novaTarefa.appendChild(btnAtualizar);
            novaTarefa.appendChild(btnExcluir);

            ul.appendChild(novaTarefa);
        });

    } catch (error) {
        console.error("Erro no render do listarTarefas:", error);
        showError({status: "Erro", titulo: "Erro ao exibir a lista.", erroTecnico: error.message});
    }
}

function atualizarTarefa() {
    let idSessao = sessionStorage.getItem("id")
    let tituloSessao = sessionStorage.getItem("titulo")
    let descSessao = sessionStorage.getItem("desc")
    let sttsSessao = sessionStorage.getItem("stts")

    const dialog = document.querySelector('dialog')
    const modalForms = dialog.querySelector('form#modalForms')

    modalForms.querySelector('input#id_Tarefa').value = idSessao
    modalForms.querySelector('input#tituloModal').value = tituloSessao
    modalForms.querySelector('textarea#descModal').value = descSessao
    modalForms.querySelector(`input[name="statusModal"][value="${sttsSessao}"]`).checked = true
    
    urlAtual = '/api/atualizar.php'
    metodoAtual = 'PUT'
    msgErro = "Erro ao editar tarefa."
    
    dialog.showModal()
}

document.getElementById('modalForms').addEventListener('submit', async function(event) {
    event.preventDefault();

    const formDadosModal = new FormData(this);
    const rawModal = Object.fromEntries(formDadosModal.entries());

    // 🟢 Mapeia os campos do Modal para os nomes exatos que o PHP aguarda:
    const dadosModal = {
        id: rawModal.id_Tarefa || rawModal.id,
        titulo: rawModal.tituloModal || rawModal.titulo,
        descricao: rawModal.descModal || rawModal.descricao || rawModal.desc,
        situacao: rawModal.statusModal || rawModal.situacao || rawModal.status
    };

    console.log("Enviando para atualização:", dadosModal);

    urlAtual = '/api/atualizar.php'; // 🟢 Garanta a barra inicial '/'
    metodoAtual = 'PUT';
    msgErro = "Erro ao editar tarefa.";

    const dadosEditar = await fetchApi(urlAtual, metodoAtual, dadosModal, msgErro);

    
    // 🟢 Se sucesso for true, assume 'Sucesso', senão 'Erro'
    const statusExibir = dadosEditar.sucesso ? (dadosEditar.status || 'Sucesso') : (dadosEditar.status || 'Erro');
    const tituloExibir = dadosEditar.titulo || dadosEditar.mensagem || dadosEditar.erro || msgErro;

    p.innerText = `${statusExibir}: ${tituloExibir}`;

    if (dadosEditar.sucesso) {
        buscarDados(); // Recarrega a lista
        sessionStorage.clear();
        document.querySelector('dialog').close();
    } else {
        showError(dadosEditar);
    }
})

async function excluirTarefa(id) {
    if (!confirm('Tem certeza que deseja excluir?')) return;

    urlAtual = '/api/excluir.php';
    metodoAtual = 'DELETE';
    msgErro = 'Erro ao excluir tarefa.';

    const dadosExcluir = await fetchApi(urlAtual, metodoAtual, { id: id }, msgErro);

    const statusExibir = dadosExcluir.status || (dadosExcluir.sucesso ? 'Sucesso' : 'Erro');
    const tituloExibir = dadosExcluir.titulo || dadosExcluir.erro || dadosExcluir.mensagem || msgErro;
    p.innerText = `${statusExibir}: ${tituloExibir}`;

    if (dadosExcluir.sucesso) {
        buscarDados();
    } else { 
        showError(dadosExcluir);
    }

    sessionStorage.clear();
}

async function realizarLogin(dadosLogin) {
    limparAlertas();

    urlAtual = '/api/login.php';
    metodoAtual = 'POST';
    msgErro = 'Erro ao realizar login. Verifique suas credenciais.';

    // Faz a requisição enviando email e senha
    const resposta = await fetchApi(urlAtual, metodoAtual, dadosLogin, msgErro);

    if (resposta.sucesso && resposta.token) {
        // 1. Salva o Token JWT no localStorage
        localStorage.setItem('token_jwt', resposta.token);
        
        console.log("Token armazenado com sucesso!");

        // 2. Carrega a lista de tarefas após o login
        buscarDados();
        
        // Se a sua tela de login for em outro arquivo HTML (ex: login.html):
        // window.location.href = 'index.html';
    } else {
        showError(resposta);
    }
}

function realizarLogout() {
    localStorage.removeItem('token_jwt');
    window.location.href = 'index.php';
}

// Simplificando as requisições fetch...
// async function fetchApi(url, metodo = 'GET', corpo = null, msgErro) {
//     const config = {
//         method: metodo,
//         headers: { 'Content-Type' : 'application/json' }
//     }
    
//     if (corpo && metodo !== 'GET') {
//         config.body = JSON.stringify(corpo)
//     }
    
//     try {
//         const response = await fetch(url, config) 
//         const dadosFetchApi = await response.json()

//         if (Array.isArray(dadosFetchApi)) {
//             return {
//                 sucesso: response.ok,
//                 lista:  dadosFetchApi
//             }
//         }
        
//         return { 
//             sucesso: response.ok, 
//             ...dadosFetchApi 
//         }
        
//     } catch (error) {
//         return {
//             sucesso: false, 
//             status: "Erro", 
//             titulo: msgErro,
//             erroTecnico: error.message
//         }
//     }
// }

// Simplificando as requisições fetch com Suporte a JWT
async function fetchApi(url, metodo = 'GET', corpo = null, msgErro) {
    const token = localStorage.getItem('token_jwt');

    const headers = { 
        'Content-Type': 'application/json' 
    };

    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const config = {
        method: metodo,
        headers: headers
    };
    
    if (corpo && metodo !== 'GET') {
        config.body = JSON.stringify(corpo);
    }
    
    try {
        const response = await fetch(url, config);

        // 1. Trata sessão expirada
        if (response.status === 401 || response.status === 403) {
            localStorage.removeItem('token_jwt');
            return {
                sucesso: false,
                status: "Sessão Expirada",
                titulo: "Por favor, faça login novamente."
            };
        }

        // 2. Lê a resposta primeiro como texto
        const textoResposta = await response.text();

        let dadosFetchApi;
        try {
            // Tenta converter o texto para JSON
            dadosFetchApi = JSON.parse(textoResposta);
        } catch (e) {
            // Se falhar (retornou HTML do PHP), exibe o erro bruto no console
            console.error("O servidor retornou HTML/Texto ao invés de JSON:", textoResposta);
            return {
                sucesso: false,
                status: `Erro ${response.status}`,
                titulo: msgErro,
                erroTecnico: "Resposta inválida do servidor (HTML/PHP Error)."
            };
        }

        if (Array.isArray(dadosFetchApi)) {
            return {
                sucesso: response.ok,
                lista: dadosFetchApi
            };
        }
        
        return { 
            sucesso: response.ok, 
            ...dadosFetchApi 
        };
        
    } catch (error) {
        return {
            sucesso: false, 
            status: "Erro", 
            titulo: msgErro,
            erroTecnico: error.message
        };
    }
}

function showError(objResposta) {
    limparAlertas()

    console.error("Erro detectado:", objResposta.erroTecnico || objResposta.status)
   
    p.innerText = `${objResposta.status || 'Erro'}: ${objResposta.titulo || 'Falha na operação'}`

    if (objResposta.erros) {
        let primeiroCampoComErro = null

        Object.keys(objResposta.erros).forEach(campo =>{
            
            const mensagem = objResposta.erros[campo]

            const input = document.querySelector(`#forms [name="${campo}"], #modalForms [name="${campo}"], #modalForms [name="${campo}Modal"]`)

            const span = document.getElementById(`erro-${campo}`)

            console.log(`O campo ${campo} está com o erro: ${mensagem}`)

            if (input) {
                input.style.borderColor = 'red'
                if (!primeiroCampoComErro) primeiroCampoComErro = input
            }

            if (span) {
                span.textContent = mensagem
                span.style.color = 'red'
            }
        })
        
        if (primeiroCampoComErro) primeiroCampoComErro.focus()
    }
}


function renderItem(resultado) {
    buscarDados()
    p.innerText = `${resultado.status}: ${resultado.titulo}`
}

function limparAlertas() {
    p.innerText = ""; 
    
    document.querySelectorAll('input, textarea').forEach(campo => {
        campo.style.borderColor = ''; 
    });

    document.querySelectorAll('.erro_msg').forEach(span => {
        span.textContent = '';
    });
}