<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">
            <?php echo isset($curso) ? 'Editar Curso' : 'Novo Curso'; ?>
        </h2>
        <a href="index.php?page=cursos" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Voltar
        </a>
    </div>

    <?php if(isset($mensagem)): ?>
        <div class="<?php echo strpos($mensagem, 'sucesso') !== false ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> px-4 py-3 mb-4 rounded border">
            <p><?php echo $mensagem; ?></p>
        </div>
    <?php endif; ?>

    <form action="index.php?page=<?php echo isset($curso) ? 'curso-update' : 'curso-store'; ?>" method="POST" class="space-y-6">
        <?php if(isset($curso)): ?>
            <input type="hidden" name="id" value="<?php echo $curso->id; ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nome do Curso -->
            <div>
                <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome do Curso <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="nome" 
                    name="nome" 
                    required
                    value="<?php echo isset($curso) ? htmlspecialchars($curso->nome) : ''; ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            <!-- Carga Horária -->
            <div>
                <label for="carga_horaria" class="block text-sm font-medium text-gray-700 mb-2">
                    Carga Horária <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    id="carga_horaria" 
                    name="carga_horaria" 
                    required
                    min="1"
                    value="<?php echo isset($curso) ? $curso->carga_horaria : ''; ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>
        </div>

        <!-- Ementa -->
        <div>
            <label for="ementa" class="block text-sm font-medium text-gray-700 mb-2">
                Ementa
            </label>
            <textarea 
                id="ementa"
                name="ementa"
                rows="4" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            ><?php echo isset($curso) ? htmlspecialchars($curso->ementa ?? '') : ''; ?></textarea>
        </div>

        <!-- Objetivos -->
        <div>
            <label for="objetivos" class="block text-sm font-medium text-gray-700 mb-2">
                Objetivos
            </label>
            <textarea 
                id="objetivos"
                name="objetivos"
                rows="4" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            ><?php echo isset($curso) ? htmlspecialchars($curso->objetivos ?? '') : ''; ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Coordenador -->
            <div>
                <label for="coordenador_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Coordenador
                </label>
                <select 
                    id="coordenador_id" 
                    name="coordenador_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="">Selecione um coordenador</option>
                    <?php if (isset($coordenadores)): ?>
                        <?php foreach ($coordenadores as $coordenador): ?>
                            <option value="<?php echo $coordenador->id; ?>" 
                                    <?php echo (isset($curso) && $curso->coordenador_id == $coordenador->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($coordenador->nome); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>
                <select 
                    id="status" 
                    name="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="ativo" <?php echo (isset($curso) && $curso->status == 'ativo') ? 'selected' : ''; ?>>Ativo</option>
                    <option value="inativo" <?php echo (isset($curso) && $curso->status == 'inativo') ? 'selected' : ''; ?>>Inativo</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t">
            <button 
                type="reset" 
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md transition duration-200"
            >
                <i class="fas fa-undo mr-2"></i> Limpar
            </button>
            <button 
                type="submit" 
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition duration-200"
            >
                <i class="fas fa-save mr-2"></i> <?php echo isset($curso) ? 'Atualizar' : 'Salvar'; ?>
            </button>
        </div>
    </form>
</div>

<script>
// Validação do formulário
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        const nome = document.getElementById('nome').value.trim();
        const cargaHoraria = document.getElementById('carga_horaria').value;
        
        if (!nome) {
            e.preventDefault();
            alert('O nome do curso é obrigatório');
            return;
        }
        
        if (!cargaHoraria || cargaHoraria <= 0) {
            e.preventDefault();
            alert('A carga horária deve ser um número positivo');
            return;
        }
    });
});
</script>
