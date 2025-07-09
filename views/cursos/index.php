<div class="mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Gestão de Cursos</h1>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Lista de Cursos</h2>
        <a href="index.php?page=curso-create" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i> Novo Curso
        </a>
    </div>

    <?php if(isset($mensagem)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded">
            <p><?php echo $mensagem; ?></p>
        </div>
    <?php endif; ?>

    <!-- Filtros de Busca -->
    <div class="mb-6">
        <form action="index.php" method="GET" class="flex flex-wrap gap-4">
            <input type="hidden" name="page" value="cursos">
            
            <div class="flex-1">
                <input 
                    type="text" 
                    name="busca" 
                    placeholder="Buscar por nome..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                    value="<?php echo isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : ''; ?>"
                >
            </div>
            
            <div class="w-full md:w-auto">
                <select 
                    name="status" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                >
                    <option value="">Todos os status</option>
                    <option value="ativo" <?php echo (isset($_GET['status']) && $_GET['status'] == 'ativo') ? 'selected' : ''; ?>>Ativo</option>
                    <option value="inativo" <?php echo (isset($_GET['status']) && $_GET['status'] == 'inativo') ? 'selected' : ''; ?>>Inativo</option>
                </select>
            </div>
            
            <div class="flex space-x-2">
                <button type="submit" class="bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-4 py-2 rounded-md transition">
                    <i class="fas fa-search mr-2"></i> Filtrar
                </button>
                <a href="index.php?page=cursos" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md transition">
                    <i class="fas fa-redo mr-2"></i> Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela de Cursos -->
    <?php if(count($cursos) > 0): ?>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left text-gray-600">ID</th>
                    <th class="px-4 py-2 text-left text-gray-600">Nome</th>
                    <th class="px-4 py-2 text-left text-gray-600">Carga Horária</th>
                    <th class="px-4 py-2 text-left text-gray-600">Coordenador</th>
                    <th class="px-4 py-2 text-left text-gray-600">Status</th>
                    <th class="px-4 py-2 text-right text-gray-600">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cursos as $curso): ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-3"><?php echo $curso->id; ?></td>
                    <td class="px-4 py-3"><?php echo $curso->nome; ?></td>
                    <td class="px-4 py-3"><?php echo $curso->carga_horaria; ?> horas</td>
                    <td class="px-4 py-3">
                        <?php echo isset($curso->coordenador_nome) ? $curso->coordenador_nome : '-'; ?>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $curso->status == 'ativo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                            <?php echo ucfirst($curso->status); ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="index.php?page=curso-view&id=<?php echo $curso->id; ?>" class="text-blue-600 hover:text-blue-800" title="Visualizar">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="index.php?page=curso-edit&id=<?php echo $curso->id; ?>" class="text-yellow-600 hover:text-yellow-800" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="confirmarExclusao(<?php echo $curso->id; ?>)" class="text-red-600 hover:text-red-800" title="Excluir">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="bg-gray-50 rounded-lg p-8 text-center">
        <i class="fas fa-folder-open text-gray-400 text-5xl mb-4"></i>
        <p class="text-gray-500">Nenhum curso encontrado.</p>
        <?php if(isset($_GET['busca']) || isset($_GET['status'])): ?>
            <a href="index.php?page=cursos" class="text-indigo-600 hover:underline mt-2 inline-block">
                Limpar filtros
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Modal de Confirmação de Exclusão (usando JavaScript) -->
<script>
function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir este curso? Esta ação não pode ser desfeita.')) {
        window.location.href = 'index.php?page=curso-delete&id=' + id;
    }
}
</script>

<script>
    function openModalForm(type, id = null) {
        let url = '';
        let title = '';
        
        if (type === 'curso-form') {
            url = id ? `index.php?page=cursos&action=form&id=${id}` : 'index.php?page=cursos&action=form';
            title = id ? 'Editar Curso' : 'Novo Curso';
        }
        
        // Mostrar spinner de carregamento
        showLoading();
        
        // Carregar formulário via AJAX
        fetch(url)
            .then(response => response.text())
            .then(html => {
                // Criar conteúdo do modal
                const modalContent = `
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">${title}</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    ${html}
                `;
                
                // Abrir modal com o formulário
                openModal(modalContent);
                hideLoading();
            })
            .catch(error => {
                console.error('Erro ao carregar formulário:', error);
                hideLoading();
                showNotification('Erro ao carregar formulário', 'error');
            });
    }
    
    function deleteCurso(id) {
        if (confirm('Tem certeza que deseja excluir este curso?')) {
            showLoading();
            
            fetch(`index.php?page=cursos&action=delete&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    
                    if (data.success) {
                        showNotification('Curso excluído com sucesso!', 'success');
                        // Recarregar página após sucesso
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showNotification(data.message || 'Erro ao excluir curso', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Erro:', error);
                    showNotification('Erro ao processar solicitação', 'error');
                });
        }
    }
    
    function searchCursos() {
        const searchTerm = document.getElementById('search-curso').value.trim();
        if (!searchTerm) return;
        
        showLoading();
        
        fetch(`index.php?page=cursos&action=search&term=${encodeURIComponent(searchTerm)}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('cursos-table-body').innerHTML = html;
                hideLoading();
            })
            .catch(error => {
                console.error('Erro na busca:', error);
                hideLoading();
                showNotification('Erro ao buscar cursos', 'error');
            });
    }
</script>
