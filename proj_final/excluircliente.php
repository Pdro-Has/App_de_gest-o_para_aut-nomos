<?php
session_start();
include('conndb.php');



// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['msg'] = 'Faça login para prosseguir';
    header('Location:Login.php');
    exit();
}

// Verifica se foi passado o ID do cliente
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['msg'] = 'ID do cliente inválido.';
    header('Location: clientes.php');
    exit();
}

$id_cliente = (int) $_GET['id'];

$link->autocommit(false); // Inicia a transação

try {
    // 1. Buscar todos os endereços vinculados ao cliente
    $stmt = $link->prepare("SELECT id_endereco_cliente FROM tb_clientes_enderecos WHERE id_cliente = ?");

    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();
    $enderecos = [];
    $enderecos[] = $row['id_endereco_cliente'];{

        $enderecos[] = $row['id_endereco'];
    }
    $stmt->close();

    // 2. Excluir os vínculos da tabela intermediária
    $stmt = $link->prepare("DELETE FROM tb_clientes_enderecos WHERE id_cliente = ?");
    $stmt->bind_param("i", $id_cliente);
    if (!$stmt->execute()) throw new Exception("Erro ao remover vínculo cliente-endereço.");
    $stmt->close();

    // 3. Excluir os contatos
    $stmt = $link->prepare("DELETE FROM tb_contatos WHERE id_cliente = ?");
    $stmt->bind_param("i", $id_cliente);
    if (!$stmt->execute()) throw new Exception("Erro ao remover contatos.");
    $stmt->close();

    // 4. Excluir os endereços (apenas os que estavam associados ao cliente)
    if (!empty($enderecos)) {
        foreach ($enderecos as $id_endereco) {
            $stmt = $link->prepare("DELETE FROM tb_enderecos WHERE id_endereco_cliente = ?");
            $stmt->bind_param("i", $id_endereco);
            if (!$stmt->execute()) throw new Exception("Erro ao remover endereço ID $id_endereco.");
            $stmt->close();
        }
    }

    // 5. Por fim, excluir o cliente
    $stmt = $link->prepare("DELETE FROM tb_clientes WHERE id_cliente = ?");
    $stmt->bind_param("i", $id_cliente);
    if (!$stmt->execute()) throw new Exception("Erro ao remover cliente.");
    $stmt->close();

    // Commit após todas as etapas
    $link->commit();
    $_SESSION['msg'] = 'Cliente excluído com sucesso!';
} catch (Exception $e) {
    $link->rollback();
    $_SESSION['msg'] = 'Erro ao excluir cliente: ' . $e->getMessage();
}

$link->close();
header('Location: clientes.php');
exit();
?>
