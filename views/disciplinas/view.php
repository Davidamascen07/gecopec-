<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800"><?php echo htmlspecialchars($disciplina->nome); ?></h1>
                <p class="text-gray-500 mt-1">
                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-sm rounded-lg font-mono">
                        <?php echo htmlspecialchars($disciplina->codigo); ?>
                    </span>
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="index.php?page=disciplina-edit&id=<?php echo $disciplina->id; ?>" 
                   class="flex items-center bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Editar
                </a>
                <a href="index.php?page=disciplinas" 
                   class="flex items-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Voltar
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Coluna Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informações da Disciplina -->
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-800">Informações da Disciplina</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Código</dt>
                                <dd class="mt-1 text-gray-900">
                                    <code class="px-2 py-1 bg-gray-100 rounded text-sm font-mono">
                                        <?php echo htmlspecialchars($disciplina->codigo); ?>
                                    </code>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Carga Horária</dt>
                                <dd class="mt-1 text-gray-900">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        <?php echo $disciplina->carga_horaria; ?> horas
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Curso</dt>
                                <dd class="mt-1 text-gray-900">
                                    <?php echo htmlspecialchars($disciplina->curso_nome ?? 'Não informado'); ?>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-gray-900">
                                    <?php if ($disciplina->status == 'ativo'): ?>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Ativo</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Inativo</span>
                                    <?php endif; ?>
                                </dd>
                            </div>
                        </dl>

                        <?php if (!empty($disciplina->ementa)): ?>
                            <div class="mt-6">
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Ementa</h3>
                                <div class="text-gray-700 bg-gray-50 p-4 rounded-lg text-sm">
                                    <?php echo nl2br(htmlspecialchars($disciplina->ementa)); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($disciplina->prerequisitos)): ?>
                            <div class="mt-6">
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Pré-requisitos</h3>
                                <div class="text-gray-700 bg-gray-50 p-4 rounded-lg text-sm">
                                    <?php echo nl2br(htmlspecialchars($disciplina->prerequisitos)); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Turmas da Disciplina -->
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-800">Turmas</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <?php echo count($turmas); ?> turma(s)
                        </span>
                    </div>
                    <div class="p-6">
                        <?php if (empty($turmas)): ?>
                            <div class="text-center py-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma turma cadastrada</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Esta disciplina ainda não possui turmas cadastradas.
                                </p>
                                <div class="mt-6">
                                    <a href="index.php?page=turma-create&disciplina_id=<?php echo $disciplina->id; ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        Nova Turma
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="overflow-hidden border border-gray-200 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome da Turma</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Professor</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($turmas as $turma): ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="font-medium text-gray-900">
                                                        <?php echo htmlspecialchars($turma->nome); ?>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-gray-900">
                                                        <?php echo htmlspecialchars($turma->professor_nome ?? 'Não definido'); ?>
                                                    </div>
                                                    <?php if (!empty($turma->professor_matricula)): ?>
                                                        <div class="text-gray-500 text-sm">
                                                            <?php echo htmlspecialchars($turma->professor_matricula); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-gray-900">
                                                        <?php echo htmlspecialchars($turma->semestre); ?>/<?php echo $turma->ano; ?>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <?php if ($turma->status == 'ativo'): ?>
                                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Ativa</span>
                                                    <?php else: ?>
                                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Inativa</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="index.php?page=turma-view&id=<?php echo $turma->id; ?>" 
                                                       class="text-blue-600 hover:text-blue-900">Ver</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <a href="index.php?page=turma-create&disciplina_id=<?php echo $disciplina->id; ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Nova Turma
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Coluna Lateral -->
            <div class="space-y-6">
                <!-- Ações Rápidas -->
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-800">Ações Rápidas</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <a href="index.php?page=disciplina-edit&id=<?php echo $disciplina->id; ?>" 
                           class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Editar Disciplina
                        </a>
                        <a href="index.php?page=turma-create&disciplina_id=<?php echo $disciplina->id; ?>" 
                           class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                            </svg>
                            Nova Turma
                        </a>
                        <a href="index.php?page=plano-ensino-create&disciplina_id=<?php echo $disciplina->id; ?>" 
                           class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                            </svg>
                            Novo Plano de Ensino
                        </a>
                        <button onclick="confirmDelete(<?php echo $disciplina->id; ?>)" 
                                class="w-full flex items-center justify-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Excluir Disciplina
                        </button>
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-800">Estatísticas</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-2 gap-4 text-center">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dt class="text-sm font-medium text-gray-500">Turmas</dt>
                                <dd class="mt-1 text-3xl font-semibold text-indigo-600"><?php echo count($turmas); ?></dd>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dt class="text-sm font-medium text-gray-500">Carga Horária</dt>
                                <dd class="mt-1 text-3xl font-semibold text-green-600"><?php echo $disciplina->carga_horaria; ?><span class="text-sm">h</span></dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Tem certeza que deseja excluir esta disciplina? Esta ação não pode ser desfeita.')) {
        window.location.href = 'index.php?page=disciplina-delete&id=' + id;
    }
}
</script>
