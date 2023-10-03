<?php
$mysqli = mysqli_connect("localhost", "root", "123456789", "praticaFinal");

if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

$query = "SELECT id, nome FROM tipos_produtos";
$result = $mysqli->query($query);

if ($result) {
    $tiposProdutos = array();

    while ($row = $result->fetch_assoc()) {
        $tiposProdutos[] = $row;
    }

    $result->close();
    $mysqli->close();

    echo json_encode($tiposProdutos);
} else {
    echo "Erro na consulta de tipos de produtos.";
}
?>
