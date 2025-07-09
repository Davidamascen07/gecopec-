<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Gerenciar Professores</h1>
            <a href="index.php?page=professor-create" class="flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Novo Professor
            </a>
        </div>

        <?php if (isset($mensagem) && $mensagem): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        </div>
        <?php endif; ?>

        <!-- Filtros -->
        <div class="mb-6">
            <h2 class="text-sm font-medium text-gray-600 mb-2">Buscar por nome, matrícula ou email</h2>
            <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-4">
                <div class="flex-1">
                    <input 
                        type="text" 
                        id="search-professor" 
                        placeholder="Digite o nome, matrícula ou email..." 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        value="<?php echo htmlspecialchars($filtros['busca'] ?? ''); ?>"
                    >
                </div>
                <div>
                    <select id="departamento-filter" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Todos os departamentos</option>
                        <?php if (isset($departamentos) && is_array($departamentos)): ?>
                            <?php foreach ($departamentos as $departamento): ?>
                                <option value="<?php echo htmlspecialchars($departamento); ?>" <?php echo (isset($filtros['departamento']) && $filtros['departamento'] == $departamento) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($departamento); ?>
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
        <?php if (empty($professores)): ?>
            <div class="text-center py-16 flex flex-col items-center justify-center">
                <div class="bg-gray-100 p-6 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Nenhum professor encontrado</h2>
                <p class="text-gray-600 mb-6">Não há professores cadastrados ou que atendam aos filtros aplicados.</p>
                <a href="index.php?page=professor-create" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Cadastrar primeiro professor
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-hidden border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Professor</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matrícula</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departamento</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contato</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="professores-table-body">
                        <?php foreach ($professores as $professor): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                <?php echo strtoupper(substr($professor['nome'], 0, 2)); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($professor['nome']); ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?php echo htmlspecialchars($professor['email']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-sm rounded-lg font-mono">
                                    <?php echo htmlspecialchars($professor['matricula']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php echo htmlspecialchars($professor['departamento'] ?? 'Não informado'); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php echo htmlspecialchars($professor['telefone'] ?? 'Não informado'); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($professor['status'] == 'ativo'): ?>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Ativo</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="index.php?page=professor-view&id=<?php echo $professor['id']; ?>" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="index.php?page=professor-edit&id=<?php echo $professor['id']; ?>" 
                                   class="text-amber-600 hover:text-amber-900 mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                                <a href="#" onclick="confirmDelete(<?php echo $professor['id']; ?>); return false;" 
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
                Mostrando <?php echo count($professores); ?> professor(es)
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function applyFilters() {
    const busca = document.getElementById('search-professor').value;
    const departamento = document.getElementById('departamento-filter').value;
    const status = document.getElementById('status-filter').value;
    
    let url = 'index.php?page=professores';
    
    if (busca) {
        url += '&busca=' + encodeURIComponent(busca);
    }
    
    if (departamento) {
        url += '&departamento=' + encodeURIComponent(departamento);
    }
    
    if (status) {
        url += '&status=' + encodeURIComponent(status);
    }
    
    window.location.href = url;
}

function clearFilters() {
    window.location.href = 'index.php?page=professores';
}

function confirmDelete(id) {
    if (confirm('Tem certeza que deseja excluir este professor?')) {
        window.location.href = 'index.php?page=professor-delete&id=' + id;
    }
}

// Adiciona evento de pressionar Enter no campo de busca
document.getElementById('search-professor').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});
</script>
