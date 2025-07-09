<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Detalhes do Curso</h2>
        <div class="flex space-x-3">
            <a href="index.php?page=cursos" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
            <a href="index.php?page=curso-edit&id=<?php echo $curso->id; ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i> Editar
            </a>
        </div>
    </div>

    <!-- Informações Básicas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gray-50 rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Nome do Curso</h3>
            <p class="text-lg font-medium text-gray-800"><?php echo $curso->nome; ?></p>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Carga Horária</h3>
            <p class="text-lg font-medium text-gray-800"><?php echo $curso->carga_horaria; ?> horas</p>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Coordenador</h3>
            <p class="text-lg font-medium text-gray-800">
                <?php echo isset($coordenador) ? $coordenador->nome : 'Não definido'; ?>
            </p>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Status</h3>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    <?php echo $curso->status == 'ativo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                <?php echo ucfirst($curso->status); ?>
            </span>
        </div>
    </div>

    <!-- Ementa e Objetivos -->
    <div class="grid grid-cols-1 gap-6">
        <div class="bg-gray-50 rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Ementa</h3>
            <div class="prose max-w-none text-gray-700">
                <?php echo !empty($curso->ementa) ? nl2br(htmlspecialchars($curso->ementa)) : '<em class="text-gray-400">Nenhuma ementa definida</em>'; ?>
            </div>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Objetivos</h3>
            <div class="prose max-w-none text-gray-700">
                <?php echo !empty($curso->objetivos) ? nl2br(htmlspecialchars($curso->objetivos)) : '<em class="text-gray-400">Nenhum objetivo definido</em>'; ?>
            </div>
        </div>
    </div>

    <!-- Disciplinas do Curso -->
    <div class="mt-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-800">Disciplinas</h3>
            <a href="index.php?page=disciplina-create&curso_id=<?php echo $curso->id; ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded transition duration-200 text-sm">
                <i class="fas fa-plus mr-1"></i> Nova Disciplina
            </a>
        </div>

        <?php if(isset($disciplinas) && count($disciplinas) > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left text-gray-600">Código</th>
                        <th class="px-4 py-2 text-left text-gray-600">Nome</th>
                        <th class="px-4 py-2 text-left text-gray-600">Carga Horária</th>
                        <th class="px-4 py-2 text-left text-gray-600">Status</th>
                        <th class="px-4 py-2 text-right text-gray-600">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($disciplinas as $disciplina): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3"><?php echo $disciplina->codigo; ?></td>
                        <td class="px-4 py-3"><?php echo $disciplina->nome; ?></td>
                        <td class="px-4 py-3"><?php echo $disciplina->carga_horaria; ?> horas</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $disciplina->status == 'ativo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo ucfirst($disciplina->status); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="index.php?page=disciplina-view&id=<?php echo $disciplina->id; ?>" class="text-blue-600 hover:text-blue-800" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="index.php?page=disciplina-edit&id=<?php echo $disciplina->id; ?>" class="text-yellow-600 hover:text-yellow-800" title="Editar">
                                    <i class="fas fa-edit"></i>
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
            <i class="fas fa-book text-gray-400 text-4xl mb-3"></i>
            <p class="text-gray-500">Nenhuma disciplina cadastrada para este curso.</p>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Informações adicionais -->
    <div class="mt-8 text-sm text-gray-500 border-t pt-4">
        <p>Data de criação: <?php echo date('d/m/Y H:i', strtotime($curso->created_at)); ?></p>
        <p>Última atualização: <?php echo date('d/m/Y H:i', strtotime($curso->updated_at)); ?></p>
    </div>
</div>
