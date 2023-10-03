<?php 

require_once('conexao.php');



session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $tipo = $_POST["tipo"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT); // Hash da senha

    $stmt = $mysqli->prepare("INSERT INTO usuarios (nome, email, username, tipo, senha) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nome, $email, $username, $tipo, $senha);

    if ($stmt->execute()) {
        $_SESSION["mensagem"] = "Cadastro realizado com sucesso. Faça o login.";
        header("Location: ../frontend/home.php");
        exit();
    } else {
       
        $_SESSION["mensagem"] = "Erro ao cadastrar o usuário.";
        header("Location: ../frontend/resgister.php");
        exit();
    }

    $stmt->close();
    $mysqli->close();
} else {
    header("Location: ../frontend/register.php");
    exit();
}
?>
