<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800"><?php echo $title; ?></h1>
            <a href="index.php?page=turmas" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                Voltar
            </a>
        </div>

        <?php if (isset($erros) && !empty($erros)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <ul class="list-disc ml-4">
                <?php foreach ($erros as $erro): ?>
                    <li><?php echo htmlspecialchars($erro); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo isset($turma) ? 'index.php?page=turma-update' : 'index.php?page=turma-store'; ?>" class="space-y-6">
            <?php if (isset($turma)): ?>
                <input type="hidden" name="id" value="<?php echo $turma->id; ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome da Turma -->
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome da Turma *</label>
                    <input type="text" id="nome" name="nome" required
                           value="<?php echo htmlspecialchars($turma->nome ?? $dados['nome'] ?? ''); ?>"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Ex: Turma A, Manhã, etc.">
                </div>

                <!-- Disciplina -->
                <div>
                    <label for="disciplina_id" class="block text-sm font-medium text-gray-700 mb-1">Disciplina *</label>
                    <select id="disciplina_id" name="disciplina_id" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Selecione uma disciplina</option>
                        <?php foreach ($disciplinas as $disciplina): ?>
                            <option value="<?php echo $disciplina['id']; ?>"
                                    <?php echo (isset($turma) && $turma->disciplina_id == $disciplina['id']) || 
                                              (isset($dados) && $dados['disciplina_id'] == $disciplina['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($disciplina['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Professor -->
                <div>
                    <label for="professor_id" class="block text-sm font-medium text-gray-700 mb-1">Professor *</label>
                    <select id="professor_id" name="professor_id" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Selecione um professor</option>
                        <?php foreach ($professores as $professor): ?>
                            <option value="<?php echo $professor['id']; ?>"
                                    <?php echo (isset($turma) && $turma->professor_id == $professor['id']) || 
                                              (isset($dados) && $dados['professor_id'] == $professor['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($professor['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Semestre -->
                <div>
                    <label for="semestre" class="block text-sm font-medium text-gray-700 mb-1">Semestre *</label>
                    <select id="semestre" name="semestre" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Selecione o semestre</option>
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo $i; ?>" 
                                    <?php echo (isset($turma) && $turma->semestre == $i) || (isset($dados) && $dados['semestre'] == $i) ? 'selected' : ''; ?>>
                                <?php echo $i; ?>º Semestre
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Ano -->
                <div>
                    <label for="ano" class="block text-sm font-medium text-gray-700 mb-1">Ano *</label>
                    <input type="number" id="ano" name="ano" required min="2020" max="2030"
                           value="<?php echo htmlspecialchars($turma->ano ?? $dados['ano'] ?? date('Y')); ?>"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <!-- Vagas -->
                <div>
                    <label for="vagas" class="block text-sm font-medium text-gray-700 mb-1">Número de Vagas</label>
                    <input type="number" id="vagas" name="vagas" min="1" max="100"
                           value="<?php echo htmlspecialchars($turma->vagas ?? $dados['vagas'] ?? '30'); ?>"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <!-- Sala -->
                <div>
                    <label for="sala" class="block text-sm font-medium text-gray-700 mb-1">Sala</label>
                    <input type="text" id="sala" name="sala"
                           value="<?php echo htmlspecialchars($turma->sala ?? $dados['sala'] ?? ''); ?>"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Ex: Sala 101, Lab A, etc.">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="ativa" <?php echo (isset($turma) && $turma->status == 'ativa') || (isset($dados) && $dados['status'] == 'ativa') || (!isset($turma) && !isset($dados)) ? 'selected' : ''; ?>>Ativa</option>
                        <option value="finalizada" <?php echo (isset($turma) && $turma->status == 'finalizada') || (isset($dados) && $dados['status'] == 'finalizada') ? 'selected' : ''; ?>>Finalizada</option>
                        <option value="cancelada" <?php echo (isset($turma) && $turma->status == 'cancelada') || (isset($dados) && $dados['status'] == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                </div>
            </div>

            <!-- Horário -->
            <div>
                <label for="horario" class="block text-sm font-medium text-gray-700 mb-1">Horário</label>
                <textarea id="horario" name="horario" rows="3"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Ex: Segunda: 08:00-10:00, Quarta: 08:00-10:00"><?php echo htmlspecialchars($turma->horario ?? $dados['horario'] ?? ''); ?></textarea>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="index.php?page=turmas" 
                   class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                    <?php echo isset($turma) ? 'Atualizar' : 'Criar'; ?> Turma
                </button>
            </div>
        </form>
    </div>
</div>
