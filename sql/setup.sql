CREATE DATABASE IF NOT EXISTS gecopec;
USE gecopec;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Usuário de teste (em produção, use senhas hash)
INSERT INTO users (name, email, password) VALUES 
('Administrador', 'admin@exemplo.com', 'senha123');
