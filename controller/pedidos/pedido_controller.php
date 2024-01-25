<?php
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(isset($_GET['operation'])){
        $operation = $_GET['operation'];
        $ID = $_GET['ID'];
        $motivo = $_GET['motivo'];

        require_once '../../model/conexao.php';
        if($operation == 'cancelar'){
            $sql = "UPDATE pedidos SET cancelado = 1, motivo_cancelamento = '$motivo' WHERE ID = $ID";
            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            $sql2 = "UPDATE titulos SET cancelado = 1 WHERE pedido_referencia = $ID";
            $stmt2 = $conexao->prepare($sql2);
            $stmt2->execute();

            header('Location: ../../view/DetalhandoPedidos/index.php?ID='.$ID);


        }
    }
}
?>