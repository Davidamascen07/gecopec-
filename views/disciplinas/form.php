<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800"><?php echo $title; ?></h1>
            <a href="index.php?page=disciplinas" class="flex items-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
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

        <!-- Formulário -->
        <form action="index.php?page=<?php echo isset($disciplina) ? 'disciplina-update' : 'disciplina-store'; ?>" method="POST">
            <?php if (isset($disciplina)): ?>
                <input type="hidden" name="id" value="<?php echo $disciplina->id; ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="col-span-1 lg:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">
                        Nome da Disciplina <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                          id="nome" 
                          name="nome" 
                          required
                          value="<?php echo isset($disciplina) ? htmlspecialchars($disciplina->nome) : (isset($dados) ? htmlspecialchars($dados['nome']) : ''); ?>"
                          placeholder="Ex: Banco de Dados I"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label for="codigo" class="block text-sm font-medium text-gray-700 mb-1">
                        Código <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                          id="codigo" 
                          name="codigo" 
                          required
                          value="<?php echo isset($disciplina) ? htmlspecialchars($disciplina->codigo) : (isset($dados) ? htmlspecialchars($dados['codigo']) : ''); ?>"
                          placeholder="Ex: BD001"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="carga_horaria" class="block text-sm font-medium text-gray-700 mb-1">
                        Carga Horária <span class="text-red-500">*</span>
                    </label>
                    <div class="flex rounded-md shadow-sm">
                        <input type="number" 
                              id="carga_horaria" 
                              name="carga_horaria" 
                              min="1" 
                              required
                              value="<?php echo isset($disciplina) ? $disciplina->carga_horaria : (isset($dados) ? $dados['carga_horaria'] : ''); ?>"
                              class="flex-1 border border-gray-300 border-r-0 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 border border-gray-300 rounded-r-lg">horas</span>
                    </div>
                </div>
                
                <div>
                    <label for="curso_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Curso <span class="text-red-500">*</span>
                    </label>
                    <select id="curso_id" 
                            name="curso_id" 
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Selecione um curso</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?php echo $curso->id; ?>" 
                                    <?php 
                                    $selected = false;
                                    if (isset($disciplina) && $disciplina->curso_id == $curso->id) {
                                        $selected = true;
                                    } elseif (isset($dados) && $dados['curso_id'] == $curso->id) {
                                        $selected = true;
                                    }
                                    echo $selected ? 'selected' : '';
                                    ?>>
                                <?php echo htmlspecialchars($curso->nome); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" 
                            name="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="ativo" <?php echo (isset($disciplina) && $disciplina->status == 'ativo') || (isset($dados) && $dados['status'] == 'ativo') || (!isset($disciplina) && !isset($dados)) ? 'selected' : ''; ?>>
                            Ativo
                        </option>
                        <option value="inativo" <?php echo (isset($disciplina) && $disciplina->status == 'inativo') || (isset($dados) && $dados['status'] == 'inativo') ? 'selected' : ''; ?>>
                            Inativo
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label for="ementa" class="block text-sm font-medium text-gray-700 mb-1">Ementa</label>
                <textarea id="ementa" 
                          name="ementa" 
                          rows="4"
                          placeholder="Descrição detalhada do conteúdo da disciplina..."
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                ><?php echo isset($disciplina) ? htmlspecialchars($disciplina->ementa ?? '') : (isset($dados) ? htmlspecialchars($dados['ementa'] ?? '') : ''); ?></textarea>
                <p class="text-sm text-gray-500 mt-1">Máximo de 5000 caracteres</p>
            </div>

            <div class="mb-6">
                <label for="prerequisitos" class="block text-sm font-medium text-gray-700 mb-1">Pré-requisitos</label>
                <textarea id="prerequisitos" 
                          name="prerequisitos" 
                          rows="3"
                          placeholder="Liste os pré-requisitos necessários para cursar esta disciplina..."
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                ><?php echo isset($disciplina) ? htmlspecialchars($disciplina->prerequisitos ?? '') : (isset($dados) ? htmlspecialchars($dados['prerequisitos'] ?? '') : ''); ?></textarea>
                <p class="text-sm text-gray-500 mt-1">Máximo de 1000 caracteres</p>
            </div>

            <div class="flex justify-end items-center space-x-3 border-t pt-6">
                <a href="index.php?page=disciplinas" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Cancelar
                </a>
                <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                    Limpar
                </button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414l1.293-1.293z" clip-rule="evenodd" />
                    </svg>
                    <?php echo isset($disciplina) ? 'Atualizar' : 'Salvar'; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        const nome = document.getElementById('nome').value.trim();
        const codigo = document.getElementById('codigo').value.trim();
        const cargaHoraria = document.getElementById('carga_horaria').value;
        const cursoId = document.getElementById('curso_id').value;
        
        let hasError = false;
        let errorMessage = '';
        
        if (!nome) {
            hasError = true;
            errorMessage += '- O nome da disciplina é obrigatório\n';
        }
        
        if (!codigo) {
            hasError = true;
            errorMessage += '- O código da disciplina é obrigatório\n';
        }
        
        if (!cargaHoraria || cargaHoraria <= 0) {
            hasError = true;
            errorMessage += '- A carga horária deve ser um número positivo\n';
        }
        
        if (!cursoId) {
            hasError = true;
            errorMessage += '- Selecione um curso\n';
        }
        
        if (hasError) {
            e.preventDefault();
            alert('Por favor, corrija os seguintes erros:\n\n' + errorMessage);
        }
    });
});
</script>
