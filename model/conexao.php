<?php
    $banco = 'jewelry_db';
    $user = 'root';
    $pass = '';

    $conexao = new
    PDO("mysql:host=localhost;dbname=$banco", $user, $pass);
?>

<!-- 
---------Production:---------
$banco = 'jewelry_db';
$user = 'jewelry';
$pass = 'MJWP2RmRxnx2ay4D'

$conexao = new PDO("mysql:host=144.126.213.110;port=9911;dbname=$banco", $user, $pass);

 -->