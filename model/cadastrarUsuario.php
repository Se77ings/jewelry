<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['newUsername']) && isset($_POST['newPassword'])) {
        include_once("conexao.php");
        $id = $_POST['id'];
        $username = $_POST['newUsername'];
        $password = $_POST['newPassword'];
        $hashPassword = hash('sha512', $password);

        $sql = "INSERT INTO usuarios (ID, login, senha, ativo) VALUES ($id, '$username', '$hashPassword', 1)";
        $result = $conexao->query($sql);
        if ($result){
            echo "Cadastrado com sucesso.";
        }else{
            echo "Erro ao cadastrar.";
        }
    }
}
?>