<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["produto_id"])) {
    $produto_id = $_POST["produto_id"];


    $mysqli = mysqli_connect("localhost", "root", "123456789", "praticaFinal");

    if ($mysqli->connect_error) {
        die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
    }

    $query = "SELECT nome, descricao, preco, tipo_id FROM produtos WHERE id = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $produto_id);
        $stmt->execute();
        $stmt->bind_result($nome, $descricao, $preco, $tipo_id);

        if ($stmt->fetch()) {
            $detalhesProduto = array(
                "nome" => $nome,
                "descricao" => $descricao,
                "preco" => $preco,
                "tipo_id" => $tipo_id
            );

            echo json_encode($detalhesProduto);
        } else {
            echo "Produto não encontrado.";
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
