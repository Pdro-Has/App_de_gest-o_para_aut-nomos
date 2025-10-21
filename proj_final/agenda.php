<?php session_start();
include('conndb.php');

if(!isset($_SESSION['id_usuario'])){
    $_SESSION['msg'] = "Faça seu Login";
    header('Location:login.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
     <link rel="stylesheet" href="style.css/index.css">
</head>
<body>
    <br>
    
    <div class="container">
        
        <h1>Agenda</h1></a>
        <div>
            <a href="servicos.php" class="btn"><button>Serviços</button></a>
            <a href="orcamentos.php" class="btn"><button>Orçamentos</button></a>
            <a href="index.php" class="btn"><button>Voltar</button></a>
        </div>
        <p>
            <?= htmlspecialchars($_SESSION['nome_usuario']) ?>!...O que vai ser hoje?
        </p>
        
</body>
</body>
</html>