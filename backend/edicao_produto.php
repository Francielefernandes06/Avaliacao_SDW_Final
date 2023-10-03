<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["produto_id"], $_POST["nomeProdutoEditar"], $_POST["descricaoProdutoEditar"], $_POST["precoProdutoEditar"], $_POST["tipoProdutoEditar"])) {
        $produto_id = $_POST["produto_id"];
        $nomeProdutoEditar = $_POST["nomeProdutoEditar"];
        $descricaoProdutoEditar = $_POST["descricaoProdutoEditar"];
        $precoProdutoEditar = $_POST["precoProdutoEditar"];
        $tipoProdutoEditar = $_POST["tipoProdutoEditar"];

        $mysqli = mysqli_connect("localhost", "root", "123456789", "praticaFinal");


        if ($mysqli->connect_error) {
            die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
        }

        $query = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, tipo_id = ? WHERE id = ?";
        $stmt = $mysqli->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ssdii", $nomeProdutoEditar, $descricaoProdutoEditar, $precoProdutoEditar, $tipoProdutoEditar, $produto_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $_SESSION["mensagem"] = "Produto atualizado com sucesso";
                header("Location: ../frontend/home.php");
            } else {
                echo "Produto não encontrado ou não foi alterado.";
            }

            $stmt->close();
        } else {
            echo "Erro na preparação da consulta.";
        }

        $mysqli->close();
    } else {
        echo "Campos obrigatórios não fornecidos.";
    }
} else {
    echo "Método de solicitação inválido.";
}
?>
