<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Lista de Tarefas</h1>
    <form id="forms" action="/api/criar.php" method="post">
        <input type="hidden" name="id" id="id_Tarefa">
        <label for="titulo">Titulo:</label>
        <input type="text" name="titulo" id="titulo" placeholder="Dê um titulo aqui...">
        <span name="spantitulo" class="erro_msg"></span>
        <br><br>
        <label for="desc">Descrição:</label>
        <br>
        <textarea name="desc" id="desc" cols="30" rows="10" placeholder="Descreva a tarefa aqui..."></textarea><br>
        <span name="spandesc" class="erro_msg"></span>
        <br>
        <input type="radio" name="status" value="nao_feito" checked>Não Feito
        <br>
        <input type="radio" name="status" value="feito">Feito
        <br><br>
        <button type="submit" id="botao">Enviar</button>
    </form>

    <hr>

    <h3>Status Tarefa</h3>
    <ol id="listaDeTarefas">
    </ol>
    <p id="msg"></p>

    <dialog>
        <form id="modalForms">
            <input type="hidden" name="id_Tarefa" id="id_Tarefa">
            <label for="tituloModal">Titulo:</label>
            <input type="text" name="tituloModal" id="tituloModal">
            <br><br>
            <label for="descModal">Descrição:</label>
            <br>
            <textarea name="descModal" id="descModal" cols="30" rows="10" placeholder="Descreva a tarefa aqui..."></textarea>
            <br>
            <input type="radio" name="statusModal" value="nao_feito">Não Feito
            <br>
            <input type="radio" name="statusModal" value="feito">Feito
            <br><br>
            <button type="submit">Atualizar</button>
        </form>
    </dialog>

    <script src="/../controllers/script.js"></script>
</body>
</html>
