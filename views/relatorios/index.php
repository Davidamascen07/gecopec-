<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Relatórios e Exportações</h2>
    </div>

    <?php if(isset($_SESSION['mensagem'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded">
            <p><?php echo $_SESSION['mensagem']; ?></p>
        </div>
        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>

    <!-- Planos de Ensino Aprovados -->
    <div class="mb-8">
        <h3 class="text-lg font-medium text-gray-700 mb-3">Planos de Ensino Aprovados</h3>
        
        <?php if(empty($planosAprovados)): ?>
            <div class="bg-gray-50 p-4 rounded text-gray-500 text-center">
                Nenhum plano de ensino aprovado encontrado.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disciplina</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Professor</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semestre/Ano</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Aprovação</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach($planosAprovados as $plano): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($plano['disciplina_nome']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($plano['disciplina_codigo']); ?></div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($plano['professor_nome']); ?></div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($plano['curso_nome']); ?></div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo $plano['semestre']; ?>/<?php echo $plano['ano']; ?></div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?php echo date('d/m/Y', strtotime($plano['data_aprovacao'])); ?>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-3">
                                        <a href="index.php?page=relatorio-word&id=<?php echo $plano['id']; ?>" class="text-indigo-600 hover:text-indigo-900" title="Exportar para Word">
                                            <i class="fas fa-file-word"></i> Word
                                        </a>
                                        <a href="index.php?page=relatorio-pdf&id=<?php echo $plano['id']; ?>" class="text-red-600 hover:text-red-900" title="Exportar para PDF">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
