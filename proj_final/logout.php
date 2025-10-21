<?php 
session_destroy();
header('Location:login.php');
exit();
?>
<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>
<body>
    <form action="login.php" method="get">
    <h1>Encerrar Seção?</h1>
    <input type="submit" name="logout" value="Sim">
    <a href="configura.php"><input type="button" value="Não"></a>
    
   </form>



</body>
</html>