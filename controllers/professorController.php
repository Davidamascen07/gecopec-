<?php
require_once 'models/ProfessorModel.php';
require_once 'models/Usuario.php';

class ProfessorController {
    private $professorModel;
    private $usuarioModel;
    
    public function __construct() {
        $this->professorModel = new ProfessorModel();
        $this->usuarioModel = new Usuario();
    }

    // Listagem de professores
    public function index() {
        // Obter filtros da URL
        $filtros = [
            'busca' => isset($_GET['busca']) ? $_GET['busca'] : '',
            'departamento' => isset($_GET['departamento']) ? $_GET['departamento'] : '',
            'status' => isset($_GET['status']) ? $_GET['status'] : ''
        ];
        
        // Obter todos os professores com usuários
        $professores = $this->professorModel->getProfessoresWithUsuario();
        
        // Aplicar filtros
        if (!empty($filtros['busca'])) {
            $professores = array_filter($professores, function($professor) use ($filtros) {
                return stripos($professor['nome'], $filtros['busca']) !== false ||
                       stripos($professor['matricula'], $filtros['busca']) !== false ||
                       stripos($professor['email'], $filtros['busca']) !== false;
            });
        }
        
        if (!empty($filtros['departamento'])) {
            $professores = array_filter($professores, function($professor) use ($filtros) {
                return $professor['departamento'] === $filtros['departamento'];
            });
        }
        
        if (!empty($filtros['status'])) {
            $professores = array_filter($professores, function($professor) use ($filtros) {
                return $professor['status'] === $filtros['status'];
            });
        }
        
        // Obter departamentos únicos para o filtro
        $todosOsProfessores = $this->professorModel->getProfessoresWithUsuario();
        $departamentos = array_unique(array_filter(array_column($todosOsProfessores, 'departamento')));
        sort($departamentos);
        
        $data = [
            'title' => 'Gerenciar Professores',
            'professores' => $professores,
            'departamentos' => $departamentos,
            'filtros' => $filtros,
            'mensagem' => isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : null
        ];
        
        // Limpar mensagem da sessão após exibir
        if(isset($_SESSION['mensagem'])) {
            unset($_SESSION['mensagem']);
        }
        
        $this->renderView('professores/index', $data);
    }
    
    // Exibir detalhes de um professor
    public function view() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=professores');
            exit;
        }
        
        $id = $_GET['id'];
        $professor = $this->professorModel->getById($id);
        
        if(!$professor) {
            $_SESSION['mensagem'] = 'Professor não encontrado';
            header('Location: index.php?page=professores');
            exit;
        }
        
        // Obter dados do usuário
        $usuario = $this->usuarioModel->getUsuarioById($professor['usuario_id']);
        $professor['nome'] = $usuario->nome;
        $professor['email'] = $usuario->email;
        
        // Obter turmas do professor
        $turmas = $this->professorModel->getTurmasProfessor($id);
        
        $data = [
            'title' => $professor['nome'],
            'professor' => (object)$professor,
            'turmas' => $turmas
        ];
        
        $this->renderView('professores/view', $data);
    }
    
    // Exibir formulário de criação
    public function create() {
        // Debug logs comentados
        // $todosUsuarios = $this->usuarioModel->getAll();
        // error_log("Todos os usuários no banco: " . print_r($todosUsuarios, true));
        
        // Usar método específico para obter usuários disponíveis
        $usuarios = $this->usuarioModel->getUsuariosDisponiveis();
        
        // Debug logs comentados
        // error_log("Usuários disponíveis para professor: " . print_r($usuarios, true));
        
        // Debug logs comentados
        // $professoresExistentes = $this->professorModel->getAll();
        // error_log("Professores existentes: " . print_r($professoresExistentes, true));
        
        // Validar integridade dos dados (mantido para segurança)
        $this->validarIntegridadeDados();
        
        $data = [
            'title' => 'Novo Professor',
            'usuarios' => $usuarios
            // Debug arrays comentados
            // 'debug_todos_usuarios' => $todosUsuarios,
            // 'debug_professores_existentes' => $professoresExistentes
        ];
        
        $this->renderView('professores/form', $data);
    }
    
    // Método para validar integridade dos dados
    private function validarIntegridadeDados() {
        // Verificar se há professores com usuario_id inválido
        $sql = "SELECT p.id, p.usuario_id 
                FROM professores p 
                LEFT JOIN usuarios u ON p.usuario_id = u.id 
                WHERE u.id IS NULL";
        
        $stmt = $this->professorModel->verificarConsulta($sql);
        $professoresOrfaos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($professoresOrfaos)) {
            // Debug logs comentados - mantido apenas para casos críticos
            error_log("ERRO: Professores com usuario_id inválido encontrados: " . print_r($professoresOrfaos, true));
        }
        
        // Verificar se há duplicatas na tabela professores
        $sql = "SELECT usuario_id, COUNT(*) as count 
                FROM professores 
                GROUP BY usuario_id 
                HAVING COUNT(*) > 1";
        
        $stmt = $this->professorModel->verificarConsulta($sql);
        $duplicatas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($duplicatas)) {
            // Debug logs comentados - mantido apenas para casos críticos
            error_log("ERRO: Duplicatas encontradas na tabela professores: " . print_r($duplicatas, true));
        }
    }
    
    // Processar formulário de criação
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Debug logs comentados
            // error_log("POST data: " . print_r($_POST, true));
            
            // Obter dados do formulário
            $dados = [
                'usuario_id' => (int)$_POST['usuario_id'],
                'matricula' => trim($_POST['matricula']),
                'data_nascimento' => !empty($_POST['data_nascimento']) ? $_POST['data_nascimento'] : null,
                'telefone' => isset($_POST['telefone']) ? trim($_POST['telefone']) : '',
                'endereco' => isset($_POST['endereco']) ? trim($_POST['endereco']) : '',
                'departamento' => trim($_POST['departamento']),
                'especializacao' => isset($_POST['especializacao']) ? trim($_POST['especializacao']) : '',
                'lattes_url' => isset($_POST['lattes_url']) ? trim($_POST['lattes_url']) : '',
                'status' => isset($_POST['status']) ? $_POST['status'] : 'ativo'
            ];
            
            // Debug logs comentados
            // error_log("Dados processados: " . print_r($dados, true));
            
            // Validações básicas
            $erros = [];
            
            if (empty($dados['usuario_id']) || $dados['usuario_id'] <= 0) {
                $erros[] = 'Usuário é obrigatório';
            } else {
                // Verificação tripla do usuário
                
                // 1. Verificar se usuário existe
                $usuarioExiste = $this->usuarioModel->verificarUsuarioExiste($dados['usuario_id']);
                // Debug logs comentados
                // error_log("1. Usuário existe: " . print_r($usuarioExiste, true));
                
                if (!$usuarioExiste) {
                    $erros[] = 'Usuário selecionado não existe no banco de dados (ID: ' . $dados['usuario_id'] . ')';
                } else {
                    // 2. Verificar se usuário já é professor
                    $professorExistente = $this->professorModel->getByField('usuario_id', $dados['usuario_id']);
                    // Debug logs comentados
                    // error_log("2. Professor já existe: " . print_r($professorExistente, true));
                    
                    if ($professorExistente) {
                        $erros[] = 'Este usuário já está cadastrado como professor';
                    }
                    
                    // 3. Verificar se usuário está na lista de disponíveis
                    $usuariosDisponiveis = $this->usuarioModel->getUsuariosDisponiveis();
                    $usuarioNaLista = false;
                    foreach ($usuariosDisponiveis as $user) {
                        if ($user['id'] == $dados['usuario_id']) {
                            $usuarioNaLista = true;
                            break;
                        }
                    }
                    
                    if (!$usuarioNaLista) {
                        $erros[] = 'Usuário não está disponível para ser professor';
                    }
                }
            }
            
            if (empty($dados['matricula'])) {
                $erros[] = 'Matrícula é obrigatória';
            } elseif ($this->professorModel->checkMatriculaExists($dados['matricula'])) {
                $erros[] = 'Matrícula já existe';
            }
            
            if (empty($dados['departamento'])) {
                $erros[] = 'Departamento é obrigatório';
            }
            
            // Validar data de nascimento se fornecida
            if (!empty($dados['data_nascimento'])) {
                $data = DateTime::createFromFormat('Y-m-d', $dados['data_nascimento']);
                if (!$data || $data->format('Y-m-d') !== $dados['data_nascimento']) {
                    $erros[] = 'Data de nascimento inválida';
                }
            }
            
            // Validar URL do Lattes se fornecida
            if (!empty($dados['lattes_url']) && !filter_var($dados['lattes_url'], FILTER_VALIDATE_URL)) {
                $erros[] = 'URL do Lattes inválida';
            }
            
            if (!empty($erros)) {
                $usuarios = $this->usuarioModel->getUsuariosDisponiveis();
                
                $data = [
                    'title' => 'Novo Professor',
                    'usuarios' => $usuarios,
                    'erros' => $erros,
                    'dados' => $dados
                ];
                $this->renderView('professores/form', $data);
                return;
            }
            
            // Debug logs comentados
            // error_log("Tentando criar professor com dados: " . print_r($dados, true));
            
            // Criar professor com transação para garantir integridade
            try {
                // Começar transação
                $this->professorModel->beginTransaction();
                
                // Verificar novamente se o usuário existe e está disponível
                $usuarioFinal = $this->usuarioModel->verificarUsuarioExiste($dados['usuario_id']);
                if (!$usuarioFinal) {
                    throw new Exception("Usuário não encontrado no momento da criação");
                }
                
                $professorExistenteFinal = $this->professorModel->getByField('usuario_id', $dados['usuario_id']);
                if ($professorExistenteFinal) {
                    throw new Exception("Usuário já é professor");
                }
                
                // Criar professor
                $resultado = $this->professorModel->createWithValidation($dados);
                
                if($resultado) {
                    $this->professorModel->commit();
                    $_SESSION['mensagem'] = 'Professor cadastrado com sucesso';
                    header('Location: index.php?page=professores');
                    exit;
                } else {
                    $this->professorModel->rollback();
                    throw new Exception("Falha ao inserir professor");
                }
            } catch (Exception $e) {
                $this->professorModel->rollback();
                // Debug logs comentados - mantido apenas para erros críticos
                error_log("Erro ao criar professor: " . $e->getMessage());
                // error_log("Stack trace: " . $e->getTraceAsString());
                
                $usuarios = $this->usuarioModel->getUsuariosDisponiveis();
                
                $data = [
                    'title' => 'Novo Professor',
                    'usuarios' => $usuarios,
                    'erros' => ['Erro ao cadastrar professor: ' . $e->getMessage()],
                    'dados' => $dados
                ];
                $this->renderView('professores/form', $data);
                return;
            }
        } else {
            // Redirecionar se não for POST
            header('Location: index.php?page=professor-create');
            exit;
        }
    }
    
    // Exibir formulário de edição
    public function edit() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=professores');
            exit;
        }
        
        $id = $_GET['id'];
        $professor = $this->professorModel->getById($id);
        
        if(!$professor) {
            $_SESSION['mensagem'] = 'Professor não encontrado';
            header('Location: index.php?page=professores');
            exit;
        }
        
        // Obter dados do usuário
        $usuario = $this->usuarioModel->getUsuarioById($professor['usuario_id']);
        $professor['nome'] = $usuario->nome;
        $professor['email'] = $usuario->email;
        
        // Obter usuários disponíveis (incluindo o atual)
        $usuarios = $this->usuarioModel->getAllUsuarios(['status' => 'ativo']);
        
        $data = [
            'title' => 'Editar Professor',
            'professor' => (object)$professor,
            'usuarios' => $usuarios
        ];
        
        $this->renderView('professores/form', $data);
    }
    
    // Processar formulário de edição
    public function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verificar se o ID foi fornecido
            if(!isset($_POST['id'])) {
                header('Location: index.php?page=professores');
                exit;
            }
            
            // Obter dados do formulário
            $dados = [
                'id' => (int)$_POST['id'],
                'usuario_id' => (int)$_POST['usuario_id'],
                'matricula' => trim($_POST['matricula']),
                'data_nascimento' => !empty($_POST['data_nascimento']) ? $_POST['data_nascimento'] : null,
                'telefone' => isset($_POST['telefone']) ? trim($_POST['telefone']) : '',
                'endereco' => isset($_POST['endereco']) ? trim($_POST['endereco']) : '',
                'departamento' => trim($_POST['departamento']),
                'especializacao' => isset($_POST['especializacao']) ? trim($_POST['especializacao']) : '',
                'lattes_url' => isset($_POST['lattes_url']) ? trim($_POST['lattes_url']) : '',
                'status' => isset($_POST['status']) ? $_POST['status'] : 'ativo'
            ];
            
            // Validações básicas
            $erros = [];
            
            if (empty($dados['usuario_id'])) {
                $erros[] = 'Usuário é obrigatório';
            }
            
            if (empty($dados['matricula'])) {
                $erros[] = 'Matrícula é obrigatória';
            } elseif ($this->professorModel->checkMatriculaExists($dados['matricula'], $dados['id'])) {
                $erros[] = 'Matrícula já existe';
            }
            
            if (empty($dados['departamento'])) {
                $erros[] = 'Departamento é obrigatório';
            }
            
            if (!empty($erros)) {
                $usuarios = $this->usuarioModel->getAllUsuarios(['status' => 'ativo']);
                $data = [
                    'title' => 'Editar Professor',
                    'usuarios' => $usuarios,
                    'erros' => $erros,
                    'professor' => (object)$dados
                ];
                $this->renderView('professores/form', $data);
                return;
            }
            
            // Atualizar professor
            $resultado = $this->professorModel->update($dados['id'], $dados);
            
            if($resultado) {
                $_SESSION['mensagem'] = 'Professor atualizado com sucesso';
                header('Location: index.php?page=professores');
                exit;
            } else {
                $_SESSION['mensagem'] = 'Erro ao atualizar professor';
                header('Location: index.php?page=professor-edit&id=' . $dados['id']);
                exit;
            }
        } else {
            // Redirecionar se não for POST
            header('Location: index.php?page=professores');
            exit;
        }
    }
    
    // Excluir professor
    public function delete() {
        // Verificar se o ID foi fornecido
        if(!isset($_GET['id'])) {
            header('Location: index.php?page=professores');
            exit;
        }
        
        $id = $_GET['id'];
        
        // Verificar se professor existe
        $professor = $this->professorModel->getById($id);
        if(!$professor) {
            $_SESSION['mensagem'] = 'Professor não encontrado';
            header('Location: index.php?page=professores');
            exit;
        }
        
        // Excluir professor
        if($this->professorModel->delete($id)) {
            $_SESSION['mensagem'] = 'Professor excluído com sucesso';
        } else {
            $_SESSION['mensagem'] = 'Erro ao excluir professor. Verifique se não há turmas vinculadas.';
        }
        
        header('Location: index.php?page=professores');
        exit;
    }
    
    // Buscar professores via AJAX
    public function search() {
        if(isset($_GET['term'])) {
            $termo = $_GET['term'];
            $professores = $this->professorModel->getAllProfessores(['busca' => $termo]);
            
            // Renderizar apenas a tabela
            $this->renderPartial('professores/table_body', ['professores' => $professores]);
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
