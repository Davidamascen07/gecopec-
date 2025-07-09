<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' . APP_NAME : APP_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #10b981;
            --dark: #1e293b;
            --light: #f8fafc;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f1f5f9;
        }
        
        /* Estilos específicos para login */
        <?php if(!isset($_GET['page']) || $_GET['page'] == 'login'): ?>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        .input-field:focus + label, .input-field:not(:placeholder-shown) + label {
            transform: translateY(-24px) scale(0.85);
            color: #6366f1;
        }
        
        .password-toggle:hover {
            color: #6366f1;
        }
        <?php else: ?>
        /* Estilos para o painel */
        .sidebar {
            transition: all 0.3s ease;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .sidebar.collapsed .nav-text {
            display: none;
        }
        
        .sidebar.collapsed .logo-text {
            display: none;
        }
        
        .sidebar.collapsed .nav-item {
            justify-content: center;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .progress-bar {
            height: 6px;
            border-radius: 3px;
            background-color: #e2e8f0;
        }
        
        .progress-bar-fill {
            height: 100%;
            border-radius: 3px;
            background-color: var(--primary);
            transition: width 0.3s ease;
        }
        
        .timeline {
            position: relative;
            padding-left: 50px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #e2e8f0;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }
        
        .timeline-dot {
            position: absolute;
            left: -40px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: var(--primary);
            border: 3px solid white;
        }
        
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        
        .animate-bounce {
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% {
                transform: translateY(-5%);
                animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
            }
            50% {
                transform: translateY(0);
                animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
            }
        }

        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            background-color: #fff;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        <?php endif; ?>
    </style>
</head>
<?php if(!isset($_GET['page']) || $_GET['page'] == 'login'): ?>
<body>
<?php else: ?>
<body class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar bg-white text-gray-800 w-64 shadow-lg flex flex-col">
        <div class="p-4 flex items-center space-x-3 border-b border-gray-200">
            <div class="bg-indigo-600 text-white p-2 rounded-lg">
                <i class="fas fa-graduation-cap text-xl"></i>
            </div>
            <h1 class="logo-text text-xl font-bold text-indigo-600"><?php echo APP_NAME; ?></h1>
        </div>
        
        <div class="flex-1 overflow-y-auto py-4">
            <div class="px-4 mb-6">
                <div class="bg-indigo-50 rounded-lg p-3 flex items-center space-x-3">
                    <div class="bg-indigo-100 p-2 rounded-full">
                        <i class="fas fa-user text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium"><?php echo isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : 'Usuário'; ?></p>
                        <p class="text-xs text-gray-500"><?php echo isset($_SESSION['usuario_tipo']) ? ucfirst($_SESSION['usuario_tipo']) : 'Perfil'; ?></p>
                    </div>
                </div>
            </div>
            
            <nav>
                <ul class="space-y-1 px-2" id="navigation-menu">
                    <li>
                        <a href="index.php?page=dashboard" class="nav-item flex items-center space-x-3 px-4 py-3 <?php echo $page == 'dashboard' ? 'text-indigo-600 bg-indigo-50 font-medium' : 'text-gray-600 hover:bg-gray-100'; ?> rounded-lg">
                            <i class="fas fa-home"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=disciplinas" class="nav-item flex items-center space-x-3 px-4 py-3 <?php echo $page == 'disciplinas' ? 'text-indigo-600 bg-indigo-50 font-medium' : 'text-gray-600 hover:bg-gray-100'; ?> rounded-lg">
                            <i class="fas fa-book"></i>
                            <span class="nav-text">Disciplinas</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=cursos" class="nav-item flex items-center space-x-3 px-4 py-3 <?php echo $page == 'cursos' ? 'text-indigo-600 bg-indigo-50 font-medium' : 'text-gray-600 hover:bg-gray-100'; ?> rounded-lg">
                            <i class="fas fa-graduation-cap"></i>
                            <span class="nav-text">Cursos</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=professores" class="nav-item flex items-center space-x-3 px-4 py-3 <?php echo $page == 'professores' ? 'text-indigo-600 bg-indigo-50 font-medium' : 'text-gray-600 hover:bg-gray-100'; ?> rounded-lg">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span class="nav-text">Professores</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=alunos" class="nav-item flex items-center space-x-3 px-4 py-3 <?php echo $page == 'alunos' ? 'text-indigo-600 bg-indigo-50 font-medium' : 'text-gray-600 hover:bg-gray-100'; ?> rounded-lg">
                            <i class="fas fa-user-graduate"></i>
                            <span class="nav-text">Alunos</span>
                        </a>
                    </li>
                    <?php if(isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] != 'professor'): ?>
                    <li>
                        <a href="index.php?page=turmas" class="nav-item flex items-center space-x-3 px-4 py-3 <?php echo $page == 'turmas' ? 'text-indigo-600 bg-indigo-50 font-medium' : 'text-gray-600 hover:bg-gray-100'; ?> rounded-lg">
                            <i class="fas fa-users"></i>
                            <span class="nav-text">Turmas</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="index.php?page=cronogramas" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 
                                  <?php echo ($page == 'cronogramas' || strpos($page, 'cronograma-') === 0) ? 'bg-gray-100 font-medium' : ''; ?>">
                            <i class="fas fa-calendar-alt mr-2"></i> Cronogramas
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=planos-ensino" class="nav-item flex items-center space-x-3 px-4 py-3 <?php echo $page == 'planos-ensino' ? 'text-indigo-600 bg-indigo-50 font-medium' : 'text-gray-600 hover:bg-gray-100'; ?> rounded-lg">
                            <i class="fas fa-file-alt"></i>
                            <span class="nav-text">Planos de Ensino</span>
                        </a>
                    </li>
                    <?php if(isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] != 'professor'): ?>
                    <li>
                        <a href="index.php?page=relatorios" class="nav-item flex items-center space-x-3 px-4 py-3 <?php echo $page == 'relatorios' ? 'text-indigo-600 bg-indigo-50 font-medium' : 'text-gray-600 hover:bg-gray-100'; ?> rounded-lg">
                            <i class="fas fa-chart-bar"></i>
                            <span class="nav-text">Relatórios</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        
        <div class="p-4 border-t border-gray-200">
            <a href="index.php?page=logout" class="flex items-center space-x-2 text-gray-600 hover:text-indigo-600">
                <i class="fas fa-sign-out-alt"></i>
                <span>Sair</span>
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between px-6 py-3">
                <div class="flex items-center space-x-4">
                    <button id="sidebarToggle" class="text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800"><?php echo isset($title) ? $title : 'Dashboard'; ?></h2>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative dropdown">
                        <button class="flex items-center space-x-2">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-user text-indigo-600"></i>
                            </div>
                            <span class="hidden md:inline"><?php echo isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : 'Usuário'; ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                            <a href="index.php?page=perfil" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
                            <a href="index.php?page=configuracoes" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Configurações</a>
                            <div class="border-t border-gray-200"></div>
                            <a href="index.php?page=logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sair</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Dynamic Content Area -->
        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
<?php endif; ?>
