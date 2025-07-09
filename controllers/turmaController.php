<?php
// filepath: c:\xampp\htdocs\GECOPEC\controllers\turmaController.php
require_once 'models/TurmaModel.php';
require_once 'models/DisciplinaModel.php';
require_once 'models/ProfessorModel.php';
require_once 'models/AlunoModel.php';

class TurmaController {
    private $turmaModel;
    private $disciplinaModel;
    private $professorModel;
    private $alunoModel;
    
    public function __construct() {
        $this->turmaModel = new TurmaModel();
        $this->disciplinaModel = new DisciplinaModel();
        $this->professorModel = new ProfessorModel();
        $this->alunoModel = new AlunoModel();
    }

    // Listagem de turmas
    public function index() {
        // Obter filtros da URL
        $filtros = [
            'busca' => isset($_GET['busca']) ? $_GET['busca'] : '',
            'disciplina_id' => isset($_GET['disciplina_id']) ? $_GET['disciplina_id'] : '',
            'professor_id' => isset($_GET['professor_id']) ? $_GET['professor_id'] : '',
            'status' => isset($_GET['status']) ? $_GET['status'] : '',
            'semestre' => isset($_GET['semestre']) ? $_GET['semestre'] : '',
            'ano' => isset($_GET['ano']) ? $_GET['ano'] : ''
        ];
        
        // Obter todas as turmas com dados relacionados
        $turmas = $this->turmaModel->getTurmasCompletas();
        
        // Aplicar filtros
        if (!empty($filtros['busca'])) {
            $turmas = array_filter($turmas, function($turma) use ($filtros) {
                return stripos($turma['nome'], $filtros['busca']) !== false ||
                       stripos($turma['disciplina_nome'], $filtros['busca']) !== false ||
                       stripos($turma['professor_nome'], $filtros['busca']) !== false ||
                       stripos($turma['sala'], $filtros['busca']) !== false;
            });
        }
        
        if (!empty($filtros['disciplina_id'])) {
            $turmas = array_filter($turmas, function($turma) use ($filtros) {
                return $turma['disciplina_id'] == $filtros['disciplina_id'];
            });
        }
        
        if (!empty($filtros['professor_id'])) {
            $turmas = array_filter($turmas, function($turma) use ($filtros) {
                return $turma['professor_id'] == $filtros['professor_id'];
            });
        }
        
        if (!empty($filtros['status'])) {
            $turmas = array_filter($turmas, function($turma) use ($filtros) {
                return $turma['status'] === $filtros['status'];
            });
        }
        
        if (!empty($filtros['semestre'])) {
            $turmas = array_filter($turmas, function($turma) use ($filtros) {
                return $turma['semestre'] === $filtros['semestre'];
            });
        }
        
        if (!empty($filtros['ano'])) {
            $turmas = array_filter($turmas, function($turma) use ($filtros) {
                return $turma['ano'] == $filtros['ano'];
            });
        }
        
        // Obter dados para os filtros
        $disciplinas = $this->disciplinaModel->getAll();
        $professores = $this->professorModel->getAllProfessores();
        
        $data = [
            'title' => 'Gerenciar Turmas',
            'turmas' => $turmas,
            'disciplinas' => $disciplinas,
            'professores' => $professores,
            'filtros' => $filtros,
            'mensagem' => isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : null
        ];
        
        // Limpar mensagem da sessão após exibir
        if(isset($_SESSION['mensagem'])) {
            unset($_SESSION['mensagem']);
        }
        
        $this->renderView('turmas/index', $data);
    }
    
    // Exibir detalhes de uma turma
    public function view() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=turmas');
            exit;
        }
        
        $id = $_GET['id'];
        $turma = $this->turmaModel->getTurmaCompleta($id);
        
        if(!$turma) {
            $_SESSION['mensagem'] = 'Turma não encontrada';
            header('Location: index.php?page=turmas');
            exit;
        }
        
        // Obter alunos matriculados
        $alunosMatriculados = $this->turmaModel->getAlunosMatriculados($id);
        
        $data = [
            'title' => $turma['nome'],
            'turma' => (object)$turma,
            'alunos' => $alunosMatriculados
        ];
        
        $this->renderView('turmas/view', $data);
    }
    
    // Exibir formulário de criação
    public function create() {
        // Obter disciplinas e professores para os selects
        try {
            $disciplinas = $this->disciplinaModel->getAll();
            $professores = $this->professorModel->getAllProfessores();
        } catch (Exception $e) {
            error_log("Erro ao buscar dados para criação de turma: " . $e->getMessage());
            $disciplinas = [];
            $professores = [];
        }
        
        $data = [
            'title' => 'Nova Turma',
            'disciplinas' => $disciplinas,
            'professores' => $professores
        ];
        
        $this->renderView('turmas/form', $data);
    }
    
    // Processar formulário de criação
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obter dados do formulário
            $dados = [
                'nome' => trim($_POST['nome']),
                'disciplina_id' => (int)$_POST['disciplina_id'],
                'professor_id' => (int)$_POST['professor_id'],
                'semestre' => (int)$_POST['semestre'],
                'ano' => (int)$_POST['ano'],
                'vagas' => isset($_POST['vagas']) ? (int)$_POST['vagas'] : 30,
                'horario' => isset($_POST['horario']) ? trim($_POST['horario']) : '',
                'sala' => isset($_POST['sala']) ? trim($_POST['sala']) : '',
                'status' => isset($_POST['status']) ? $_POST['status'] : 'ativa'
            ];
            
            // Validações básicas
            $erros = [];
            
            if (empty($dados['nome'])) {
                $erros[] = 'Nome da turma é obrigatório';
            }
            
            if (empty($dados['disciplina_id']) || $dados['disciplina_id'] <= 0) {
                $erros[] = 'Disciplina é obrigatória';
            }
            
            if (empty($dados['professor_id']) || $dados['professor_id'] <= 0) {
                $erros[] = 'Professor é obrigatório';
            }
            
            if (empty($dados['semestre']) || $dados['semestre'] < 1 || $dados['semestre'] > 12) {
                $erros[] = 'Semestre deve estar entre 1º e 12º semestre';
            }
            
            if (empty($dados['ano']) || $dados['ano'] < 2020 || $dados['ano'] > 2030) {
                $erros[] = 'Ano inválido';
            }
            
            if ($dados['vagas'] <= 0) {
                $erros[] = 'Número de vagas deve ser positivo';
            }
            
            if (!empty($erros)) {
                // Recarregar dados em caso de erro
                try {
                    $disciplinas = $this->disciplinaModel->getAll();
                    $professores = $this->professorModel->getAllProfessores();
                } catch (Exception $e) {
                    $disciplinas = [];
                    $professores = [];
                }
                
                $data = [
                    'title' => 'Nova Turma',
                    'disciplinas' => $disciplinas,
                    'professores' => $professores,
                    'erros' => $erros,
                    'dados' => $dados
                ];
                $this->renderView('turmas/form', $data);
                return;
            }
            
            // Criar turma
            $resultado = $this->turmaModel->create($dados);
            
            if($resultado) {
                $_SESSION['mensagem'] = 'Turma criada com sucesso';
                header('Location: index.php?page=turmas');
                exit;
            } else {
                $disciplinas = $this->disciplinaModel->getAll();
                $professores = $this->professorModel->getAllProfessores();
                $data = [
                    'title' => 'Nova Turma',
                    'disciplinas' => $disciplinas,
                    'professores' => $professores,
                    'erros' => ['Erro ao criar turma'],
                    'dados' => $dados
                ];
                $this->renderView('turmas/form', $data);
            }
        } else {
            // Redirecionar se não for POST
            header('Location: index.php?page=turma-create');
            exit;
        }
    }
    
    // Exibir formulário de edição
    public function edit() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=turmas');
            exit;
        }
        
        $id = $_GET['id'];
        $turma = $this->turmaModel->getById($id);
        
        if(!$turma) {
            $_SESSION['mensagem'] = 'Turma não encontrada';
            header('Location: index.php?page=turmas');
            exit;
        }
        
        // Obter disciplinas e professores para os selects
        try {
            $disciplinas = $this->disciplinaModel->getAll();
            $professores = $this->professorModel->getAllProfessores();
        } catch (Exception $e) {
            error_log("Erro ao buscar dados na edição de turma: " . $e->getMessage());
            $disciplinas = [];
            $professores = [];
        }
        
        $data = [
            'title' => 'Editar Turma',
            'turma' => (object)$turma,
            'disciplinas' => $disciplinas,
            'professores' => $professores
        ];
        
        $this->renderView('turmas/form', $data);
    }
    
    // Processar formulário de edição
    public function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verificar se o ID foi fornecido
            if(!isset($_POST['id'])) {
                header('Location: index.php?page=turmas');
                exit;
            }
            
            // Obter dados do formulário
            $dados = [
                'id' => (int)$_POST['id'],
                'nome' => trim($_POST['nome']),
                'disciplina_id' => (int)$_POST['disciplina_id'],
                'professor_id' => (int)$_POST['professor_id'],
                'semestre' => trim($_POST['semestre']),
                'ano' => (int)$_POST['ano'],
                'vagas' => isset($_POST['vagas']) ? (int)$_POST['vagas'] : 30,
                'horario' => isset($_POST['horario']) ? trim($_POST['horario']) : '',
                'sala' => isset($_POST['sala']) ? trim($_POST['sala']) : '',
                'status' => isset($_POST['status']) ? $_POST['status'] : 'ativa'
            ];
            
            // Validações básicas
            $erros = [];
            
            if (empty($dados['nome'])) {
                $erros[] = 'Nome da turma é obrigatório';
            }
            
            if (empty($dados['disciplina_id']) || $dados['disciplina_id'] <= 0) {
                $erros[] = 'Disciplina é obrigatória';
            }
            
            if (empty($dados['professor_id']) || $dados['professor_id'] <= 0) {
                $erros[] = 'Professor é obrigatório';
            }
            
            if (empty($dados['semestre'])) {
                $erros[] = 'Semestre é obrigatório';
            }
            
            if (empty($dados['ano']) || $dados['ano'] < 2020 || $dados['ano'] > 2030) {
                $erros[] = 'Ano inválido';
            }
            
            if ($dados['vagas'] <= 0) {
                $erros[] = 'Número de vagas deve ser positivo';
            }
            
            if (!empty($erros)) {
                $disciplinas = $this->disciplinaModel->getAll();
                $professores = $this->professorModel->getAllProfessores();
                $data = [
                    'title' => 'Editar Turma',
                    'disciplinas' => $disciplinas,
                    'professores' => $professores,
                    'erros' => $erros,
                    'turma' => (object)$dados
                ];
                $this->renderView('turmas/form', $data);
                return;
            }
            
            // Atualizar turma
            $resultado = $this->turmaModel->update($dados['id'], $dados);
            
            if($resultado) {
                $_SESSION['mensagem'] = 'Turma atualizada com sucesso';
                header('Location: index.php?page=turmas');
                exit;
            } else {
                $_SESSION['mensagem'] = 'Erro ao atualizar turma';
                header('Location: index.php?page=turma-edit&id=' . $dados['id']);
                exit;
            }
        } else {
            // Redirecionar se não for POST
            header('Location: index.php?page=turmas');
            exit;
        }
    }
    
    // Excluir turma
    public function delete() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=turmas');
            exit;
        }
        
        $id = $_GET['id'];
        
        // Verificar se turma existe
        $turma = $this->turmaModel->getById($id);
        if(!$turma) {
            $_SESSION['mensagem'] = 'Turma não encontrada';
            header('Location: index.php?page=turmas');
            exit;
        }
        
        // Excluir turma
        if($this->turmaModel->delete($id)) {
            $_SESSION['mensagem'] = 'Turma excluída com sucesso';
        } else {
            $_SESSION['mensagem'] = 'Erro ao excluir turma. Verifique se não há matrículas vinculadas.';
        }
        
        header('Location: index.php?page=turmas');
        exit;
    }
    
    // Buscar turmas via AJAX
    public function search() {
        if(isset($_GET['term'])) {
            $termo = $_GET['term'];
            $turmas = $this->turmaModel->getTurmasCompletas();
            
            // Filtrar por termo
            $turmas = array_filter($turmas, function($turma) use ($termo) {
                return stripos($turma['nome'], $termo) !== false ||
                       stripos($turma['disciplina_nome'], $termo) !== false ||
                       stripos($turma['professor_nome'], $termo) !== false;
            });
            
            // Renderizar apenas a tabela
            $this->renderPartial('turmas/table_body', ['turmas' => $turmas]);
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