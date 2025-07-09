<?php
session_start();
require_once 'config/config.php';
require_once 'lib/Database.php';
require_once 'lib/Session.php';
require_once 'controllers/UserController.php';
require_once 'controllers/CursoController.php';
require_once 'controllers/DisciplinaController.php';
require_once 'controllers/ProfessorController.php';
require_once 'controllers/AlunoController.php';
require_once 'controllers/DashboardController.php'; // Adicione esta linha

// Verificar se existe o controller de TurmaController antes de incluir
if (file_exists('controllers/TurmaController.php')) {
    require_once 'controllers/TurmaController.php';
}

// Verificar se existe o controller de PlanoEnsino antes de incluir
if (file_exists('controllers/PlanoEnsinoController.php')) {
    require_once 'controllers/PlanoEnsinoController.php';
}

// Verificar se existe o controller de CronogramaController antes de incluir
if (file_exists('controllers/CronogramaController.php')) {
    require_once 'controllers/CronogramaController.php';
}

// Verificar se existe o controller de RelatorioController antes de incluir
if (file_exists('controllers/RelatorioController.php')) {
    require_once 'controllers/RelatorioController.php';
}

// Sistema de roteamento simples
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// Verificar se usuário está logado para páginas protegidas
$paginasPublicas = ['login'];
if (!in_array($page, $paginasPublicas) && !Session::isLoggedIn()) {
    header('Location: index.php?page=login');
    exit;
}

// Roteamento baseado em página
switch ($page) {
    case 'login':
        $controller = new UserController();
        $controller->login();
        break;

    case 'dashboard':
        $controller = new DashboardController(); // Use o novo controller
        $controller->index();
        break;

    case 'logout':
        $controller = new UserController();
        $controller->logout();
        break;

    // Rotas de cursos
    case 'cursos':
        $controller = new CursoController();
        $controller->index();
        break;

    case 'curso-create':
        $controller = new CursoController();
        $controller->create();
        break;

    case 'curso-store':
        $controller = new CursoController();
        $controller->store();
        break;

    case 'curso-edit':
        $controller = new CursoController();
        $controller->edit();
        break;

    case 'curso-update':
        $controller = new CursoController();
        $controller->update();
        break;

    case 'curso-view':
        $controller = new CursoController();
        $controller->view();
        break;

    case 'curso-delete':
        $controller = new CursoController();
        $controller->delete();
        break;

    // Rotas de disciplinas
    case 'disciplinas':
        $controller = new DisciplinaController();
        $controller->index();
        break;

    case 'disciplina-create':
        $controller = new DisciplinaController();
        $controller->create();
        break;

    case 'disciplina-store':
        $controller = new DisciplinaController();
        $controller->store();
        break;

    case 'disciplina-edit':
        $controller = new DisciplinaController();
        $controller->edit();
        break;

    case 'disciplina-update':
        $controller = new DisciplinaController();
        $controller->update();
        break;

    case 'disciplina-view':
        $controller = new DisciplinaController();
        $controller->view();
        break;

    case 'disciplina-delete':
        $controller = new DisciplinaController();
        $controller->delete();
        break;

    case 'disciplina-search':
        $controller = new DisciplinaController();
        $controller->search();
        break;

    // Rotas de planos de ensino
    case 'planos-ensino':
        if (class_exists('PlanoEnsinoController')) {
            $controller = new PlanoEnsinoController();
            $controller->index();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;

    case 'plano-ensino-create':
        if (class_exists('PlanoEnsinoController')) {
            $controller = new PlanoEnsinoController();
            $controller->create();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;
        
    case 'plano-ensino-store':
        if (class_exists('PlanoEnsinoController')) {
            $controller = new PlanoEnsinoController();
            $controller->store();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;
        
    case 'plano-ensino-view':
        if (class_exists('PlanoEnsinoController')) {
            $controller = new PlanoEnsinoController();
            $controller->view();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;
        
    case 'plano-ensino-aprovar':
        if (class_exists('PlanoEnsinoController')) {
            $controller = new PlanoEnsinoController();
            $controller->aprovar();
        } else {
            echo json_encode(['success' => false, 'message' => 'Controller não encontrado']);
        }
        break;
        
    case 'plano-ensino-rejeitar':
        if (class_exists('PlanoEnsinoController')) {
            $controller = new PlanoEnsinoController();
            $controller->rejeitar();
        } else {
            echo json_encode(['success' => false, 'message' => 'Controller não encontrado']);
        }
        break;

    // Rotas de professores
    case 'professores':
        $controller = new ProfessorController();
        $controller->index();
        break;

    case 'professor-create':
        $controller = new ProfessorController();
        $controller->create();
        break;

    case 'professor-store':
        $controller = new ProfessorController();
        $controller->store();
        break;

    case 'professor-edit':
        $controller = new ProfessorController();
        $controller->edit();
        break;

    case 'professor-update':
        $controller = new ProfessorController();
        $controller->update();
        break;

    case 'professor-view':
        $controller = new ProfessorController();
        $controller->view();
        break;

    case 'professor-delete':
        $controller = new ProfessorController();
        $controller->delete();
        break;

    case 'professor-search':
        $controller = new ProfessorController();
        $controller->search();
        break;

    // Rotas de alunos
    case 'alunos':
        $controller = new AlunoController();
        $controller->index();
        break;

    case 'aluno-create':
        $controller = new AlunoController();
        $controller->create();
        break;

    case 'aluno-store':
        $controller = new AlunoController();
        $controller->store();
        break;

    case 'aluno-edit':
        $controller = new AlunoController();
        $controller->edit();
        break;

    case 'aluno-update':
        $controller = new AlunoController();
        $controller->update();
        break;

    case 'aluno-view':
        $controller = new AlunoController();
        $controller->view();
        break;

    case 'aluno-delete':
        $controller = new AlunoController();
        $controller->delete();
        break;

    case 'aluno-search':
        $controller = new AlunoController();
        $controller->search();
        break;

    // Rotas de turmas
    case 'turmas':
        if (class_exists('TurmaController')) {
            $controller = new TurmaController();
            $controller->index();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;

    case 'turma-create':
        if (class_exists('TurmaController')) {
            $controller = new TurmaController();
            $controller->create();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;

    case 'turma-store':
        if (class_exists('TurmaController')) {
            $controller = new TurmaController();
            $controller->store();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;

    case 'turma-edit':
        if (class_exists('TurmaController')) {
            $controller = new TurmaController();
            $controller->edit();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;

    case 'turma-update':
        if (class_exists('TurmaController')) {
            $controller = new TurmaController();
            $controller->update();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;

    case 'turma-view':
        if (class_exists('TurmaController')) {
            $controller = new TurmaController();
            $controller->view();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;

    case 'turma-delete':
        if (class_exists('TurmaController')) {
            $controller = new TurmaController();
            $controller->delete();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;

    case 'turma-search':
        if (class_exists('TurmaController')) {
            $controller = new TurmaController();
            $controller->search();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;

    // Rotas de cronogramas
    case 'cronogramas':
        if (class_exists('CronogramaController')) {
            $controller = new CronogramaController();
            $controller->index();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;

    case 'cronograma-create':
        if (class_exists('CronogramaController')) {
            $controller = new CronogramaController();
            $controller->create();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;
        
    case 'cronograma-store':
        if (class_exists('CronogramaController')) {
            $controller = new CronogramaController();
            $controller->store();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;
        
    case 'cronograma-edit':
        if (class_exists('CronogramaController')) {
            $controller = new CronogramaController();
            $controller->edit();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;
        
    case 'cronograma-update':
        if (class_exists('CronogramaController')) {
            $controller = new CronogramaController();
            $controller->update();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;
        
    case 'cronograma-view':
        if (class_exists('CronogramaController')) {
            $controller = new CronogramaController();
            $controller->view();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;
        
    case 'cronograma-delete':
        if (class_exists('CronogramaController')) {
            $controller = new CronogramaController();
            $controller->delete();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;
        
    case 'cronograma-por-plano':
        if (class_exists('CronogramaController')) {
            $controller = new CronogramaController();
            $controller->porPlano();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;

    // Rotas de relatórios
    case 'relatorios':
        if (class_exists('RelatorioController')) {
            $controller = new RelatorioController();
            $controller->index();
        } else {
            $controller = new UserController();
            $controller->dashboard();
        }
        break;

    case 'relatorio-word':
        if (class_exists('RelatorioController')) {
            $controller = new RelatorioController();
            $controller->exportarWord();
        } else {
            echo json_encode(['success' => false, 'message' => 'Controller não encontrado']);
        }
        break;

    case 'relatorio-pdf':
        if (class_exists('RelatorioController')) {
            $controller = new RelatorioController();
            $controller->exportarPdf();
        } else {
            echo json_encode(['success' => false, 'message' => 'Controller não encontrado']);
        }
        break;

    // Página padrão
    default:
        $controller = new UserController();
        $controller->login();
        break;
}
