<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../lib/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <title>Títulos de Direito</title>
    <style>
        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container container-fluid mt-4">
        <h4>Filtros:</h4>
        <p>Placeholder</p>
        <p>Placeholder</p>
        <div id="contents" class="container container-fluid">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th class="col">ID Venda</th>
                        <th class="col">Nome Cliente</th>
                        <th class="col">Data Venda</th>
                        <th class="col">Valor Venda</th>
                        <th class="col">Valor em Aberto</th>
                        <th class="col">Opção</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        require_once('../../../model/conexao.php');
                        $sql = "SELECT t.valor_pago, t.valor_venda, t.pedido_referencia, prs.nome as 'IDPessoa', p.data as 'dataP', t.ID as 'IDTitulo'  FROM titulos t
                        LEFT JOIN pedidos p on t.pedido_referencia = p.ID
                        LEFT JOIN pessoas prs on p.id_cliente = prs.ID
                        ORDER BY (valor_venda - valor_pago) DESC";
                        $result = $conexao->query($sql);
                        if ($result) {
                            while ($row = $result->fetch()) {

                                $valor_pago = $row['valor_pago'];
                                $valor_venda = $row['valor_venda'];
                                echo "<tr id='" . $row['IDTitulo'] . "'>";
                                echo "<td>" . $row['pedido_referencia'] . "</td>";
                                echo "<td>" . $row['dataP'] . "</td>";
                                echo "<td> " . $row['IDPessoa'] . " </td>";
                                echo "<td id='valor_venda'>" . $row['valor_venda'] . "</td>";
                                echo "<td>" . ($valor_venda - $valor_pago) . "</td>";
                                echo "<td><button class='btn btn-success' onclick='quitacao(this.parentElement.parentElement)'>Quitar</button></td>";
                                echo "</tr>";
                            }
                        }

                        ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function connection(data) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../../../controller/titulos/titulo_controller.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText == "OK") {
                    window.location.reload();
                }
            }
            xhr.send(data)
        }
        function quitacao(elemento) {
            var IDTitulo = elemento.id;
            var valor = elemento.children[4].innerHTML;
            console.log(valor, IDTitulo);


            Swal.fire({
                title: 'Confirme o Valor:',
                html: `<label>R$: &nbsp;</label><input id="valorDigitado" type="number" value=${valor}>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirmo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    valorDigitado = parseFloat(valorDigitado)
                    valor = parseFloat(valor)

                    var valorDigitado = document.getElementById("valorDigitado").value
                    console.log(valorDigitado);
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
                                connection(`IDTitulo=${IDTitulo}&valor_quitado=${valorDigitado}`)
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
                        connection(`IDTitulo=${IDTitulo}&valor_quitado=${valorDigitado}`)
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