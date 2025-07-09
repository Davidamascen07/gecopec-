<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Plano de Ensino</h1>
            <a href="index.php?page=planos-ensino" class="flex items-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Voltar
            </a>
        </div>

        <!-- Informações Básicas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Disciplina</h3>
                <p class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($plano->disciplina_nome ?? 'N/A'); ?></p>
                <p class="text-sm text-gray-600"><?php echo htmlspecialchars($plano->disciplina_codigo ?? ''); ?></p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Professor</h3>
                <p class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($plano->professor_nome ?? 'N/A'); ?></p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Período</h3>
                <p class="text-lg font-medium text-gray-900"><?php echo ($plano->ano ?? '') . '/' . ($plano->semestre ?? ''); ?></p>
                <?php 
                $statusColors = [
                    'pendente' => 'bg-yellow-100 text-yellow-800',
                    'aprovado' => 'bg-green-100 text-green-800',
                    'rejeitado' => 'bg-red-100 text-red-800'
                ];
                $colorClass = $statusColors[$plano->status] ?? 'bg-gray-100 text-gray-800';
                ?>
                <span class="inline-block mt-1 px-2 py-1 <?php echo $colorClass; ?> rounded-full text-xs">
                    <?php echo ucfirst($plano->status); ?>
                </span>
            </div>
        </div>

        <!-- Conteúdo do Plano -->
        <div class="space-y-6">
            <!-- Objetivos Gerais -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Objetivos Gerais</h3>
                <div class="prose max-w-none text-gray-700">
                    <?php echo !empty($plano->objetivos_gerais) ? nl2br(htmlspecialchars($plano->objetivos_gerais)) : '<em class="text-gray-400">Não definido</em>'; ?>
                </div>
            </div>

            <!-- Objetivos Específicos -->
            <?php if (!empty($plano->objetivos_especificos)): ?>
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Objetivos Específicos</h3>
                <div class="prose max-w-none text-gray-700">
                    <?php echo nl2br(htmlspecialchars($plano->objetivos_especificos)); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Metodologia -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Metodologia</h3>
                <div class="prose max-w-none text-gray-700">
                    <?php echo !empty($plano->metodologia) ? nl2br(htmlspecialchars($plano->metodologia)) : '<em class="text-gray-400">Não definida</em>'; ?>
                </div>
            </div>

            <!-- Avaliação -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Avaliação</h3>
                <div class="prose max-w-none text-gray-700">
                    <?php echo !empty($plano->avaliacao) ? nl2br(htmlspecialchars($plano->avaliacao)) : '<em class="text-gray-400">Não definida</em>'; ?>
                </div>
            </div>

            <!-- Bibliografia -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php if (!empty($plano->bibliografia_basica)): ?>
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Bibliografia Básica</h3>
                    <div class="prose max-w-none text-gray-700">
                        <?php echo nl2br(htmlspecialchars($plano->bibliografia_basica)); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($plano->bibliografia_complementar)): ?>
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Bibliografia Complementar</h3>
                    <div class="prose max-w-none text-gray-700">
                        <?php echo nl2br(htmlspecialchars($plano->bibliografia_complementar)); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Cronograma -->
            <?php if (!empty($plano->cronograma_detalhado)): ?>
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Cronograma Detalhado</h3>
                <div class="prose max-w-none text-gray-700">
                    <?php echo nl2br(htmlspecialchars($plano->cronograma_detalhado)); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Observações -->
            <?php if (!empty($plano->observacoes)): ?>
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Observações</h3>
                <div class="prose max-w-none text-gray-700">
                    <?php echo nl2br(htmlspecialchars($plano->observacoes)); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Informações de Criação -->
        <div class="mt-8 text-sm text-gray-500 border-t pt-4">
            <?php if (!empty($plano->created_at)): ?>
                <p>Criado em: <?php echo date('d/m/Y H:i', strtotime($plano->created_at)); ?></p>
            <?php endif; ?>
            <?php if (!empty($plano->updated_at) && $plano->updated_at !== $plano->created_at): ?>
                <p>Última atualização: <?php echo date('d/m/Y H:i', strtotime($plano->updated_at)); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
