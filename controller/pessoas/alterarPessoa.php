<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../model/conexao.php';

    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];

    $sql = "UPDATE pessoas SET nome = '$nome', telefone = '$telefone' WHERE id = $id";

    $stmt = $conexao->prepare("UPDATE pessoas SET nome = :nome, telefone = :telefone WHERE id = :id");

    // Bind parameters
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo 1;
    } else {
        echo 0;
    }
}
?>