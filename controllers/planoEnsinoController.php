<?php
require_once 'models/PlanoEnsinoModel.php';
require_once 'models/Curso.php';
require_once 'models/DisciplinaModel.php';
require_once 'models/ProfessorModel.php';

class PlanoEnsinoController {
    private $planoEnsinoModel;
    private $cursoModel;
    private $disciplinaModel;
    private $professorModel;
    
    public function __construct() {
        $this->planoEnsinoModel = new PlanoEnsinoModel();
        $this->cursoModel = new Curso();
        $this->disciplinaModel = new DisciplinaModel();
        $this->professorModel = new ProfessorModel();
    }

    // Listagem de planos de ensino
    public function index() {
        $planos = $this->planoEnsinoModel->getPlanosCompletos();
        
        $data = [
            'title' => 'Planos de Ensino',
            'planos' => $planos,
            'mensagem' => isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : null
        ];
        
        if(isset($_SESSION['mensagem'])) {
            unset($_SESSION['mensagem']);
        }
        
        $this->renderView('planos-ensino/index', $data);
    }

    // Meus planos (professor logado)
    public function meusPlanos() {
        // Aqui você implementaria a lógica para pegar o professor logado
        $professorId = $_SESSION['usuario_id'] ?? 1; // Por enquanto hardcoded
        
        $planos = $this->planoEnsinoModel->getPlanosByProfessor($professorId);
        
        $data = [
            'title' => 'Meus Planos de Ensino',
            'planos' => $planos,
            'mensagem' => isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : null
        ];
        
        if(isset($_SESSION['mensagem'])) {
            unset($_SESSION['mensagem']);
        }
        
        $this->renderView('planos-ensino/meus-planos', $data);
    }

    // Exibir formulário de criação
    public function create() {
        $cursos = $this->cursoModel->getAllCursos(['status' => 'ativo']);
        $disciplinas = $this->disciplinaModel->getAll();
        $professores = $this->professorModel->getAllProfessores();
        
        $data = [
            'title' => 'Novo Plano de Ensino',
            'cursos' => $cursos,
            'disciplinas' => $disciplinas,
            'professores' => $professores
        ];
        
        $this->renderView('planos-ensino/form', $data);
    }

    // Processar formulário de criação
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            error_log("POST data recebido: " . print_r($_POST, true));
            
            $dados = [
                'disciplina_id' => (int)$_POST['disciplina_id'],
                'professor_id' => (int)$_POST['professor_id'],
                'curso_id' => (int)$_POST['curso_id'],
                'semestre' => (int)$_POST['semestre'],
                'ano' => (int)$_POST['ano'],
                'objetivos_gerais' => trim($_POST['objetivos_gerais']),
                'objetivos_especificos' => trim($_POST['objetivos_especificos'] ?? ''),
                'metodologia' => trim($_POST['metodologia']),
                'recursos_didaticos' => trim($_POST['recursos_didaticos'] ?? ''),
                'avaliacao' => trim($_POST['avaliacao']),
                'bibliografia_basica' => trim($_POST['bibliografia_basica'] ?? ''),
                'bibliografia_complementar' => trim($_POST['bibliografia_complementar'] ?? ''),
                'cronograma_detalhado' => trim($_POST['cronograma_detalhado'] ?? ''),
                'observacoes' => trim($_POST['observacoes'] ?? ''),
                'status' => 'pendente'
            ];
            
            error_log("Dados processados para criação: " . json_encode($dados));
            
            // Validações básicas
            $erros = [];
            
            if (empty($dados['disciplina_id']) || $dados['disciplina_id'] <= 0) {
                $erros[] = 'Disciplina é obrigatória';
            }
            
            if (empty($dados['professor_id']) || $dados['professor_id'] <= 0) {
                $erros[] = 'Professor é obrigatório';
            }
            
            if (empty($dados['curso_id']) || $dados['curso_id'] <= 0) {
                $erros[] = 'Curso é obrigatório';
            }
            
            if (empty($dados['semestre']) || $dados['semestre'] < 1 || $dados['semestre'] > 2) {
                $erros[] = 'Semestre deve ser 1 ou 2';
            }
            
            if (empty($dados['ano']) || $dados['ano'] < 2020 || $dados['ano'] > 2030) {
                $erros[] = 'Ano inválido';
            }
            
            if (empty($dados['objetivos_gerais'])) {
                $erros[] = 'Objetivos gerais são obrigatórios';
            }
            
            if (empty($dados['metodologia'])) {
                $erros[] = 'Metodologia é obrigatória';
            }
            
            if (empty($dados['avaliacao'])) {
                $erros[] = 'Avaliação é obrigatória';
            }
            
            if (!empty($erros)) {
                error_log("Erros de validação encontrados: " . print_r($erros, true));
                
                $cursos = $this->cursoModel->getAllCursos(['status' => 'ativo']);
                $disciplinas = $this->disciplinaModel->getAll();
                $professores = $this->professorModel->getAllProfessores();
                
                $data = [
                    'title' => 'Novo Plano de Ensino',
                    'cursos' => $cursos,
                    'disciplinas' => $disciplinas,
                    'professores' => $professores,
                    'erros' => $erros,
                    'dados' => $dados
                ];
                $this->renderView('planos-ensino/form', $data);
                return;
            }
            
            // Tentar criar o plano de ensino
            try {
                error_log("Tentando criar plano de ensino...");
                $resultado = $this->planoEnsinoModel->create($dados);
                error_log("Resultado da criação: " . ($resultado ? 'sucesso' : 'falha'));
                
                if($resultado) {
                    $_SESSION['mensagem'] = 'Plano de ensino criado com sucesso';
                    header('Location: index.php?page=planos-ensino');
                    exit;
                } else {
                    throw new Exception("Falha na criação do plano de ensino");
                }
            } catch (Exception $e) {
                error_log("Erro na criação do plano: " . $e->getMessage());
                
                $cursos = $this->cursoModel->getAllCursos(['status' => 'ativo']);
                $disciplinas = $this->disciplinaModel->getAll();
                $professores = $this->professorModel->getAllProfessores();
                
                $data = [
                    'title' => 'Novo Plano de Ensino',
                    'cursos' => $cursos,
                    'disciplinas' => $disciplinas,
                    'professores' => $professores,
                    'erros' => ['Erro ao criar plano de ensino: ' . $e->getMessage()],
                    'dados' => $dados
                ];
                $this->renderView('planos-ensino/form', $data);
                return;
            }
        }
    }
    
    // Exibir detalhes de um plano
    public function view() {
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=planos-ensino');
            exit;
        }
        
        $id = $_GET['id'];
        $plano = $this->planoEnsinoModel->getById($id);
        
        if(!$plano) {
            $_SESSION['mensagem'] = 'Plano de ensino não encontrado';
            header('Location: index.php?page=planos-ensino');
            exit;
        }
        
        $data = [
            'title' => 'Plano de Ensino',
            'plano' => (object)$plano
        ];
        
        $this->renderView('planos-ensino/view', $data);
    }

    // Aprovar plano de ensino via AJAX
    public function aprovar() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        if (!isset($_POST['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID do plano não fornecido']);
            return;
        }
        
        $planoId = (int)$_POST['id'];
        
        try {
            $plano = $this->planoEnsinoModel->getById($planoId);
            
            if (!$plano) {
                echo json_encode(['success' => false, 'message' => 'Plano de ensino não encontrado']);
                return;
            }
            
            if ($plano['status'] === 'aprovado') {
                echo json_encode(['success' => false, 'message' => 'Plano já está aprovado']);
                return;
            }
            
            $dados = [
                'status' => 'aprovado',
                'aprovado_por' => $_SESSION['usuario_id'] ?? 1,
                'data_aprovacao' => date('Y-m-d H:i:s')
            ];
            
            $resultado = $this->planoEnsinoModel->updateStatus($planoId, $dados);
            
            if ($resultado) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Plano aprovado com sucesso!',
                    'plano_id' => $planoId
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao aprovar plano']);
            }
            
        } catch (Exception $e) {
            error_log("Erro ao aprovar plano: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
        }
    }
    
    // Rejeitar plano de ensino via AJAX
    public function rejeitar() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        if (!isset($_POST['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID do plano não fornecido']);
            return;
        }
        
        $planoId = (int)$_POST['id'];
        $observacao = isset($_POST['observacao']) ? trim($_POST['observacao']) : '';
        
        try {
            $plano = $this->planoEnsinoModel->getById($planoId);
            
            if (!$plano) {
                echo json_encode(['success' => false, 'message' => 'Plano de ensino não encontrado']);
                return;
            }
            
            if ($plano['status'] === 'rejeitado') {
                echo json_encode(['success' => false, 'message' => 'Plano já está rejeitado']);
                return;
            }
            
            $dados = [
                'status' => 'rejeitado',
                'aprovado_por' => $_SESSION['usuario_id'] ?? 1,
                'observacoes_rejeicao' => $observacao
            ];
            
            $resultado = $this->planoEnsinoModel->updateStatus($planoId, $dados);
            
            if ($resultado) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Plano rejeitado com sucesso!',
                    'plano_id' => $planoId
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao rejeitar plano']);
            }
            
        } catch (Exception $e) {
            error_log("Erro ao rejeitar plano: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
        }
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