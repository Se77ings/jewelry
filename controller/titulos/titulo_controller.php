<?php
var_dump($_POST);   
if (isset($_POST['IDTitulo']) && isset($_POST['valor_quitado'])) {
    $IDTitulo = $_POST['IDTitulo'];
    $valor_quitado = $_POST['valor_quitado'];
    $dataQuitacao = $_POST['dataQuitacao'];
    $quitado = $_POST['quitado'];

    function quitarTitulo($IDTitulo, $valor_quitado, $dataQuitacao, $quitado)
    {
        require_once('../../model/conexao.php');
        $sql = "UPDATE titulos SET valor_pago = (valor_pago + $valor_quitado), pago = $quitado, data_quitacao = '$dataQuitacao' WHERE ID = $IDTitulo";
        // echo $sql;
        $result = $conexao->query($sql);

        if ($result) {
            return "OK";
        } else {
            return "<script>Swal.fire('Erro 505!', 'Houve um erro no banco de dados, contate o Gabriel !', 'error')</script>";
        }
    }

    echo quitarTitulo($IDTitulo, $valor_quitado, $dataQuitacao, $quitado);
}
?>