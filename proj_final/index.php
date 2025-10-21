<?php 
session_start();
include('conndb.php');

if(!isset($_SESSION['id_usuario'])){
    $_SESSION['msg'] = "Faça login para abrir o Index";
    header('location:login.php');
    exit();
}

   
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="style.css/index.css">

</head>
<body>
    <br>
    
    <div class="container">
        
        <h1>Index</h1></a>
        <div>
        
            <a href="agenda.php" class="btn"><button>Agenda</button></a>
            <a href="clientes.php" class="btn"><button>Clientes</button></a>

            
     


           
            <a href="Configura.php" class="btn"><button>Configurações</button></a>
            <a href="logout.php" class="btn"><button>Sair</button></a>
        </div>
        <p>
            Olá <?= htmlspecialchars($_SESSION['nome_usuario']) ?>, bem-vindo a área de trabalho!
        </p>
        
</body>

</html>

<style>
    .menu-custom {
  width: 200px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-family: 'Montserrat', sans-serif;
}

.item-menu {
  padding: 10px;
  cursor: pointer;
  border-bottom: 1px solid #eee;
  background-color: #f9f9f9;
  position: relative;
}

.item-menu:hover {
  background-color: #e9e9e9;
}

.submenu {
  display: none;
  position: absolute;
  left: 100%;
  top: 0;
  border: 1px solid #ccc;
  background: white;
  width: 160px;
  z-index: 999;
}

.submenu-container:hover .submenu {
  display: block;
}

.item-submenu {
  padding: 8px;
  border-bottom: 1px solid #eee;
  cursor: pointer;
}

.item-submenu:hover {
  background-color: #ddd;
}





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


