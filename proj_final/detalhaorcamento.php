<?php   
session_start();
include('conndb.php');

if(!isset($_SESSION['id_usuario'])){
$_SESSION['msg'] = "Por favro, faça Login para prosseguir";
header('location:login.php');
mysqli_close($link);
exit();
}

$idorc = $_GET['id'];

$sql = "SELECT nome_cliente, sobrenome_cliente, descricao_orcamento, status_orcamento, valor_orcamento, data_orcamento, 
rua_cliente, numero_cliente, celular_cliente, bairro_cliente, tb_clientes.id_cliente
FROM tb_clientes 
JOIN tb_contatos ON tb_contatos.id_cliente = tb_clientes.id_cliente
JOIN tb_orcamentos ON tb_orcamentos.id_cliente = tb_clientes.id_cliente 
JOIN tb_enderecos ON tb_orcamentos.id_endereco_cliente = tb_enderecos.id_endereco_cliente 
WHERE id_orcamento = $idorc";

$result = mysqli_query($link,$sql);
$tbl = mysqli_fetch_array($result);

?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalha Orçamento</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="style.css/detalha.css">
</head>
<body>
     <h1>Detalhes do Orçamento</h1>

    <form> 
       
        <div class="form-group"> 
           <a href="detalhacliente.php?id=<?=$tbl[10]?>"><h2 class="h2">Solicitante</h2></a>
            <br><br>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" maxlength="50" value="<?= htmlspecialchars($tbl[0]) ?> <?= htmlspecialchars($tbl[1]) ?>" disabled />
    <br>
            <label for="celular">WhatsApp:</label>
            <input type="text" name="celular" id="celular" value="<?= htmlspecialchars($tbl[8]) ?>" disabled />
       
        </div>

       <h2 class="h2">Orçamento</h2>
        <div class="form-group"> 
<br>
            <label for="sobrenome">Descrição:</label>
            <input type="text" name="descricao" id="descricao" maxlength="200" value="<?= htmlspecialchars($tbl[2]) ?>" disabled />
     <br>
            <label for="cpf">Situação:</label>
            <input type="text" name="status" id="status" maxlength="15" value="<?= htmlspecialchars($tbl[3]) ?>" disabled />
    <br>
            <label for="valor">Valor:</label>
            <input type="text" name="valor" id="valor" maxlength="11" value="<?= htmlspecialchars($tbl[4]) ?>" disabled />
        <br>
            <label for="data">Data de Criação:</label>
            <input type="text" name="data" id="data" maxlength="12" value="<?= htmlspecialchars($tbl[5]) ?>" disabled />
        </div>

        <h2 class="h2">Casa do Orçamento</h2>
        <div class="form-group">
            <br>
            <label for="rua">Rua:</label>
            <input type="text" id="rua" name="rua" maxlength="50" value="<?= htmlspecialchars($tbl[6]) ?> " disabled />
   <br>
            <label for="numero">Número:</label>
            <input type="text" name="numero" id="numero" maxlength="5" value="Nº<?= htmlspecialchars($tbl[7]) ?>" disabled />
       <br>
            <label for="bairro">Bairro:</label>
            <input type="text" name="bairro" id="bairro" maxlength="30" value="<?= htmlspecialchars($tbl[9]) ?>" disabled />
        </div>

        <a href="orcamentos.php"><input type="button" value="Voltar" /></a>
    </form>
</body>
</html>

<style>
    input{
        text-align: center;
    }
    label{
        text-align: center;
    }
</style>