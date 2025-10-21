<?php
session_start();
include('conndb.php');


//IF PARA AVALIAR USUARIO LOGADO
if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['msg'] = "Por favor, faça login";
    header('location:login.php');
    mysqli_close($link);
    exit();
}

//AVALIA SE ESTA SETADA VARIAVEL DE SESSÃO QUE COINFIRMA CLIENTE, SE NÃO: RODA O CODGIO COMO "PRIMEIRA REQUISIÇÃO",  CASO ESTEJA AVALIA SE É DIFERENTE DE TRUE,
// SE FOR: DA UNSET, SE FOR TRUE RODA O CODGIO PARA ADD ORCÇAMENTO
if(isset($_SESSION['cliente_confirmado'])){

   if($_SESSION['cliente_confirmado'] != true){
    unset($_SESSION['cliente_confirmado']);
   }
}


//RECEBE AS INFOS POR POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  
    if(isset($_POST['cpf'])){
    //ANALISA SE O CPF ESTA SETADO (GERALMENTE NÃO CAI AQUI POR CAUSA DO "REQUIRED", MAS EM CASO DE TIRAREM NO F12 É SEGURANÇA EXTRA)
        if(empty($_POST['cpf'])){

        $_SESSION['msg'] = "Por favor, indique um cliente para vincular o orçamento";
        mysqli_close($link);
        header('location:addorcamento.php');
        exit(); 
    }
    else{
        //ADICIONA O CPF NA SESSION
        $_SESSION['cpf'] = $_POST['cpf'];

        $sql = "SELECT COUNT(*) FROM tb_clientes WHERE cpf_cliente = '{$_POST['cpf']}'";
        $result = mysqli_query($link,$sql);
        $tbl = mysqli_fetch_array($result);

      
        //AVALIA SE EXISTE CLIENTE CADASTRADO COM ESSE CPF, CASO NÃO: HEADER NESSA PAGINA COM MSG E LINK PARA CADASTRAR
        if($tbl[0] < 1){
        $_SESSION['msg'] = "Cliente não cadastrado, clique <a class= 'cadastra' href ='addcliente.php'> aqui </a> para cadastra-lo";
        $_SESSION['cliente_confirmado'] = false;
        header('location:addorcamento.php');
        mysqli_close($link);
        exit();
        }

        // Se deu certo:
        $_SESSION['cliente_confirmado'] = true;
        $_SESSION['msg'] = "<p class = 'certo' >Cliente confirmado, você já pode fazer o orçamento </p>";
        mysqli_close($link);
        header('Location: addorcamento.php');
        exit();
    }
    }
    

    $cpf = $_SESSION['cpf'];
    unset($_SESSION['cpf']);
    unset($_SESSION['cliente_confirmado']);

    $item = $_POST['new_item_nome'];
    $desc_item = $_POST['new_item_descricao'];
    $quant_item = $_POST['new_item_quantidade'];
    $preco_item = $_POST['new_item_preco'];

    $desc_orc = $_POST['descricao'];
    $status = $_POST['status'];
    $valor = $_POST['valor'];

    // PEGA O ID DO CLIENTE A PARTIR DO CPF
    $sql = "SELECT id_cliente FROM tb_clientes WHERE cpf_cliente = '$cpf'";
    $result = mysqli_query($link,$sql);
    $tbl = mysqli_fetch_array($result);
    $id = $tbl[0];
    //echo($id);

    //PEGA O ID DOS ENDERECOS A PARTIR DO ID CLIENTE
    $sql = "SELECT id_endereco_cliente FROM tb_clientes_enderecos WHERE id_cliente = $id";
    $result = mysqli_query($link,$sql);
    $tbl = mysqli_fetch_array($result);


    $data = date('Y-m-d');
    //INSERE DADOS DO ORÇAMENTO (SEM ITEM)
    $sql = "INSERT INTO tb_orcamentos(id_endereco_cliente, descricao_orcamento, valor_orcamento, status_orcamento, id_cliente, data_orcamento, id_usuario
    )
    VALUES ($tbl[0], '$desc_orc', '$valor', '$status', $id, '$data', '{$_SESSION['id_usuario']}')";
    $result = mysqli_query($link,$sql);




    //ANALISA SUCESSO DA INSERÇÃO ANTERIOR
    if($result){
        //PEGA O ID DO ORÇAMENTO QURE ACABOU DE SER CRIADO
      $id_orc = mysqli_insert_id($link);

       // Insere cada item
        foreach ($itens as $index => $nome_item) {
            $desc_item = $desc_itens[$index];
            $quant_item = $quant_itens[$index];
            $preco_item = $preco_itens[$index];

            $sql_item = "INSERT INTO tb_itens_orcamentos (id_orcamento, nome_item, descricao_item, quantidade, preco_unitario) 
                         VALUES ($id_orc, '$nome_item', '$desc_item', $quant_item, $preco_item)";
            $ok_item = mysqli_query($link, $sql_item);

            if(!$ok_item) {
                die("Erro ao inserir item: " . mysqli_error($link));
            }
        }

        $_SESSION['msg'] = "Orçamento e itens cadastrados com sucesso.";
        header('Location: orcamentos.php');
        mysqli_close($link);
        exit();
    } else {
        die("Erro ao inserir orçamento: " . mysqli_error($link));
    }
}



?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Orçamento</title>
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="style.css/editaorc.css">
</head>
<body>
    
 <h1>Adicionar Orçamento</h1>

 <a id="voltar" href="orcamentos.php"><span class="material-symbols-outlined">arrow_back_ios</span></a>

    <?php
  if (isset($_SESSION['msg'])) {
        echo "<p>" . $_SESSION['msg'] . "</p>";
        unset($_SESSION['msg']); // limpa a mensagem para não mostrar de novo
    }
   ?>

    <br>
  
        <form action="addorcamento.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        
        <label for="cpf">CPF:</label>
        <input type="text" name="cpf" id="cpf" maxlength="14" minlength="14" placeholder="CPF do cliente" 
        pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" title="Por favor, informe no formato 123.456.789-10"  required>
        <br>
    </div>
    <div>
        <input type="submit" value="Sim"> <h2 class="cliente">Confirmar cliente? </h2> <a class="nao" href="addorcamento.php"> Não</a> 

    </div>
    </form>


  <form action="addorcamento.php" method="post" enctype="multipart/form-data"> 
     <fieldset <?= !isset($_SESSION['cliente_confirmado']) ? 'disabled' : '' ?>>

             Orçamento 
        <?php if (!isset($_SESSION['cliente_confirmado'])): ?>
            🔒
        <?php endif; ?>
    </legend>

        <!-- ITENS PARA ADICIONAR -->
        <h2>Peças do Serviço</h2>
        <div class="item-group">
        <div id="itens-container">
        

            <label>Item:</label>
            <input type="text" name="new_item_nome[]" placeholder="Nome do item">

            <label>Descrição do item:</label>
            <input type="text" name="new_item_descricao[]" placeholder="Descrição">

            <label>Quantidade:</label>
            <input type="number" min="0" name="new_item_quantidade[]" placeholder="Ex: 10">

            <label>Preço da Peça:</label>
            <input step="0.05" min="0" type="number" name="new_item_preco[]" placeholder="Ex: 25.00">
            <br><br>
        </div>
    </div>

    <button type="button" id="add-item">+ Adicionar outro item</button>

        <h2>Orçamento</h2>
        <div id="itens-container">
        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" maxlength="200" placeholder="O que vai ser feito?" required></textarea>
        </div>

        <!-- SELECT DO STATUS -->
         <div id="itens-container">
        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="aprovado">Aprovado</option>
            <option value="pendente" selected>Pendente</option>
            <option value="concluido">Concluído</option>
        </select>
        </div>
        
        <div id="itens-container">
        <label for="valor">Valor:</label>
        <input step="0.05" min="0" type="number" name="valor" id="valor" placeholder="Valor com mão de obra" required>
        </div>
        <br>        

         
        <input type="submit" value="Adicionar">
     </fieldset>
    </form>

</body>
</html>

<!-- JS PARA ADICIONAR NOVOS ITENS -->
<script>
    const addItemButton = document.getElementById('add-item');
    const itensContainer = document.getElementById('itens-container');

    addItemButton.addEventListener('click', function() {
        // Copia o primeiro bloco de item
        const firstItem = document.querySelector('.item-group');
        const newItem = firstItem.cloneNode(true);

        // Limpa os valores dos inputs clonados
        newItem.querySelectorAll('input').forEach(input => {
            input.value = '';
        });

        // Adiciona o novo item ao container
        itensContainer.appendChild(newItem);
    });
</script>