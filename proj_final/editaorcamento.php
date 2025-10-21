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

//PEGA O ID QUE VEIO DA PAGINA ANTERIOR 
$idorc = $_GET['id'];

//IF PARA AVALIAR ID DE ORÇAMENTO
if (!isset($_GET['id'])) {
    $_SESSION['msg'] = "Selecione um orçamento para detalhar";
    header('location:orcamentos.php');
    mysqli_close($link);
    exit();
}

// NOVO ITEM
if (!empty($_POST['new_item_nome']) && !empty($_POST['new_item_descricao']) && !empty($_POST['new_item_quantidade']) && !empty($_POST['new_item_preco'])) {
    $novo_nome = $_POST['new_item_nome'];
    $novo_desc = $_POST['new_item_descricao'];
    $novo_qtd = $_POST['new_item_quantidade'];
    $novo_preco = $_POST['new_item_preco'];

    $sql_new_item = "INSERT INTO tb_itens_orcamentos (id_orcamento, nome_item, descricao_item, quantidade, preco_unitario) VALUES (?, ?, ?, ?, ?)";
    $stmt_new = mysqli_prepare($link, $sql_new_item);
    $total_item = $novo_qtd * $novo_preco;
    mysqli_stmt_bind_param($stmt_new, "issid", $idorc, $novo_nome, $novo_desc, $novo_qtd, $novo_preco);
    mysqli_stmt_execute($stmt_new);
}

//SQL DE EDIÇÃO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $valor = trim($_POST['valor']);
    $status = trim($_POST['status']);
    $descricao = trim($_POST['descricao']);

    mysqli_begin_transaction($link);

    //ATUALIZA TB_ORCAMENTOS
    $sql1 = "UPDATE tb_orcamentos SET valor_orcamento = ?, status_orcamento = ?, descricao_orcamento = ? WHERE id_orcamento = ?";
    $stmt1 =  mysqli_prepare($link, $sql1);
    mysqli_stmt_bind_param($stmt1, "dssi", $valor, $status, $descricao, $idorc);
    $ok1 = mysqli_stmt_execute($stmt1);

    //ATUALIZA CADA ITEM
    $ok2 = true;
    for ($i = 0; $i < count($_POST['id_item']); $i++) {
        $id_item = $_POST['id_item'][$i];
        $desc_item = $_POST['descricao_item'][$i];
        $qtd = $_POST['quantidade'][$i];

        $sql2 = "UPDATE tb_itens_orcamentos SET descricao_item = ?, quantidade = ? WHERE id_item = ?";
        $stmt2 = mysqli_prepare($link, $sql2);
        mysqli_stmt_bind_param($stmt2, "sii", $desc_item, $qtd, $id_item);
        if (!mysqli_stmt_execute($stmt2)) {
            $ok2 = false;
            break;
        }
    }

    if ($ok1 && $ok2) {
        mysqli_commit($link);
        echo "<script>alert('Orçamento atualizado com sucesso!'); window.location.href='orcamentos.php';</script>";
    } else {
        mysqli_rollback($link);
        echo "<script>alert('Falha ao atualizar orçamento. Todas as alterações foram canceladas!'); window.location.href='orcamentos.php';</script>";
    }
    mysqli_close($link);
    exit();
}

//SELECTS PARA INPUTS
$sql = "SELECT tb_orcamentos.id_orcamento, nome_cliente, sobrenome_cliente, valor_orcamento, status_orcamento, data_orcamento, descricao_orcamento
FROM tb_clientes 
JOIN tb_orcamentos ON tb_clientes.id_cliente = tb_orcamentos.id_cliente
WHERE tb_orcamentos.id_orcamento = $idorc";

$result = mysqli_query($link, $sql);
$tbl = mysqli_fetch_array($result);

// PEGA OS ITENS DO ORÇAMENTO
$sql_itens = "SELECT id_item, nome_item, descricao_item, quantidade, preco_unitario, total_item FROM tb_itens_orcamentos WHERE id_orcamento = $idorc";
$result_itens = mysqli_query($link, $sql_itens);
$itens = mysqli_fetch_all($result_itens, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Orçamentos</title>
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="style.css/editaorc.css">
</head>

<body>
    <h1>Editar Orçamento</h1>

    <form action="editaorcamento.php?id=<?= $idorc ?>" method="POST">
        <div class="form-group">
            <label for="nome">Solicitante:</label>
            <input type="text" name="nome" id="nome" maxlength="50" value="<?= htmlspecialchars($tbl['nome_cliente']) ?>  <?= htmlspecialchars($tbl['sobrenome_cliente']) ?>" disabled />
        </div>

        <div class="form-group">
            <label for="valor">Valor:</label>
            <input type="number" name="valor" id="valor" maxlength="8" value="<?= htmlspecialchars($tbl['valor_orcamento']) ?>" />
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
              <select name="status" id="status" maxlength="10">
               <option value="pendente" <?= ($tbl['status_orcamento'] == 'pendente') ? 'selected' : '' ?>>Pendente</option>
               <option value="aprovado" <?= ($tbl['status_orcamento'] == 'aprovado') ? 'selected' : '' ?>>Aprovado</option>
               <option value="recusado" <?= ($tbl['status_orcamento'] == 'cancelado') ? 'selected' : '' ?>>Recusado</option>
               <option value="concluido" <?= ($tbl['status_orcamento'] == 'concluido') ? 'selected' : '' ?>>Concluido</option>
           </select>
        </div>


        <div class="form-group">
            <label for="data">Data:</label>
            <input type="date" name="data" id="data" maxlength="10" value="<?= htmlspecialchars($tbl['data_orcamento']) ?>" disabled />
        </div>

        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" maxlength="200" value="<?= htmlspecialchars($tbl['descricao_orcamento']) ?>" />
        </div>

        <h2>Itens do Orçamento</h2>
        <?php foreach ($itens as $item): ?>
            <div class="form-group">
                <input type="hidden" name="id_item[]" value="<?= $item['id_item'] ?>">

                <label>Item:</label>
                <input type="text" name="item[]" value="<?= htmlspecialchars($item['nome_item']) ?>">

                <label>Descrição do item:</label>
                <input type="text" name="descricao_item[]" value="<?= htmlspecialchars($item['descricao_item']) ?>">

                <label>Quantidade:</label>
                <input type="number" name="quantidade[]" value="<?= $item['quantidade'] ?>">

                <label>Preço Unitário:</label>
                <input type="decimal" name="preco[]" value="<?= $item['preco_unitario'] ?>" >

                <label>Total Item:</label>
                <input type="decimal" name="valor_itens[]" value="<?= $item['total_item'] ?>" disabled>
                
             <a href="deleta_item.php?id_item=<?= $item['id_item'] ?>&idorc=<?= $idorc ?>"
                title="Deletar este item"
                onClick="return confirm('Tem certeza que deseja deletar este item?');">
                <span class="material-symbols-outlined">delete</span>
            </a>
            </div>

            


        <?php endforeach; ?>

        <!-- NOVOS ITENS PARA ADICIONAR -->
        <h3>Adicionar novo item</h3>
        <div class="form-group">
            <label>Item:</label>
            <input type="text" name="new_item_nome" placeholder="Nome do item">

            <label>Descrição do item:</label>
            <input type="text" name="new_item_descricao" placeholder="Descrição">

            <label>Quantidade:</label>
            <input type="number" name="new_item_quantidade" placeholder="Ex: 10">

            <label>Preço Unitário:</label>
            <input type="decimal" name="new_item_preco" placeholder="Ex: 25.00">
        </div>


        <input type="submit" value="Gravar">
        <a href="orcamentos.php"><input type="button" value="Voltar" /></a>
    </form>
</body>

</html>