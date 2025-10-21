<?php
session_start();
include('conndb.php');

if(!isset($_SESSION['id_usuario'])){
    $_SESSION['msg'] = "Faça login para alterar senha";
    header('location:login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nsenha = $_POST['nsenha'];
    $nsenha2 = $_POST['nsenha2'];
    $osenha = $_POST['osenha'];
    $id = $_SESSION['id_usuario'];

    if ($nsenha !== $nsenha2) {
        header('location:alterasenha.php?msg=As novas senhas devem ser iguais!');
        exit();
    }

    // Busca a senha atual e o salt
    $stmt = $link->prepare("SELECT senha_usuario, salt_usuario FROM tb_usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();

    $senha_atual_hash = md5($usuario['salt_usuario'] . $osenha);

    if ($senha_atual_hash !== $usuario['senha_usuario']) {
        header('location:alterasenha.php?msg=Senha atual incorreta!');
        exit();
    }

    // Gera um novo salt e nova hash
    $novo_salt = bin2hex(random_bytes(16));
    $nova_hash = md5($novo_salt . $nsenha);

    // Atualiza no banco
    $stmt = $link->prepare("UPDATE tb_usuarios SET senha_usuario = ?, salt_usuario = ? WHERE id_usuario = ?");
    $stmt->bind_param("ssi", $nova_hash, $novo_salt, $id);
    $stmt->execute();
    $stmt->close();

    unset($_SESSION['id_usuario']);
    $_SESSION['msg'] = 'Senha alterada com sucesso. Faça login novamente.';
    header('location:login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Altera Senha</title>
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="style.css/alterasenha.css">
   
</head>
<body>
    
<a id="voltar" href="configura.php"><span class="material-symbols-outlined">arrow_back_ios</span></a>
 
<br>

   <form action="alterasenha.php" method="post">  

     <h1>Altera Senha</h1>

        <label for="osenha">Senha atual:</label>
        <input type="password" id="osenha" name="osenha"  required>
        <br>
        <label for="nsenha">Nova Senha:</label>
        <input type="password" name="nsenha" id="nsenha" required>
        <br>
        <label for="nsenha2">Confirme sua senha</label>
        <input type="password" name="nsenha2" id="nsenha2" required>
        <br>
        <br>
        <input type="submit" value="Alterar">
</form>
  
</body>
</html>

<style>
    form{
        margin-top: 110px;
    }
    h1{
        color: #5c4825;
    }
    span{
        color: #5c4825;
        margin-left: 45px;
        margin-top: 45px;
    }
</style>
