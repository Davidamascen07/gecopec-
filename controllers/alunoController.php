<?php
// filepath: c:\xampp\htdocs\GECOPEC\controllers\alunoController.php
require_once 'models/AlunoModel.php';
require_once 'models/Curso.php';

class AlunoController {
    private $alunoModel;
    private $cursoModel;
    
    public function __construct() {
        $this->alunoModel = new AlunoModel();
        $this->cursoModel = new Curso();
    }

    // Listagem de alunos
    public function index() {
        // Obter filtros da URL
        $filtros = [
            'busca' => isset($_GET['busca']) ? $_GET['busca'] : '',
            'curso_id' => isset($_GET['curso_id']) ? $_GET['curso_id'] : '',
            'status' => isset($_GET['status']) ? $_GET['status'] : ''
        ];
        
        // Obter todos os alunos com filtros aplicados
        $alunos = $this->alunoModel->getAlunosWithCurso();
        
        // Aplicar filtros
        if (!empty($filtros['busca'])) {
            $alunos = array_filter($alunos, function($aluno) use ($filtros) {
                return stripos($aluno['nome'], $filtros['busca']) !== false ||
                       stripos($aluno['matricula'], $filtros['busca']) !== false ||
                       stripos($aluno['email'], $filtros['busca']) !== false ||
                       stripos($aluno['cpf'], $filtros['busca']) !== false;
            });
        }
        
        if (!empty($filtros['curso_id'])) {
            $alunos = array_filter($alunos, function($aluno) use ($filtros) {
                return $aluno['curso_id'] == $filtros['curso_id'];
            });
        }
        
        if (!empty($filtros['status'])) {
            $alunos = array_filter($alunos, function($aluno) use ($filtros) {
                return $aluno['status'] === $filtros['status'];
            });
        }
        
        // Obter cursos para o filtro
        $cursos = $this->cursoModel->getAllCursos(['status' => 'ativo']);
        
        $data = [
            'title' => 'Gerenciar Alunos',
            'alunos' => $alunos,
            'cursos' => $cursos,
            'filtros' => $filtros,
            'mensagem' => isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : null
        ];
        
        // Limpar mensagem da sessão após exibir
        if(isset($_SESSION['mensagem'])) {
            unset($_SESSION['mensagem']);
        }
        
        $this->renderView('alunos/index', $data);
    }
    
    // Exibir detalhes de um aluno
    public function view() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=alunos');
            exit;
        }
        
        $id = $_GET['id'];
        $aluno = $this->alunoModel->getById($id);
        
        if(!$aluno) {
            $_SESSION['mensagem'] = 'Aluno não encontrado';
            header('Location: index.php?page=alunos');
            exit;
        }
        
        // Obter dados do curso diretamente pela relação
        $curso = null;
        if($aluno['curso_id']) {
            $curso = $this->cursoModel->getCursoById($aluno['curso_id']);
        }
        
        // Adicionar nome do curso ao array do aluno
        $aluno['curso_nome'] = $curso ? $curso->nome : 'Sem curso';
        
        // Obter matrículas do aluno
        $matriculas = $this->alunoModel->getMatriculasAluno($id);
        
        // Obter histórico escolar
        $historico = $this->alunoModel->getHistoricoEscolar($id);
        
        $data = [
            'title' => $aluno['nome'],
            'aluno' => (object)$aluno,
            'curso' => $curso,
            'matriculas' => $matriculas,
            'historico' => $historico
        ];
        
        $this->renderView('alunos/view', $data);
    }
    
    // Exibir formulário de criação
    public function create() {
        // Debug: Testar diferentes métodos para obter cursos
        try {
            // Método 1: Usar getAllCursos
            $cursos = $this->cursoModel->getAllCursos(['status' => 'ativo']);
            
            // Debug: Verificar se retornou dados
            if (empty($cursos)) {
                // Método 2: Tentar sem filtros
                $cursos = $this->cursoModel->getAllCursos();
                
                if (empty($cursos)) {
                    // Método 3: Query direta
                    $cursos = $this->cursoModel->getAll();
                }
            }
            
            // Log para debug
            error_log("Cursos encontrados no create: " . count($cursos));
            error_log("Dados dos cursos: " . print_r($cursos, true));
            
        } catch (Exception $e) {
            error_log("Erro ao buscar cursos: " . $e->getMessage());
            $cursos = [];
        }
        
        $data = [
            'title' => 'Novo Aluno',
            'cursos' => $cursos
        ];
        
        $this->renderView('alunos/form', $data);
    }
    
    // Processar formulário de criação
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obter dados do formulário
            $dados = [
                'nome' => trim($_POST['nome']),
                'email' => trim($_POST['email']),
                'cpf' => trim($_POST['cpf']),
                'matricula' => trim($_POST['matricula']),
                'data_nascimento' => !empty($_POST['data_nascimento']) ? $_POST['data_nascimento'] : null,
                'telefone' => isset($_POST['telefone']) ? trim($_POST['telefone']) : '',
                'endereco' => isset($_POST['endereco']) ? trim($_POST['endereco']) : '',
                'curso_id' => (int)$_POST['curso_id'],
                'semestre_atual' => isset($_POST['semestre_atual']) ? (int)$_POST['semestre_atual'] : 1,
                'status' => isset($_POST['status']) ? $_POST['status'] : 'ativo'
            ];
            
            // Validações básicas
            $erros = [];
            
            if (empty($dados['nome'])) {
                $erros[] = 'Nome é obrigatório';
            }
            
            if (empty($dados['email'])) {
                $erros[] = 'Email é obrigatório';
            } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                $erros[] = 'Email inválido';
            } elseif ($this->alunoModel->checkEmailExists($dados['email'])) {
                $erros[] = 'Email já está em uso';
            }
            
            if (empty($dados['cpf'])) {
                $erros[] = 'CPF é obrigatório';
            } elseif ($this->alunoModel->checkCpfExists($dados['cpf'])) {
                $erros[] = 'CPF já está cadastrado';
            }
            
            if (empty($dados['matricula'])) {
                $erros[] = 'Matrícula é obrigatória';
            } elseif ($this->alunoModel->checkMatriculaExists($dados['matricula'])) {
                $erros[] = 'Matrícula já existe';
            }
            
            if (empty($dados['curso_id']) || $dados['curso_id'] <= 0) {
                $erros[] = 'Curso é obrigatório';
            }
            
            // Validar data de nascimento se fornecida
            if (!empty($dados['data_nascimento'])) {
                $data = DateTime::createFromFormat('Y-m-d', $dados['data_nascimento']);
                if (!$data || $data->format('Y-m-d') !== $dados['data_nascimento']) {
                    $erros[] = 'Data de nascimento inválida';
                }
            }
            
            if (!empty($erros)) {
                // Recarregar cursos em caso de erro
                try {
                    $cursos = $this->cursoModel->getAllCursos(['status' => 'ativo']);
                    if (empty($cursos)) {
                        $cursos = $this->cursoModel->getAll();
                    }
                } catch (Exception $e) {
                    $cursos = [];
                }
                
                $data = [
                    'title' => 'Novo Aluno',
                    'cursos' => $cursos,
                    'erros' => $erros,
                    'dados' => $dados
                ];
                $this->renderView('alunos/form', $data);
                return;
            }
            
            // Criar aluno
            $resultado = $this->alunoModel->create($dados);
            
            if($resultado) {
                $_SESSION['mensagem'] = 'Aluno cadastrado com sucesso';
                header('Location: index.php?page=alunos');
                exit;
            } else {
                $cursos = $this->cursoModel->getAllCursos(['status' => 'ativo']);
                $data = [
                    'title' => 'Novo Aluno',
                    'cursos' => $cursos,
                    'erros' => ['Erro ao cadastrar aluno'],
                    'dados' => $dados
                ];
                $this->renderView('alunos/form', $data);
            }
        } else {
            // Redirecionar se não for POST
            header('Location: index.php?page=aluno-create');
            exit;
        }
    }
    
    // Exibir formulário de edição
    public function edit() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=alunos');
            exit;
        }
        
        $id = $_GET['id'];
        $aluno = $this->alunoModel->getById($id);
        
        if(!$aluno) {
            $_SESSION['mensagem'] = 'Aluno não encontrado';
            header('Location: index.php?page=alunos');
            exit;
        }
        
        // Obter cursos para o select
        try {
            $cursos = $this->cursoModel->getAllCursos(['status' => 'ativo']);
            if (empty($cursos)) {
                $cursos = $this->cursoModel->getAll();
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar cursos na edição: " . $e->getMessage());
            $cursos = [];
        }
        
        $data = [
            'title' => 'Editar Aluno',
            'aluno' => (object)$aluno,
            'cursos' => $cursos
        ];
        
        $this->renderView('alunos/form', $data);
    }
    
    // Processar formulário de edição
    public function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verificar se o ID foi fornecido
            if(!isset($_POST['id'])) {
                header('Location: index.php?page=alunos');
                exit;
            }
            
            // Obter dados do formulário
            $dados = [
                'id' => (int)$_POST['id'],
                'nome' => trim($_POST['nome']),
                'email' => trim($_POST['email']),
                'cpf' => trim($_POST['cpf']),
                'matricula' => trim($_POST['matricula']),
                'data_nascimento' => !empty($_POST['data_nascimento']) ? $_POST['data_nascimento'] : null,
                'telefone' => isset($_POST['telefone']) ? trim($_POST['telefone']) : '',
                'endereco' => isset($_POST['endereco']) ? trim($_POST['endereco']) : '',
                'curso_id' => (int)$_POST['curso_id'],
                'semestre_atual' => isset($_POST['semestre_atual']) ? (int)$_POST['semestre_atual'] : 1,
                'status' => isset($_POST['status']) ? $_POST['status'] : 'ativo'
            ];
            
            // Validações básicas
            $erros = [];
            
            if (empty($dados['nome'])) {
                $erros[] = 'Nome é obrigatório';
            }
            
            if (empty($dados['email'])) {
                $erros[] = 'Email é obrigatório';
            } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                $erros[] = 'Email inválido';
            } elseif ($this->alunoModel->checkEmailExists($dados['email'], $dados['id'])) {
                $erros[] = 'Email já está em uso';
            }
            
            if (empty($dados['cpf'])) {
                $erros[] = 'CPF é obrigatório';
            } elseif ($this->alunoModel->checkCpfExists($dados['cpf'], $dados['id'])) {
                $erros[] = 'CPF já está cadastrado';
            }
            
            if (empty($dados['matricula'])) {
                $erros[] = 'Matrícula é obrigatória';
            } elseif ($this->alunoModel->checkMatriculaExists($dados['matricula'], $dados['id'])) {
                $erros[] = 'Matrícula já existe';
            }
            
            if (empty($dados['curso_id']) || $dados['curso_id'] <= 0) {
                $erros[] = 'Curso é obrigatório';
            }
            
            if (!empty($erros)) {
                $cursos = $this->cursoModel->getAllCursos(['status' => 'ativo']);
                $data = [
                    'title' => 'Editar Aluno',
                    'cursos' => $cursos,
                    'erros' => $erros,
                    'aluno' => (object)$dados
                ];
                $this->renderView('alunos/form', $data);
                return;
            }
            
            // Atualizar aluno
            $resultado = $this->alunoModel->update($dados['id'], $dados);
            
            if($resultado) {
                $_SESSION['mensagem'] = 'Aluno atualizado com sucesso';
                header('Location: index.php?page=alunos');
                exit;
            } else {
                $_SESSION['mensagem'] = 'Erro ao atualizar aluno';
                header('Location: index.php?page=aluno-edit&id=' . $dados['id']);
                exit;
            }
        } else {
            // Redirecionar se não for POST
            header('Location: index.php?page=alunos');
            exit;
        }
    }
    
    // Excluir aluno
    public function delete() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=alunos');
            exit;
        }
        
        $id = $_GET['id'];
        
        // Verificar se aluno existe
        $aluno = $this->alunoModel->getById($id);
        if(!$aluno) {
            $_SESSION['mensagem'] = 'Aluno não encontrado';
            header('Location: index.php?page=alunos');
            exit;
        }
        
        // Excluir aluno
        if($this->alunoModel->delete($id)) {
            $_SESSION['mensagem'] = 'Aluno excluído com sucesso';
        } else {
            $_SESSION['mensagem'] = 'Erro ao excluir aluno. Verifique se não há matrículas vinculadas.';
        }
        
        header('Location: index.php?page=alunos');
        exit;
    }
    
    // Buscar alunos via AJAX
    public function search() {
        if(isset($_GET['term'])) {
            $termo = $_GET['term'];
            $alunos = $this->alunoModel->getAlunosWithCurso();
            
            // Filtrar por termo
            $alunos = array_filter($alunos, function($aluno) use ($termo) {
                return stripos($aluno['nome'], $termo) !== false ||
                       stripos($aluno['matricula'], $termo) !== false ||
                       stripos($aluno['email'], $termo) !== false;
            });
            
            // Renderizar apenas a tabela
            $this->renderPartial('alunos/table_body', ['alunos' => $alunos]);
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