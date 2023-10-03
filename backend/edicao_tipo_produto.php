<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["tipo_produto_id"], $_POST["nomeTipoProdutoEdit"])) {
        $tipo_produto_id = $_POST["tipo_produto_id"];
        $nomeTipoProdutoEdit = $_POST["nomeTipoProdutoEdit"];
        

        $mysqli = mysqli_connect("localhost", "root", "123456789", "praticaFinal");


        if ($mysqli->connect_error) {
            die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
        }

        $query = "UPDATE tipos_produtos SET nome = ? WHERE id = ?";
        $stmt = $mysqli->prepare($query);

        if ($stmt) {
            $stmt->bind_param("si", $nomeTipoProdutoEdit, $tipo_produto_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $_SESSION["mensagem"] = "Tipo de Produto atualizado com sucesso";
                header("Location: ../frontend/home.php");
            } else {
                echo "Tipo de  Produto não encontrado ou não foi alterado.";
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
