<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">
            <?php echo isset($cronograma) ? 'Editar Encontro' : 'Novo Encontro'; ?>
        </h2>
        <a href="index.php?page=cronogramas" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Voltar
        </a>
    </div>

    <?php if(isset($erros) && count($erros) > 0): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 rounded">
            <ul class="list-disc pl-5">
                <?php foreach($erros as $erro): ?>
                    <li><?php echo $erro; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="index.php?page=<?php echo isset($cronograma) ? 'cronograma-update' : 'cronograma-store'; ?>" method="POST" class="space-y-6">
        <?php if(isset($cronograma)): ?>
            <input type="hidden" name="id" value="<?php echo $cronograma->id; ?>">
        <?php endif; ?>

        <!-- Informações Básicas -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Informações do Encontro</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Plano de Ensino -->
                <div class="md:col-span-2">
                    <label for="plano_ensino_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Plano de Ensino <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="plano_ensino_id" 
                        name="plano_ensino_id"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">Selecione um plano de ensino</option>
                        <?php foreach($planos as $plano): ?>
                            <option value="<?php echo $plano['id']; ?>" 
                                    <?php echo (isset($cronograma) && $cronograma->plano_ensino_id == $plano['id']) || 
                                              (isset($dados) && $dados['plano_ensino_id'] == $plano['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($plano['disciplina_nome']); ?> - 
                                <?php echo htmlspecialchars($plano['professor_nome']); ?> - 
                                <?php echo htmlspecialchars($plano['curso_nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Número do Encontro -->
                <div>
                    <label for="encontro_numero" class="block text-sm font-medium text-gray-700 mb-2">
                        Nº do Encontro <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="encontro_numero" 
                        name="encontro_numero" 
                        required
                        min="1"
                        max="100"
                        value="<?php echo isset($cronograma) ? $cronograma->encontro_numero : (isset($dados) ? $dados['encontro_numero'] : ''); ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Ex: 1"
                    >
                </div>

                <!-- Data do Encontro -->
                <div>
                    <label for="data_encontro" class="block text-sm font-medium text-gray-700 mb-2">
                        Data do Encontro <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="data_encontro" 
                        name="data_encontro" 
                        required
                        value="<?php echo isset($cronograma) ? $cronograma->data_encontro : (isset($dados) ? $dados['data_encontro'] : ''); ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                </div>
            </div>
        </div>

        <!-- Conteúdo do Encontro -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Conteúdo da Aula</h3>
            
            <!-- Assunto -->
            <div class="mb-4">
                <label for="assunto" class="block text-sm font-medium text-gray-700 mb-2">
                    Assunto <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="assunto" 
                    name="assunto" 
                    required
                    value="<?php echo isset($cronograma) ? htmlspecialchars($cronograma->assunto) : (isset($dados) ? htmlspecialchars($dados['assunto']) : ''); ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Ex: Engenharia de Requisitos, AP1, Feriado..."
                >
            </div>

            <!-- Conteúdo -->
            <div class="mb-4">
                <label for="conteudo" class="block text-sm font-medium text-gray-700 mb-2">
                    Conteúdo <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="conteudo"
                    name="conteudo"
                    rows="3" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Ex: Levantamento de Requisitos, Requisitos Funcionais e Não Funcionais..."
                ><?php echo isset($cronograma) ? htmlspecialchars($cronograma->conteudo) : (isset($dados) ? htmlspecialchars($dados['conteudo']) : ''); ?></textarea>
            </div>

            <!-- Atividade -->
            <div class="mb-4">
                <label for="atividade" class="block text-sm font-medium text-gray-700 mb-2">
                    Atividade <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="atividade" 
                    name="atividade" 
                    required
                    value="<?php echo isset($cronograma) ? htmlspecialchars($cronograma->atividade) : (isset($dados) ? htmlspecialchars($dados['atividade']) : ''); ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Ex: Aula expositiva e debates, Atividades Práticas, AP1..."
                >
            </div>
        </div>

        <!-- Informações Complementares -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Informações Complementares</h3>
            
            <!-- Metodologia -->
            <div class="mb-4">
                <label for="metodologia" class="block text-sm font-medium text-gray-700 mb-2">
                    Metodologia
                </label>
                <textarea 
                    id="metodologia"
                    name="metodologia"
                    rows="2" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Metodologia específica para este encontro..."
                ><?php echo isset($cronograma) ? htmlspecialchars($cronograma->metodologia ?? '') : (isset($dados) ? htmlspecialchars($dados['metodologia']) : ''); ?></textarea>
            </div>

            <!-- Recursos -->
            <div class="mb-4">
                <label for="recursos" class="block text-sm font-medium text-gray-700 mb-2">
                    Recursos Didáticos
                </label>
                <textarea 
                    id="recursos"
                    name="recursos"
               