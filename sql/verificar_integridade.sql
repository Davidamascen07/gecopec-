-- Verificar integridade dos dados para planos de ensino

-- Verificar se há professores cadastrados
SELECT COUNT(*) AS total_professores FROM professores;

-- Verificar se há disciplinas cadastradas
SELECT COUNT(*) AS total_disciplinas FROM disciplinas;

-- Verificar se há cursos cadastrados
SELECT COUNT(*) AS total_cursos FROM cursos;

-- Verificar professor_id em relação à tabela de professores
SELECT p.id, p.usuario_id, u.nome 
FROM professores p
LEFT JOIN usuarios u ON p.usuario_id = u.id
LIMIT 10;

-- Verificar disciplina_id em relação à tabela de disciplinas
SELECT d.id, d.nome, d.codigo, c.nome AS curso_nome
FROM disciplinas d
LEFT JOIN cursos c ON d.curso_id = c.id
LIMIT 10;

-- Verificar curso_id em relação à tabela de cursos
SELECT c.id, c.nome, u.nome AS coordenador_nome
FROM cursos c
LEFT JOIN usuarios u ON c.coordenador_id = u.id
LIMIT 10;

-- Verificar planos de ensino existentes (se houver)
SELECT * FROM planos_ensino LIMIT 10;
