# 📋 Sistema de Gerenciamento de Tarefas (CRUD API)

Este projeto consiste em uma aplicação web para gerenciamento de tarefas, utilizando uma API RESTful desenvolvida em PHP no back-end e JavaScript (Fetch API) no front-end. O foco principal foi a implementação de uma arquitetura organizada e validações centralizadas.

## 🚀 Funcionalidades

* **Criar tarefa:** Registro de novas atividades no banco de dados.
* **Listar tarefas:** Visualização dinâmica dos registros existentes.
* **Atualizar tarefa:** Edição de informações de tarefas já criadas.
* **Deletar tarefa:** Remoção lógica ou física de registros.
* **API REST:** Comunicação baseada em respostas JSON.
* **Padronização:** Uso de Status HTTP adequados para cada resposta.
* **Arquitetura:** Implementação de **Service Layer** para separar a lógica de negócio e **Validação Centralizada** para garantir a integridade dos dados.

## 🛠️ Tecnologias Utilizadas

* **Back-end:** PHP
* **Banco de Dados:** MySQL
* **Front-end:** HTML5, CSS3 e JavaScript (ES6+)
* **Comunicação:** Fetch API

## 📂 Estrutura do Projeto

```text
├── /api         # Endpoints e controladores da API
├── /config      # Arquivo inicializador (Env)
├── /controllers # Arquivos de controle (JS e Funções PHP)
├── /core        # Arquivos centrais (Conexão e Validadores)
├── /database    # Script de criação do banco de dados
├── /logs        # Arquivo de logs de erros (Registros de erro)
├── /public      # Interface do usuário (HTML e CSS)
└── /services    # Camada de serviço (Lógica de negócio)
```

## ⚙️ Como Rodar o Projeto

Siga os passos abaixo para executar o projeto em sua máquina local:

### 1. Preparar o Ambiente
Certifique-se de ter um ambiente de servidor local instalado, como o **XAMPP**, **WAMP** ou **Laragon**, para processar o PHP e gerenciar o banco de dados MySQL.

### 2. Configurar o Banco de Dados
1. Abra o **phpMyAdmin** (geralmente em `http://localhost/phpmyadmin`).
2. Crie um novo banco de dados (ex: `db_tarefas`).
3. Clique na aba **Importar**, selecione o arquivo `schema.sql` localizado na raiz deste projeto e clique em **Executar**.

### 3. Configurar os Arquivos
1. Mova a pasta completa do projeto para o diretório de arquivos públicos do seu servidor (ex: `C:/xampp/htdocs/`).
2. No painel de controle do seu servidor (XAMPP Control Panel), inicie os módulos **Apache** e **MySQL**.

### 4. Acessar a Aplicação
Abra o seu navegador de preferência e acesse o endereço abaixo:

```bash
http://localhost/NOME_DA_SUA_PASTA/public
