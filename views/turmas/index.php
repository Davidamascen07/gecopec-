<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Gerenciar Turmas</h1>
            <a href="index.php?page=turma-create" class="flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Nova Turma
            </a>
        </div>

        <?php if (isset($mensagem) && $mensagem): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        </div>
        <?php endif; ?>

        <!-- Filtros -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
            <form method="GET" action="index.php" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <input type="hidden" name="page" value="turmas">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="busca" value="<?php echo htmlspecialchars($filtros['busca']); ?>" 
                           placeholder="Nome, disciplina, professor..."
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Disciplina</label>
                    <select name="disciplina_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todas</option>
                        <?php foreach ($disciplinas as $disciplina): ?>
                            <option value="<?php echo $disciplina['id']; ?>" 
                                    <?php echo $filtros['disciplina_id'] == $disciplina['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($disciplina['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Professor</label>
                    <select name="professor_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        <?php foreach ($professores as $professor): ?>
                            <option value="<?php echo $professor['id']; ?>" 
                                    <?php echo $filtros['professor_id'] == $professor['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($professor['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        <option value="ativa" <?php echo $filtros['status'] === 'ativa' ? 'selected' : ''; ?>>Ativa</option>
                        <option value="finalizada" <?php echo $filtros['status'] === 'finalizada' ? 'selected' : ''; ?>>Finalizada</option>
                        <option value="cancelada" <?php echo $filtros['status'] === 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ano</label>
                    <input type="number" name="ano" value="<?php echo htmlspecialchars($filtros['ano']); ?>" 
                           min="2020" max="2030"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Semestre</label>
                    <select name="semestre" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo $i; ?>" 
                                    <?php echo $filtros['semestre'] == $i ? 'selected' : ''; ?>>
                                <?php echo $i; ?>º Semestre
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabela de Turmas -->
        <?php if (empty($turmas)): ?>
            <div class="text-center py-16">
                <div class="bg-gray-100 p-6 rounded-full inline-block mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Nenhuma turma encontrada</h2>
                <p class="text-gray-600 mb-6">Não há turmas cadastradas com os filtros aplicados.</p>
                <a href="index.php?page=turma-create" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                    Criar primeira turma
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Turma</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disciplina</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Professor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vagas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($turmas as $turma): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($turma['nome']); ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?php echo htmlspecialchars($turma['sala'] ?? 'Sala não definida'); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo htmlspecialchars($turma['disciplina_nome'] ?? 'N/A'); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($turma['disciplina_codigo'] ?? ''); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo htmlspecialchars($turma['professor_nome'] ?? 'N/A'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo ($turma['ano'] ?? '') . '/' . ($turma['semestre'] ?? ''); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php echo ($turma['matriculados'] ?? 0) . '/' . ($turma['vagas'] ?? 0); ?>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <?php 
                                    $percentual = $turma['vagas'] > 0 ? (($turma['matriculados'] ?? 0) / $turma['vagas']) * 100 : 0;
                                    $corBarra = $percentual >= 90 ? 'bg-red-600' : ($percentual >= 70 ? 'bg-yellow-600' : 'bg-green-600');
                                    ?>
                                    <div class="<?php echo $corBarra; ?> h-2 rounded-full" style="width: <?php echo min($percentual, 100); ?>%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php 
                                $statusColors = [
                                    'ativa' => 'bg-green-100 text-green-800',
                                    'finalizada' => 'bg-gray-100 text-gray-800',
                                    'cancelada' => 'bg-red-100 text-red-800'
                                ];
                                $colorClass = $statusColors[$turma['status']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $colorClass; ?>">
                                    <?php echo ucfirst($turma['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="index.php?page=turma-view&id=<?php echo $turma['id']; ?>" 
                                       class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                    <a href="index.php?page=turma-edit&id=<?php echo $turma['id']; ?>" 
                                       class="text-yellow-600 hover:text-yellow-900">Editar</a>
                                    <a href="index.php?page=turma-delete&id=<?php echo $turma['id']; ?>" 
                                       class="text-red-600 hover:text-red-900"
                                       onclick="return confirm('Tem certeza que deseja excluir esta turma?')">Excluir</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 text-sm text-gray-600">
                Mostrando <?php echo count($turmas); ?> turma(s)
            </div>
        <?php endif; ?>
    </div>
</div>
