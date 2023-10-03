<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["produto_id"])) {
        $produto_id = $_POST["produto_id"];

        $mysqli = mysqli_connect("localhost", "root", "123456789", "praticaFinal");


        if ($mysqli->connect_error) {
            die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
        }

        $query = "DELETE FROM produtos WHERE id = ?";
        $stmt = $mysqli->prepare($query);

        if ($stmt) {
            $stmt->bind_param("i", $produto_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Produto excluído com sucesso.";
            } else {
                echo "Produto não encontrado ou já foi excluído.";
            }

            $stmt->close();
        } else {
            echo "Erro na preparação da consulta.";
        }

        $mysqli->close();
    } else {
        echo "ID do produto não fornecido.";
    }
} else {
    echo "Método de solicitação inválido.";
}
