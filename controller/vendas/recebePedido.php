<?php
require_once('../../model/conexao.php');
// var_dump($_POST);
// array(4) { ["id_produto"]=> string(1) "1" ["valor"]=> string(2) "25" ["nome"]=> string(13) "Gabriel Lucas" ["telefone"]=> string(11) "17992304335" }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Inserindo o Pedido
    $id_produto = $_POST['id_produto'];
    $valor = $_POST['valor'];
    $nome = $_POST['nome'];
    $dataVenda = $_POST['dataVenda'];
    $condiçãoPagamento = $_POST['condiçãoPagamento'];


    $sqlIDCliente = "SELECT id FROM pessoas WHERE nome = :nome";
    $stmt = $conexao->prepare($sqlIDCliente);
    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($resultado) {
        $id_cliente = $resultado['id'];
    } else {
        echo "Erro na inserção";
    }
    // var_dump($id_cliente);

    $sql = "INSERT INTO pedidos (ID, id_produto, valor, id_cliente, data) VALUES (NULL,'$id_produto', '$valor', '$id_cliente', '$dataVenda')";
    $result = $conexao->query($sql);

    if ($result) {
        echo "<script>alert('Pedido realizado com sucesso!');</script>";
        // echo "<script>window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Erro ao realizar pedido!');</script>";
        // echo "<script>window.location.href = 'index.php';</script>";
    }
    //Inserir o Título de Direito:
    $pago = '';

    $sqlGetLastInsertID = "SELECT id FROM pedidos ORDER BY id DESC LIMIT 1";
    $stmt = $conexao->prepare($sqlGetLastInsertID);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($resultado) {
        $id_pedido = $resultado['id'];
    } else {
        echo "Erro na obtenção do ID do pedido";
    }

    if ($condiçãoPagamento == 1) {
        $dataVencimento = $dataVenda;
        $pago = 1;
    } else
        if ($condiçãoPagamento == 2) {
            $dataVencimento = date('Y-m-d', strtotime($dataVenda . ' + 30 days')); // deixei 30 dias por padrao
        } else
            if ($condiçãoPagamento == 3) { //preciso gerar 2 titulos aqui, pois a condição 3 é 2 parcelas 30 dias cada
                $dataVencimento1 = date('Y-m-d', strtotime($dataVenda . ' + 30 days'));
                $dataVencimento2 = date('Y-m-d', strtotime($dataVencimento1 . ' + 30 days'));
            }
    if ($condiçãoPagamento == 1 || $condiçãoPagamento == 2) {
        $sql = "INSERT INTO titulos (ID, valor_venda, data_emissao, data_vencimento, pedido_referencia, pago) VALUES (NULL,'$valor', '$dataVenda', '$dataVencimento', '$id_pedido', '$pago')";
        $result = $conexao->query($sql);
    } else
        if ($condiçãoPagamento == 3) {
            $sql = "INSERT INTO titulos (ID, valor_venda, data_emissao, data_vencimento, pedido_referencia, pago) VALUES (NULL,'".$valor/2 ."', '$dataVenda', '$dataVencimento1', '$id_pedido', '$pago')";
            $result = $conexao->query($sql);
            $sql = "INSERT INTO titulos (ID, valor_venda, data_emissao, data_vencimento, pedido_referencia, pago) VALUES (NULL,'".$valor/2 ."', '$dataVenda', '$dataVencimento2', '$id_pedido', '$pago')";
            $result = $conexao->query($sql);

        }
}
?>