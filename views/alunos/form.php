<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800"><?php echo $title; ?></h1>
            <a href="index.php?page=alunos" class="flex items-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
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
        <form action="index.php?page=<?php echo isset($aluno) ? 'aluno-update' : 'aluno-store'; ?>" method="POST" id="aluno-form">
            <?php if (isset($aluno)): ?>
                <input type="hidden" name="id" value="<?php echo $aluno->id; ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">
                        Nome Completo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                          id="nome" 
                          name="nome" 
                          required
                          maxlength="255"
                          value="<?php echo isset($aluno) ? htmlspecialchars($aluno->nome) : (isset($dados) ? htmlspecialchars($dados['nome']) : ''); ?>"
                          placeholder="Nome completo do aluno"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                          id="email" 
                          name="email" 
                          required
                          maxlength="255"
                          value="<?php echo isset($aluno) ? htmlspecialchars($aluno->email) : (isset($dados) ? htmlspecialchars($dados['email']) : ''); ?>"
                          placeholder="email@exemplo.com"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="cpf" class="block text-sm font-medium text-gray-700 mb-1">
                        CPF <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                          id="cpf" 
                          name="cpf" 
                          required
                          maxlength="14"
                          value="<?php echo isset($aluno) ? htmlspecialchars($aluno->cpf) : (isset($dados) ? htmlspecialchars($dados['cpf']) : ''); ?>"
                          placeholder="000.000.000-00"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label for="matricula" class="block text-sm font-medium text-gray-700 mb-1">
                        Matrícula <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                          id="matricula" 
                          name="matricula" 
                          required
                          maxlength="50"
                          value="<?php echo isset($aluno) ? htmlspecialchars($aluno->matricula) : (isset($dados) ? htmlspecialchars($dados['matricula']) : ''); ?>"
                          placeholder="Ex: 2024001001"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="curso_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Curso <span class="text-red-500">*</span>
                    </label>
                    <select id="curso_id" 
                            name="curso_id" 
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Selecione um curso</option>
                        <?php 
                        // Debug: Mostrar informações sobre os cursos
                        if (isset($cursos)): 
                            echo "<!-- Debug: Cursos recebidos: " . count($cursos) . " -->";
                            if (!empty($cursos) && is_array($cursos)): 
                        ?>
                            <?php foreach ($cursos as $curso): ?>
                                <?php 
                                // Debug: Verificar estrutura do curso
                                echo "<!-- Debug curso: " . print_r($curso, true) . " -->";
                                ?>
                                <option value="<?php echo isset($curso['id']) ? $curso['id'] : ''; ?>" 
                                        <?php 
                                        $selected = false;
                                        if (isset($aluno) && isset($curso['id']) && $aluno->curso_id == $curso['id']) {
                                            $selected = true;
                                        } elseif (isset($dados) && isset($curso['id']) && $dados['curso_id'] == $curso['id']) {
                                            $selected = true;
                                        }
                                        echo $selected ? 'selected' : '';
                                        ?>>
                                    <?php echo isset($curso['nome']) ? htmlspecialchars($curso['nome']) : 'Nome não disponível'; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Nenhum curso encontrado no array</option>
                            <!-- Debug: Estrutura dos cursos: <?php echo print_r($cursos, true); ?> -->
                        <?php endif; ?>
                        <?php else: ?>
                            <option value="" disabled>Variável cursos não definida</option>
                        <?php endif; ?>
                    </select>
                    
                    <?php if (!isset($cursos) || empty($cursos)): ?>
                        <p class="text-sm text-red-500 mt-1">
                            Nenhum curso disponível. 
                            <a href="index.php?page=curso-create" class="text-blue-500 underline">Cadastre um curso</a> 
                            antes de continuar.
                        </p>
                    <?php endif; ?>
                    
                    <!-- Debug adicional -->
                    <div class="text-xs text-gray-500 mt-1">
                        Debug: <?php echo isset($cursos) ? count($cursos) . ' cursos encontrados' : 'Cursos não definidos'; ?>
                    </div>
                </div>
                
                <div>
                    <label for="data_nascimento" class="block text-sm font-medium text-gray-700 mb-1">
                        Data de Nascimento
                    </label>
                    <input type="date" 
                          id="data_nascimento" 
                          name="data_nascimento"
                          value="<?php echo isset($aluno) ? $aluno->data_nascimento : (isset($dados) ? $dados['data_nascimento'] : ''); ?>"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700 mb-1">
                        Telefone
                    </label>
                    <input type="tel" 
                          id="telefone" 
                          name="telefone"
                          maxlength="20"
                          value="<?php echo isset($aluno) ? htmlspecialchars($aluno->telefone ?? '') : (isset($dados) ? htmlspecialchars($dados['telefone'] ?? '') : ''); ?>"
                          placeholder="(11) 99999-9999"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label for="semestre_atual" class="block text-sm font-medium text-gray-700 mb-1">
                        Semestre Atual
                    </label>
                    <select id="semestre_atual" 
                            name="semestre_atual"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <?php for($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>" 
                                    <?php 
                                    $selected = false;
                                    if (isset($aluno) && $aluno->semestre_atual == $i) {
                                        $selected = true;
                                    } elseif (isset($dados) && $dados['semestre_atual'] == $i) {
                                        $selected = true;
                                    } elseif (!isset($aluno) && !isset($dados) && $i == 1) {
                                        $selected = true;
                                    }
                                    echo $selected ? 'selected' : '';
                                    ?>>
                                <?php echo $i; ?>º Semestre
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" 
                            name="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="ativo" <?php echo (isset($aluno) && $aluno->status == 'ativo') || (isset($dados) && $dados['status'] == 'ativo') || (!isset($aluno) && !isset($dados)) ? 'selected' : ''; ?>>
                            Ativo
                        </option>
                        <option value="formado" <?php echo (isset($aluno) && $aluno->status == 'formado') || (isset($dados) && $dados['status'] == 'formado') ? 'selected' : ''; ?>>
                            Formado
                        </option>
                        <option value="trancado" <?php echo (isset($aluno) && $aluno->status == 'trancado') || (isset($dados) && $dados['status'] == 'trancado') ? 'selected' : ''; ?>>
                            Trancado
                        </option>
                        <option value="jubilado" <?php echo (isset($aluno) && $aluno->status == 'jubilado') || (isset($dados) && $dados['status'] == 'jubilado') ? 'selected' : ''; ?>>
                            Jubilado
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
                ><?php echo isset($aluno) ? htmlspecialchars($aluno->endereco ?? '') : (isset($dados) ? htmlspecialchars($dados['endereco'] ?? '') : ''); ?></textarea>
            </div>

            <div class="flex justify-end items-center space-x-3 border-t pt-6">
                <a href="index.php?page=alunos" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <?php echo isset($aluno) ? 'Atualizar' : 'Salvar'; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('aluno-form');
    
    // Máscara para CPF
    const cpfInput = document.getElementById('cpf');
    cpfInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{2})$/, '$1-$2');
        this.value = value;
    });
    
    // Máscara para telefone
    const telefoneInput = document.getElementById('telefone');
    telefoneInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length <= 10) {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        } else {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
        }
        this.value = value;
    });
    
    form.addEventListener('submit', function(e) {
        const nome = document.getElementById('nome').value.trim();
        const email = document.getElementById('email').value.trim();
        const cpf = document.getElementById('cpf').value.trim();
        const matricula = document.getElementById('matricula').value.trim();
        const cursoId = document.getElementById('curso_id').value;
        
        let hasError = false;
        let errorMessage = '';
        
        if (!nome) {
            hasError = true;
            errorMessage += '- O nome é obrigatório\n';
        }
        
        if (!email) {
            hasError = true;
            errorMessage += '- O email é obrigatório\n';
        }
        
        if (!cpf) {
            hasError = true;
            errorMessage += '- O CPF é obrigatório\n';
        }
        
        if (!matricula) {
            hasError = true;
            errorMessage += '- A matrícula é obrigatória\n';
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
