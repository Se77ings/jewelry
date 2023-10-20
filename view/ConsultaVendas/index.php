<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../lib/css/bootstrap.min.css">
    <title>Consulta Vendas:</title>
    <style>
        .card {
            margin-top: 20px;
            border: solid 1px black;
            border-radius: 20px;
        }
        table{
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container container-fluid">
        <h4>Consultar Vendas:</h4>
        <h5>Exibindo da mais antiga para a mais recente</h5>
        <table class="table table-hover">
            <tr>
                <th>Data da Venda:</th>
                <th>Valor da Venda:</th>
                <th>Nome do Cliente:</th>
                <th>ID Produto:</th>
            </tr>
            <tbody>
                <?php
                require_once("../../model/conexao.php");

                // Retrieve data from the sales table
                $sql = "SELECT * FROM pedidos ORDER BY data ASC";
                $result = $conexao->query($sql);

                // Verifique se a consulta foi bem-sucedida
                if ($result) {
                    $totalRows = $result->rowCount();

                    if ($totalRows > 0) {
                        while ($row = $result->fetch()) {
                            echo "<tr id='". $row["ID"] ."'>";
                            echo "<td>" . $row["data"] . "</td>";
                            echo "<td>" . $row["valor"] . "</td>";
                            echo "<td> Por enquanto nenhum</td>";
                            echo "<td>" . $row["id_produto"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "0 results";
                    }
                } else {
                    // Trate o erro na execução da consulta, se necessário
                    echo "Erro na consulta: " . $conexao->errorInfo()[2];
                }

                $conexao = null; 
                
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>