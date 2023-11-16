<?php
if (isset($_POST)) {
    require_once('../../model/conexao.php');

    $id_produto = $_POST['id_produto'];
    $dataManutencao = $_POST['dataManutencao'];
    $motivo = $_POST['motivo'];
    $descricaoProduto = $_POST['descricao_produto'];

    $sql = "INSERT INTO manutencao (ID, id_produto, descricao, data, motivo) VALUES (NULL, '$id_produto', '$descricaoProduto', '$dataManutencao', '$motivo')";
    $result = $conexao->query($sql);
    if($result){
        echo "<script>alert('Manutenção registrada com sucesso!');</script>";
        echo "<script>window.location.href = '../../view/Manuten%C3%A7%C3%A3o/index.php';</script>";
    }else{
        echo "<script>alert('Deu algum erro, contate o Gabriel!');</script>";
    }

}

?>