<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido nº </title>
    <link rel="stylesheet" href="../assets/lib/css/personalStyle.css">
    <link rel="stylesheet" href="../assets/lib/css/styles.css">
    <style>
        body,
        h2,
        h3,
        h4,
        h1 {
            font-size: 1.1em;
        }

        #bckd {
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            width: 65%;
        }

        span {
            font-weight: bold;
            font-style: italic;
            color: rgb(150, 150, 150);
        }

        table {
            width: 100%;
        }

        #tableProd>tbody>tr>td{
            text-align: center;
        }

        #tableProd{
            border-bottom: solid 1px black;
        }

        #tableParc>tbody>tr>td{
            text-align: center;
        }

        #tableParc>thead>tr>th{
            text-align: center;
        }

        @media screen and (max-width:500px) {
            #bckd {
                width: 100%;
                margin: 10px;
                background-color: rgba(255, 255, 255, 0.55);
                box-sizing: border-box;
                overflow-x: hidden;
            }
            #flex1{
                flex-direction: column;
            
            }
        }
    </style>
</head>

<body>
    <div class="container container-fluid" id="bckd">
        <?php
        require_once "../../model/conexao.php";
        require_once "../assets/global_functions/dateAndNumberFormatting.php";
        $id = $_GET['ID'];
        $placeholder = "nada ainda";

        $sql = "SELECT p.valor, p.id_cliente, p.data, ps.nome, ps.telefone, fp.descricao FROM pedidos p
        LEFT JOIN pessoas ps on p.id_cliente = ps.ID
        LEFT JOIN formas_pagamentos fp on fp.ID = p.forma_pagamento
        WHERE p.ID = $id";

        $resultado = $conexao->query($sql);
        if ($resultado) {
            while ($row = $resultado->fetch()) {
                echo "<div style='display:flex; justify-content: space-between;'><h1>Pedido nº $id</h1>";
                echo "<h1>" . YYYYMMDDtoDDMMYYYY($row['data'], '/') . "</h1></div><hr style='margin:0px; margin-bottom:15px'>";
                echo "<div id='flex1' style='display:flex; justify-content: space-between;'><h4><span>Cliente: </span>" . $row['nome'] . "</h4>";
                echo "<h4><span>Telefone: </span>" . $row['telefone'] . "</h4></div>";
                echo "<h4><span>Forma de Pagamento:</span> ". $row['descricao']." </h4>";

                echo "<br><hr><h2 style='text-align:center;'>Produtos: </h2><hr>";
                $sql2 = "SELECT * FROM produto_pedido WHERE pedido_referencia = $id";
                $resultado2 = $conexao->query($sql2);
                if ($resultado2) {
                    echo "<table id='tableProd' style='width: fit-content; margin:auto;'><thead><th style='padding: 0px 15px'>Código</th><th style='padding: 0px 15px'>Valor</th></thead>";
                    while ($row2 = $resultado2->fetch()) {
                        echo "<tr><td>".$row2[1]."</td><td>".$row2[3]."</td></tr>";
                    }
                }
                echo "</table>";
                echo "<h4 style='text-align:center;'><span>Valor Total: </span> R$ " . $row['valor'] . " </h4>";
                echo "<br><hr><h2 style='text-align:center;'>Parcelas: </h2><hr>";
                $sql3 = "SELECT valor_venda, valor_pago, data_emissao, data_vencimento, pedido_referencia, pago, data_quitacao FROM titulos WHERE pedido_referencia = $id";
                $resultado3 = $conexao->query($sql3);
                if ($resultado3) {
                    echo "<table id='tableParc'><thead><th>Valor</th><th>Vencimento</th><th>Pago</th><th>Situação</th></thead><tbody>";
                    while ($row3 = $resultado3->fetch()) {
                        echo "<tr><td>" . $row3['valor_venda'] . "</td>";
                        echo "<td>" . YYYYMMDDtoDDMMYYYY($row3['data_vencimento'], '/') . "</td>";
                        echo "<td>" . $row3['valor_pago'] . "</td>";
                        echo  $row3['pago'] == 1? "<td>Pago</td>": "<td>Em Aberto</td>";
                        // echo $row3['data_quitacao'] == "0000-00-00" ? "<td>Aberto</td></tr>" : "<td>" . YYYYMMDDtoDDMMYYYY($row3['data_quitacao'], '/') . "</td></tr>";
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
    // console.log(id);
    document.getElementsByTagName("title")[0].innerText += id;
</script>

</html>
<!-- 
array(10) { 
    ["id_produto"]=> string(1) "3"
    [0]=> string(1) "3" 
    ["codigo"]=> string(2) "42" 
    [1]=> string(2) "42" 
    ["descricao"]=> NULL 
    [2]=> NULL 
    ["valor"]=> string(5) "15.00" 
    [3]=> string(5) "15.00" 
    ["pedido_referencia"]=> string(2) "32" 
    [4]=> string(2) "32"
} -->