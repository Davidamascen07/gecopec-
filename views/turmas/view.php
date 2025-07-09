<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800"><?php echo htmlspecialchars($turma->nome); ?></h1>
            <div class="flex space-x-2">
                <a href="index.php?page=turma-edit&id=<?php echo $turma->id; ?>" 
                   class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors">
                    Editar
                </a>
                <a href="index.php?page=turmas" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    Voltar
                </a>
            </div>
        </div>

        <!-- Informações da Turma -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações Gerais</h3>
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-gray-600">Disciplina:</span>
                        <span class="ml-2"><?php echo htmlspecialchars($turma->disciplina_nome ?? 'N/A'); ?></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Código:</span>
                        <span class="ml-2"><?php echo htmlspecialchars($turma->disciplina_codigo ?? 'N/A'); ?></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Professor:</span>
                        <span class="ml-2"><?php echo htmlspecialchars($turma->professor_nome ?? 'N/A'); ?></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Curso:</span>
                        <span class="ml-2"><?php echo htmlspecialchars($turma->curso_nome ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalhes</h3>
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-gray-600">Período:</span>
                        <span class="ml-2"><?php echo ($turma->ano ?? '') . '/' . ($turma->semestre ?? ''); ?></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Sala:</span>
                        <span class="ml-2"><?php echo htmlspecialchars($turma->sala ?? 'Não definida'); ?></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Vagas:</span>
                        <span class="ml-2"><?php echo ($turma->matriculados ?? 0) . '/' . ($turma->vagas ?? 0); ?></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Status:</span>
                        <?php 
                        $statusColors = [
                            'ativa' => 'bg-green-100 text-green-800',
                            'finalizada' => 'bg-gray-100 text-gray-800',
                            'cancelada' => 'bg-red-100 text-red-800'
                        ];
                        $colorClass = $statusColors[$turma->status] ?? 'bg-gray-100 text-gray-800';
                        ?>
                        <span class="ml-2 px-2 py-1 <?php echo $colorClass; ?> rounded-full text-xs font-semibold">
                            <?php echo ucfirst($turma->status); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Horário -->
        <?php if (!empty($turma->horario)): ?>
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Horário</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <pre class="whitespace-pre-wrap text-gray-700"><?php echo htmlspecialchars($turma->horario); ?></pre>
            </div>
        </div>
        <?php endif; ?>

        <!-- Alunos Matriculados -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Alunos Matriculados</h3>
            
            <?php if (empty($alunos)): ?>
                <div class="text-center py-8 bg-gray-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM9 9a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-gray-600">Nenhum aluno matriculado nesta turma</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aluno</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Matrícula</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nota Final</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frequência</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($alunos as $aluno): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($aluno['nome']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo !empty($aluno['data_matricula']) ? date('d/m/Y', strtotime($aluno['data_matricula'])) : 'N/A'; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php 
                                    $statusColors = [
                                        'matriculado' => 'bg-blue-100 text-blue-800',
                                        'aprovado' => 'bg-green-100 text-green-800',
                                        'reprovado' => 'bg-red-100 text-red-800',
                                        'trancado' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $colorClass = $statusColors[$aluno['status_matricula']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $colorClass; ?>">
                                        <?php echo ucfirst($aluno['status_matricula']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo $aluno['nota_final'] ? number_format($aluno['nota_final'], 1) : '-'; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo $aluno['frequencia'] ? number_format($aluno['frequencia'], 1) . '%' : '-'; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
