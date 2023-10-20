<?php
require_once "../../model/conexao.php"; // Inclua o arquivo de conexão PDO
header("Content-Type: application/json");

$termo = $_GET["termo"];


// Use uma consulta preparada para evitar injeção de SQL

$query = "SELECT nome, telefone FROM pessoas WHERE nome LIKE ?";
// echo($query);
$stmt = $conexao->prepare($query);
$stmt->bindParam(1, $termo, PDO::PARAM_STR);
$stmt->execute();
// var_dump($stmt);
class Pessoa
{
    public $nome;
    public $telefone;
    function __construct($nome, $telefone)
    {
        $this->nome = $nome;
        $this->telefone = $telefone;
    }
}



$nomes = array();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $nomes[] = new Pessoa($row["nome"], $row["telefone"]);
}

echo json_encode($nomes);
$conexao = null;
?>