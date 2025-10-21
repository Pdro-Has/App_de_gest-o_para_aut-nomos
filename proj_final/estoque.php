<?php 
session_start();
include('conndb.php');

if(!isset($_SESSION['id_usuario'])){
    $_SESSION['msg'] = "Faça Login para prosseguir";
    header('location:login.php');
    mysqli_close($link);
    exit();
}













?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque</title>
</head>
<body>
    
</body>
</html>








