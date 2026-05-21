-- ============================================================
-- LGPD4DEVS — Schema completo do banco de dados
-- Atualizado em: 17/05/2026
-- ============================================================

CREATE DATABASE IF NOT EXISTS db_lgpd4devs
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_lgpd4devs

-- ============================================================
-- TABELAS
-- ============================================================

CREATE TABLE IF NOT EXISTS categorias (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS usuarios (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nome           VARCHAR(150) NOT NULL,
    email          VARCHAR(150) NOT NULL UNIQUE,
    senha          VARCHAR(255) NOT NULL,
    perfil         VARCHAR(20)  NOT NULL DEFAULT 'usuario',
    data_cadastro  DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS projetos (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id   INT NOT NULL,
    nome         VARCHAR(200) NOT NULL,
    descricao    TEXT,
    publico_alvo VARCHAR(50)  NOT NULL DEFAULT 'ambos',
    status       VARCHAR(50)  NOT NULL DEFAULT 'em_desenvolvimento',
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS perguntas (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    texto        VARCHAR(500) NOT NULL,
    peso         INT NOT NULL DEFAULT 1,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

CREATE TABLE IF NOT EXISTS respostas (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id    INT NOT NULL,
    pergunta_id   INT NOT NULL,
    resposta      TINYINT(1) NOT NULL,
    data_resposta DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id)  REFERENCES usuarios(id)  ON DELETE CASCADE,
    FOREIGN KEY (pergunta_id) REFERENCES perguntas(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS materiais (
    id                 INT AUTO_INCREMENT PRIMARY KEY,
    titulo             VARCHAR(200) NOT NULL,
    categoria          VARCHAR(100) NOT NULL,
    descricao_curta    TEXT,
    conteudo_detalhado TEXT,
    url_referencia     VARCHAR(500)
);

CREATE TABLE IF NOT EXISTS pergunta_material (
    pergunta_id INT NOT NULL,
    material_id INT NOT NULL,
    PRIMARY KEY (pergunta_id, material_id),
    FOREIGN KEY (pergunta_id) REFERENCES perguntas(id)  ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materiais(id)  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS historico_diagnosticos (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id       INT NOT NULL,
    projeto_id       INT NULL,
    percentual       INT NOT NULL,
    total_pontos     INT NOT NULL,
    pontos_obtidos   INT NOT NULL,
    total_perguntas  INT NOT NULL,
    perguntas_ok     INT NOT NULL,
    perguntas_falha  INT NOT NULL,
    data_salvo       DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)  ON DELETE CASCADE,
    FOREIGN KEY (projeto_id) REFERENCES projetos(id)  ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS historico_respostas (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    historico_id   INT NOT NULL,
    pergunta_id    INT NOT NULL,
    pergunta_texto VARCHAR(500) NOT NULL,
    pergunta_peso  INT NOT NULL,
    categoria_nome VARCHAR(100) NOT NULL,
    resposta       TINYINT(1) NOT NULL,
    FOREIGN KEY (historico_id) REFERENCES historico_diagnosticos(id) ON DELETE CASCADE,
    FOREIGN KEY (pergunta_id)  REFERENCES perguntas(id)
);

-- ============================================================
-- MIGRATION: para bancos já existentes antes desta versão
-- Execute apenas se o banco já estava criado anteriormente
-- ============================================================
-- ALTER TABLE usuarios ADD COLUMN perfil VARCHAR(20) NOT NULL DEFAULT 'usuario';
-- ALTER TABLE historico_diagnosticos ADD COLUMN projeto_id INT NULL AFTER usuario_id,
--     ADD CONSTRAINT fk_hd_projeto FOREIGN KEY (projeto_id) REFERENCES projetos(id) ON DELETE SET NULL;

-- ============================================================
-- Para promover um usuário a administrador:
-- UPDATE usuarios SET perfil = 'admin' WHERE email = 'seu@email.com';
-- ============================================================