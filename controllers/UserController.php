<?php
require_once 'models/User.php';
require_once 'lib/Session.php';

class UserController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            error_log("=== INÍCIO DO LOGIN ===");
            error_log("Email recebido: " . $_POST['email']);
            error_log("Senha recebida: " . $_POST['password']);
            
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            
            if (empty($email) || empty($password)) {
                error_log("Campos vazios detectados");
                $data = ['error' => 'Por favor, preencha todos os campos'];
                $this->renderView('login', $data);
                return;
            }
            
            try {
                // Buscar usuário no banco
                $db = Database::getInstance();
                $sql = "SELECT * FROM usuarios WHERE email = ? AND status = 'ativo'";
                $stmt = $db->prepare($sql);
                $stmt->execute([$email]);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                
                error_log("Usuário encontrado: " . ($usuario ? 'SIM' : 'NÃO'));
                
                if ($usuario) {
                    error_log("Hash no banco: " . $usuario['senha']);
                    error_log("Verificando senha...");
                    
                    if (password_verify($password, $usuario['senha'])) {
                        error_log("Senha verificada com sucesso!");
                        
                        // Login bem-sucedido
                        Session::set('usuario_id', $usuario['id']);
                        Session::set('usuario_nome', $usuario['nome']);
                        Session::set('usuario_email', $usuario['email']);
                        Session::set('usuario_tipo', $usuario['tipo']);
                        Session::set('logado', true);
                        
                        error_log("Sessão criada para usuário: " . $usuario['nome']);
                        
                        header('Location: index.php?page=dashboard');
                        exit;
                    } else {
                        error_log("Senha incorreta!");
                        $data = ['error' => 'Email ou senha incorretos'];
                    }
                } else {
                    error_log("Usuário não encontrado ou inativo");
                    $data = ['error' => 'Email ou senha incorretos'];
                }
            } catch (Exception $e) {
                error_log("Erro no login: " . $e->getMessage());
                $data = ['error' => 'Erro interno do servidor'];
            }
            
            $this->renderView('login', $data);
        } else {
            // Exibir formulário de login
            $this->renderView('login');
        }
    }
    
    public function logout() {
        Session::destroy();
        header('Location: index.php?page=login');
        exit;
    }
    
    public function dashboard() {
        if (!Session::isLoggedIn()) {
            header('Location: index.php?page=login');
            exit;
        }
        
        $data = [
            'title' => 'Dashboard',
            'usuario' => [
                'nome' => Session::get('usuario_nome'),
                'email' => Session::get('usuario_email'),
                'tipo' => Session::get('usuario_tipo')
            ]
        ];
        
        $this->renderView('dashboard', $data);
    }
    
    private function renderView($view, $data = []) {
        extract($data);
        
        // Para a página de login, não usar header/footer padrão
        if ($view === 'login') {
            require_once 'views/templates/header.php';
            require_once 'views/' . $view . '.php';
            require_once 'views/templates/footer.php';
        } else {
            require_once 'views/templates/header.php';
            require_once 'views/' . $view . '.php';
            require_once 'views/templates/footer.php';
        }
    }
}
?>
