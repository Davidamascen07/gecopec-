<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Gerenciar Disciplinas</h1>
            <a href="index.php?page=disciplina-create" class="flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Nova Disciplina
            </a>
        </div>

        <?php if (isset($mensagem) && $mensagem): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        </div>
        <?php endif; ?>

        <!-- Filtros -->
        <div class="mb-6">
            <h2 class="text-sm font-medium text-gray-600 mb-2">Buscar por nome ou código</h2>
            <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-4">
                <div class="flex-1">
                    <input 
                        type="text" 
                        id="search-disciplina" 
                        placeholder="Digite o nome ou código..." 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        value="<?php echo htmlspecialchars($filtros['busca'] ?? ''); ?>"
                    >
                </div>
                <div>
                    <select id="curso-filter" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Todos os cursos</option>
                        <?php if (isset($cursos) && is_array($cursos)): ?>
                            <?php foreach ($cursos as $curso): ?>
                                <option value="<?php echo $curso->id; ?>" <?php echo (isset($filtros['curso_id']) && $filtros['curso_id'] == $curso->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($curso->nome); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div>
                    <select id="status-filter" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        <option value="ativo" <?php echo (isset($filtros['status']) && $filtros['status'] == 'ativo') ? 'selected' : ''; ?>>Ativo</option>
                        <option value="inativo" <?php echo (isset($filtros['status']) && $filtros['status'] == 'inativo') ? 'selected' : ''; ?>>Inativo</option>
                    </select>
                </div>
                <div>
                    <button 
                        onclick="applyFilters()" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg inline-flex items-center transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filtrar
                    </button>
                    <button 
                        onclick="clearFilters()" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg inline-flex items-center ml-2 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <?php if (empty($disciplinas)): ?>
            <div class="text-center py-16 flex flex-col items-center justify-center">
                <div class="bg-gray-100 p-6 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Nenhuma disciplina encontrada</h2>
                <p class="text-gray-600 mb-6">Não há disciplinas cadastradas ou que atendam aos filtros aplicados.</p>
                <a href="index.php?page=disciplina-create" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Cadastrar primeira disciplina
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-hidden border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carga Horária</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="disciplinas-table-body">
                        <?php foreach ($disciplinas as $disciplina): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-sm rounded-lg font-mono">
                                    <?php echo htmlspecialchars($disciplina->codigo); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">
                                    <?php echo htmlspecialchars($disciplina->nome); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php echo htmlspecialchars($disciplina->curso_nome ?? 'Sem curso'); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    <?php echo $disciplina->carga_horaria; ?> horas
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($disciplina->status == 'ativo'): ?>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Ativo</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="index.php?page=disciplina-view&id=<?php echo $disciplina->id; ?>" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="index.php?page=disciplina-edit&id=<?php echo $disciplina->id; ?>" 
                                   class="text-amber-600 hover:text-amber-900 mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                                <a href="#" onclick="confirmDelete(<?php echo $disciplina->id; ?>); return false;" 
                                   class="text-red-600 hover:text-red-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                Mostrando <?php echo count($disciplinas); ?> disciplina(s)
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function applyFilters() {
    const busca = document.getElementById('search-disciplina').value;
    const curso = document.getElementById('curso-filter').value;
    const status = document.getElementById('status-filter').value;
    
    let url = 'index.php?page=disciplinas';
    
    if (busca) {
        url += '&busca=' + encodeURIComponent(busca);
    }
    
    if (curso) {
        url += '&curso_id=' + encodeURIComponent(curso);
    }
    
    if (status) {
        url += '&status=' + encodeURIComponent(status);
    }
    
    window.location.href = url;
}

function clearFilters() {
    window.location.href = 'index.php?page=disciplinas';
}

function confirmDelete(id) {
    if (confirm('Tem certeza que deseja excluir esta disciplina?')) {
        window.location.href = 'index.php?page=disciplina-delete&id=' + id;
    }
}

// Adiciona evento de pressionar Enter no campo de busca
document.getElementById('search-disciplina').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});
</script>
