<?php
require_once 'models/CronogramaModel.php';
require_once 'models/PlanoEnsinoModel.php';

class CronogramaController {
    private $cronogramaModel;
    private $planoEnsinoModel;
    
    public function __construct() {
        $this->cronogramaModel = new CronogramaModel();
        $this->planoEnsinoModel = new PlanoEnsinoModel();
    }

    // Listagem de cronogramas
    public function index() {
        // Obter filtros da URL
        $filtros = [
            'busca' => isset($_GET['busca']) ? $_GET['busca'] : '',
            'plano_ensino_id' => isset($_GET['plano_ensino_id']) ? $_GET['plano_ensino_id'] : '',
            'data_inicio' => isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '',
            'data_fim' => isset($_GET['data_fim']) ? $_GET['data_fim'] : ''
        ];
        
        // Obter cronogramas
        if (!empty($filtros['data_inicio']) && !empty($filtros['data_fim'])) {
            $cronogramas = $this->cronogramaModel->getCronogramaSemanal($filtros['data_inicio'], $filtros['data_fim']);
        } else {
            $cronogramas = $this->cronogramaModel->getCronogramasCompletos();
        }
        
        // Aplicar filtros
        if (!empty($filtros['busca'])) {
            $cronogramas = array_filter($cronogramas, function($cronograma) use ($filtros) {
                return stripos($cronograma['disciplina_nome'], $filtros['busca']) !== false ||
                       stripos($cronograma['professor_nome'], $filtros['busca']) !== false ||
                       stripos($cronograma['conteudo'], $filtros['busca']) !== false;
            });
        }
        
        if (!empty($filtros['plano_ensino_id'])) {
            $cronogramas = array_filter($cronogramas, function($cronograma) use ($filtros) {
                return $cronograma['plano_ensino_id'] == $filtros['plano_ensino_id'];
            });
        }
        
        // Obter planos de ensino para filtro
        $planos = $this->planoEnsinoModel->getPlanosCompletos();
        
        $data = [
            'title' => 'Cronogramas',
            'cronogramas' => $cronogramas,
            'planos' => $planos,
            'filtros' => $filtros,
            'mensagem' => isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : null
        ];
        
        if(isset($_SESSION['mensagem'])) {
            unset($_SESSION['mensagem']);
        }
        
        $this->renderView('cronogramas/index', $data);
    }
    
    // Exibir detalhes de um cronograma
    public function view() {
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=cronogramas');
            exit;
        }
        
        $id = $_GET['id'];
        $cronograma = $this->cronogramaModel->getById($id);
        
        if(!$cronograma) {
            $_SESSION['mensagem'] = 'Cronograma não encontrado';
            header('Location: index.php?page=cronogramas');
            exit;
        }
        
        $data = [
            'title' => 'Cronograma - Semana ' . $cronograma['semana'],
            'cronograma' => (object)$cronograma
        ];
        
        $this->renderView('cronogramas/view', $data);
    }
    
    // Exibir formulário de criação
    public function create() {
        $planos = $this->planoEnsinoModel->getPlanosCompletos();
        
        $data = [
            'title' => 'Novo Cronograma',
            'planos' => $planos
        ];
        
        $this->renderView('cronogramas/form', $data);
    }
    
    // Processar formulário de criação
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dados = [
                'plano_ensino_id' => (int)$_POST['plano_ensino_id'],
                'semana' => (int)$_POST['semana'],
                'data_inicio' => $_POST['data_inicio'],
                'data_fim' => $_POST['data_fim'],
                'conteudo' => trim($_POST['conteudo']),
                'metodologia' => trim($_POST['metodologia'] ?? ''),
                'recursos' => trim($_POST['recursos'] ?? ''),
                'avaliacao' => trim($_POST['avaliacao'] ?? ''),
                'observacoes' => trim($_POST['observacoes'] ?? '')
            ];
            
            // Validações
            $erros = [];
            
            if (empty($dados['plano_ensino_id']) || $dados['plano_ensino_id'] <= 0) {
                $erros[] = 'Plano de ensino é obrigatório';
            }
            
            if (empty($dados['semana']) || $dados['semana'] <= 0) {
                $erros[] = 'Semana deve ser um número positivo';
            }
            
            if (empty($dados['data_inicio'])) {
                $erros[] = 'Data de início é obrigatória';
            }
            
            if (empty($dados['data_fim'])) {
                $erros[] = 'Data de fim é obrigatória';
            }
            
            if (empty($dados['conteudo'])) {
                $erros[] = 'Conteúdo é obrigatório';
            }
            
            // Validar datas
            if (!empty($dados['data_inicio']) && !empty($dados['data_fim'])) {
                if ($dados['data_inicio'] > $dados['data_fim']) {
                    $erros[] = 'Data de início deve ser anterior à data de fim';
                }
            }
            
            if (!empty($erros)) {
                $planos = $this->planoEnsinoModel->getPlanosCompletos();
                $data = [
                    'title' => 'Novo Cronograma',
                    'planos' => $planos,
                    'erros' => $erros,
                    'dados' => $dados
                ];
                $this->renderView('cronogramas/form', $data);
                return;
            }
            
            $resultado = $this->cronogramaModel->create($dados);
            
            if($resultado) {
                $_SESSION['mensagem'] = 'Cronograma criado com sucesso';
                header('Location: index.php?page=cronogramas');
                exit;
            } else {
                $planos = $this->planoEnsinoModel->getPlanosCompletos();
                $data = [
                    'title' => 'Novo Cronograma',
                    'planos' => $planos,
                    'erros' => ['Erro ao criar cronograma'],
                    'dados' => $dados
                ];
                $this->renderView('cronogramas/form', $data);
            }
        } else {
            header('Location: index.php?page=cronograma-create');
            exit;
        }
    }
    
    // Exibir formulário de edição
    public function edit() {
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=cronogramas');
            exit;
        }
        
        $id = $_GET['id'];
        $cronograma = $this->cronogramaModel->getById($id);
        
        if(!$cronograma) {
            $_SESSION['mensagem'] = 'Cronograma não encontrado';
            header('Location: index.php?page=cronogramas');
            exit;
        }
        
        $planos = $this->planoEnsinoModel->getPlanosCompletos();
        
        $data = [
            'title' => 'Editar Cronograma',
            'cronograma' => (object)$cronograma,
            'planos' => $planos
        ];
        
        $this->renderView('cronogramas/form', $data);
    }
    
    // Processar formulário de edição
    public function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(!isset($_POST['id'])) {
                header('Location: index.php?page=cronogramas');
                exit;
            }
            
            $dados = [
                'id' => (int)$_POST['id'],
                'plano_ensino_id' => (int)$_POST['plano_ensino_id'],
                'semana' => (int)$_POST['semana'],
                'data_inicio' => $_POST['data_inicio'],
                'data_fim' => $_POST['data_fim'],
                'conteudo' => trim($_POST['conteudo']),
                'metodologia' => trim($_POST['metodologia'] ?? ''),
                'recursos' => trim($_POST['recursos'] ?? ''),
                'avaliacao' => trim($_POST['avaliacao'] ?? ''),
                'observacoes' => trim($_POST['observacoes'] ?? '')
            ];
            
            // Validações (mesmas da criação)
            $erros = [];
            
            if (empty($dados['plano_ensino_id']) || $dados['plano_ensino_id'] <= 0) {
                $erros[] = 'Plano de ensino é obrigatório';
            }
            
            if (empty($dados['semana']) || $dados['semana'] <= 0) {
                $erros[] = 'Semana deve ser um número positivo';
            }
            
            if (empty($dados['data_inicio'])) {
                $erros[] = 'Data de início é obrigatória';
            }
            
            if (empty($dados['data_fim'])) {
                $erros[] = 'Data de fim é obrigatória';
            }
            
            if (empty($dados['conteudo'])) {
                $erros[] = 'Conteúdo é obrigatório';
            }
            
            if (!empty($dados['data_inicio']) && !empty($dados['data_fim'])) {
                if ($dados['data_inicio'] > $dados['data_fim']) {
                    $erros[] = 'Data de início deve ser anterior à data de fim';
                }
            }
            
            if (!empty($erros)) {
                $planos = $this->planoEnsinoModel->getPlanosCompletos();
                $data = [
                    'title' => 'Editar Cronograma',
                    'planos' => $planos,
                    'erros' => $erros,
                    'cronograma' => (object)$dados
                ];
                $this->renderView('cronogramas/form', $data);
                return;
            }
            
            $resultado = $this->cronogramaModel->update($dados['id'], $dados);
            
            if($resultado) {
                $_SESSION['mensagem'] = 'Cronograma atualizado com sucesso';
                header('Location: index.php?page=cronogramas');
                exit;
            } else {
                $_SESSION['mensagem'] = 'Erro ao atualizar cronograma';
                header('Location: index.php?page=cronograma-edit&id=' . $dados['id']);
                exit;
            }
        } else {
            header('Location: index.php?page=cronogramas');
            exit;
        }
    }
    
    // Excluir cronograma
    public function delete() {
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=cronogramas');
            exit;
        }
        
        $id = $_GET['id'];
        $cronograma = $this->cronogramaModel->getById($id);
        
        if(!$cronograma) {
            $_SESSION['mensagem'] = 'Cronograma não encontrado';
            header('Location: index.php?page=cronogramas');
            exit;
        }
        
        if($this->cronogramaModel->delete($id)) {
            $_SESSION['mensagem'] = 'Cronograma excluído com sucesso';
        } else {
            $_SESSION['mensagem'] = 'Erro ao excluir cronograma';
        }
        
        header('Location: index.php?page=cronogramas');
        exit;
    }
    
    // Visualizar cronogramas por plano
    public function porPlano() {
        if(!isset($_GET['plano_id'])) {
            header('Location: index.php?page=cronogramas');
            exit;
        }
        
        $planoId = $_GET['plano_id'];
        $cronogramas = $this->cronogramaModel->getCronogramasByPlano($planoId);
        
        // Obter dados do plano
        $plano = $this->planoEnsinoModel->getById($planoId);
        
        $data = [
            'title' => 'Cronograma do Plano de Ensino',
            'cronogramas' => $cronogramas,
            'plano' => $plano
        ];
        
        $this->renderView('cronogramas/por-plano', $data);
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