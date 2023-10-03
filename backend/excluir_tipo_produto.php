<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["tipo_produto_id"])) {
        $tipo_produto_id = $_POST["tipo_produto_id"];

        $mysqli = mysqli_connect("localhost", "root", "123456789", "praticaFinal");


        if ($mysqli->connect_error) {
            die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
        }

        $query = "DELETE FROM tipos_produtos WHERE id = ?";
        $stmt = $mysqli->prepare($query);

        if ($stmt) {
            $stmt->bind_param("i", $tipo_produto_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Tipo de Produto excluído com sucesso.";
            } else {
                echo "Tipo de  Produto não encontrado ou já foi excluído.";
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
