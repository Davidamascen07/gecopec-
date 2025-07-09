<?php
// filepath: c:\xampp\htdocs\GECOPEC\controllers\cursoController.php
require_once 'models/Curso.php';
require_once 'models/User.php';

class CursoController {
    private $cursoModel;
    private $userModel;
    
    public function __construct() {
        $this->cursoModel = new Curso();
        $this->userModel = new User();
    }

    // Listagem de cursos
    public function index() {
        // Obter filtros da URL
        $filtros = [
            'busca' => isset($_GET['busca']) ? $_GET['busca'] : '',
            'status' => isset($_GET['status']) ? $_GET['status'] : ''
        ];
        
        // Obter todos os cursos com filtros aplicados
        $cursos = $this->cursoModel->getAllCursos($filtros);
        
        $data = [
            'title' => 'Gerenciar Cursos',
            'cursos' => $cursos,
            'mensagem' => isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : null
        ];
        
        // Limpar mensagem da sessão após exibir
        if(isset($_SESSION['mensagem'])) {
            unset($_SESSION['mensagem']);
        }
        
        $this->renderView('cursos/index', $data);
    }
    
    // Exibir detalhes de um curso
    public function view() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=cursos');
            exit;
        }
        
        $id = $_GET['id'];
        $curso = $this->cursoModel->getCursoById($id);
        
        if(!$curso) {
            $_SESSION['mensagem'] = 'Curso não encontrado';
            header('Location: index.php?page=cursos');
            exit;
        }
        
        // Obter informações do coordenador se houver
        $coordenador = null;
        if($curso->coordenador_id) {
            $coordenador = $this->userModel->getUserById($curso->coordenador_id);
        }
        
        // Obter disciplinas do curso
        $disciplinas = $this->cursoModel->getDisciplinasByCurso($id);
        
        $data = [
            'title' => $curso->nome,
            'curso' => $curso,
            'coordenador' => $coordenador,
            'disciplinas' => $disciplinas
        ];
        
        $this->renderView('cursos/view', $data);
    }
    
    // Exibir formulário de criação
    public function create() {
        // Obter coordenadores para o select
        $coordenadores = $this->userModel->getAllCoordenadores();
        
        $data = [
            'title' => 'Novo Curso',
            'coordenadores' => $coordenadores
        ];
        
        $this->renderView('cursos/form', $data);
    }
    
    // Processar formulário de criação
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obter dados do formulário
            $dados = [
                'nome' => trim($_POST['nome']),
                'carga_horaria' => (int)$_POST['carga_horaria'],
                'ementa' => isset($_POST['ementa']) ? trim($_POST['ementa']) : '',
                'objetivos' => isset($_POST['objetivos']) ? trim($_POST['objetivos']) : '',
                'status' => isset($_POST['status']) ? $_POST['status'] : 'ativo',
                'coordenador_id' => isset($_POST['coordenador_id']) && !empty($_POST['coordenador_id']) ? (int)$_POST['coordenador_id'] : null
            ];
            
            // Validações
            $erros = [];
            
            if(empty($dados['nome'])) {
                $erros[] = 'O nome do curso é obrigatório';
            }
            
            if($dados['carga_horaria'] <= 0) {
                $erros[] = 'A carga horária deve ser um número positivo';
            }
            
            // Se houver erros, retornar ao formulário
            if(!empty($erros)) {
                $coordenadores = $this->userModel->getAllCoordenadores();
                $data = [
                    'title' => 'Novo Curso',
                    'coordenadores' => $coordenadores,
                    'erros' => $erros,
                    'dados' => $dados
                ];
                $this->renderView('cursos/form', $data);
                return;
            }
            
            // Criar curso
            $cursoId = $this->cursoModel->create($dados);
            
            if($cursoId) {
                $_SESSION['mensagem'] = 'Curso criado com sucesso';
                header('Location: index.php?page=cursos');
                exit;
            } else {
                $coordenadores = $this->userModel->getAllCoordenadores();
                $data = [
                    'title' => 'Novo Curso',
                    'coordenadores' => $coordenadores,
                    'erros' => ['Erro ao criar curso'],
                    'dados' => $dados
                ];
                $this->renderView('cursos/form', $data);
            }
        } else {
            // Redirecionar se não for POST
            header('Location: index.php?page=curso-create');
            exit;
        }
    }
    
    // Exibir formulário de edição
    public function edit() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=cursos');
            exit;
        }
        
        $id = $_GET['id'];
        $curso = $this->cursoModel->getCursoById($id);
        
        if(!$curso) {
            $_SESSION['mensagem'] = 'Curso não encontrado';
            header('Location: index.php?page=cursos');
            exit;
        }
        
        // Obter coordenadores para o select
        $coordenadores = $this->userModel->getAllCoordenadores();
        
        $data = [
            'title' => 'Editar Curso',
            'curso' => $curso,
            'coordenadores' => $coordenadores
        ];
        
        $this->renderView('cursos/form', $data);
    }
    
    // Processar formulário de edição
    public function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verificar se o ID foi fornecido
            if(!isset($_POST['id'])) {
                header('Location: index.php?page=cursos');
                exit;
            }
            
            // Obter dados do formulário
            $dados = [
                'id' => (int)$_POST['id'],
                'nome' => trim($_POST['nome']),
                'carga_horaria' => (int)$_POST['carga_horaria'],
                'ementa' => isset($_POST['ementa']) ? trim($_POST['ementa']) : '',
                'objetivos' => isset($_POST['objetivos']) ? trim($_POST['objetivos']) : '',
                'status' => isset($_POST['status']) ? $_POST['status'] : 'ativo',
                'coordenador_id' => isset($_POST['coordenador_id']) && !empty($_POST['coordenador_id']) ? (int)$_POST['coordenador_id'] : null
            ];
            
            // Verificar se curso existe
            if(!$this->cursoModel->cursoExists($dados['id'])) {
                $_SESSION['mensagem'] = 'Curso não encontrado';
                header('Location: index.php?page=cursos');
                exit;
            }
            
            // Validações
            $erros = [];
            
            if(empty($dados['nome'])) {
                $erros[] = 'O nome do curso é obrigatório';
            }
            
            if($dados['carga_horaria'] <= 0) {
                $erros[] = 'A carga horária deve ser um número positivo';
            }
            
            // Se houver erros, retornar ao formulário
            if(!empty($erros)) {
                $coordenadores = $this->userModel->getAllCoordenadores();
                $data = [
                    'title' => 'Editar Curso',
                    'coordenadores' => $coordenadores,
                    'erros' => $erros,
                    'curso' => (object)$dados
                ];
                $this->renderView('cursos/form', $data);
                return;
            }
            
            // Atualizar curso
            if($this->cursoModel->update($dados)) {
                $_SESSION['mensagem'] = 'Curso atualizado com sucesso';
                header('Location: index.php?page=cursos');
                exit;
            } else {
                $_SESSION['mensagem'] = 'Erro ao atualizar curso';
                header('Location: index.php?page=curso-edit&id=' . $dados['id']);
                exit;
            }
        } else {
            // Redirecionar se não for POST
            header('Location: index.php?page=cursos');
            exit;
        }
    }
    
    // Excluir curso
    public function delete() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=cursos');
            exit;
        }
        
        $id = $_GET['id'];
        
        // Verificar se curso existe
        if(!$this->cursoModel->cursoExists($id)) {
            $_SESSION['mensagem'] = 'Curso não encontrado';
            header('Location: index.php?page=cursos');
            exit;
        }
        
        // Excluir curso
        if($this->cursoModel->delete($id)) {
            $_SESSION['mensagem'] = 'Curso excluído com sucesso';
        } else {
            $_SESSION['mensagem'] = 'Erro ao excluir curso. Verifique se não há disciplinas ou alunos vinculados.';
        }
        
        header('Location: index.php?page=cursos');
        exit;
    }
    
    // Método auxiliar para carregar views
    private function renderView($view, $data = []) {
        extract($data);
        
        require_once 'views/templates/header.php';
        require_once 'views/' . $view . '.php';
        require_once 'views/templates/footer.php';
    }
}
?>