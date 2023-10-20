<?php
    include('../../model/conexao.php');
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];

    $sql = "INSERT INTO pessoas (nome, telefone) VALUES ('$nome', '$telefone')";
    $result = $conexao->query($sql);

    if ($result) {
        echo "1";
    } else {
        echo "0";
    }

?>