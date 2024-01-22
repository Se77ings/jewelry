<?php

// -----------------------------------------------------------IDEAL----------------------------------------------------------- \\
//                                                                                                                             \\
// Usar Begin Transaction e Commit para garantir que tudo será inserido corretamente                                           \\
// Criar verificações para confirmar que tudo foi inserido, e caso não, retornar um erro informando aonde deu erro de inserção \\
//                                                                                                                             \\
//-----------------------------------------------------------------------------------------------------------------------------\\



require_once('../../model/conexao.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    var_dump($_POST);
    //Inserindo o Pedido
    $id_produto = $_POST['id_produto'];
    $nome = $_POST['nome'];
    $dataVenda = $_POST['dataVenda'];
    $condiçãoPagamento = $_POST['condiçãoPagamento'];
    $valorFinal = $_POST['valorFinal'];
    if (isset($_POST['entrada'])) {
        $entrada = $_POST['entrada'];
    }

    $produto_ids = $_POST['produto_id'];
    $produto_valores = $_POST['produto_valor'];
    $valor = 0;

//Setando o valor:
    for ($i = 0; $i < count($produto_valores); $i++) {
        $valor = $valor + $produto_valores[$i];
    }

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

//Inserindo Pedido
    $sql = "INSERT INTO pedidos (ID, valor, id_cliente, data, forma_pagamento) VALUES (NULL, '$valor', '$id_cliente', '$dataVenda', '$condiçãoPagamento')";
    $result = $conexao->query($sql);

    if ($result) {
        echo "<script>alert('Pedido realizado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao realizar pedido!');</script>";
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

//Inserindo Produtos:
    for ($i = 0; $i < count($produto_ids); $i++) {
        $produto_id = $produto_ids[$i];
        $produto_valor = $produto_valores[$i];

        $sql = "INSERT INTO produto_pedido(id_produto, codigo, valor, pedido_referencia) VALUES (NULL, '$produto_id', '$produto_valor', '$id_pedido')";
        $result = $conexao->query($sql);
        // echo "\n";echo $sql;
    }

//Inserindo Parcelas:
    if ($condiçãoPagamento == 1) {
        $dataVencimento = $dataVenda;
        $pago = 1;
    } else
        if ($condiçãoPagamento == 2) {
            $dataVencimento = date('Y-m-d', strtotime($dataVenda . ' + 30 days')); // deixei 30 dias por padrao
        } else
            if ($condiçãoPagamento == 3 || $condiçãoPagamento == 4) { //preciso gerar 2 titulos aqui, pois a condição 3 é 2 parcelas 30 dias cada
                $dataVencimento1 = date('Y-m-d', strtotime($dataVenda . ' + 30 days'));
                $dataVencimento2 = date('Y-m-d', strtotime($dataVencimento1 . ' + 30 days'));
            }
    if ($condiçãoPagamento == 1) {
        $sql = "INSERT INTO titulos (ID, valor_venda, valor_pago,data_emissao, data_vencimento, pedido_referencia, pago) VALUES (NULL,'$valor', '$valor', '$dataVenda', '$dataVencimento', '$id_pedido', '$pago')";
        $result = $conexao->query($sql);
        echo "<script>window.location.href = '../../view/NovaVenda/index.php';</script>";
    } else
        if ($condiçãoPagamento == 2) {
            $sql = "INSERT INTO titulos (ID, valor_venda, data_emissao, data_vencimento, pedido_referencia, pago) VALUES (NULL,'$valor', '$dataVenda', '$dataVencimento', '$id_pedido', '$pago')";
            $result = $conexao->query($sql);
            echo "<script>window.location.href = '../../view/NovaVenda/index.php';</script>";
        } else
            if ($condiçãoPagamento == 3) {
                $sql = "INSERT INTO titulos (ID, valor_venda, data_emissao, data_vencimento, pedido_referencia, pago) VALUES (NULL,'" . $valor / 2 . "', '$dataVenda', '$dataVencimento1', '$id_pedido', '$pago')";
                $result = $conexao->query($sql);
                $sql = "INSERT INTO titulos (ID, valor_venda, data_emissao, data_vencimento, pedido_referencia, pago) VALUES (NULL,'" . $valor / 2 . "', '$dataVenda', '$dataVencimento2', '$id_pedido', '$pago')";
                $result = $conexao->query($sql);
                echo "<script>window.location.href = '../../view/NovaVenda/index.php';</script>";
            } else
                if ($condiçãoPagamento == 4 && isset($_POST['entrada'])) {
                    $pago = 1;
                    $sql = "INSERT INTO titulos (ID, valor_venda, valor_pago, data_emissao, data_vencimento, pedido_referencia, pago) VALUES (NULL,'$entrada', '$entrada', '$dataVenda', '$dataVenda', '$id_pedido', '$pago')";
                    $result = $conexao->query($sql);
                    $pago = '';
                    $sql = "INSERT INTO titulos (ID, valor_venda, data_emissao, data_vencimento, pedido_referencia, pago) VALUES (NULL,'" . ($valor - $entrada) / 2 . "', '$dataVenda', '$dataVencimento1', '$id_pedido', '$pago')";
                    $result = $conexao->query($sql);
                    $sql = "INSERT INTO titulos (ID, valor_venda, data_emissao, data_vencimento, pedido_referencia, pago) VALUES (NULL,'" . ($valor - $entrada) / 2 . "', '$dataVenda', '$dataVencimento2', '$id_pedido', '$pago')";
                    $result = $conexao->query($sql);
                    echo "<script>window.location.href = '../../view/NovaVenda/index.php';</script>";
                }else{
                    echo "Houve um erro ao gerar as parcelas.";
                }
}
?>