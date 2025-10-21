<?php
session_start();
include('conndb.php');

if(!isset($_SESSION['id_usuario'])){
   $_SESSION['msg'] = 'Faça Login para prosseguir';
   header('location:Login.php');
}

$sql = "SELECT id_servico, nome_cliente,sobrenome_cliente, valor_servico, status_servico, data_entrega_prevista
FROM tb_clientes JOIN tb_servicos ON tb_clientes.id_cliente = tb_servicos.id_cliente";
 
$result = mysqli_query($link,$sql);


/* if(isset($_POST['Buscar'])  && trim($_POST['Buscar']) !== ''){
    $busca = $_POST['Buscar'];

    $sql = "SELECT nome_cliente,sobrenome_cliente,MIN(celular_cliente) ,MIN(telefone_cliente),tb_clientes.id_cliente
    FROM tb_clientes 
    LEFT JOIN tb_contatos ON tb_clientes.id_cliente = tb_contatos.id_cliente 
    WHERE nome_cliente LIKE '%$busca%' GROUP BY tb_clientes.id_cliente";

    $result = mysqli_query($link,$sql);
   
} */







//IFs DE BUSCA POR CLIENTE (NOME POR PADRÃO)
if (isset($_GET['Buscar']) && isset($_GET['tipo_filtro'])) {

//IF DO FILTRAR POR CLIENTE_NOME
  if($_GET['tipo_filtro'] == 'nome_cliente'){

    $nome = $_GET['Buscar'];

   $sql = "SELECT id_servico, nome_cliente,sobrenome_cliente, valor_servico, status_servico, data_entrega_prevista
FROM tb_clientes JOIN tb_servicos ON tb_clientes.id_cliente = tb_servicos.id_cliente
WHERE nome_cliente LIKE '%$nome%'";
 
$result = mysqli_query($link,$sql);
  }

  //ID DO FILTRAR POR STATUS_ORCAMENTO
  if($_GET['tipo_filtro'] == 'status_servico'){

    $status = $_GET['Buscar'];

   $sql = "SELECT id_servico, nome_cliente,sobrenome_cliente, valor_servico, status_servico, data_entrega_prevista
FROM tb_clientes JOIN tb_servicos ON tb_clientes.id_cliente = tb_servicos.id_cliente
WHERE status_servico LIKE '%$status%' ";
 
$result = mysqli_query($link,$sql);
  }
  
  //IF DO FILTRAR POR VALOR
  if($_GET['tipo_filtro'] == 'valor_servico'){

    $valor = $_GET['Buscar'];

   $sql = "SELECT id_servico, nome_cliente,sobrenome_cliente, valor_servico, status_servico, data_entrega_prevista
FROM tb_clientes JOIN tb_servicos ON tb_clientes.id_cliente = tb_servicos.id_cliente
WHERE valor_servico LIKE '$valor%' ";
 
$result = mysqli_query($link,$sql);
  }

  //IF DO FILTRAR POR DATA
  if($_GET['tipo_filtro'] == 'data_servico'){

    $data = $_GET['Buscar'];

    $sql = "SELECT id_servico, nome_cliente,sobrenome_cliente, valor_servico, status_servico, data_entrega_prevista
FROM tb_clientes JOIN tb_servicos ON tb_clientes.id_cliente = tb_servicos.id_cliente
WHERE data_entrega_prevista LIKE '%$data%' ";
 
$result = mysqli_query($link,$sql);
  }
}

?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="style.css/clientes.css?v=1.0">
</head>
<body>
     <a href="agenda.php?back"><span class="material-symbols-outlined">arrow_back_ios</span></a>
    <h1>Serviços</h1>
    <br>
         <p>
            Olá  <?=htmlspecialchars($_SESSION['nome_usuario']) ?>,bem vindo a aba serviços concluídos!
        </p>
    <br>
     <div>
    <form action="servicos.php" method="get">
   
       <!--<a href="addorcamento.php" ><span id="add" class="material-symbols-outlined">add</span> </a> -->
       

<div class="input-com-icone">
  <input type="text" name="Buscar" placeholder="Procurar...">
  
  <!-- Ícone de funil -->
  <span class="material-symbols-outlined icone-filtro" onclick="toggleFiltro()">filter_alt</span>
  
  <!-- Select suspenso -->
  <div class="select-opcoes" id="filtroSelect">
    <select name="tipo_filtro">
      <option value="nome_cliente" selected>Nome</option>
      <option value="status_servico">Status</option>
      <option value="valor_servico">Valor</option>
      <option value="data_servico">Data</option>
    </select>
  </div>
</div>
       <input type="submit" value="Pesquisar">
        <a href="servicos.php"><input id="voltar" type="button" value="Voltar"></a>
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
            <th>Solicitante</th>
            <th>Status</th>
            <th>Valor Cobrado</th>
            <th>Previsão de Entrega</th>
            <th>Excluir</th>
        </tr>
        <?php
        while($tbl = mysqli_fetch_array($result)){
            if($tbl[5] == null)$tbl[5] = "-"
        ?>
        <tr>

            <td><a href="detalhaservico.php?id=<?=$tbl[0]?>"><span class="material-symbols-outlined">search</span></a></td>
            <td><?= $tbl[1] ?>  <?=$tbl[2]?></td>
            <td><?= $tbl[4] ?></td>
            <td><?= $tbl[3] ?></td>
            <td><?= $tbl[5] ?></td>
            <td>
                <a href="deletaservico.php?id=<?=$tbl[0]?>">
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

<style>
.input-com-icone {
  position: relative;
  display: inline-block;
  width: 100%;
  max-width: 400px;
}

.input-com-icone input {
  width: 100%;
  padding: 10px 40px 10px 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
}

.input-com-icone .icone-filtro {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: #888;
  z-index: 2;
}

.select-opcoes {
  position: absolute;
  right: 0;
  top: 100%;
  background: white;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-top: 5px;
  font-size: 14px;
  display: none;
  z-index: 10;
  width: 160px;
  font-family: 'Montserrat', sans-serif;
}

.select-opcoes select {
  width: 100%;
  border: none;
  padding: 8px;
  border-radius: 8px;
  outline: none;
  background: #fff;
}
</style>

<script>
function toggleFiltro() {
  const selectBox = document.getElementById('filtroSelect');
  selectBox.style.display = selectBox.style.display === 'block' ? 'none' : 'block';
}
</script>
