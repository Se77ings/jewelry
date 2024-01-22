<?php
session_start();
if (!isset($_SESSION["login"])) {
    header('Location: ../../index.php?unlog');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/lib/css/styles.css">
    <link rel="stylesheet" href="../assets/lib/css/personalStyle.css">
    <title>Consulta Vendas:</title>

    <style>
        body {
            justify-content: flex-start;
        }

        .card {
            margin-top: 20px;
            border: solid 1px black;
            border-radius: 20px;
        }

        table {
            width: 100%;
            border-radius: 10px;
        }

        h4 {
            text-align: center;
            margin-top: 10px;
        }
        .linha-clicavel{
            cursor: pointer;
        }

        @media screen and (max-width:500px) {
            
        }
    </style>
</head>

<body>
    <div class="container container-fluid">
        <h4>Consultando Pedidos:</h4>
        <hr>
        <table class="table table-hover">
            <tr id="header" style='border-bottom:solid black 1px'>
                <th>Ped. nº:</th>
                <th>Data:</th>
                <th>Valor:</th>
                <th>Cliente:</th>
            </tr>
            <tbody>
                <?php
                require_once("../../model/conexao.php");
                require_once("../assets/global_functions/dateAndNumberFormatting.php");

                // Retrieve data from the sales table
                $sql = "SELECT p.ID, p.data, p.valor, prs.nome as 'cliente' FROM pedidos p
                        LEFT JOIN pessoas prs on prs.ID = p.id_cliente
                        ORDER BY data ASC";
                $result = $conexao->query($sql);

                // Verifique se a consulta foi bem-sucedida
                if ($result) {
                    $totalRows = $result->rowCount();

                    if ($totalRows > 0) {
                        while ($row = $result->fetch()) {
                            echo "<tr id='" . $row["ID"] . "' class='linha-clicavel' onclick='redirecionarParaDetalhes(" . $row["ID"] . ")'>";
                            echo "<td>" . $row["ID"] . "</td>";
                            echo "<td>" . YYYYMMDDtoDDMMYYYY($row["data"], '/') . "</td>";
                            echo "<td>" . convertePonto($row["valor"]) . "</td>";
                            echo "<td>" . $row["cliente"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "0 results";
                    }
                } else {
                    // Trate o erro na execução da consulta, se necessário
                    echo "Erro na consulta: " . $conexao->errorInfo()[2];
                }
                echo "
                    <script>
                        function redirecionarParaDetalhes(id) {
                            window.location.href = '../DetalhandoPedidos/index.php?ID=' + id;
                        }
                    </script>
                    ";
                $conexao = null;

                ?>
            </tbody>
        </table>
    </div>
</body>

</html>