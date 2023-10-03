<?php
$servername = "localhost"; // Nome do servidor MySQL
$username = "root"; // Nome de usuário do MySQL
$password = "123456789"; // Senha do MySQL
$database = "praticaFinal"; // Nome do banco de dados

// Conecte-se ao servidor MySQL
$conn = new mysqli($servername, $username, $password);

// Verifique a conexão
if ($conn->connect_error) {
  die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Crie um banco de dados chamado "praticaFinal"
$sqlCreateDB = "CREATE DATABASE IF NOT EXISTS $database";

if ($conn->query($sqlCreateDB) === TRUE) {
  echo "Banco de dados criado com sucesso!";
} else {
  echo "Erro ao criar o banco de dados: " . $conn->error;
}

// Conecte-se ao banco de dados recém-criado
$conn->close();
$conn = new mysqli($servername, $username, $password, $database);

// Verifique a conexão com o banco de dados
if ($conn->connect_error) {
  die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Crie as tabelas
$sqlCreateTables = "
-- Tabela de Usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    tipo VARCHAR(5) NOT NULL, -- comum e admin
    senha VARCHAR(255) NOT NULL
);

-- Tabela de Tipos de Produtos
CREATE TABLE IF NOT EXISTS tipos_produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

-- Tabela de Produtos
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    tipo_id INT,
    FOREIGN KEY (tipo_id) REFERENCES tipos_produtos(id)
);
";

if ($conn->multi_query($sqlCreateTables)) {
  echo "Tabelas criadas com sucesso!";
} else {
  echo "Erro ao criar tabelas: " . $conn->error;
}

// Feche a conexão com o servidor MySQL
$conn->close();
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Usuário</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>

<body>
  <div class="container mt-5">
    <h2>Cadastro de Usuário</h2>
    <form action="../backend/register.php" method="POST">
      <div class="mb-3">
        <label for="nome" class="form-label">Nome:</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username:</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="mb-3">
        <label for="tipo" class="form-label">Tipo:</label>
        <select class="form-select" id="tipo" name="tipo" required>
          <option value="comum">Comum</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="senha" class="form-label">Senha:</label>
        <input type="password" class="form-control" id="senha" name="senha" required>
      </div>
      <div class="mb-3">
        <a href="../frontend/login.php"> Já tem conta? Faça o Login!</a>
      </div>
      <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>