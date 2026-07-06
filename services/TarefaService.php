<?php 
    require_once __DIR__ .  "/../core/validador.php";
    require_once __DIR__ .  "/../controllers/buildResponse.php";

class TarefaService {
    private $tarefaRepo;

    public function __construct($tarefaRepo) {
        $this->tarefaRepo = $tarefaRepo;
    }

    public function criar($dados) {
        $verif = new Validador();
        $tipoFuncao = "Criar";

        if (empty($dados['titulo'])) {
            $verif->adicionarErro('titulo', 'O título não pode estar vazio!');
        }

        if (strlen($dados['desc']) < 5) {
            $verif->adicionarErro('desc', 'A descrição deve ter pelo menos 5 caracteres!');
        }

        if ($verif->temErros()) {
            return $verif->retornarErros(); 
        }

        // Se passar na validação, manda o Repositório salvar e retorna true/false
        $tarefaCriada = $this->tarefaRepo->criar($dados);

        $resultado = formatResponse($tipoFuncao, $tarefaCriada);

        return $resultado;
    }

    public function listar($id_Usuario) {
        $tipoFuncao = "Listar";
        $tarefaLista = $this->tarefaRepo->listar($id_Usuario);

        $resultado = formatResponse($tipoFuncao, $tarefaLista);
        return $resultado;
    }

    public function atualizar($id_Usuario, $id_Tarefa, $dados) {
        $verif = new Validador();
        $tipoFuncao = "Atualizar";

        if (empty($dados['tituloModal'])) {
            $verif->adicionarErro('tituloModal', 'O título não pode estar vazio!');
        }

        if (strlen($dados['descModal']) < 5) {
            $verif->adicionarErro('descModal', 'A descrição deve ter pelo menos 5 caracteres!');
        }

        if ($verif->temErros()) {
            return $verif->retornarErros();
        }

       
        $tarefaAtualizada = $this->tarefaRepo->atualizar($id_Usuario, $id_Tarefa, $dados);

        $resultado = formatResponse($tipoFuncao, $tarefaAtualizada);

        return $resultado;
    }

    public function excluir($id_Usuario, $id_Tarefa) {
        $tipoFuncao = "Excluir";
        
        $tarefaExcluida = $this->tarefaRepo->excluir($id_Usuario, $id_Tarefa);
        $resultado = formatResponse($tipoFuncao, $tarefaExcluida);
        return $resultado;
    }
}
?>