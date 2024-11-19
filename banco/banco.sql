DROP DATABASE IF EXISTS docs_gym;
CREATE DATABASE docs_gym;
USE docs_gym;

-- Criar tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    sobrenome VARCHAR(255) NOT NULL,
    data_nascimento DATE NOT NULL,
    genero ENUM('m', 'f', 'n') NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    receber_informativos BOOLEAN DEFAULT FALSE,
    termos_aceitos BOOLEAN DEFAULT FALSE,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_type ENUM('user', 'instrutor') DEFAULT 'user'
);

-- Criar tabela de ficha_treino
CREATE TABLE ficha_treino (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    exercicio VARCHAR(255) NOT NULL,
    series INT NOT NULL,
    repeticoes INT NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

INSERT INTO usuarios (
    email, senha, nome, sobrenome, data_nascimento, genero, cpf, user_type
) VALUES (
    'instrutor@docs.com', 
    '$2y$10$hRGW7m902c6qa397DGJsvuQtZ5n4gXAAPWw7CCzrdrh9kVFoui9mi', 
    'João',           
    'Leal',            
    '1985-03-12',        
    'm',                  
    '12345678901',       
    'instrutor'
);

-- Verificar se a tabela foi criada corretamente
SELECT * FROM ficha_treino;
SELECT * FROM usuarios;