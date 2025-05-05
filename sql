CREATE TABLE tarefa (
id SERIAL PRIMARY KEY,
descricao TEXT,
data_criacao TEXT,
data_prevista TEXT,
data_encerramento TEXT,
situacao TEXT
);

CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);