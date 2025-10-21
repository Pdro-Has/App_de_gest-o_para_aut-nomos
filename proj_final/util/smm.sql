Olá avaliador, esse é o banco que vc irá interagir nesse projeto!

para devs:(sujeito a alterações)











-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS mei;
USE mei;

-- Tabela de clientes
CREATE TABLE tb_clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome_cliente VARCHAR(15) NOT NULL,
    sobrenome_cliente VARCHAR(15) NOT NULL,
    cpf_cliente VARCHAR(14) NOT NULL UNIQUE
);

-- Tabela de endereços
CREATE TABLE tb_enderecos (
    id_endereco_cliente INT AUTO_INCREMENT PRIMARY KEY,
    cep_cliente VARCHAR(9) NOT NULL,
    bairro_cliente VARCHAR(30) NOT NULL,
    rua_cliente VARCHAR(20) NOT NULL,
    numero_cliente VARCHAR(5) NOT NULL
);

-- Relação N:N entre clientes e endereços (permite múltiplos endereços por cliente)
CREATE TABLE tb_clientes_enderecos (
    id_cliente INT NOT NULL,
    id_endereco_cliente INT NOT NULL,
    PRIMARY KEY (id_cliente, id_endereco_cliente),
    FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente) ON DELETE CASCADE,
    FOREIGN KEY (id_endereco_cliente) REFERENCES tb_enderecos(id_endereco_cliente) ON DELETE CASCADE
);

-- Tabela de contatos
CREATE TABLE tb_contatos (
    id_contatos INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    telefone_cliente VARCHAR(14),
    celular_cliente VARCHAR(14),
    email_cliente VARCHAR(100),
    FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente) ON DELETE CASCADE
);

-- Tabela de orçamentos
CREATE TABLE tb_orcamentos (
    id_orcamento INT AUTO_INCREMENT PRIMARY KEY,
    valor_orcamento DECIMAL(8,2) NOT NULL,
    id_cliente INT NOT NULL,
    id_endereco_cliente INT NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente) ON DELETE CASCADE,
    FOREIGN KEY (id_endereco_cliente) REFERENCES tb_enderecos(id_endereco_cliente) ON DELETE CASCADE
);

-- Tabela de medidas dos orçamentos
CREATE TABLE tb_orcamentos_medidas (
    id_orcamento_medida INT AUTO_INCREMENT PRIMARY KEY,
    id_orcamento INT NOT NULL,
    porta_altura DECIMAL(4,3) NOT NULL,
    porta_largura DECIMAL(4,3) NOT NULL,
    FOREIGN KEY (id_orcamento) REFERENCES tb_orcamentos(id_orcamento) ON DELETE CASCADE
);

-- Tabela de serviços
CREATE TABLE tb_servicos (
    id_servico INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    valor_servico DECIMAL(8,2) NOT NULL,
    forma_pagamento_servico VARCHAR(15) NOT NULL,
    data_servico DATE NOT NULL,
    status_servico VARCHAR(15) NOT NULL,
    prazo_servcio DATE NOT NULL,
    id_endereco_cliente INT NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente) ON DELETE CASCADE,
    FOREIGN KEY (id_endereco_cliente) REFERENCES tb_enderecos(id_endereco_cliente) ON DELETE CASCADE
);

-- Tabela de medidas dos serviços
CREATE TABLE tb_servicos_medidas (
    id_servico_medida INT AUTO_INCREMENT PRIMARY KEY,
    id_servico INT NOT NULL,
    porta_altura DECIMAL(4,3) NOT NULL,
    porta_largura DECIMAL(4,3) NOT NULL,
    FOREIGN KEY (id_servico) REFERENCES tb_servicos(id_servico) ON DELETE CASCADE
);
