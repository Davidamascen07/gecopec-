<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Cronograma de Encontros</h2>
        <a href="index.php?page=cronograma-create" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i> Novo Encontro
        </a>
    </div>

    <?php if(isset($mensagem)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded">
            <p><?php echo $mensagem; ?></p>
        </div>
    <?php endif; ?>

    <!-- Filtros de Busca -->
    <div class="mb-6">
        <form action="index.php" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="page" value="cronogramas">
            
            <div>
                <input 
                    type="text" 
                    name="busca" 
                    placeholder="Buscar por disciplina, assunto, conteúdo..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                    value="<?php echo isset($filtros['busca']) ? htmlspecialchars($filtros['busca']) : ''; ?>"
                >
            </div>
            
            <div>
                <select 
                    name="plano_ensino_id" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                >
                    <option value="">Todos os planos</option>
                    <?php foreach($planos as $plano): ?>
                        <option value="<?php echo $plano['id']; ?>" 
                                <?php echo (isset($filtros['plano_ensino_id']) && $filtros['plano_ensino_id'] == $plano['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($plano['disciplina_nome']); ?> - <?php echo htmlspecialchars($plano['professor_nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <input 
                    type="date" 
                    name="data_inicio" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                    value="<?php echo isset($filtros['data_inicio']) ? $filtros['data_inicio'] : ''; ?>"
                    placeholder="Data inicial"
                >
            </div>
            
            <div>
                <input 
                    type="date" 
                    name="data_fim" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                    value="<?php echo isset($filtros['data_fim']) ? $filtros['data_fim'] : ''; ?>"
                    placeholder="Data final"
                >
            </div>
            
            <div class="md:col-span-4 flex space-x-2">
                <button type="submit" class="bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-4 py-2 rounded-md transition">
                    <i class="fas fa-search mr-2"></i> Filtrar
                </button>
                <a href="index.php?page=cronogramas" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md transition">
                    <i class="fas fa-redo mr-2"></i> Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela de Cronogramas -->
    <?php if(count($cronogramas) > 0): ?>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left text-gray-600">Encontro</th>
                    <th class="px-4 py-2 text-left text-gray-600">Data</th>
                    <th class="px-4 py-2 text-left text-gray-600">Disciplina</th>
                    <th class="px-4 py-2 text-left text-gray-600">Assunto</th>
                    <th class="px-4 py-2 text-left text-gray-600">Conteúdo</th>
                    <th class="px-4 py-2 text-left text-gray-600">Atividade</th>
                    <th class="px-4 py-2 text-right text-gray-600">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cronogramas as $cronograma): ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">
                        <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-sm">
                            <?php echo $cronograma['encontro_numero']; ?>º
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm">
                            <?php echo date('d/m/Y', strtotime($cronograma['data_encontro'])); ?>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div>
                            <div class="font-medium"><?php echo htmlspecialchars($cronograma['disciplina_nome']); ?></div>
                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($cronograma['professor_nome']); ?></div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="<?php echo strpos($cronograma['assunto'], 'AP') !== false ? 'bg-red-100 text-red-800' : 
                                              (strpos($cronograma['assunto'], 'Feriado') !== false ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-blue-100 text-blue-800'); ?> px-2 py-1 rounded text-xs">
                            <?php echo htmlspecialchars($cronograma['assunto']); ?>
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="max-w-xs truncate" title="<?php echo htmlspecialchars($cronograma['conteudo']); ?>">
                            <?php echo htmlspecialchars(substr($cronograma['conteudo'], 0, 40)) . (strlen($cronograma['conteudo']) > 40 ? '...' : ''); ?>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="max-w-xs truncate" title="<?php echo htmlspecialchars($cronograma['atividade']); ?>">
                            <?php echo htmlspecialchars(substr($cronograma['atividade'], 0, 30)) . (strlen($cronograma['atividade']) > 30 ? '...' : ''); ?>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="index.php?page=cronograma-view&id=<?php echo $cronograma['id']; ?>" class="text-blue-600 hover:text-blue-800" title="Visualizar">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="index.php?page=cronograma-edit&id=<?php echo $cronograma['id']; ?>" class="text-yellow-600 hover:text-yellow-800" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="confirmarExclusao(<?php echo $cronograma['id']; ?>)" class="text-red-600 hover:text-red-800" title="Excluir">
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
        <i class="fas fa-calendar-alt text-gray-400 text-5xl mb-4"></i>
        <p class="text-gray-500">Nenhum encontro encontrado.</p>
        <?php if(isset($filtros) && !empty(array_filter($filtros))): ?>
            <a href="index.php?page=cronogramas" class="text-indigo-600 hover:underline mt-2 inline-block">
                Limpar filtros
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir este encontro? Esta ação não pode ser desfeita.')) {
        window.location.href = 'index.php?page=cronograma-delete&id=' + id;
    }
}
</script>
