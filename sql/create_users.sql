-- Senha para todos os usuários: senha123 
-- (hash gerado com password_hash usando algoritmo padrão)

-- Inserir usuário administrador
INSERT INTO usuarios (nome, email, senha, tipo) VALUES 
('Administrador do Sistema', 'admin@gecopec.com', '$2y$10$lyPo.igIwLT3n1vkTmJIDensr4JA.Myy9vs1Fx.qBlSUm9aKVP/7mi', 'administrador');

-- Inserir coordenadores
INSERT INTO usuarios (nome, email, senha, tipo) VALUES 
('Prof. Carlos Silva', 'carlos.silva@instituicao.edu.br', '$2y$10$lyPo.igIwLT3n1vkTmJIDensr4JA.Myy9vs1Fx.qBlSUm9aKVP/7m', 'coordenador'),
('Profa. Ana Oliveira', 'ana.oliveira@instituicao.edu.br', '$2y$10$lyPo.igIwLT3n1vkTmJIDensr4JA.Myy9vs1Fx.qBlSUm9aKVP/7m', 'coordenador');

-- Inserir professores
INSERT INTO usuarios (nome, email, senha, tipo) VALUES 
('Prof. João Santos', 'joao.santos@instituicao.edu.br', '$2y$10$lyPo.igIwLT3n1vkTmJIDensr4JA.Myy9vs1Fx.qBlSUm9aKVP/7m', 'professor'),
('Profa. Maria Costa', 'maria.costa@instituicao.edu.br', '$2y$10$lyPo.igIwLT3n1vkTmJIDensr4JA.Myy9vs1Fx.qBlSUm9aKVP/7m', 'professor'),
('Prof. Roberto Lima', 'roberto.lima@instituicao.edu.br', '$2y$10$lyPo.igIwLT3n1vkTmJIDensr4JA.Myy9vs1Fx.qBlSUm9aKVP/7m', 'professor');

-- Inserir dados na tabela professores
INSERT INTO professores (usuario_id, matricula, data_nascimento, telefone, departamento, especializacao) VALUES 
(4, '20230001', '1980-05-15', '(11) 98765-4321', 'Departamento de Ciência da Computação', 'Inteligência Artificial'),
(5, '20230002', '1975-08-22', '(11) 98765-1234', 'Departamento de Sistemas de Informação', 'Banco de Dados'),
(6, '20230003', '1983-03-10', '(11) 98765-5678', 'Departamento de Engenharia de Software', 'Engenharia de Requisitos');
