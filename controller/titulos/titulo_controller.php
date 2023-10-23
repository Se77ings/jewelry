<?php
if (isset($_POST['IDTitulo']) && isset($_POST['valor_quitado'])) {
    $IDTitulo = $_POST['IDTitulo'];
    $valor_quitado = $_POST['valor_quitado'];
    function quitarTitulo($IDTitulo, $valor_quitado)
    {
        require_once('../../model/conexao.php');
        $sql = "UPDATE titulos SET valor_pago = valor_pago + $valor_quitado WHERE ID = $IDTitulo";
        $result = $conexao->query($sql);

        if ($result) {
            echo "OK";
        } else {
            echo "<script>Swal.fire('Ooops!', 'Houve um erro no banco de dados, contate o Gabriel !', 'error')</script>";
        }
    }

    quitarTitulo($IDTitulo, $valor_quitado);
}
?>