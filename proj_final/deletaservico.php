<?php
session_start();
include('conndb.php');

if(!isset($_SESSION['id_usuario'])){
    $_SESSION['msg'] = "Faça Login para prosseguir";
    header('location:login.php');
    mysqli_close($link);
    exit();
}

    //AVALIA SE TEM VARIAVEL ID SETADA
    if (isset($_GET['id'])) {

    //PEGA O ID DO ORÇAMENTO E PÕE NO $IDORC
    $idserv = (int)$_GET['id']; // força para inteiro por segurança
    }


    //AVALIA SE A PESSOA CONFIRMOU O EXCLUDE
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idserv = (int)$_POST['id'];

    // Inicia a transação
    mysqli_begin_transaction($link);

    try {
        $sql = "DELETE FROM tb_servicos WHERE id_servico = $idserv";
        $ok = mysqli_query($link, $sql);

        if (!$ok) {
            throw new Exception("Erro ao excluir o serviço."); 
        }

        // Se tudo deu certo, confirma
        mysqli_commit($link);
        $_SESSION['msg'] = "Serviço excluído com sucesso.";
        header('Location: servicos.php');
        exit();

    } catch (Exception $e) {
        // Se deu erro, faz rollback e retorna mensagem
        mysqli_rollback($link);
        $_SESSION['msg'] = "Erro ao excluir, nenhuma alteração foi feita! Detalhes: " . $e->getMessage();
        header('Location: servicos.php');
        exit();
    }
}



?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deleta Serviço</title>
    <link rel="stylesheet" href="style.css/edita.css">
</head>
<body>

  <h1>Exluir</h1>
<form action="deletaservico.php" method="post">
    <input type="hidden" name="id" value="<?=$idserv?>">
    <div>
       <label for="certeza">Esse serviço será apagado permanentemente, tem certeza?</label> 
    </div>
    <div>
    <input type="submit" value="Tenho!">
    <a class="nao" href="servicos.php">Espera!</a>
</div>
</form>


</body>
</html>