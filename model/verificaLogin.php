<?php
session_start();
include_once("conexao.php");
// var_dump($_POST);
if (isset($_POST["username"])) {
    $username = $_POST["username"];
    $senha = $_POST["password"];
    $senhaC = hash('sha512', $senha);

    $sql = "SELECT * FROM usuarios WHERE login = '$username' AND senha = '$senhaC'";
    $result = $conexao->query($sql);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $_SESSION["login"] = $row["login"];
        // var_dump($_SESSION);
        header("Location: ../view/index.php");
    } else {
        header("Location: ../index.html?p=loginError");
    }

} else if (isset($_SESSION["login"])) {
    header("Location: ../view/index.php");
}
?>