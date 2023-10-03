<?php

$mysqli = mysqli_connect("localhost", "root", "123456789", "praticaFinal");

if (!$mysqli) {
  die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}else{
  echo "Conectado ao banco de dados";
}

