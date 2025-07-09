<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800"><?php echo htmlspecialchars($aluno->nome); ?></h1>
                <p class="text-gray-500 mt-1">
                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-sm rounded-lg font-mono">
                        Mat: <?php echo htmlspecialchars($aluno->matricula); ?>
                    </span>
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="index.php?page=aluno-edit&id=<?php echo $aluno->id; ?>" 
                   class="flex items-center bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Editar
                </a>
                <a href="index.php?page=alunos" 
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
                <!-- Informações Pessoais -->
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-800">Informações Pessoais</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nome Completo</dt>
                                <dd class="mt-1 text-gray-900"><?php echo htmlspecialchars($aluno->nome); ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-gray-900"><?php echo htmlspecialchars($aluno->email); ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">CPF</dt>
                                <dd class="mt-1 text-gray-900">
                                    <code class="px-2 py-1 bg-gray-100 rounded text-sm font-mono">
                                        <?php echo htmlspecialchars($aluno->cpf); ?>
                                    </code>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Matrícula</dt>
                                <dd class="mt-1 text-gray-900">
                                    <code class="px-2 py-1 bg-gray-100 rounded text-sm font-mono">
                                        <?php echo htmlspecialchars($aluno->matricula); ?>
                                    </code>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Telefone</dt>
                                <dd class="mt-1 text-gray-900">
                                    <?php echo !empty($aluno->telefone) ? htmlspecialchars($aluno->telefone) : 'Não informado'; ?>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Data de Nascimento</dt>
                                <dd class="mt-1 text-gray-900">
                                    <?php echo !empty($aluno->data_nascimento) ? date('d/m/Y', strtotime($aluno->data_nascimento)) : 'Não informado'; ?>
                                </dd>
                            </div>
                        </dl>
                        
                        <?php if (!empty($aluno->endereco)): ?>
                        <div class="mt-4">
                            <dt class="text-sm font-medium text-gray-500">Endereço</dt>
                            <dd class="mt-1 text-gray-900"><?php echo nl2br(htmlspecialchars($aluno->endereco)); ?></dd>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Histórico Escolar -->
                <?php if (!empty($historico)): ?>
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-800">Histórico Escolar</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disciplina</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CH</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Período</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nota</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Frequência</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Situação</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($historico as $disciplina): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                        <?php echo htmlspecialchars($disciplina['codigo']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($disciplina['disciplina_nome']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo $disciplina['carga_horaria']; ?>h
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo $disciplina['ano'] . '.' . $disciplina['semestre']; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo $disciplina['nota_final'] ?? '-'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo $disciplina['frequencia'] ? $disciplina['frequencia'] . '%' : '-'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php 
                                        $statusColors = [
                                            'matriculado' => 'bg-blue-100 text-blue-800',
                                            'aprovado' => 'bg-green-100 text-green-800',
                                            'reprovado' => 'bg-red-100 text-red-800',
                                            'trancado' => 'bg-yellow-100 text-yellow-800'
                                        ];
                                        $colorClass = $statusColors[$disciplina['status']] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <span class="px-2 py-1 <?php echo $colorClass; ?> rounded-full text-xs">
                                            <?php echo ucfirst($disciplina['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informações Acadêmicas -->
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-800">Informações Acadêmicas</h2>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Curso</dt>
                                <dd class="mt-1 text-gray-900">
                                    <?php if (isset($curso) && $curso): ?>
                                        <div class="flex items-center">
                                            <span class="font-medium"><?php echo htmlspecialchars($curso->nome); ?></span>
                                            <?php if (!empty($curso->carga_horaria)): ?>
                                                <span class="ml-2 text-sm text-gray-500">
                                                    (<?php echo $curso->carga_horaria; ?>h)
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($curso->coordenador_nome)): ?>
                                            <div class="text-sm text-gray-500 mt-1">
                                                Coordenador: <?php echo htmlspecialchars($curso->coordenador_nome); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-gray-400 italic">Curso não informado</span>
                                    <?php endif; ?>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Semestre Atual</dt>
                                <dd class="mt-1">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        <?php echo $aluno->semestre_atual ?? 1; ?>º Semestre
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <?php 
                                    $statusColors = [
                                        'ativo' => 'bg-green-100 text-green-800',
                                        'formado' => 'bg-blue-100 text-blue-800',
                                        'trancado' => 'bg-yellow-100 text-yellow-800',
                                        'jubilado' => 'bg-red-100 text-red-800'
                                    ];
                                    $colorClass = $statusColors[$aluno->status] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-2 py-1 <?php echo $colorClass; ?> rounded-full text-sm">
                                        <?php echo ucfirst($aluno->status); ?>
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Matrículas Atuais -->
                <?php if (!empty($matriculas)): ?>
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-800">Matrículas Atuais</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <?php foreach ($matriculas as $matricula): ?>
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($matricula['disciplina_nome']); ?></div>
                                <div class="text-sm text-gray-500">
                                    <?php echo htmlspecialchars($matricula['codigo']); ?> - 
                                    <?php echo $matricula['ano'] . '.' . $matricula['semestre']; ?>
                                </div>
                                <div class="mt-1">
                                    <?php 
                                    $statusColors = [
                                        'matriculado' => 'bg-blue-100 text-blue-800',
                                        'aprovado' => 'bg-green-100 text-green-800',
                                        'reprovado' => 'bg-red-100 text-red-800',
                                        'trancado' => 'bg-yellow-100 text-yellow-800'
                                    ];
                                    $colorClass = $statusColors[$matricula['status']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-2 py-1 <?php echo $colorClass; ?> rounded-full text-xs">
                                        <?php echo ucfirst($matricula['status']); ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Estatísticas -->
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-medium text-gray-800">Estatísticas</h2>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total de Disciplinas</dt>
                                <dd class="mt-1 text-2xl font-semibold text-gray-900"><?php echo count($historico ?? []); ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Disciplinas Aprovadas</dt>
                                <dd class="mt-1 text-2xl font-semibold text-green-600">
                                    <?php 
                                    $aprovadas = 0;
                                    if (!empty($historico)) {
                                        foreach ($historico as $h) {
                                            if ($h['status'] == 'aprovado') $aprovadas++;
                                        }
                                    }
                                    echo $aprovadas;
                                    ?>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Matrículas Ativas</dt>
                                <dd class="mt-1 text-2xl font-semibold text-blue-600"><?php echo count($matriculas ?? []); ?></dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
