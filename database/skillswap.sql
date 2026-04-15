-- Criar banco de dados
CREATE DATABASE skillswap;
USE skillswap;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    cidade VARCHAR(100),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    creditos INT DEFAULT 100,
    foto VARCHAR(255) DEFAULT 'default.jpg'
);

-- Tabela de habilidades
CREATE TABLE habilidades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL
);

-- Tabela de usuário_habilidades
CREATE TABLE usuario_habilidades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    habilidade_id INT,
    tipo ENUM('ensina', 'aprende'),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (habilidade_id) REFERENCES habilidades(id)
);

-- Tabela de aulas
CREATE TABLE aulas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    professor_id INT,
    aluno_id INT,
    data DATE,
    horario TIME,
    status ENUM('pendente', 'confirmada', 'concluida', 'cancelada') DEFAULT 'pendente',
    custo_creditos INT DEFAULT 10,
    FOREIGN KEY (professor_id) REFERENCES usuarios(id),
    FOREIGN KEY (aluno_id) REFERENCES usuarios(id)
);

-- Inserir habilidades populares
INSERT INTO habilidades (nome) VALUES 
('Guitarra'), ('Inglês'), ('Programação'), ('Culinária'), 
('Fotografia'), ('Design Gráfico'), ('Marketing Digital'), 
('Yoga'), ('Excel Avançado'), ('Edição de Vídeo');

-- Usuário admin de teste
INSERT INTO usuarios (nome, email, senha, cidade, creditos) 
VALUES ('Admin SkillSwap', 'admin@skillswap.com', MD5('admin123'), 'São Paulo', 500);
