<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tipo_produto_id"])) {
    $tipo_produto_id = $_POST["tipo_produto_id"];

   
    $mysqli = mysqli_connect("localhost", "root", "123456789", "praticaFinal");

    if ($mysqli->connect_error) {
        die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
    }

    $query = "SELECT nome FROM tipos_produtos WHERE id = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $tipo_produto_id);
        $stmt->execute();
        $stmt->bind_result($nome);

        if ($stmt->fetch()) {
            $detalhesTipoProduto = array(
                "nome" => $nome,
            );

            echo json_encode($detalhesTipoProduto);
        } else {
            echo "Tipo de Produto não encontrado.";
        }

        $stmt->close();
    } else {
        echo "Erro na preparação da consulta.";
    }

    $mysqli->close();
} else {
    echo "Solicitação inválida.";
}
?>
