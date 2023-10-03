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

$nomeTipoProduto = $_POST["nomeTipoProduto"];


$stmt = $mysqli->prepare("INSERT INTO tipos_produtos (nome) VALUES (?)");
$stmt->bind_param("s", $nomeTipoProduto);

if ($stmt->execute()) {
    $_SESSION["mensagem"] = "Tipo de produto cadastrado com sucesso.";
    header("Location: ../frontend/home.php");
    exit();
} else {
    $_SESSION["mensagem"] = "Erro ao cadastrar o tipo de produto.";
    header("Location: ../frontend/home.php");
    exit();
}

$stmt->close();
$mysqli->close();
?>
