<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['opt'])) {
        require_once '../../model/conexao.php';
        $opt = $_POST['opt'];

        if ($opt == 'pesquisa') {
            $args = $_POST['nome'];
            $sql = "SELECT id, nome, telefone FROM pessoas WHERE nome LIKE '%$args%' ORDER BY nome ASC";

            if ($result = $conexao->query($sql)) {
                while ($row = $result->fetch()) {
                    echo "<tr id='" . $row['id'] . "'>";
                    echo "<td>" . $row['nome'] . "</td>";
                    echo "<td>" . $row['telefone'] . "</td>";
                    echo "<td><button class='btn btn-success' onclick='alterar(" . $row['id'] . ")'>Alterar</button></td>";
                    echo "</tr>";
                }
            } else {
                echo 0;
            }
        } else if ($opt == 'todos') {
            $sql = "SELECT id, nome, telefone FROM pessoas ORDER BY nome ASC";
            $result = $conexao->query($sql);
            while ($row = $result->fetch()) {
                echo "<tr id='" . $row['id'] . "'>";
                echo "<td>" . $row['nome'] . "</td>";
                echo "<td>" . $row['telefone'] . "</td>";
                echo "<td><button class='btn btn-success' onclick='alterar(" . $row['id'] . ")'>Alterar</button></td>";
                echo "</tr>";
            }
        }
    }
}
?>