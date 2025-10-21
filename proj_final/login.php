<?php
session_start();
include('conndb.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['usuario']) && isset($_POST['senha'])) {
        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];

        $sql = "SELECT id_usuario, nome_usuario, senha_usuario, salt_usuario FROM tb_usuarios WHERE nome_usuario = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $senha_hash = md5($row['salt_usuario'] . $senha);

            if ($senha_hash === $row['senha_usuario']) {
                $_SESSION['id_usuario'] = $row['id_usuario'];
                $_SESSION['nome_usuario'] = $row['nome_usuario'];
                header('location:index.php');
                exit();
            } else {
                $_SESSION['msg'] = "Senha inválida";
            }
        } else {
            $_SESSION['msg'] = "Usuário não encontrado";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link rel="stylesheet" href="style.css/login.css"> 
    

</head>

<body>

 

    <h1>Login</h1>
    <?php
    if (isset($_SESSION['msg'])) {
        echo "<p>" . $_SESSION['msg'] . "</p>";
        unset($_SESSION['msg']); // limpa a mensagem para não mostrar de novo
    }
    ?>
    <style>
    .senha-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .senha-container input[type="password"],
    .senha-container input[type="text"] {
        padding-right: 30px; /* espaço para o ícone */
    }

    .olho {
        position: absolute;
        right: 10px;
        cursor: pointer;
        user-select: none;
    }
</style>

<form action="login.php" method="POST">
    <label for="usuario">Usuário</label>
    <input type="text" name="usuario" id="usuario" required>

    <label for="senha">Senha</label>
    <div class="senha-container">
        <input type="password" name="senha" id="senha" required>
        <span class="olho" id="toggle-senha">👁️</span>
    </div>

    <input type="submit" value="Entrar">
</form>

<script>
document.getElementById('toggle-senha').addEventListener('click', function () {
    const senhaInput = document.getElementById('senha');
    const tipo = senhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
    senhaInput.setAttribute('type', tipo);
    this.textContent = tipo === 'password' ? '👁️' : '🙈';
});
</script>




