<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800"><?php echo $cronograma->encontro_numero; ?>º ENCONTRO – <?php echo date('d/m/Y', strtotime($cronograma->data_encontro)); ?></h2>
        <div class="flex space-x-3">
            <a href="index.php?page=cronogramas" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
            <a href="index.php?page=cronograma-edit&id=<?php echo $cronograma->id; ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i> Editar
            </a>
        </div>
    </div>

    <!-- Informações Básicas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gray-50 rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Disciplina</h3>
            <p class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($cronograma->disciplina_nome); ?></p>
            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($cronograma->disciplina_codigo ?? ''); ?></p>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Professor</h3>
            <p class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($cronograma->professor_nome); ?></p>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Curso</h3>
            <p class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($cronograma->curso_nome); ?></p>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Data do Encontro</h3>
            <p class="text-lg font-medium text-gray-800">
                <?php echo date('d/m/Y', strtotime($cronograma->data_encontro)); ?>
            </p>
        </div>
    </div>

    <!-- Conteúdo Detalhado do Encontro -->
    <div class="space-y-6">
        <!-- Estrutura como no formato padrão -->
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6">
            <div class="space-y-3">
                <div>
                    <span class="font-semibold text-blue-800">1) Assunto:</span>
                    <span class="ml-2 text-gray-700"><?php echo htmlspecialchars($cronograma->assunto); ?></span>
                </div>
                
                <div>
                    <span class="font-semibold text-blue-800">2) Conteúdo:</span>
                    <span class="ml-2 text-gray-700"><?php echo htmlspecialchars($cronograma->conteudo); ?></span>
                </div>
                
                <div>
                    <span class="font-semibold text-blue-800">3) Atividade:</span>
                    <span class="ml-2 text-gray-700"><?php echo htmlspecialchars($cronograma->atividade); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Metodologia -->
        <?php if (!empty($cronograma->metodologia)): ?>
        <div class="bg-gray-50 rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Metodologia</h3>
            <div class="prose max-w-none text-gray-700">
                <?php echo nl2br(htmlspecialchars($cronograma->metodologia)); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Recursos -->
        <?php if (!empty($cronograma->recursos)): ?>
        <div class="bg-gray-50 rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Recursos Didáticos</h3>
            <div class="prose max-w-none text-gray-700">
                <?php echo nl2br(htmlspecialchars($cronograma->recursos)); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Observações -->
        <?php if (!empty($cronograma->observacoes)): ?>
        <div class="bg-gray-50 rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Observações</h3>
            <div class="prose max-w-none text-gray-700">
                <?php echo nl2br(htmlspecialchars($cronograma->observacoes)); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Informações adicionais -->
    <div class="mt-8 text-sm text-gray-500 border-t pt-4">
        <p>Data de criação: <?php echo date('d/m/Y H:i', strtotime($cronograma->created_at)); ?></p>
        <?php if ($cronograma->updated_at != $cronograma->created_at): ?>
        <p>Última atualização: <?php echo date('d/m/Y H:i', strtotime($cronograma->updated_at)); ?></p>
        <?php endif; ?>
    </div>
</div>
