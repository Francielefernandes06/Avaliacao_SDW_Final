<?php

session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $mysqli = mysqli_connect("localhost", "root", "123456789", "praticaFinal");


    if ($mysqli->connect_error) {
        die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
    }

 
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $stmt = $mysqli->prepare("SELECT id, nome, email, username, tipo, senha FROM usuarios WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
   
        $row = $result->fetch_assoc();


        if (password_verify($senha, $row["senha"])) {
          
            $_SESSION["usuario_id"] = $row["id"];
            $_SESSION["usuario_nome"] = $row["nome"];
            $_SESSION["usuario_tipo"] = $row["tipo"];

            
            header("Location: ../frontend/home.php");
            exit();
        } else {
            $_SESSION["mensagem"] = "Senha incorreta.";
            header("Location: ../frontend/login.php");
            exit();
        }
    } else {
        $_SESSION["mensagem"] = "Usuário não encontrado.";
        header("Location: ../frontend/login.php");
        exit();
    }

    $stmt->close();
    $mysqli->close();
} else {
    header("Location: ../frontend/login.php");
    exit();
}
?>
