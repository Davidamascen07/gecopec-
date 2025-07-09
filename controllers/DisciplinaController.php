<?php
// filepath: c:\xampp\htdocs\GECOPEC\controllers\DisciplinaController.php
require_once 'models/Disciplina.php';
require_once 'models/DisciplinaModel.php';
require_once 'models/Curso.php';

class DisciplinaController {
    private $disciplinaModel;
    private $cursoModel;
    
    public function __construct() {
        $this->disciplinaModel = new Disciplina();
        $this->cursoModel = new Curso();
    }

    // Listagem de disciplinas
    public function index() {
        // Obter filtros da URL
        $filtros = [
            'busca' => isset($_GET['busca']) ? $_GET['busca'] : '',
            'curso_id' => isset($_GET['curso_id']) ? $_GET['curso_id'] : '',
            'status' => isset($_GET['status']) ? $_GET['status'] : ''
        ];
        
        // Obter todas as disciplinas com filtros aplicados
        $disciplinas = $this->disciplinaModel->getAllDisciplinas($filtros);
        
        // Obter cursos para o filtro
        $cursos = $this->cursoModel->getAllCursos();
        
        $data = [
            'title' => 'Gerenciar Disciplinas',
            'disciplinas' => $disciplinas,
            'cursos' => $cursos,
            'filtros' => $filtros,
            'mensagem' => isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : null
        ];
        
        // Limpar mensagem da sessão após exibir
        if(isset($_SESSION['mensagem'])) {
            unset($_SESSION['mensagem']);
        }
        
        $this->renderView('disciplinas/index', $data);
    }
    
    // Exibir detalhes de uma disciplina
    public function view() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=disciplinas');
            exit;
        }
        
        $id = $_GET['id'];
        $disciplina = $this->disciplinaModel->getDisciplinaById($id);
        
        if(!$disciplina) {
            $_SESSION['mensagem'] = 'Disciplina não encontrada';
            header('Location: index.php?page=disciplinas');
            exit;
        }
        
        // Obter turmas da disciplina
        $turmas = $this->disciplinaModel->getTurmasByDisciplina($id);
        
        $data = [
            'title' => $disciplina->nome,
            'disciplina' => $disciplina,
            'turmas' => $turmas
        ];
        
        $this->renderView('disciplinas/view', $data);
    }
    
    // Exibir formulário de criação
    public function create() {
        // Obter cursos para o select
        $cursos = $this->cursoModel->getAllCursos(['status' => 'ativo']);
        
        $data = [
            'title' => 'Nova Disciplina',
            'cursos' => $cursos
        ];
        
        $this->renderView('disciplinas/form', $data);
    }
    
    // Processar formulário de criação
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obter dados do formulário
            $dados = [
                'nome' => trim($_POST['nome']),
                'codigo' => trim($_POST['codigo']),
                'carga_horaria' => (int)$_POST['carga_horaria'],
                'ementa' => isset($_POST['ementa']) ? trim($_POST['ementa']) : '',
                'prerequisitos' => isset($_POST['prerequisitos']) ? trim($_POST['prerequisitos']) : '',
                'curso_id' => (int)$_POST['curso_id'],
                'status' => isset($_POST['status']) ? $_POST['status'] : 'ativo'
            ];
            
            // Criar disciplina
            $resultado = $this->disciplinaModel->create($dados);
            
            if(is_array($resultado) && !$resultado['success']) {
                // Erros de validação
                $cursos = $this->cursoModel->getAllCursos(['status' => 'ativo']);
                $data = [
                    'title' => 'Nova Disciplina',
                    'cursos' => $cursos,
                    'erros' => $resultado['errors'],
                    'dados' => $dados
                ];
                $this->renderView('disciplinas/form', $data);
                return;
            } elseif($resultado) {
                $_SESSION['mensagem'] = 'Disciplina criada com sucesso';
                header('Location: index.php?page=disciplinas');
                exit;
            } else {
                $cursos = $this->cursoModel->getAllCursos(['status' => 'ativo']);
                $data = [
                    'title' => 'Nova Disciplina',
                    'cursos' => $cursos,
                    'erros' => ['Erro ao criar disciplina'],
                    'dados' => $dados
                ];
                $this->renderView('disciplinas/form', $data);
            }
        } else {
            // Redirecionar se não for POST
            header('Location: index.php?page=disciplina-create');
            exit;
        }
    }
    
    // Exibir formulário de edição
    public function edit() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=disciplinas');
            exit;
        }
        
        $id = $_GET['id'];
        $disciplina = $this->disciplinaModel->getDisciplinaById($id);
        
        if(!$disciplina) {
            $_SESSION['mensagem'] = 'Disciplina não encontrada';
            header('Location: index.php?page=disciplinas');
            exit;
        }
        
        // Obter cursos para o select
        $cursos = $this->cursoModel->getAllCursos(['status' => 'ativo']);
        
        $data = [
            'title' => 'Editar Disciplina',
            'disciplina' => $disciplina,
            'cursos' => $cursos
        ];
        
        $this->renderView('disciplinas/form', $data);
    }
    
    // Processar formulário de edição
    public function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verificar se o ID foi fornecido
            if(!isset($_POST['id'])) {
                header('Location: index.php?page=disciplinas');
                exit;
            }
            
            // Obter dados do formulário
            $dados = [
                'id' => (int)$_POST['id'],
                'nome' => trim($_POST['nome']),
                'codigo' => trim($_POST['codigo']),
                'carga_horaria' => (int)$_POST['carga_horaria'],
                'ementa' => isset($_POST['ementa']) ? trim($_POST['ementa']) : '',
                'prerequisitos' => isset($_POST['prerequisitos']) ? trim($_POST['prerequisitos']) : '',
                'curso_id' => (int)$_POST['curso_id'],
                'status' => isset($_POST['status']) ? $_POST['status'] : 'ativo'
            ];
            
            // Verificar se disciplina existe
            if(!$this->disciplinaModel->disciplinaExists($dados['id'])) {
                $_SESSION['mensagem'] = 'Disciplina não encontrada';
                header('Location: index.php?page=disciplinas');
                exit;
            }
            
            // Atualizar disciplina
            $resultado = $this->disciplinaModel->update($dados);
            
            if(is_array($resultado) && !$resultado['success']) {
                // Erros de validação
                $cursos = $this->cursoModel->getAllCursos(['status' => 'ativo']);
                $data = [
                    'title' => 'Editar Disciplina',
                    'cursos' => $cursos,
                    'erros' => $resultado['errors'],
                    'disciplina' => (object)$dados
                ];
                $this->renderView('disciplinas/form', $data);
                return;
            } elseif($resultado) {
                $_SESSION['mensagem'] = 'Disciplina atualizada com sucesso';
                header('Location: index.php?page=disciplinas');
                exit;
            } else {
                $_SESSION['mensagem'] = 'Erro ao atualizar disciplina';
                header('Location: index.php?page=disciplina-edit&id=' . $dados['id']);
                exit;
            }
        } else {
            // Redirecionar se não for POST
            header('Location: index.php?page=disciplinas');
            exit;
        }
    }
    
    // Excluir disciplina
    public function delete() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=disciplinas');
            exit;
        }
        
        $id = $_GET['id'];
        
        // Verificar se disciplina existe
        if(!$this->disciplinaModel->disciplinaExists($id)) {
            $_SESSION['mensagem'] = 'Disciplina não encontrada';
            header('Location: index.php?page=disciplinas');
            exit;
        }
        
        // Excluir disciplina
        if($this->disciplinaModel->delete($id)) {
            $_SESSION['mensagem'] = 'Disciplina excluída com sucesso';
        } else {
            $_SESSION['mensagem'] = 'Erro ao excluir disciplina. Verifique se não há turmas ou alunos vinculados.';
        }
        
        header('Location: index.php?page=disciplinas');
        exit;
    }
    
    // Buscar disciplinas via AJAX
    public function search() {
        if(isset($_GET['term'])) {
            $termo = $_GET['term'];
            $disciplinas = $this->disciplinaModel->getAllDisciplinas(['busca' => $termo]);
            
            // Renderizar apenas a tabela
            $this->renderPartial('disciplinas/table_body', ['disciplinas' => $disciplinas]);
        }
    }
    
    // Método auxiliar para carregar views
    private function renderView($view, $data = []) {
        extract($data);
        
        require_once 'views/templates/header.php';
        require_once 'views/' . $view . '.php';
        require_once 'views/templates/footer.php';
    }
    
    // Método auxiliar para carregar views parciais (AJAX)
    private function renderPartial($view, $data = []) {
        extract($data);
        require_once 'views/' . $view . '.php';
    }
}
?>