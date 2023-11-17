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
    <link rel="stylesheet" href="../assets/lib/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/lib/css/personalStyle.css">

    <title>Saldo:</title>
    <style>
        #central {
            margin: 10% 8%;
            border-radius: 40px;
        }

        body {
            color: black;
        }

        h1 {
            text-align: center;
        }

        h4 {
            font-weight: 10px;
        }

        #iconEye {
            margin-left: 20px;
            margin-bottom: 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container" id="central">

        <h3 id="saldo">Saldo: <div style="display:flex; flex-align:row;justify-content:center; margin-top:10px">
                <h1 id="valor">R$
                    <?php
                    require_once("../../model/conexao.php");
                    require_once("../assets/global_functions/dateAndNumberFormatting.php");
                    $sql = "SELECT saldo FROM carteira";
                    $result = $conexao->query($sql);
                    $row = $result->fetch();
                    echo convertePonto($row["saldo"]);
                    ?>
                </h1>
                <h2 id="iconEye" onclick="toggleVisibility()" class="bi bi-eye"></h2>
            </div>
        </h3>
        <hr>
        <h4>Movimentações:</h4>
        <hr>
        <?php
        $sql = "SELECT * FROM historico_carteira ORDER BY ID DESC";
        $result = $conexao->query($sql);
        $totalRows = $result->rowCount();
        if ($totalRows > 0) {
            while ($row = $result->fetch()) {
                echo "<p> " . DatetimeYYYYMMDDtoDDMMYYYY($row['data_transacao']) . "  -  Recebimento-> R$ " . convertePonto($row["valor_transacao"]) . "</p>";
            }
        } else {
            echo "0 results";
        }

        ?>

    </div>
    <script>
        var valor = document.getElementById("valor").innerText;
        function toggleVisibility() {
            var id = document.getElementById("iconEye");
            if (id.className == "bi bi-eye-slash") {
                id.className = "bi bi-eye";
                document.getElementById("valor").innerText = valor;
            } else {
                id.className = "bi bi-eye-slash";
                document.getElementById("valor").innerText = "R$ " + "*****";
            }

        }
    </script>
</body>

</html>