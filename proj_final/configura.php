<?php
session_start();

if(!isset($_SESSION['id_usuario'])){
  $_SESSION['msg'] = "Faça login para configurar preferências";
  header('location:login.php');
  exit();
}


?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Configurações</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<style>
  /* Fonts */
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
  justify-content: center;
  align-items: flex-start;
  min-height: 100vh;
  padding: 100px 20px 40px;
  user-select: none;
}

.config-container {
  background-color: #fffaf0;
  border-radius: 20px;
  box-shadow: 0 15px 40px rgba(93, 64, 55, 0.2);
  padding: 40px 60px;
  text-align: center;
  width: 300px;
}

h1 {
  font-family: 'Merriweather', serif;
  font-size: 2.2rem;
  color: #8D6E63;
  margin-bottom: 30px;
  text-align: center;
}

nav {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

/* Links e botões com mesmo estilo */
nav a,
nav button {
  background-color: #8D6E63;
  color: white;
  text-decoration: none;
  padding: 12px 20px;
  border: none;
  border-radius: 12px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  box-shadow: 0 6px 15px rgba(141, 110, 99, 0.3);
  transition: all 0.3s ease;
}

nav a:hover,
nav button:hover {
  background-color: #FFEB3B;
  color: #5D4037;
  box-shadow: 0 10px 25px rgba(255, 235, 59, 0.6);
}

</style>
</head>
<body>

<div class="config-container">
  <h2><span class="material-symbols-outlined">settings</span>Configurações</h2>
  <br>
  <nav> 







  
    <a href="alterasenha.php">Alterar Senha</a>
    <a href="index.php">Voltar</a>
  </nav>
</div>




</body>
</html>
