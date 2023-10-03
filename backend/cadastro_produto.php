<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../frontend/login.php");
    exit();
}

$mysqli = mysqli_connect("localhost", "root", "123456789", "praticaFinal");

if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

$nomeProduto = $_POST["nomeProduto"];
$descricaoProduto = $_POST["descricaoProduto"];
$precoProduto = $_POST["precoProduto"];
$tipoProduto = $_POST["tipoProduto"];

$stmt = $mysqli->prepare("INSERT INTO produtos (nome, descricao, preco, tipo_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssdi", $nomeProduto, $descricaoProduto, $precoProduto, $tipoProduto);

if ($stmt->execute()) {
    $_SESSION["mensagem"] = "Produto cadastrado com sucesso.";
    header("Location: ../frontend/home.php");
    exit();
} else {
    $_SESSION["mensagem"] = "Erro ao cadastrar o produto.";
    header("Location: ../frontend/home.php");
    exit();
}

$stmt->close();
$mysqli->close();
?>
