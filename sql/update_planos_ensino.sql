-- Atualizar estrutura da tabela planos_ensino
USE gecopec_db;

-- Remover a tabela existente e recriar com a estrutura correta
DROP TABLE IF EXISTS planos_ensino;

CREATE TABLE planos_ensino (
    id INT AUTO_INCREMENT PRIMARY KEY,
    disciplina_id INT NOT NULL,
    professor_id INT NOT NULL,
    curso_id INT NOT NULL,
    semestre INT NOT NULL,
    ano YEAR NOT NULL,
    objetivos_gerais TEXT,
    objetivos_especificos TEXT,
    metodologia TEXT,
    recursos_didaticos TEXT,
    avaliacao TEXT,
    bibliografia_basica TEXT,
    bibliografia_complementar TEXT,
    cronograma_detalhado TEXT,
    observacoes TEXT,
    status ENUM('pendente', 'aprovado', 'rejeitado') DEFAULT 'pendente',
    aprovado_por INT,
    data_aprovacao TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id) ON DELETE CASCADE,
    FOREIGN KEY (professor_id) REFERENCES professores(id) ON DELETE CASCADE,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
    FOREIGN KEY (aprovado_por) REFERENCES usuarios(id) ON DELETE SET NULL
);
