<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800"><?php echo $title; ?></h1>
            <a href="index.php?page=professores" class="flex items-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Voltar
            </a>
        </div>

        <?php if (isset($erros) && !empty($erros)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Corrija os seguintes erros:</p>
            <ul class="list-disc ml-6 mt-2">
                <?php foreach ($erros as $erro): ?>
                    <li><?php echo htmlspecialchars($erro); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Debug sections comentadas -->
        <?php /* Debug detalhado comentado
        <?php if (isset($debug_todos_usuarios)): ?>
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
            <p class="font-bold">Debug - Todos os usuários no banco:</p>
            <ul class="list-disc ml-6 mt-2 text-sm">
                <?php foreach ($debug_todos_usuarios as $usuario): ?>
                    <li>ID: <?php echo $usuario['id']; ?> - <?php echo htmlspecialchars($usuario['nome']); ?> (<?php echo htmlspecialchars($usuario['email']); ?>) - Status: <?php echo $usuario['status']; ?> - Tipo: <?php echo $usuario['tipo']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if (isset($debug_professores_existentes)): ?>
        <div class="bg-purple-100 border-l-4 border-purple-500 text-purple-700 p-4 mb-6" role="alert">
            <p class="font-bold">Debug - Professores já cadastrados:</p>
            <?php if (empty($debug_professores_existentes)): ?>
                <p>Nenhum professor cadastrado ainda.</p>
            <?php else: ?>
                <ul class="list-disc ml-6 mt-2 text-sm">
                    <?php foreach ($debug_professores_existentes as $prof): ?>
                        <li>ID: <?php echo $prof['id']; ?> - Usuario ID: <?php echo $prof['usuario_id']; ?> - Matrícula: <?php echo $prof['matricula']; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if (isset($usuarios)): ?>
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
            <p class="font-bold">Debug - Usuários disponíveis para ser professor:</p>
            <?php if (empty($usuarios)): ?>
                <p>Nenhum usuário disponível encontrado.</p>
            <?php else: ?>
                <ul class="list-disc ml-6 mt-2">
                    <?php foreach ($usuarios as $usuario): ?>
                        <li>ID: <?php echo $usuario['id']; ?> - <?php echo htmlspecialchars($usuario['nome']); ?> (<?php echo htmlspecialchars($usuario['email']); ?>)</li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        */ ?>

        <!-- Formulário -->
        <form action="index.php?page=<?php echo isset($professor) ? 'professor-update' : 'professor-store'; ?>" method="POST" id="professor-form">
            <?php if (isset($professor)): ?>
                <input type="hidden" name="id" value="<?php echo $professor->id; ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Usuário <span class="text-red-500">*</span>
                    </label>
                    <select id="usuario_id" 
                            name="usuario_id" 
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Selecione um usuário</option>
                        <?php if (isset($usuarios) && !empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?php echo (int)$usuario['id']; ?>" 
                                        <?php 
                                        $selected = false;
                                        if (isset($professor) && $professor->usuario_id == $usuario['id']) {
                                            $selected = true;
                                        } elseif (isset($dados) && $dados['usuario_id'] == $usuario['id']) {
                                            $selected = true;
                                        }
                                        echo $selected ? 'selected' : '';
                                        ?>
                                        data-user-id="<?php echo (int)$usuario['id']; ?>">
                                    <?php echo htmlspecialchars($usuario['nome'] . ' (' . $usuario['email'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Nenhum usuário disponível</option>
                        <?php endif; ?>
                    </select>
                    <!-- Debug display comentado -->
                    <!-- <small class="text-gray-500">Valor selecionado: <span id="selected-user-id">Nenhum</span></small> -->
                    <?php if (empty($usuarios)): ?>
                        <p class="text-sm text-red-500 mt-1">
                            Nenhum usuário disponível. Verifique se há usuários ativos que não sejam professores.
                        </p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="matricula" class="block text-sm font-medium text-gray-700 mb-1">
                        Matrícula <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                          id="matricula" 
                          name="matricula" 
                          required
                          maxlength="14"
                          value="<?php echo isset($professor) ? htmlspecialchars($professor->matricula) : (isset($dados) ? htmlspecialchars($dados['matricula']) : ''); ?>"
                          placeholder="Ex: 123456789012"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="departamento" class="block text-sm font-medium text-gray-700 mb-1">
                        Departamento <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                          id="departamento" 
                          name="departamento" 
                          required
                          maxlength="255"
                          value="<?php echo isset($professor) ? htmlspecialchars($professor->departamento) : (isset($dados) ? htmlspecialchars($dados['departamento']) : ''); ?>"
                          placeholder="Ex: Ciência da Computação"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label for="data_nascimento" class="block text-sm font-medium text-gray-700 mb-1">
                        Data de Nascimento
                    </label>
                    <input type="date" 
                          id="data_nascimento" 
                          name="data_nascimento"
                          value="<?php echo isset($professor) ? $professor->data_nascimento : (isset($dados) ? $dados['data_nascimento'] : ''); ?>"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700 mb-1">
                        Telefone
                    </label>
                    <input type="tel" 
                          id="telefone" 
                          name="telefone"
                          maxlength="20"
                          value="<?php echo isset($professor) ? htmlspecialchars($professor->telefone ?? '') : (isset($dados) ? htmlspecialchars($dados['telefone'] ?? '') : ''); ?>"
                          placeholder="Ex: (11) 99999-9999"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" 
                            name="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="ativo" <?php echo (isset($professor) && $professor->status == 'ativo') || (isset($dados) && $dados['status'] == 'ativo') || (!isset($professor) && !isset($dados)) ? 'selected' : ''; ?>>
                            Ativo
                        </option>
                        <option value="inativo" <?php echo (isset($professor) && $professor->status == 'inativo') || (isset($dados) && $dados['status'] == 'inativo') ? 'selected' : ''; ?>>
                            Inativo
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label for="endereco" class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
                <textarea id="endereco" 
                          name="endereco" 
                          rows="3"
                          placeholder="Endereço completo..."
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                ><?php echo isset($professor) ? htmlspecialchars($professor->endereco ?? '') : (isset($dados) ? htmlspecialchars($dados['endereco'] ?? '') : ''); ?></textarea>
            </div>

            <div class="mb-6">
                <label for="especializacao" class="block text-sm font-medium text-gray-700 mb-1">Especialização</label>
                <textarea id="especializacao" 
                          name="especializacao" 
                          rows="3"
                          placeholder="Áreas de especialização e formação acadêmica..."
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                ><?php echo isset($professor) ? htmlspecialchars($professor->especializacao ?? '') : (isset($dados) ? htmlspecialchars($dados['especializacao'] ?? '') : ''); ?></textarea>
            </div>

            <div class="mb-6">
                <label for="lattes_url" class="block text-sm font-medium text-gray-700 mb-1">URL do Currículo Lattes</label>
                <input type="url" 
                      id="lattes_url" 
                      name="lattes_url"
                      maxlength="500"
                      value="<?php echo isset($professor) ? htmlspecialchars($professor->lattes_url ?? '') : (isset($dados) ? htmlspecialchars($dados['lattes_url'] ?? '') : ''); ?>"
                      placeholder="http://lattes.cnpq.br/..."
                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <p class="text-sm text-gray-500 mt-1">Máximo de 500 caracteres</p>
            </div>

            <div class="flex justify-end items-center space-x-3 border-t pt-6">
                <a href="index.php?page=professores" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <?php echo isset($professor) ? 'Atualizar' : 'Salvar'; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('professor-form');
    const usuarioSelect = document.getElementById('usuario_id');
    // Debug element comentado
    // const selectedUserIdSpan = document.getElementById('selected-user-id');
    
    // Debug listener comentado
    // usuarioSelect.addEventListener('change', function() {
    //     selectedUserIdSpan.textContent = this.value || 'Nenhum';
    //     console.log('Usuário selecionado:', this.value);
    // });
    
    form.addEventListener('submit', function(e) {
        const usuarioId = document.getElementById('usuario_id').value;
        const matricula = document.getElementById('matricula').value.trim();
        const departamento = document.getElementById('departamento').value.trim();
        
        // Debug console comentado
        // console.log('Submit - Dados do formulário:', {
        //     usuario_id: usuarioId,
        //     matricula: matricula,
        //     departamento: departamento
        // });
        
        let hasError = false;
        let errorMessage = '';
        
        if (!usuarioId || usuarioId === '' || usuarioId === '0') {
            hasError = true;
            errorMessage += '- Selecione um usuário\n';
        }
        
        if (!matricula) {
            hasError = true;
            errorMessage += '- A matrícula é obrigatória\n';
        }
        
        if (!departamento) {
            hasError = true;
            errorMessage += '- O departamento é obrigatório\n';
        }
        
        if (hasError) {
            e.preventDefault();
            alert('Por favor, corrija os seguintes erros:\n\n' + errorMessage);
        }
    });
    
    // Debug initialization comentado
    // selectedUserIdSpan.textContent = usuarioSelect.value || 'Nenhum';
});
</script>
