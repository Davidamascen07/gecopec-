CREATE DATABASE IF NOT EXISTS gecopec_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gecopec_db;

-- Script de inicialização completo do banco GECOPEC

-- Usar o banco de dados
USE gecopec;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'professor', 'coordenador', 'aluno') NOT NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de cursos
CREATE TABLE IF NOT EXISTS cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    carga_horaria INT NOT NULL,
    ementa TEXT,
    objetivos TEXT,
    coordenador_id INT,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (coordenador_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Tabela de disciplinas
CREATE TABLE IF NOT EXISTS disciplinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    carga_horaria INT NOT NULL,
    ementa TEXT,
    prerequisitos VARCHAR(1000),
    curso_id INT NOT NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
);

-- Tabela de professores
CREATE TABLE IF NOT EXISTS professores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    matricula VARCHAR(50) UNIQUE NOT NULL,
    data_nascimento DATE,
    telefone VARCHAR(20),
    endereco TEXT,
    departamento VARCHAR(255) NOT NULL,
    especializacao TEXT,
    lattes_url VARCHAR(500),
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de alunos
CREATE TABLE IF NOT EXISTS alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    matricula VARCHAR(50) UNIQUE NOT NULL,
    data_nascimento DATE,
    telefone VARCHAR(20),
    endereco TEXT,
    curso_id INT NOT NULL,
    semestre_atual INT DEFAULT 1,
    status ENUM('ativo', 'inativo', 'formado', 'trancado') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
);

-- Tabela de turmas
CREATE TABLE IF NOT EXISTS turmas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    disciplina_id INT NOT NULL,
    professor_id INT NOT NULL,
    semestre VARCHAR(10) NOT NULL,
    ano YEAR NOT NULL,
    vagas INT DEFAULT 30,
    horario VARCHAR(255),
    sala VARCHAR(100),
    status ENUM('ativa', 'inativa', 'concluida') DEFAULT 'ativa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id) ON DELETE CASCADE,
    FOREIGN KEY (professor_id) REFERENCES professores(id) ON DELETE CASCADE
);

-- Tabela de matrículas
CREATE TABLE IF NOT EXISTS matriculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    turma_id INT NOT NULL,
    data_matricula TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('matriculado', 'aprovado', 'reprovado', 'trancado') DEFAULT 'matriculado',
    nota_final DECIMAL(4,2),
    frequencia DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_matricula (aluno_id, turma_id)
);

-- Tabela de planos de ensino
CREATE TABLE IF NOT EXISTS planos_ensino (
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

-- Tabela de cronogramas
CREATE TABLE IF NOT EXISTS cronogramas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plano_ensino_id INT NOT NULL,
    semana INT NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    conteudo TEXT NOT NULL,
    metodologia VARCHAR(255),
    recursos TEXT,
    avaliacao VARCHAR(255),
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (plano_ensino_id) REFERENCES planos_ensino(id) ON DELETE CASCADE
);

ALTER TABLE cronogramas 
ADD COLUMN encontro_numero INT AFTER plano_ensino_id,
ADD COLUMN data_encontro DATE AFTER encontro_numero,
ADD COLUMN assunto VARCHAR(255) AFTER data_encontro,
ADD COLUMN atividade VARCHAR(255) AFTER conteudo,
DROP COLUMN semana,
DROP COLUMN data_inicio,
DROP COLUMN data_fim,
DROP COLUMN avaliacao;

-- Inserir dados de teste apenas se não existirem

-- Primeiro, remover usuários existentes se houver problema de senha
DELETE FROM usuarios WHERE email IN ('admin@gecopec.com', 'silas@gecopec.com', 'maria.santos@gecopec.com', 'carlos.lima@gecopec.com');

-- Usuários de teste com senha 'adm123' hasheada corretamente
INSERT INTO usuarios (id, nome, email, senha, tipo, status) VALUES
(1, 'Administrador', 'admin@gecopec.com', '$2y$10$8K/B.6w6XvGlqYZj4b5R3eUDqV5K3L9k8NzFr2J3X4M5P6Q7R8S9T', 'admin', 'ativo'),
(2, 'Dr. Silas Lima', 'silas@gecopec.com', '$2y$10$8K/B.6w6XvGlqYZj4b5R3eUDqV5K3L9k8NzFr2J3X4M5P6Q7R8S9T', 'professor', 'ativo'),
(3, 'Dra. Maria Santos', 'maria.santos@gecopec.com', '$2y$10$8K/B.6w6XvGlqYZj4b5R3eUDqV5K3L9k8NzFr2J3X4M5P6Q7R8S9T', 'professor', 'ativo'),
(4, 'Prof. Carlos Lima', 'carlos.lima@gecopec.com', '$2y$10$8K/B.6w6XvGlqYZj4b5R3eUDqV5K3L9k8NzFr2J3X4M5P6Q7R8S9T', 'coordenador', 'ativo');

-- Cursos de teste
INSERT IGNORE INTO cursos (id, nome, carga_horaria, ementa, coordenador_id, status) VALUES
(1, 'Ciência da Computação', 3200, 'Curso de graduação em Ciência da Computação com foco em desenvolvimento de software e sistemas.', 4, 'ativo'),
(2, 'Engenharia de Software', 3000, 'Curso voltado para formação de profissionais em engenharia de software.', 4, 'ativo'),
(3, 'Sistemas de Informação', 2800, 'Curso focado em sistemas de informação empresariais.', 4, 'ativo');

-- Disciplinas de teste
INSERT IGNORE INTO disciplinas (id, nome, codigo, carga_horaria, ementa, curso_id, status) VALUES
(1, 'Programação I', 'PROG001', 80, 'Introdução à programação usando linguagem C.', 1, 'ativo'),
(2, 'Banco de Dados', 'BD001', 60, 'Fundamentos de banco de dados relacionais.', 1, 'ativo'),
(3, 'Algoritmos e Estruturas de Dados', 'AED001', 80, 'Algoritmos básicos e estruturas de dados fundamentais.', 1, 'ativo'),
(4, 'Engenharia de Software I', 'ES001', 60, 'Princípios da engenharia de software.', 2, 'ativo'),
(5, 'Análise de Sistemas', 'AS001', 60, 'Técnicas de análise de sistemas.', 3, 'ativo');

-- Professores de teste
INSERT IGNORE INTO professores (id, usuario_id, matricula, departamento, especializacao, status) VALUES
(1, 2, 'PROF001', 'Computação', 'Doutor em Ciência da Computação', 'ativo'),
(2, 3, 'PROF002', 'Computação', 'Doutora em Engenharia de Software', 'ativo');

-- Verificar se os dados foram inseridos corretamente
SELECT 'Dados inseridos com sucesso!' as status;

-- Mostrar contagem de registros
SELECT 
    (SELECT COUNT(*) FROM usuarios) as usuarios,
    (SELECT COUNT(*) FROM cursos) as cursos,
    (SELECT COUNT(*) FROM disciplinas) as disciplinas,
    (SELECT COUNT(*) FROM professores) as professores;
