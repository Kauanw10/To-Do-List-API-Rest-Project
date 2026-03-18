const forms = document.getElementById('forms')
const p = document.getElementById('msg')
const listaDeTarefas = document.getElementById('listaDeTarefas')
let msgErro 
let urlAtual 
let metodoAtual 

console.log("Script carregado com sucesso.")

document.addEventListener('DOMContentLoaded', function() {
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
    urlAtual = '/api/listar.php'
    msgErro = "Falha ao carregar a lista de tarefas."

    const tarefas = await fetchApi(urlAtual, 'GET', null, msgErro)

    if (tarefas.sucesso) {
        if (tarefas.lista && Array.isArray(tarefas.lista)) {
            listarTarefas(tarefas.lista)
        }
    }else{
        showError(tarefas)
    }
}


function listarTarefas(tarefas) {
    try {
        listaDeTarefas.innerHTML = ""
        msgErro = "Erro ao exibir a lista."

        tarefas.forEach(tarefa => {
            const novaTarefa = document.createElement('li')

            const spanTexto = document.createElement('span')   
            spanTexto.textContent = `Titulo: ${tarefa.titulo}  |  Descrição: ${tarefa.descricao}  |  Situação: ${tarefa.situacao}`

            const btnAtualizar = document.createElement('button')
            btnAtualizar.textContent = 'Editar'
            btnAtualizar.style.marginLeft = '10px'

            const btnExcluir = document.createElement('button')
            btnExcluir.textContent = 'Excluir'
            btnExcluir.style.marginLeft = '10px'


            btnAtualizar.onclick = () => {
                sessionStorage.setItem("id", tarefa.id)
                sessionStorage.setItem("titulo", tarefa.titulo)
                sessionStorage.setItem("desc", tarefa.descricao)
                sessionStorage.setItem("stts", tarefa.situacao)
                
                atualizarTarefa()
            }
            
            btnExcluir.onclick = () => {
                excluirTarefa(tarefa.id)
            }

            novaTarefa.appendChild(spanTexto)
            novaTarefa.appendChild(btnAtualizar)
            novaTarefa.appendChild(btnExcluir)

            listaDeTarefas.appendChild(novaTarefa)
            
        });


    } catch (error) {
        showError({status: "Erro", titulo: msgErro, erroTecnico: error.message})
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
    
    urlAtual = 'api/atualizar.php'
    metodoAtual = 'PUT'
    msgErro = "Erro ao editar tarefa."
    
    dialog.showModal()
}

document.getElementById('modalForms').addEventListener('submit', async function(event) {
    event.preventDefault()
    
    const formDadosModal = new FormData(this)
    const dadosModal = Object.fromEntries(formDadosModal.entries())

    const dadosEditar = await fetchApi(urlAtual, metodoAtual, dadosModal, msgErro)
    
    p.innerText = `${dadosEditar.status}: ${dadosEditar.titulo}`

    if (dadosEditar.sucesso) {
        renderItem(dadosEditar)
        sessionStorage.clear()
        document.querySelector('dialog').close()
    } else{
        console.error("Falha na operação:", dadosEditar.erroTecnico)
        showError(dadosEditar)
    }
    
})  

async function excluirTarefa(id) {
    if (!confirm('Tem certeza que deseja excluir?')) return

    urlAtual = '/api/excluir.php'
    metodoAtual = 'DELETE'
    msgErro = 'Erro ao excluir tarefa.'

    const dadosExcluir = await fetchApi(urlAtual, metodoAtual, {id: id}, msgErro)

    p.innerText = `${dadosExcluir.status}: ${dadosExcluir.titulo}`

    if (dadosExcluir.sucesso) {
        buscarDados()
    }else{
        console.error("Falha na operação:", dadosExcluir.erroTecnico)
        showError(dadosExcluir)

    }
   
    sessionStorage.clear()
}

// Simplificando as requisições fetch...
async function fetchApi(url, metodo = 'GET', corpo = null, msgErro) {
    const config = {
        method: metodo,
        headers: { 'Content-Type' : 'application/json' }
    }
    
    if (corpo && metodo !== 'GET') {
        config.body = JSON.stringify(corpo)
    }
    
    try {
        const response = await fetch(url, config) 
        const dadosFetchApi = await response.json()

        if (Array.isArray(dadosFetchApi)) {
            return {
                sucesso: response.ok,
                lista:  dadosFetchApi
            }
        }
        
        return { 
            sucesso: response.ok, 
            ...dadosFetchApi 
        }
        
    } catch (error) {
        return {
            sucesso: false, 
            status: "Erro", 
            titulo: msgErro,
            erroTecnico: error.message
        }
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