<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido nº </title>
    <link rel="stylesheet" href="../assets/lib/css/personalStyle.css">
    <link rel="stylesheet" href="../assets/lib/css/styles.css">
    <style>
        body, h2, h3, h4, h1{
            font-size: 1.1em;
        }
        span{
            font-weight: bold;
            font-style: italic;
            color: rgb(150, 150, 150);
        }
        table{
            width: 100%;
        }

    </style>
</head>

<body>
    <div class="container container-fluid" style="width:65%; margin:auto">
        <?php
        require_once "../../model/conexao.php";
        require_once "../assets/global_functions/dateAndNumberFormatting.php";
        $id = $_GET['ID'];
        $placeholder = "nada ainda";

        $sql = "SELECT p.id_produto, p.valor, p.id_cliente, p.data, ps.nome, ps.telefone FROM pedidos p
        LEFT JOIN pessoas ps on p.id_cliente = ps.ID
        WHERE p.ID = $id";

        $resultado = $conexao->query($sql);
        if ($resultado) {
            while ($row = $resultado->fetch()) {
                echo "<div style='display:flex; justify-content: space-between;'><h1>Pedido nº $id</h1>";
                echo "<h1>" . YYYYMMDDtoDDMMYYYY($row['data'], '/') . "</h1></div><hr style='margin:0px; margin-bottom:15px'>";
                echo "<div style='display:flex; justify-content: space-between;'><h4><span>Cliente: </span>" . $row['nome'] . "</h4>";
                echo "<h4><span>Telefone: </span>" . $row['telefone'] . "</h4></div>";
                echo "<h4><span>Valor Total: </span> R$ " . $row['valor'] . " </h4>";
                echo "<h4><span>Forma de Pagamento:</span> $placeholder </h4>";
                echo "<h2 style='text-align:center;'>Produtos: </h2>";
                $sql2 = "SELECT * FROM produto_pedido WHERE pedido_referencia = $id";
                $resultado2 = $conexao->query($sql2);
                if ($resultado2) {
                    while ($row2 = $resultado2->fetch()) {
                        echo "<h3>$row2[1]</h3>";
                    }
                }
                echo "<h2 style='text-align:center;'>Títulos: </h2><hr>";
                $sql3 = "SELECT valor_venda, valor_pago, data_emissao, data_vencimento, pedido_referencia, pago, data_quitacao FROM titulos WHERE pedido_referencia = $id";
                $resultado3 = $conexao->query($sql3);
                if ($resultado3) {
                    echo "<table><thead><th>Valor Venda</th><th>Valor Pago</th><th>Data Emissão</th><th>Data Vencimento</th><th>pago</th><th>Data Quitação</th></thead><tbody>";
                    while ($row3 = $resultado3->fetch()) {
                        echo "<tr><td>" . $row3['valor_venda'] . "</td><td>" . $row3['valor_pago'] . "</td>";
                        echo "<td>" . YYYYMMDDtoDDMMYYYY($row3['data_emissao'], '/') . "</td>";
                        echo "<td>" . YYYYMMDDtoDDMMYYYY($row3['data_vencimento'], '/') . "</td>";
                        echo "<td>" . $row3['pago'] . "</td>";
                        echo $row3['data_quitacao'] == "0000-00-00" ? "<td>-----</td></tr>" : "<td>" . YYYYMMDDtoDDMMYYYY($row3['data_quitacao'], '/') . "</td></tr>";
                    }
                    echo "</tbody></table><hr>";
                }
            }
        }

        ?>
    </div>
</body>
<script>
    var url = window.location.href;
    url = url.split("?");
    var id = url[1];
    id = id.split("=");
    id = id[1];
    console.log(id);
    document.getElementsByTagName("title")[0].innerText += id;
</script>

</html>