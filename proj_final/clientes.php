<?php 
session_start();
include('conndb.php');

if(!isset($_SESSION['id_usuario'])){
   $_SESSION['msg'] = 'Faça Login para ver os clientes';
   header('location:Login.php');
   exit();
}

$sql = "SELECT nome_cliente,sobrenome_cliente,celular_cliente ,telefone_cliente,tb_clientes.id_cliente
FROM tb_clientes 
LEFT JOIN tb_contatos ON tb_clientes.id_cliente = tb_contatos.id_cliente";
 
$result = mysqli_query($link,$sql);


if(isset($_POST['Buscar'])  && trim($_POST['Buscar']) !== ''){
    $busca = $_POST['Buscar'];

    $sql = "SELECT nome_cliente,sobrenome_cliente,MIN(celular_cliente) ,MIN(telefone_cliente),tb_clientes.id_cliente
    FROM tb_clientes 
    LEFT JOIN tb_contatos ON tb_clientes.id_cliente = tb_contatos.id_cliente 
    WHERE nome_cliente LIKE '%$busca%' GROUP BY tb_clientes.id_cliente";

    $result = mysqli_query($link,$sql);
   
}




?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Usuários</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="style.css/clientes.css?v=1.0">
</head>
<body>
    <a href="index.php?back"><span class="material-symbols-outlined">arrow_back_ios</span></a>
    <h1>Seus Clientes</h1>
    <br>
         <p>
            Olá  <?=htmlspecialchars($_SESSION['nome_usuario']) ?>,seja Bem Vindo!
        </p>
    <br>
    <div>
    <form action="clientes.php" method="post">
    
   
       <a href="addcliente.php" ><span id="add" class="material-symbols-outlined">add</span>
       </a> <input type="text" name="Buscar" placeholder="Digite o Nome">
        <input type="submit" value="Pesquisar">
        <a href="clientes.php"><input id="voltar" type="button" value="Voltar"></a>
     </form>
    </div>
    <p>
    <?php
      $msg = $_SESSION['msg'] ?? '';
      echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
      unset($_SESSION['msg']);
     ?>
     </p>
    <br>
    <table border = 1>
        <tr>
            <th>Detalhes</th>
            <th>Nome</th>
            <th>Sobrenome</th>
            <th>Celular</th>
            <th>Telefone</th>
            <th>Editar</th>
            <th>Excluir</th>
        </tr>
        <?php
        while($tbl = mysqli_fetch_array($result)){
        ?>
        <tr>

            <td><a href="detalhacliente.php?id=<?=$tbl[4]?>"><span class="material-symbols-outlined">search</span></a></td>
            <td><?= $tbl[0] ?></td>
            <td><?= $tbl[1] ?></td>
            <td><?= $tbl[2] ?></td>
            <td><?= $tbl[3] ?></td>
            <td>
                <a href="editacliente.php?id=<?=$tbl[4]?>">
                    <span class="material-symbols-outlined">edit</span></a>
            </td>  
            <td>
                <a href="confirmar_exclusao.php?id=<?= $tbl[4] ?>">
                    <span class="material-symbols-outlined">delete</span>
                </a>

               
            </td>
        </tr> 
        <?php
        }
        ?>
    </table>

</body>
</html>


