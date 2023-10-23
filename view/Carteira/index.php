<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/lib/css/bootstrap.min.css">
    <title>Saldo:</title>
    <style>
        #central {
            margin: 10% 8%;
            border: solid 1px black;
            border-radius: 40px;
        }
    </style>
</head>

<body>
    <div class="container" id="central">
        <h1 id="saldo">Saldo: R$
            <?php
            require_once("../../model/conexao.php");
            require_once("../assets/global_functions/dateAndNumberFormatting.php");
            $sql = "SELECT saldo FROM carteira";
            $result = $conexao->query($sql);
            $row = $result->fetch();
            echo convertePonto($row["saldo"]);
            ?>
        </h1>
        <hr>

        <h4>Movimentações:</h4>
        <hr>
        <?php
        $sql = "SELECT * FROM historico_carteira ORDER BY ID DESC";
        $result = $conexao->query($sql);
        $totalRows = $result->rowCount();
        if ($totalRows > 0) {
            while ($row = $result->fetch()) {
                echo "<p> " . DatetimeYYYYMMDDtoDDMMYYYY($row['data_transacao']). "  -  Recebimento no valor de -> R$ " . convertePonto($row["valor_transacao"]) . "</p>";
            }
        } else {
            echo "0 results";
        }

        ?>

    </div>
</body>

</html>