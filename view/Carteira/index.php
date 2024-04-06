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

    <title>Saldo:</title>
    <style>
        #central {
            margin: 10% 8%;
            border-radius: 40px;
        }

        body {
            color: black;
            font-size: 20px;
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

        .LinhaMes {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        .LinhaMes button {
            border: none;
            background-color: transparent;
            color: black;
            font-size: 20px;
        }

        button:hover {
            transition: 0.4s;
            scale: 1.5;
        }

        button:active {
            color: white;
            transition: 0s;
        }

        .LinhaMes p {
            margin: 0px;
            font-size: 20px;
        }

        .rows p {
            margin-bottom: 0px;
        }
        .font-08{
            font-size: 0.8rem !important;
        }
        .font-120{
            font-size: 1.2rem !important;
        }

        @media screen and (max-width:500px) {

            button:hover {
                transition: 0s;
                scale: 1;
            }

            button:active {
                color: white;
                transition: 0.2s;
                scale: 1.5;
            }
        }
    </style>
</head>

<body>

    <div class="container" id="central">
        <hr>
        <div style="display:flex;flex-direction:column;width:50%;margin:auto;justify-content:space-between;">

            <p style="text-align:center;margin:0px;">

                <?php
                $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];


                if (isset($_GET['mes']) && $_GET['mes'] != null) {
                    $mesAtual = $_GET['mes'];
                    $anoAtual = $_GET['ano'];
                    echo $anoAtual;
                } else {
                    $anoAtual = date('Y');
                    echo $anoAtual;
                    $dataHoje = date(DATE_ATOM);
                    $dataHoje = explode('T', $dataHoje);
                    $mesAtual = explode('-', $dataHoje[0]);
                    $mesAtual = $mesAtual[1];
                }
                function verificaProximoMes($mesAtual, $direcao)
                {
                    if ($direcao == 'back') {
                        if ($mesAtual == 1) {
                            return 12;
                        } else {
                            return $mesAtual - 1;
                        }
                    } else {
                        if ($mesAtual == 12) {
                            return 1;
                        } else {
                            return $mesAtual + 1;
                        }
                    }
                }

                echo "<div class='LinhaMes'><button onclick='mudaMes(" . verificaProximoMes($mesAtual, 'back') . ", " . $anoAtual . ", \"back\")'><</button><p>" . $meses[$mesAtual - 1] . "</p><button onclick='mudaMes(" . verificaProximoMes($mesAtual, 'front') . ", " . $anoAtual . ", \"front\")'>></button></div>";
                ?>
            </p>
        </div>
        <hr>
        <h3 id="saldo">Saldo: <div style="display:flex; flex-align:row;justify-content:center; margin-top:10px">
                <h1 id="valor">R$
                    <?php
                    require_once("../../model/conexao.php");
                    require_once("../assets/global_functions/dateAndNumberFormatting.php");
                    $sql = "SELECT sum(valor_transacao) as 'saldo' FROM historico_carteira hc 
                            WHERE MONTH(hc.data_transacao) = $mesAtual AND YEAR(hc.data_transacao) = $anoAtual
                            ORDER BY ID DESC";
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
        $sql = "SELECT * FROM historico_carteira hc 
        WHERE MONTH(hc.data_transacao) = $mesAtual AND YEAR(hc.data_transacao) = $anoAtual
        ORDER BY ID DESC";
        $result = $conexao->query($sql);
        $totalRows = $result->rowCount();
        if ($totalRows > 0) {
            $dataAnterior = null;
            while ($row = $result->fetch()) {
                $data = explode(' ', $row['data_transacao']);
                $data = $data[0];
                $tipo = $row['tipo'];
                if($tipo == 'R'){
                    $tipoDescricao = 'Recebimento';
                }else if($tipo == 'P'){
                    $tipoDescricao = 'Pagamento';
                }else{
                    $tipoDescricao = 'Cancelamento';
                }
                if ($data != $dataAnterior) {
                    echo "<p class='font-120 border rounded-pill border-primary px-2' style='width:fit-content;margin-bottom:0px;margin-top:12px;'>" . DatetimeYYYYMMDDtoDDMMYYYY($data) . "</p>";
                    $dataAnterior = $data;
                }
                echo "<div class='rows border-start border-primary px-3 py-2' style='display:flex;flex-direction:column;'>
                        <p class='font-08'>" . $tipoDescricao . "</p><div class='font-120' style='display:flex; justify-content:space-between;'><p>Título #" . $row['id_titulo'] . ": </p><p style='text-align:right;width:50px;'R$>" . convertePonto($row["valor_transacao"]) . "</p></div>
                      </div>";

                // echo "<p> " . DatetimeYYYYMMDDtoDDMMYYYY($row['data_transacao']) . "  -  Recebimento-> R$ " . convertePonto($row["valor_transacao"]) . "</p>";
            }
        } else {
            echo "0 results";
        }
        echo "
                    <script>
                        function redirecionarParaDetalhes(id) {
                            window.location.href = '../DetalhandoPedidos/index.php?ID=' + id;
                        }

                        function mudaMes(mes, ano, op){
                            if(op == 'back'){
                                if(mes == 12){
                                    ano = ano - 1;
                                }
                            }else{
                                if(mes == 1){
                                    ano = ano + 1;
                                }
                            }

                            window.location.href = 'index.php?mes='+mes+'&ano='+ano;
                        }
                    </script>
                    
                    ";

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