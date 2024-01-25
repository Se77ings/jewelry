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

        .linha-clicavel {
            cursor: pointer;
        }

        #futureFilter,
        #futureFilter>p,
        #futureFilter>svg {
            display: none;
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
    <div class="container container-fluid">
        <h4>Consultando Pedidos:</h4>
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

                // vou ter que fazer o seguinte: quando clicar em algum botão, redirecionar para a pagina denovo, com um get. if tenha get alterar a logica acima.. else manter a logica acima.
                echo "<div class='LinhaMes'><button onclick='mudaMes(" . verificaProximoMes($mesAtual, 'back') . ", " . $anoAtual . ", \"back\")'><</button><p>" . $meses[$mesAtual - 1] . "</p><button onclick='mudaMes(" . verificaProximoMes($mesAtual, 'front') . ", " . $anoAtual . ", \"front\")'>></button></div>";
                ?>
            </p>
            <div style="display:flex;flex-direction:row;margin:auto;" id="futureFilter">
                <br>
                <p>Filtrar: </p><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                    class="bi bi-funnel-fill" viewBox="0 0 16 16">
                    <path
                        d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z" />
                </svg>
            </div>
        </div>
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
                WHERE MONTH(p.data) = $mesAtual AND YEAR(p.data) = $anoAtual 
                AND p.cancelado IS NULL
                ORDER BY p.data ASC";
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
                        echo "Nenhum Resultado encontrado!";
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

                        function mudaMes(mes, ano, op){
                            console.log('ano recebido: '+ano);
                            if(op == 'back'){
                                if(mes == 12){
                                    ano = ano - 1;
                                }
                            }else{
                                if(mes == 1){
                                    ano = ano + 1;
                                }
                            }
                            console.log('ano enviado: '+ano);

                            window.location.href = 'index.php?mes='+mes+'&ano='+ano;
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