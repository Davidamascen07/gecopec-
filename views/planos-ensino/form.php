<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800"><?php echo $title; ?></h1>
            <a href="index.php?page=planos-ensino" class="flex items-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Voltar
            </a>
        </div>

        <?php if (isset($erros) && !empty($erros)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
            <h4 class="font-medium mb-2">Erros encontrados:</h4>
            <ul class="list-disc list-inside">
                <?php foreach ($erros as $erro): ?>
                    <li><?php echo htmlspecialchars($erro); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Formulário -->
        <form action="index.php?page=plano-ensino-store" method="POST" id="plano-form">
            <!-- Seção: Informações Básicas -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Informações Básicas</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="curso_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Curso <span class="text-red-500">*</span>
                        </label>
                        <select id="curso_id" name="curso_id" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Selecione um curso</option>
                            <?php if (isset($cursos) && is_array($cursos)): ?>
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?php echo $curso->id; ?>" 
                                            <?php echo (isset($dados['curso_id']) && $dados['curso_id'] == $curso->id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($curso->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="disciplina_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Disciplina <span class="text-red-500">*</span>
                        </label>
                        <select id="disciplina_id" name="disciplina_id" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Selecione uma disciplina</option>
                            <?php if (isset($disciplinas) && is_array($disciplinas)): ?>
                                <?php foreach ($disciplinas as $disciplina): ?>
                                    <option value="<?php echo $disciplina['id']; ?>" 
                                            <?php echo (isset($dados['disciplina_id']) && $dados['disciplina_id'] == $disciplina['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($disciplina['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="professor_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Professor <span class="text-red-500">*</span>
                        </label>
                        <select id="professor_id" name="professor_id" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Selecione um professor</option>
                            <?php if (isset($professores) && is_array($professores)): ?>
                                <?php foreach ($professores as $professor): ?>
                                    <option value="<?php echo $professor['id']; ?>" 
                                            <?php echo (isset($dados['professor_id']) && $dados['professor_id'] == $professor['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($professor['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="ano" class="block text-sm font-medium text-gray-700 mb-1">
                            Ano <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="ano" name="ano" required min="2020" max="2030"
                               value="<?php echo isset($dados['ano']) ? $dados['ano'] : date('Y'); ?>"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label for="semestre" class="block text-sm font-medium text-gray-700 mb-1">
                            Semestre <span class="text-red-500">*</span>
                        </label>
                        <select id="semestre" name="semestre" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="1" <?php echo (isset($dados['semestre']) && $dados['semestre'] == 1) ? 'selected' : ''; ?>>1º Semestre</option>
                            <option value="2" <?php echo (isset($dados['semestre']) && $dados['semestre'] == 2) ? 'selected' : ''; ?>>2º Semestre</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Seção: Objetivos e Competências -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Objetivos e Competências</h2>
                <div class="mb-6">
                    <label for="objetivos_gerais" class="block text-sm font-medium text-gray-700 mb-1">
                        Objetivos Gerais <span class="text-red-500">*</span>
                    </label>
                    <textarea id="objetivos_gerais" name="objetivos_gerais" required rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Liste os objetivos gerais da disciplina, por exemplo:
- Conhecer sobre os bancos de dados do mercado de trabalho. 
- Compreender sobre algoritmos de ordenação e busca.
- Conseguir diferenciar os mais diversos bancos de dados.
- Desenvolver aplicação que utilize bancos de dados."><?php echo isset($dados['objetivos_gerais']) ? htmlspecialchars($dados['objetivos_gerais']) : ''; ?></textarea>
                </div>

                <div class="mb-6">
                    <label for="objetivos_especificos" class="block text-sm font-medium text-gray-700 mb-1">
                        Competências e Habilidades Esperadas
                    </label>
                    <textarea id="objetivos_especificos" name="objetivos_especificos" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Liste as competências e habilidades esperadas, por exemplo:
- Ler e interpretar artigos científicos escritos em Português e Inglês;
- Capacidade de trabalhar em grupo;
- Desenvolver projetos de software com bancos de dados;
- Escolher os membros e formar equipes para o trabalho final;
- Planejar, dividir e executar tarefas na elaboração de seminários;"><?php echo isset($dados['objetivos_especificos']) ? htmlspecialchars($dados['objetivos_especificos']) : ''; ?></textarea>
                </div>
            </div>

            <!-- Seção: Conteúdo Programático -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Conteúdo Programático</h2>
                <div class="mb-6">
                    <label for="cronograma_detalhado" class="block text-sm font-medium text-gray-700 mb-1">
                        Unidades e Tópicos
                    </label>
                    <textarea id="cronograma_detalhado" name="cronograma_detalhado" rows="8"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Detalhe as unidades e tópicos do conteúdo programático, por exemplo:

UNIDADE I - LEVANTAMENTO DE REQUISITOS
1.1 Requisitos funcionais
1.2 Requisitos não-funcionais
1.3 Prototipação

UNIDADE II - ANÁLISE DE REQUISITOS
2.1 Elicitação dos requisitos
2.2 Análise de requisitos
2.3 Registros dos requisitos"><?php echo isset($dados['cronograma_detalhado']) ? htmlspecialchars($dados['cronograma_detalhado']) : ''; ?></textarea>
                </div>
            </div>

            <!-- Seção: Metodologia e Avaliação -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Metodologia e Avaliação</h2>
                <div class="mb-6">
                    <label for="metodologia" class="block text-sm font-medium text-gray-700 mb-1">
                        Metodologia <span class="text-red-500">*</span>
                    </label>
                    <textarea id="metodologia" name="metodologia" required rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Descreva a metodologia utilizada para o ensino, por exemplo:
                              
Conforme os princípios que constituem o alicerce da matriz curricular, buscar-se-á integrar o conhecimento sobre bancos de dados com outras áreas do saber, dar um enfoque na estrutura base de formação de bancos de dados e suas aplicações. Desta forma, as aulas se darão, especialmente, de maneira expositiva, dialogadas, acrescidas de debates, e reflexões."><?php echo isset($dados['metodologia']) ? htmlspecialchars($dados['metodologia']) : ''; ?></textarea>
                </div>

                <div class="mb-6">
                    <label for="recursos_didaticos" class="block text-sm font-medium text-gray-700 mb-1">
                        Recursos Didáticos
                    </label>
                    <textarea id="recursos_didaticos" name="recursos_didaticos" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Descreva os recursos didáticos que serão utilizados, por exemplo:
                              
- Laboratório de informática
- Projetor multimídia
- Ambiente Virtual de Aprendizagem
- Ferramentas de desenvolvimento de software"><?php echo isset($dados['recursos_didaticos']) ? htmlspecialchars($dados['recursos_didaticos']) : ''; ?></textarea>
                </div>

                <div class="mb-6">
                    <label for="avaliacao" class="block text-sm font-medium text-gray-700 mb-1">
                        Sistema de Avaliação <span class="text-red-500">*</span>
                    </label>
                    <textarea id="avaliacao" name="avaliacao" required rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Descreva o sistema de avaliação, por exemplo:
                              
Durante a implementação do conteúdo curricular serão efetuadas avaliações somativas (quantitativas), com questões objetivas e dissertativas e avaliações formativas (qualitativas) ao longo do semestre.
1° Avaliação (AP1): Avaliação teórica com questões objetivas e dissertativas;
2° Avaliação (AP2): Avaliação teórica com questões objetivas e dissertativas; 
3° Avaliação (AP3): Trabalho final abordando conteúdos apresentados na Disciplina;"><?php echo isset($dados['avaliacao']) ? htmlspecialchars($dados['avaliacao']) : ''; ?></textarea>
                </div>
            </div>

            <!-- Seção: Bibliografia -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Bibliografia</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="bibliografia_basica" class="block text-sm font-medium text-gray-700 mb-1">
                            Bibliografia Básica
                        </label>
                        <textarea id="bibliografia_basica" name="bibliografia_basica" rows="6"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                  placeholder="Liste a bibliografia básica, incluindo autor, título, editora e links, por exemplo:

SERGIO, F. Java 8 - Ensino Didático - Desenvolvimento e Implementação de Aplicações. São Paulo: Editora Saraiva, 2015. 9788536519340. Disponível em: https://integrada.minhabiblioteca.com.br/#/books/9788536519340/"><?php echo isset($dados['bibliografia_basica']) ? htmlspecialchars($dados['bibliografia_basica']) : ''; ?></textarea>
                    </div>
                    
                    <div>
                        <label for="bibliografia_complementar" class="block text-sm font-medium text-gray-700 mb-1">
                            Bibliografia Complementar
                        </label>
                        <textarea id="bibliografia_complementar" name="bibliografia_complementar" rows="6"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                  placeholder="Liste a bibliografia complementar, incluindo autor, título, editora e links, por exemplo:

BARBOZA, Fabrício Felipe Meleto; FREITAS, Pedro Henrique Chagas. Modelagem e desenvolvimento de banco de dados. Porto Alegre: SAGAH, 2018. Disponivel em: https://integrada.minhabiblioteca.com.br/#/books/9788595025172/pageid/0"><?php echo isset($dados['bibliografia_complementar']) ? htmlspecialchars($dados['bibliografia_complementar']) : ''; ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Seção: Observações -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Observações Adicionais</h2>
                <div class="mb-6">
                    <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-1">
                        Observações
                    </label>
                    <textarea id="observacoes" name="observacoes" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Inclua aqui quaisquer observações adicionais sobre o plano de ensino"><?php echo isset($dados['observacoes']) ? htmlspecialchars($dados['observacoes']) : ''; ?></textarea>
                </div>
            </div>

            <div class="flex justify-end items-center space-x-3 border-t pt-6">
                <a href="index.php?page=planos-ensino" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Salvar Plano
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('plano-form');
    
    form.addEventListener('submit', function(e) {
        const curso = document.getElementById('curso_id').value;
        const disciplina = document.getElementById('disciplina_id').value;
        const professor = document.getElementById('professor_id').value;
        const objetivos = document.getElementById('objetivos_gerais').value.trim();
        const metodologia = document.getElementById('metodologia').value.trim();
        const avaliacao = document.getElementById('avaliacao').value.trim();
        
        let hasError = false;
        let errorMessage = '';
        
        if (!curso) {
            hasError = true;
            errorMessage += '- Selecione um curso\n';
        }
        
        if (!disciplina) {
            hasError = true;
            errorMessage += '- Selecione uma disciplina\n';
        }
        
        if (!professor) {
            hasError = true;
            errorMessage += '- Selecione um professor\n';
        }
        
        if (!objetivos) {
            hasError = true;
            errorMessage += '- Os objetivos gerais são obrigatórios\n';
        }
        
        if (!metodologia) {
            hasError = true;
            errorMessage += '- A metodologia é obrigatória\n';
        }
        
        if (!avaliacao) {
            hasError = true;
            errorMessage += '- A avaliação é obrigatória\n';
        }
        
        if (hasError) {
            e.preventDefault();
            alert('Por favor, corrija os seguintes erros:\n\n' + errorMessage);
        }
    });
});
</script>
