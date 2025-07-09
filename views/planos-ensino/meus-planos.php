<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Meus Planos de Ensino</h1>
            <a href="index.php?page=plano-ensino-create" class="flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Novo Plano de Ensino
            </a>
        </div>

        <?php if (isset($mensagem) && $mensagem): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        </div>
        <?php endif; ?>

        <!-- Conteúdo Principal -->
        <?php if (empty($planos)): ?>
            <div class="text-center py-16 flex flex-col items-center justify-center">
                <div class="bg-gray-100 p-6 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Nenhum plano de ensino encontrado</h2>
                <p class="text-gray-600 mb-6">Você ainda não criou nenhum plano de ensino.</p>
                <a href="index.php?page=plano-ensino-create" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Criar primeiro plano
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($planos as $plano): ?>
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                <?php echo htmlspecialchars($plano['disciplina_nome'] ?? 'N/A'); ?>
                            </h3>
                            <p class="text-sm text-gray-600 mb-2">
                                <?php echo htmlspecialchars($plano['disciplina_codigo'] ?? ''); ?>
                            </p>
                        </div>
                        <?php 
                        $statusColors = [
                            'pendente' => 'bg-yellow-100 text-yellow-800',
                            'aprovado' => 'bg-green-100 text-green-800',
                            'rejeitado' => 'bg-red-100 text-red-800'
                        ];
                        $colorClass = $statusColors[$plano['status']] ?? 'bg-gray-100 text-gray-800';
                        ?>
                        <span class="px-2 py-1 <?php echo $colorClass; ?> rounded-full text-xs">
                            <?php echo ucfirst($plano['status']); ?>
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-1">
                            <strong>Curso:</strong> <?php echo htmlspecialchars($plano['curso_nome'] ?? 'N/A'); ?>
                        </p>
                        <p class="text-sm text-gray-600">
                            <strong>Período:</strong> <?php echo ($plano['ano'] ?? '') . '/' . ($plano['semestre'] ?? ''); ?>
                        </p>
                    </div>
                    
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">
                                <?php echo !empty($plano['created_at']) ? date('d/m/Y', strtotime($plano['created_at'])) : 'Data N/A'; ?>
                            </span>
                            <a href="index.php?page=plano-ensino-view&id=<?php echo $plano['id']; ?>" 
                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                Ver detalhes →
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-6 text-sm text-gray-600">
                Mostrando <?php echo count($planos); ?> plano(s) de ensino
            </div>
        <?php endif; ?>
    </div>
</div>
