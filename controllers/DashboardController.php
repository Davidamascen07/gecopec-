<?php
// filepath: c:\xampp\htdocs\gecopec\controllers\DashboardController.php
require_once 'BaseController.php';
require_once 'models/DashboardModel.php';
require_once 'models/DisciplinaModel.php';
require_once 'models/PlanoEnsinoModel.php';

// Define BASE_URL if not already defined
if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    define('BASE_URL', $protocol . $host . $path . '/');
}

class DashboardController extends BaseController {
    private $dashboardModel;
    private $disciplinaModel;
    private $planoEnsinoModel;
    
    public function __construct() {
        parent::__construct();
        $this->dashboardModel = new DashboardModel();
        $this->disciplinaModel = new DisciplinaModel();
        $this->planoEnsinoModel = new PlanoEnsinoModel();
    }
    
    protected function initModel() {
        // Não é necessário inicializar um modelo específico para o dashboard
        // pois ele usa vários modelos
    }
    
    public function index() {
        try {
            // Carregar dados do banco
            $disciplinasCount = $this->dashboardModel->countDisciplinas();
            $planosPendentesCount = $this->dashboardModel->countPlanosPendentes();
            $planosAprovadosCount = $this->dashboardModel->countPlanosAprovados();
            $proximosEventosCount = $this->dashboardModel->countProximosEncontros();
            
            $atividadesRecentes = $this->dashboardModel->getAtividadesRecentes();
            $planosPendentes = $this->dashboardModel->getPlanosPendentes();
            $proximosEventos = $this->dashboardModel->getProximosEventos();
            
            $totalPlanos = $planosPendentesCount + $planosAprovadosCount;
            $percentualAprovados = $totalPlanos > 0 ? round(($planosAprovadosCount / $totalPlanos) * 100) : 0;
            
            $data = [
                'title' => 'Dashboard',
                'disciplinasCount' => $disciplinasCount,
                'planosPendentesCount' => $planosPendentesCount,
                'planosAprovadosCount' => $planosAprovadosCount,
                'proximosEventosCount' => $proximosEventosCount,
                'percentualAprovados' => $percentualAprovados,
                'atividadesRecentes' => $atividadesRecentes,
                'planosPendentes' => $planosPendentes,
                'proximosEventos' => $proximosEventos
            ];
            
            // Obter estatísticas
            $stats = $this->getStats();
            
            // Obter atividades recentes
            $atividades = $this->getActivities();
            
            // Obter aprovações pendentes
            $pendentes = $this->getPendingApprovals();
            
            // Obter próximos eventos
            $eventos = $this->getUpcomingEvents();
            
            // Obter anúncio do sistema
            $anuncio = $this->getSystemAnnouncement();
            
            // Renderizar o dashboard
            $pageTitle = 'Dashboard';
            $this->renderView('dashboard', [
                'title' => 'Dashboard - GECOPEC',
                'pageTitle' => $pageTitle,
                'stats' => $stats,
                'atividades' => $atividades,
                'pendentes' => $pendentes,
                'eventos' => $eventos,
                'anuncio' => $anuncio,
                'disciplinasCount' => $disciplinasCount,
                'planosPendentesCount' => $planosPendentesCount,
                'planosAprovadosCount' => $planosAprovadosCount,
                'proximosEventosCount' => $proximosEventosCount,
                'percentualAprovados' => $percentualAprovados,
                'atividadesRecentes' => $atividadesRecentes,
                'planosPendentes' => $planosPendentes,
                'proximosEventos' => $proximosEventos
            ]);
            
        } catch (Exception $e) {
            // Renderizar página de erro
            $this->renderErrorView($e->getMessage());
        }
    }
    
    private function getStats() {
        // Obter contagem de disciplinas ativas
        $disciplinasAtivas = $this->disciplinaModel->countActiveItems();
        $totalDisciplinas = $this->disciplinaModel->countTotalItems();
        $disciplinasPercentual = $totalDisciplinas > 0 ? round(($disciplinasAtivas / $totalDisciplinas) * 100) : 0;
        
        // Obter contagem de planos pendentes
        $planosPendentes = $this->dashboardModel->countPlanosPendentes();
        $planosAprovados = $this->dashboardModel->countPlanosAprovados();
        $totalPlanos = $planosPendentes + $planosAprovados;
        $planosPercentual = $totalPlanos > 0 ? round(($planosPendentes / $totalPlanos) * 100) : 0;
        // Obter contagem de aprovações
        $aprovacoes = $this->dashboardModel->countPlanosAprovados();
        $aprovacoesTotais = $totalPlanos > 0 ? $totalPlanos : 1;
        $aprovacoesPercentual = round(($aprovacoes / $aprovacoesTotais) * 100);
        $aprovacoesPercentual = round(($aprovacoes / $aprovacoesTotais) * 100);
        
        // Obter contagem de eventos próximos
        $eventos = 2; // Valor estático para exemplo
        $eventosPercentual = 20; // Valor estático para exemplo
        
        return [
            'disciplinas_ativas' => $disciplinasAtivas,
            'disciplinas_percentual' => $disciplinasPercentual,
            'planos_pendentes' => $planosPendentes,
            'planos_percentual' => $planosPercentual,
            'aprovacoes' => $aprovacoes,
            'aprovacoes_percentual' => $aprovacoesPercentual,
            'eventos' => $eventos,
            'eventos_percentual' => $eventosPercentual
        ];
    }
    
    private function getActivities() {
        // Exemplo de atividades recentes
        return [
            [
                'titulo' => 'Plano de Ensino atualizado',
                'descricao' => 'Engenharia de Software II',
                'tempo' => '10 min atrás',
                'cor' => 'indigo'
            ],
            [
                'titulo' => 'Plano aprovado',
                'descricao' => 'Banco de Dados I',
                'tempo' => '1 hora atrás',
                'cor' => 'green'
            ],
            [
                'titulo' => 'Revisão solicitada',
                'descricao' => 'Redes de Computadores',
                'tempo' => 'Ontem, 16:30',
                'cor' => 'yellow'
            ],
            [
                'titulo' => 'Novo cronograma criado',
                'descricao' => 'Sistemas Operacionais',
                'tempo' => 'Ontem, 14:15',
                'cor' => 'blue'
            ]
        ];
    }
    
    private function getPendingApprovals() {
        // Obter planos de ensino pendentes
        try {
            $planosPendentes = $this->dashboardModel->getPlanosPendentes();
            
            // Formatar para exibição
            $pendentes = [];
            foreach ($planosPendentes as $plano) {
                $pendentes[] = [
                    'id' => $plano['id'],
                    'titulo' => $plano['disciplina_nome'] ?? 'Disciplina',
                    'autor' => $plano['professor_nome'] ?? 'Professor',
                ];
            }
            
            return $pendentes;
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getUpcomingEvents() {
        // Exemplo de próximos eventos
        return [
            [
                'titulo' => 'Reunião de Coordenação',
                'data_hora' => '15/05 - 14:00 às 16:00',
                'cor' => 'purple',
                'icone' => 'fas fa-calendar-day'
            ],
            [
                'titulo' => 'Capacitação Docente',
                'data_hora' => '18/05 - 09:00 às 12:00',
                'cor' => 'blue',
                'icone' => 'fas fa-chalkboard-teacher'
            ],
            [
                'titulo' => 'Prazo Planos de Ensino',
                'data_hora' => '20/05 - Até 18:00',
                'cor' => 'green',
                'icone' => 'fas fa-file-upload'
            ]
        ];
    }
    
    private function getSystemAnnouncement() {
        // Exemplo de anúncio do sistema
        return [
            'titulo' => 'Novidades no GECOPEC!',
            'descricao' => 'Agora você pode exportar seus planos de ensino diretamente para o formato exigido pela CAPES. Confira as novas funcionalidades na seção de relatórios.',
            'link' => BASE_URL . 'relatorios'
        ];
    }

    // Método auxiliar para carregar views
    private function renderView($view, $data = []) {
        extract($data);
        
        require_once 'views/templates/header.php';
        require_once 'views/' . $view . '.php';
        require_once 'views/templates/footer.php';
    }
    
    // Método auxiliar para páginas de erro
    private function renderErrorView($message) {
        $data = [
            'title' => 'Erro - Dashboard',
            'error' => $message,
            'disciplinasCount' => 0,
            'planosPendentesCount' => 0,
            'planosAprovadosCount' => 0,
            'proximosEventosCount' => 0,
            'percentualAprovados' => 0,
            'atividadesRecentes' => [],
            'planosPendentes' => [],
            'proximosEventos' => []
        ];
        
        $this->renderView('dashboard', $data);
    }
}

// Verificar se está sendo acessado diretamente via URL
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    $controller = new DashboardController();
    $controller->handleRequest();
}
?>
