<?php
session_start();
include('conndb.php');

if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['msg'] = 'Faça login para prosseguir';
    header('Location:Login.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['msg'] = 'ID do cliente inválido.';
    header('Location: clientes.php');
    exit();
}

$id_cliente = (int) $_GET['id'];

// Buscar nome do cliente para exibir
$stmt = $link->prepare("SELECT nome_cliente, sobrenome_cliente FROM tb_clientes WHERE id_cliente = ?");
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();
$stmt->close();
$link->close();

if (!$cliente) {
    $_SESSION['msg'] = 'Cliente não encontrado.';
    header('Location: clientes.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Exclusão</title>
    <link rel="stylesheet" href="style.css/confirmar.css"> <!-- Opcional -->


<style>
    @import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Montserrat:wght@400&display=swap');

/* Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Montserrat', sans-serif;
  background: linear-gradient(135deg, #F5F5DC, #FFF8E1); /* Bege claro */
  color: #5D4037; /* Marrom escuro */
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 80px 20px;
  min-height: 100vh;
  text-align: center;
}

h1 {
  font-family: 'Merriweather', serif;
  font-size: 2.4rem;
  margin-bottom: 20px;
  color: #8D6E63;
}

p {
  font-size: 1.1rem;
  margin-bottom: 30px;
}

form {
  display: flex;
  flex-direction: column;
  gap: 15px;
  align-items: center;
}

button {
  background-color: #8D6E63;
  border: none;
  border-radius: 12px;
  padding: 12px 24px;
  cursor: pointer;
  box-shadow: 0 6px 15px rgba(141, 110, 99, 0.3);
  transition: all 0.3s ease;
  color: #fff;
  font-size: 1rem;
  font-weight: bold;
}

button:hover {
  background-color: #FFEB3B;
  color: #5D4037;
  box-shadow: 0 10px 25px rgba(255, 235, 59, 0.6);
}

a button {
  background-color: #bbb;
}

a button:hover {
  background-color: #888;
  color: white;
}

/* Tema escuro (caso você queira aplicar com script depois) */
body[data-theme="dark"] {
  background: linear-gradient(135deg, #1A120F, #2E1F1A);
  color: #FDEBD0;
}

body[data-theme="dark"] h1 {
  color: #FFD700;
}

body[data-theme="dark"] button {
  background-color: #D4AF37;
  color: #1A120F;
  box-shadow: 0 6px 15px rgba(212, 175, 55, 0.3);
}

body[data-theme="dark"] button:hover {
  background-color: #BFA132;
  color: #FFF8E1;
  box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
}

body[data-theme="dark"] a button {
  background-color: #666;
}

body[data-theme="dark"] a button:hover {
  background-color: #444;
}
</style>
</head>
<body>
    <h1>Excluir Cliente</h1>
    <p>Tudo relacionado a <strong><?= htmlspecialchars($cliente['nome_cliente'] . ' ' . $cliente['sobrenome_cliente']) ?></strong> será permanentemente apagado, deseja prosseguir?</p>
    
    <form action="excluircliente.php" method="GET">
        <input type="hidden" name="id" value="<?= $id_cliente ?>">
        <button type="submit">Sim, excluir</button>
        <a href="clientes.php"><button type="button">Voltar</button></a>
    </form>
</body>
</html>
