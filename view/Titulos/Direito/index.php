<?php
session_start();
if (!isset($_SESSION["login"])) {
    header('Location: ../../../index.php?unlog');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/lib/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../../assets/lib/css/personalStyle.css">

    <title>Títulos de Direito</title>
    <style>
        .bold {
            font-weight: bold;
        }

        td p {
            margin: 0;
        }

        td {
            text-align: center;
            vertical-align: bottom;
        }

        .pDatas {
            font-size: 12px;
            text-align: LEFT;
        }

        /* Adicione estilos adicionais conforme necessário */
        .card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .card-body {
            padding: 0px;
            width: 100px;
        }
    </style>
</head>

<body>
    <div class="container container-fluid mt-4">
        <!--futuramente... 
            <div class="border rounded p-3">
            <h4>Filtros:</h4>
            <p>Placeholder</p>
        </div> -->
        <div class="border rounded p-3 my-4">
            <h3>Data de quitação:</h3>
            <input type="date" id="dataQuitacao">
        </div>
        <div id="contents" class="container container-fluid border rounded p-3">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Cliente</th>
                        <th>Valor </th>
                        <th>Saldo</th>
                        <th>Opção</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        require_once('../../../model/conexao.php');
                        require_once("../../assets/global_functions/dateAndNumberFormatting.php");

                        $sql = "SELECT t.valor_pago, t.valor_venda, t.pedido_referencia, prs.nome as 'IDPessoa', t.data_vencimento as 'dataP', t.ID as 'IDTitulo'  FROM titulos t
                        LEFT JOIN pedidos p on t.pedido_referencia = p.ID
                        LEFT JOIN pessoas prs on p.id_cliente = prs.ID
                        WHERE pago = 0
                        ORDER BY t.data_vencimento ASC";

                        $result = $conexao->query($sql);
                        if ($result) {
                            while ($row = $result->fetch()) {

                                $valor_pago = $row['valor_pago'];
                                $valor_venda = $row['valor_venda'];

                                echo "<tr id='" . $row['IDTitulo'] . "' onclick='redirecionarParaDetalhes(" . $row["pedido_referencia"] . ")'>";
                                echo "<td>";
                                echo "<div class='card-body'>";
                                echo "<p class='pDatas'>" . YYYYMMDDtoDDMMYYYY($row['dataP'], '/') . "</p>";
                                echo "<p>" . $row['IDPessoa'] . "</p>";
                                echo "</td>";
                                echo "<td>" . convertePonto($row['valor_venda']) . "</td>";
                                echo "<td>" . convertePonto($valor_venda - $valor_pago) . "</td>";
                                echo "<td><button class='btn btn-success' onclick='quitacao(this.parentElement.parentElement)'>Quitar</button></td>";
                                echo "</div>";
                                echo "</tr>";
                            }
                        } else {
                            echo "0 results";
                        }
                        echo "
                    <script>
                        function redirecionarParaDetalhes(id) {
                            window.location.href = '../../DetalhandoPedidos/index.php?ID=' + id;
                        }
                    </script>
                    ";

                        ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/global_functions/dateAndNumberFormattingJS.js"></script>
    <script>
        var dataQuitacaoInput = document.getElementById("dataQuitacao");

        // Cria um objeto Date para a data atual
        var dataAtual = new Date();

        // Obtém o ano, mês e dia da data atual
        var ano = dataAtual.getFullYear();
        var mes = (dataAtual.getMonth() + 1).toString().padStart(2, "0"); // O mês começa de 0
        var dia = dataAtual.getDate().toString().padStart(2, "0");

        // Formata a data no formato "YYYY-MM-DD"
        var dataFormatada = `${ano}-${mes}-${dia}`;

        dataQuitacaoInput.value = dataFormatada;

        function connection(data) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../../../controller/titulos/titulo_controller.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Lógica a ser executada quando a solicitação estiver completa
                    console.log("entra aqui???");
                    console.log(xhr.responseText);
                }
            };

            xhr.send(data);
        }



        function quitacao(elemento) {
            var dataQuitacao = document.getElementById("dataQuitacao").value;
            var IDTitulo = elemento.id;
            var valor = elemento.children[2].innerHTML;
            // console.log("elemento-> "+elemento)
            // console.log(valor, IDTitulo);

            Swal.fire({
                title: 'Confirme o Valor:',
                html: `<label>R$: &nbsp;</label><input id="valorDigitado" type="number" value=${convertePonto(valor)}>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirmo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    var valorDigitado = document.getElementById("valorDigitado").value
                    valorDigitado = parseFloat(valorDigitado)
                    valor = convertePonto(valor)

                    // console.log("Valor -> " + valor);
                    // console.log("Valor digitado -> " + valorDigitado);

                    if (valorDigitado > valor) {
                        Swal.fire(
                            'Erro!',
                            'O valor informado é maior que o valor devido.',
                            'error'
                        )
                    } else if (valorDigitado < valor) {
                        Swal.fire({
                            title: 'Atenção!!',
                            html: `<p>O título será parcialmente quitado, restando <span class="bold">R$${valor - valorDigitado},00 </span>em aberto. Deseja continuar?</p>`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Confirmar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                //chamar função quitar, parcialmente.
                                connection(`IDTitulo=${IDTitulo}&valor_quitado=${valorDigitado}&dataQuitacao=${dataQuitacao}&quitado=0`)
                                Swal.fire({
                                    title: 'Quitado!',
                                    text: 'O título foi parcialmente quitado.',
                                    icon: 'info',
                                    showCancelButton: false,
                                    confirmButtonText: 'OK!',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.reload();
                                    }
                                    setTimeout(function () {
                                        location.reload();
                                    }, 3000);
                                });

                            }
                        })
                    }
                    else {
                        connection(`IDTitulo=${IDTitulo}&valor_quitado=${valorDigitado}&dataQuitacao=${dataQuitacao}&quitado=1`)
                        //chamar função quitar, completamente
                        Swal.fire({
                            title: 'Quitado!',
                            text: 'O título foi completamente quitado.',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonText: 'OK!',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                            setTimeout(function () {
                                location.reload();
                            }, 3000);
                        });
                    }
                }
            })
        }
    </script>
</body>

</html>