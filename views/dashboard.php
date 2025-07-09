<section class="dashboard">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Bem-vindo ao GECOPEC</h1>
        <p class="text-gray-600">Sistema de Gestão de Cronogramas e Planos de Ensino</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 card-hover transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Disciplinas Ativas</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?php echo $disciplinasCount; ?></h3>
                </div>
                <div class="bg-indigo-100 p-3 rounded-full">
                    <i class="fas fa-book text-indigo-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: 75%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Disciplinas cadastradas</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 card-hover transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Planos Pendentes</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?php echo $planosPendentesCount; ?></h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-file-alt text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="progress-bar">
                    <div class="progress-bar-fill bg-yellow-500" style="width: <?php echo $planosPendentesCount > 0 ? '30%' : '0%'; ?>"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1"><?php echo $planosPendentesCount; ?> para revisão</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 card-hover transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Aprovações</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?php echo $planosAprovadosCount; ?></h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="progress-bar">
                    <div class="progress-bar-fill bg-green-500" style="width: <?php echo $percentualAprovados; ?>%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1"><?php echo $percentualAprovados; ?>% aprovados</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 card-hover transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Próximos Eventos</p>
                    <h3 class="text-2xl font-bold text-gray-800"><?php echo $proximosEventosCount; ?></h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="progress-bar">
                    <div class="progress-bar-fill bg-blue-500" style="width: <?php echo $proximosEventosCount > 0 ? '50%' : '0%'; ?>"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">próximos 7 dias</p>
            </div>
        </div>
    </div>

    <!-- Main Content Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Ações Rápidas</h3>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="index.php?page=plano-ensino-create" class="flex flex-col items-center justify-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                        <i class="fas fa-plus-circle text-indigo-600 text-2xl mb-2"></i>
                        <span class="text-sm text-gray-700">Novo Plano</span>
                    </a>
                    <a href="index.php?page=cronograma-create" class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <i class="fas fa-calendar-plus text-green-600 text-2xl mb-2"></i>
                        <span class="text-sm text-gray-700">Cronograma</span>
                    </a>
                    <a href="index.php?page=disciplina-create" class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <i class="fas fa-book text-blue-600 text-2xl mb-2"></i>
                        <span class="text-sm text-gray-700">Disciplina</span>
                    </a>
                    <a href="index.php?page=planos-ensino" class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <i class="fas fa-list text-purple-600 text-2xl mb-2"></i>
                        <span class="text-sm text-gray-700">Ver Planos</span>
                    </a>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Atividades Recentes</h3>
                </div>
                <div class="timeline">
                    <?php if (empty($atividadesRecentes)): ?>
                        <p class="text-gray-500 text-center py-4">Nenhuma atividade recente encontrada</p>
                    <?php else: ?>
                        <?php foreach($atividadesRecentes as $index => $atividade): ?>
                            <div class="timeline-item">
                                <div class="timeline-dot bg-<?php echo $atividade['tipo'] == 'plano' ? 'indigo' : 'green'; ?>-500"></div>
                                <div class="bg-<?php echo $atividade['tipo'] == 'plano' ? 'indigo' : 'green'; ?>-50 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-medium text-gray-800"><?php echo htmlspecialchars($atividade['descricao']); ?></p>
                                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($atividade['titulo']); ?></p>
                                        </div>
                                        <span class="text-xs text-gray-500">
                                            <?php 
                                            $data = new DateTime($atividade['data']);
                                            $agora = new DateTime();
                                            $diff = $data->diff($agora);
                                            
                                            if ($diff->d == 0) {
                                                if ($diff->h == 0) {
                                                    echo $diff->i . ' min atrás';
                                                } else {
                                                    echo $diff->h . ' hora' . ($diff->h > 1 ? 's' : '') . ' atrás';
                                                }
                                            } else if ($diff->d == 1) {
                                                echo 'Ontem, ' . $data->format('H:i');
                                            } else {
                                                echo $data->format('d/m/Y H:i');
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Informações do Usuário -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="bg-indigo-100 p-3 rounded-full">
                        <i class="fas fa-user text-indigo-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-800"><?php echo isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : 'Usuário'; ?></h3>
                        <p class="text-sm text-gray-500"><?php echo isset($_SESSION['usuario_tipo']) ? ucfirst($_SESSION['usuario_tipo']) : 'Perfil'; ?></p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mt-2">
                    <a href="index.php?page=perfil" class="flex items-center text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-user-cog mr-2"></i>
                        <span>Editar perfil</span>
                    </a>
                </div>
            </div>

            <?php if(isset($_SESSION['usuario_tipo']) && ($_SESSION['usuario_tipo'] == 'coordenador' || $_SESSION['usuario_tipo'] == 'administrador' || $_SESSION['usuario_tipo'] == 'admin')): ?>
            <!-- Aprovações Pendentes (apenas para coordenadores e administradores) -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Aprovações Pendentes</h3>
                    <?php if(!empty($planosPendentes)): ?>
                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full"><?php echo count($planosPendentes); ?> novos</span>
                    <?php endif; ?>
                </div>
                <div class="space-y-4">
                    <?php if(empty($planosPendentes)): ?>
                        <p class="text-gray-500 text-center py-2">Nenhum plano pendente de aprovação</p>
                    <?php else: ?>
                        <?php foreach($planosPendentes as $plano): ?>
                            <div class="flex items-start space-x-3" id="plano-<?php echo $plano['id']; ?>">
                                <div class="bg-indigo-100 p-2 rounded-full">
                                    <i class="fas fa-file-alt text-indigo-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800"><?php echo htmlspecialchars($plano['disciplina_nome']); ?></p>
                                    <p class="text-xs text-gray-500">Prof. <?php echo htmlspecialchars($plano['professor_nome']); ?></p>
                                    <div class="mt-2 flex space-x-2">
                                        <button 
                                            onclick="aprovarPlano(<?php echo $plano['id']; ?>)" 
                                            class="texto-xs bg-green-100 text-green-800 px-2 py-1 rounded hover:bg-green-200 transition-colors"
                                            id="btn-aprovar-<?php echo $plano['id']; ?>"
                                        >
                                            Aprovar
                                        </button>
                                        <button 
                                            onclick="rejeitarPlano(<?php echo $plano['id']; ?>)" 
                                            class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded hover:bg-red-200 transition-colors"
                                            id="btn-rejeitar-<?php echo $plano['id']; ?>"
                                        >
                                            Rejeitar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php if(!empty($planosPendentes)): ?>
                <a href="index.php?page=planos-ensino" class="w-full mt-4 block text-center text-indigo-600 hover:text-indigo-800 text-sm font-medium py-2 border border-dashed border-gray-300 rounded-lg hover:border-indigo-300">
                    Ver todas as solicitações
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Próximos Eventos -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Próximos Eventos</h3>
                    <a href="index.php?page=cronogramas" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Ver todos</a>
                </div>
                <div class="space-y-4">
                    <?php if(empty($proximosEventos)): ?>
                        <p class="text-gray-500 text-center py-2">Nenhum evento próximo encontrado</p>
                    <?php else: ?>
                        <?php foreach($proximosEventos as $evento): ?>
                            <div class="flex items-start space-x-3">
                                <div class="bg-purple-100 p-2 rounded-full">
                                    <i class="fas fa-calendar-day text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <a href="index.php?page=cronograma-view&id=<?php echo $evento['id']; ?>" class="text-sm font-medium text-gray-800 hover:text-indigo-600">
                                        <?php echo htmlspecialchars($evento['assunto']); ?>
                                    </a>
                                    <p class="text-xs text-gray-600"><?php echo htmlspecialchars($evento['disciplina_nome']); ?></p>
                                    <p class="text-xs text-gray-500">
                                        <?php 
                                            $data = new DateTime($evento['data_encontro']);
                                            echo $data->format('d/m/Y'); 
                                        ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scripts específicos do dashboard -->
<script>
// Função para aprovar plano de ensino
function aprovarPlano(planoId) {
    if (!confirm('Tem certeza que deseja aprovar este plano de ensino?')) {
        return;
    }
    
    // Desabilitar botões
    const btnAprovar = document.getElementById(`btn-aprovar-${planoId}`);
    const btnRejeitar = document.getElementById(`btn-rejeitar-${planoId}`);
    
    btnAprovar.disabled = true;
    btnAprovar.textContent = 'Aprovando...';
    btnRejeitar.disabled = true;
    
    // Fazer requisição AJAX
    const formData = new FormData();
    formData.append('id', planoId);
    
    fetch('index.php?page=plano-ensino-aprovar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar mensagem de sucesso
            showNotification('success', data.message);
            
            // Remover o plano da lista ou atualizar status
            const planoElement = document.getElementById(`plano-${planoId}`);
            if (planoElement) {
                planoElement.style.opacity = '0.5';
                planoElement.innerHTML = `
                    <div class="bg-green-100 p-2 rounded-full">
                        <i class="fas fa-check text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Plano Aprovado</p>
                        <p class="text-xs text-green-600">Aprovação realizada com sucesso</p>
                    </div>
                `;
                
                // Remover após 3 segundos
                setTimeout(() => {
                    planoElement.remove();
                    updatePendingCount();
                }, 3000);
            }
        } else {
            showNotification('error', data.message);
            // Reabilitar botões
            btnAprovar.disabled = false;
            btnAprovar.textContent = 'Aprovar';
            btnRejeitar.disabled = false;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('error', 'Erro ao processar solicitação');
        // Reabilitar botões
        btnAprovar.disabled = false;
        btnAprovar.textContent = 'Aprovar';
        btnRejeitar.disabled = false;
    });
}

// Função para rejeitar plano de ensino
function rejeitarPlano(planoId) {
    const observacao = prompt('Digite uma observação para a rejeição (opcional):');
    if (observacao === null) {
        return; // Usuário cancelou
    }
    
    // Desabilitar botões
    const btnAprovar = document.getElementById(`btn-aprovar-${planoId}`);
    const btnRejeitar = document.getElementById(`btn-rejeitar-${planoId}`);
    
    btnRejeitar.disabled = true;
    btnRejeitar.textContent = 'Rejeitando...';
    btnAprovar.disabled = true;
    
    // Fazer requisição AJAX
    const formData = new FormData();
    formData.append('id', planoId);
    formData.append('observacao', observacao);
    
    fetch('index.php?page=plano-ensino-rejeitar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            
            // Remover o plano da lista ou atualizar status
            const planoElement = document.getElementById(`plano-${planoId}`);
            if (planoElement) {
                planoElement.style.opacity = '0.5';
                planoElement.innerHTML = `
                    <div class="bg-red-100 p-2 rounded-full">
                        <i class="fas fa-times text-red-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Plano Rejeitado</p>
                        <p class="text-xs text-red-600">Rejeição realizada com sucesso</p>
                    </div>
                `;
                
                // Remover após 3 segundos
                setTimeout(() => {
                    planoElement.remove();
                    updatePendingCount();
                }, 3000);
            }
        } else {
            showNotification('error', data.message);
            // Reabilitar botões
            btnRejeitar.disabled = false;
            btnRejeitar.textContent = 'Rejeitar';
            btnAprovar.disabled = false;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('error', 'Erro ao processar solicitação');
        // Reabilitar botões
        btnRejeitar.disabled = false;
        btnRejeitar.textContent = 'Rejeitar';
        btnAprovar.disabled = false;
    });
}

// Função para mostrar notificações
function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Remover após 5 segundos
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

// Função para atualizar contador de pendentes
function updatePendingCount() {
    const pendingElements = document.querySelectorAll('[id^="plano-"]');
    const count = pendingElements.length;
    
    // Atualizar badge se existir
    const badge = document.querySelector('.bg-red-100.text-red-800');
    if (badge && count === 0) {
        badge.textContent = '0 novos';
    } else if (badge) {
        badge.textContent = `${count} novos`;
    }
    
    // Atualizar card de planos pendentes
    const cardCount = document.querySelector('.text-2xl.font-bold.text-gray-800');
    if (cardCount) {
        // Buscar o card que contém "Planos Pendentes"
        const pendingCard = Array.from(document.querySelectorAll('.text-gray-500.text-sm'))
            .find(el => el.textContent.includes('Planos Pendentes'));
        
        if (pendingCard) {
            const countElement = pendingCard.parentElement.querySelector('.text-2xl.font-bold.text-gray-800');
            if (countElement) {
                countElement.textContent = count;
            }
        }
    }
}
</script>
