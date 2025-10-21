<?php
session_start();
include('conndb.php');

if (!isset($_GET['id_item']) || !isset($_GET['idorc'])) {
    header("Location: orcamentos.php");
    exit();
}

$id_item = $_GET['id_item'];
$idorc = $_GET['idorc'];

$sql = "DELETE FROM tb_itens_orcamentos WHERE id_item = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_item);
mysqli_stmt_execute($stmt);

// Redireciona de volta para a edição do orçamento
header("Location: editaorcamento.php?id=$idorc");
exit();
