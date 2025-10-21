<?php   
session_start();
include('conndb.php');

if(!isset($_SESSION['id_usuario'])){
$_SESSION['msg'] = "Por favro, faça Login para prosseguir";
header('location:login.php');
mysqli_close($link);
exit();
}

$idserv = $_GET['id'];

$sql = "SELECT 
   tb_clientes.id_cliente,            
   nome_cliente,
   sobrenome_cliente,   
   valor_servico,
   forma_pagamento,    
   status_servico,                    
   data_inicio,                       
   data_entrega_prevista,             
   celular_cliente,                   
   rua_cliente,                       
   numero_cliente,                    
   bairro_cliente                 
FROM tb_servicos JOIN tb_clientes ON tb_servicos.id_cliente = tb_clientes.id_cliente
JOIN tb_contatos ON tb_clientes.id_cliente = tb_contatos.id_cliente 
JOIN tb_enderecos ON tb_servicos.id_endereco_cliente = tb_enderecos.id_endereco_cliente
WHERE id_servico = $idserv";

$result = mysqli_query($link,$sql);
$tbl = mysqli_fetch_array($result);

if($tbl[6] == null)$tbl[6] = "-";
if($tbl[7] == null)$tbl[7] = "-";
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalha Serviço</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="style.css/detalha.css">
</head>
<body>
     <h1>Detalhes do Serviço</h1>

    <form> 
       
        <div class="form-group"> 
           <a href="detalhacliente.php?id=<?=$tbl[0]?>"><h2 class="h2">Solicitante</h2></a>
            <br><br>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" maxlength="50" value="<?= htmlspecialchars($tbl[1]) ?> <?= htmlspecialchars($tbl[2]) ?>" disabled />
    <br>
            <label for="celular">WhatsApp:</label>
            <input type="text" name="celular" id="celular" value="<?= htmlspecialchars($tbl[8]) ?>" disabled />
       
        </div>

       <h2 class="h2">Serviço</h2>
        <div class="form-group"> 
<br>        
            <label for="valor">Valor:</label>
            <input type="text" name="valor" id="valor" maxlength="11" value="<?= htmlspecialchars($tbl[3]) ?>" disabled />
     <br>
            <label for="pagamento">Forma de Pagamento:</label>
            <input type="text" name="pagamento" id="pagamento" maxlength="15" value="<?= htmlspecialchars($tbl[4]) ?>" disabled />
    <br>
            <label for="status">Status:</label>
            <input type="text" name="status" id="status" maxlength="200" value="<?= htmlspecialchars($tbl[5]) ?>" disabled />
        <br>
            <label for="data">Data de Inicio:</label>
            <input type="text" name="data" id="data" maxlength="12" value="<?= htmlspecialchars($tbl[6]) ?>" disabled />
            <br>
             <label for="entrega">Previsão de Entrega:</label>
            <input type="text" name="entrega" id="entrega" maxlength="12" value="<?= htmlspecialchars($tbl[7]) ?>" disabled />
        </div>

        <h2 class="h2">Casa do Serviço</h2>
        <div class="form-group">
            <br>
            <label for="rua">Rua:</label>
            <input type="text" id="rua" name="rua" maxlength="50" value="<?= htmlspecialchars($tbl[9]) ?> " disabled />
   <br>
            <label for="numero">Número:</label>
            <input type="text" name="numero" id="numero" maxlength="5" value="Nº<?= htmlspecialchars($tbl[10]) ?>" disabled />
       <br>
            <label for="bairro">Bairro:</label>
            <input type="text" name="bairro" id="bairro" maxlength="30" value="<?= htmlspecialchars($tbl[11]) ?>" disabled />
        </div>

        <a href="servicos.php"><input type="button" value="Voltar" /></a>
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