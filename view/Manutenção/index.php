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
    <link rel="stylesheet" href="../assets/lib/css/personalStyle.css">
    <link rel="stylesheet" href="../assets/lib/css/bootstrap.min.css">
    <title>Manutenção</title>
</head>

<body>
    <div class="container mt-5">
    <h3>Inserir novo registro:</h3>
        <form action="../../controller/manutencao/inserirNovo.php" method="POST">
            <div class="">
                <input type="date" name="dataManutencao" class="form-control mb-1" id="dataManutencao">
                <input type="text" name="id_produto" class="form-control mb-1" placeholder="Digite o ID do Produto">
                <input type="text" name="descricao_produto" class="form-control mb-1" placeholder="Digite a Descrição do Produto">
                <input type="text" name="motivo" class="form-control" placeholder="Digite o Motivo">
                <input type="submit" value="Registrar" class="btn btn-success" style="margin-left:140px; margin-top:15px; margin-bottom:15px;">
            </div>
        </form>
        <div>
            <h3>Registros:</h3>
            <hr>
            <table class="table table-hover" style="margin-left:10px">
            <thead class="table-dark">
                <th>ID</th>
                <th>Produto</th>
                <th>Data</th>
                <th>Motivo</th>
                <!-- <th>Opt</th> -->
            </thead>
            <tbody>
            <?php
            require_once("../../model/conexao.php");

            $sql = "SELECT id_produto, descricao, data, motivo, retornou, imagem_problema FROM manutencao ORDER BY ID DESC";

            $result = $conexao->query($sql);
            $totalRows = $result->rowCount();
            if ($totalRows > 0) {
                while ($row = $result->fetch()) {
                    $id_produto = $row['id_produto'];
                    $data = $row['data'];
                    $motivo = $row['motivo'];
                    $descricao = $row['descricao'];
                    $retornou = $row['retornou'];
                    $imagem_problema = $row['imagem_problema'];
                    if ($retornou == 0) {
                        $retornou = "Não";
                    } else {
                        $retornou = "Sim";
                    }
                    echo "<tr>";
                    echo "<td>$id_produto</td>";
                    echo "<td>$descricao</td>";
                    echo "<td>$data</td>";
                    echo "<td>$motivo</td>";
                    // echo "<td>$retornou</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='4'>Nenhum registro encontrado</td>";
                echo "</tr>";
            }
            ?>
            </tbody>

            </table>
        </div>
    </div>


</body>
<script>
    var dataManutencaoInput = document.getElementById("dataManutencao");

    // Cria um objeto Date para a data atual
    var dataAtual = new Date();

    // Obtém o ano, mês e dia da data atual
    var ano = dataAtual.getFullYear();
    var mes = (dataAtual.getMonth() + 1).toString().padStart(2, "0"); // O mês começa de 0
    var dia = dataAtual.getDate().toString().padStart(2, "0");

    // Formata a data no formato "YYYY-MM-DD"
    var dataFormatada = `${ano}-${mes}-${dia}`;

    dataManutencaoInput.value = dataFormatada;</script>

</html>