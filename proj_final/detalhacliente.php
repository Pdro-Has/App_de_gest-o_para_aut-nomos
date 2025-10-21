<?php
session_start();
include('conndb.php');


if(!isset($_SESSION['id_usuario'])){
   $_SESSION['msg'] = 'Faça Login para acessar os detalhes dos clientes';
   header('location:Login.php');
   exit();
}


if(!isset($_GET['id'])){
    header('location:clientes.php');
    exit();
}

$id = $_GET['id'];

 $sql = "SELECT nome_cliente,sobrenome_cliente,email_cliente,celular_cliente,telefone_cliente,cpf_cliente,cep_cliente,bairro_cliente,rua_cliente,numero_cliente
    FROM tb_clientes 
    LEFT JOIN tb_contatos ON tb_clientes.id_cliente = tb_contatos.id_cliente  
    JOIN tb_clientes_enderecos ON tb_clientes.id_cliente = tb_clientes_enderecos.id_cliente 
    JOIN tb_enderecos ON tb_enderecos.id_endereco_cliente = tb_clientes_enderecos.id_endereco_cliente
    WHERE tb_clientes.id_cliente = $id";



$result = mysqli_query($link, $sql);
$tbl = mysqli_fetch_array($result);
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detalhes do Cliente</title>
    <link rel="stylesheet" href="style.css/detalha.css?v=1.0" />
</head>

<body>
    <h1>Detalhes do Cliente</h1>

    <form>
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" maxlength="50" value="<?= htmlspecialchars($tbl[0]) ?>" disabled />
        </div>

        <div class="form-group">
            <label for="sobrenome">Sobrenome:</label>
            <input type="text" name="sobrenome" id="sobrenome" maxlength="15" value="<?= htmlspecialchars($tbl[1]) ?>" disabled />
        </div>

        <div class="form-group">
            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" maxlength="14" value="<?= htmlspecialchars($tbl[5]) ?>" disabled />
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" maxlength="50" value="<?= htmlspecialchars($tbl[2]) ?>" disabled />
        </div>

        <div class="form-group">
            <label for="cep">CEP:</label>
            <input type="text" name="cep" id="cep" maxlength="9" value="<?= htmlspecialchars($tbl[6]) ?>" disabled />
        </div>

        <div class="form-group">
            <label for="rua">Rua:</label>
            <input type="text" id="rua" name="rua" maxlength="50" value="<?= htmlspecialchars($tbl[8]) ?>" disabled />
        </div>

        <div class="form-group">
            <label for="numero">Número:</label>
            <input type="text" name="numero" id="numero" maxlength="5" value="<?= htmlspecialchars($tbl[9]) ?>" disabled />
        </div>

        <div class="form-group">
            <label for="bairro">Bairro:</label>
            <input type="text" name="bairro" id="bairro" maxlength="30" value="<?= htmlspecialchars($tbl[7]) ?>" disabled />
        </div>

        <div class="form-group">
            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" maxlength="11" value="<?= htmlspecialchars($tbl[4]) ?>" disabled />
        </div>

        <div class="form-group">
            <label for="celular">WhatsApp:</label>
            <input type="text" name="celular" id="celular" value="<?= htmlspecialchars($tbl[3]) ?>" disabled />
        </div>

        <a href="clientes.php"><input type="button" value="Voltar" /></a>
    </form>
</body>

</html>