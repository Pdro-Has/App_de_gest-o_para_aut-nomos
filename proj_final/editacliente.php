<?php
session_start();
include('conndb.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['msg'] = 'Faça Login para fazer edições';
    header('Location:Login.php');
    exit();
}

// Garante que o ID foi passado corretamente pela URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: clientes.php');
    exit();
}

$id = intval($_GET['id']); // protege contra injeção e força tipo inteiro

// Consulta para obter os dados atuais do cliente
$sql = "SELECT 
            c.nome_cliente, c.sobrenome_cliente, ct.email_cliente, ct.celular_cliente, ct.telefone_cliente, 
            c.cpf_cliente, e.cep_cliente, e.bairro_cliente, e.rua_cliente, e.numero_cliente
        FROM tb_clientes AS c
        LEFT JOIN tb_contatos AS ct ON c.id_cliente = ct.id_cliente
        LEFT JOIN tb_clientes_enderecos AS ce ON c.id_cliente = ce.id_cliente
        LEFT JOIN tb_enderecos AS e ON e.id_endereco_cliente = ce.id_endereco_cliente
        WHERE c.id_cliente = ? 
        ORDER BY ce.id_endereco_cliente DESC LIMIT 1";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$tbl = mysqli_fetch_array($result, MYSQLI_NUM);

if (!$tbl) {
    echo "<script>alert('Cliente não encontrado'); window.location.href='clientes.php';</script>";
    exit();
}

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados com segurança
    $nome      = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $email     = trim($_POST['email']);
    $celular   = trim($_POST['celular']);
    $telefone  = trim($_POST['telefone']);
    $cpf       = trim($_POST['cpf']);
    $cep       = trim($_POST['cep']);
    $bairro    = trim($_POST['bairro']);
    $rua       = trim($_POST['rua']);
    $numero    = trim($_POST['numero']);

    mysqli_begin_transaction($link);

    // Atualiza tb_clientes
    $sql1 = "UPDATE tb_clientes SET nome_cliente = ?, sobrenome_cliente = ?, cpf_cliente = ? WHERE id_cliente = ?";
    $stmt1 = mysqli_prepare($link, $sql1);
    mysqli_stmt_bind_param($stmt1, "sssi", $nome, $sobrenome, $cpf, $id);
    $ok1 = mysqli_stmt_execute($stmt1);

    // Atualiza tb_contatos
    $sql2 = "UPDATE tb_contatos SET celular_cliente = ?, telefone_cliente = ?, email_cliente = ? WHERE id_cliente = ?";
    $stmt2 = mysqli_prepare($link, $sql2);
    mysqli_stmt_bind_param($stmt2, "sssi", $celular, $telefone, $email, $id);
    $ok2 = mysqli_stmt_execute($stmt2);

    // Pega último endereço
    $res3 = mysqli_query($link, "SELECT id_endereco_cliente FROM tb_clientes_enderecos WHERE id_cliente = $id ORDER BY id_endereco_cliente DESC LIMIT 1");
    $row3 = mysqli_fetch_assoc($res3);
    $idEndereco = $row3['id_endereco_cliente'] ?? null;

    if ($idEndereco) {
        // Atualiza endereço
        $sql3 = "UPDATE tb_enderecos SET cep_cliente = ?, bairro_cliente = ?, rua_cliente = ?, numero_cliente = ? WHERE id_endereco_cliente = ?";
        $stmt3 = mysqli_prepare($link, $sql3);
        mysqli_stmt_bind_param($stmt3, "ssssi", $cep, $bairro, $rua, $numero, $idEndereco);
        $ok3 = mysqli_stmt_execute($stmt3);
    } else {
        $ok3 = false;
    }

    // Verifica se tudo deu certo
    if ($ok1 && $ok2 && $ok3) {
        mysqli_commit($link);
        echo "<script>alert('Cliente atualizado com sucesso!'); window.location.href='clientes.php';</script>";
    } else {
        mysqli_rollback($link);
        echo "<script>alert('Erro ao atualizar cliente. Nenhuma alteração foi feita.');</script>";
    }

    mysqli_close($link);
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="style.css/edita.css">
</head>
<body>
<h1>Editar Cliente</h1>
<form action="editacliente.php?id=<?=htmlspecialchars($id)?>" method="post">
  
  <div class="form-column">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" id="nome" maxlength="15" value="<?=htmlspecialchars($tbl[0])?>" required>

    <label for="cpf">CPF:</label>
    <input type="text" name="cpf" id="cpf" maxlength="14" value="<?=htmlspecialchars($tbl[5])?>" required>
    <small id="cpf-erro" style="color: red; display: none;">CPF inválido</small>

<script>
function aplicarMascaraCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    cpf = cpf.replace(/^(\d{3})(\d)/, '$1.$2');
    cpf = cpf.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
    cpf = cpf.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3-$4');
    return cpf;
}

function validarCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

    let soma = 0, resto;

    for (let i = 1; i <= 9; i++)
        soma += parseInt(cpf.charAt(i - 1)) * (11 - i);
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.charAt(9))) return false;

    soma = 0;
    for (let i = 1; i <= 10; i++)
        soma += parseInt(cpf.charAt(i - 1)) * (12 - i);
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.charAt(10))) return false;

    return true;
}

const inputCPF = document.getElementById('cpf');
const erroCPF = document.getElementById('cpf-erro');

inputCPF.addEventListener('input', () => {
    inputCPF.value = aplicarMascaraCPF(inputCPF.value);
});

inputCPF.addEventListener('blur', () => {
    const valido = validarCPF(inputCPF.value);
    if (!valido) {
        erroCPF.style.display = 'inline';
    } else {
        erroCPF.style.display = 'none';
    }
});
</script>

    <label for="cep">CEP:</label>
    <input type="text" name="cep" id="cep" maxlength="9" value="<?=htmlspecialchars($tbl[6])?>" required>

    <label for="numero">Número:</label>
    <input type="text" name="numero" id="numero" maxlength="5" value="<?=htmlspecialchars($tbl[9])?>" required>

    <label for="telefone">Telefone:</label>
    <input type="text" id="telefone" name="telefone" maxlength="14" value="<?=htmlspecialchars($tbl[4])?>">

    <script>
            document.addEventListener("DOMContentLoaded", function() {
            const telefoneInput = document.getElementById("telefone");

            telefoneInput.addEventListener("input", function () {
            let valor = telefoneInput.value.replace(/\D/g, ''); // Só números

            if (valor.length > 10) valor = valor.slice(0, 10); // Limita a 10 dígitos

            if (valor.length >= 1) {
                valor = '(' + valor;
            }
            if (valor.length >= 3) {
                valor = valor.slice(0, 3) + ') ' + valor.slice(3);
            }
            if (valor.length >= 9) {
                valor = valor.slice(0, 9) + '-' + valor.slice(9);
            }

            telefoneInput.value = valor;
            });
           });
        </script>
  </div>

  <div class="form-column">
    <label for="sobrenome">Sobrenome:</label>
    <input type="text" name="sobrenome" id="sobrenome" maxlength="15" value="<?=htmlspecialchars($tbl[1])?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" maxlength="50" value="<?=htmlspecialchars($tbl[2])?>" required>

    <label for="rua">Rua:</label>
    <input type="text" id="rua" name="rua" maxlength="50" value="<?=htmlspecialchars($tbl[8])?>" required>

    <label for="bairro">Bairro:</label>
    <input type="text" name="bairro" id="bairro" maxlength="30" value="<?=htmlspecialchars($tbl[7])?>" required>

    <label for="celular">WhatsApp:</label>
            <input type="text" name="celular" id="celular" maxlength="15" value="<?=htmlspecialchars($tbl[3])?>" required>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
            const celularInput = document.getElementById("celular");

            celularInput.addEventListener("input", function () {
            let valor = celularInput.value.replace(/\D/g, ''); // Remove não dígitos

            if (valor.length > 11) valor = valor.slice(0, 11); // Limita a 11 dígitos

            if (valor.length >= 1) {
                valor = '(' + valor;
            }
            if (valor.length >= 3) {
                valor = valor.slice(0, 3) + ') ' + valor.slice(3);
            }
            if (valor.length >= 10) {
                valor = valor.slice(0, 10) + '-' + valor.slice(10);
            }

            celularInput.value = valor;
            });
            });
        </script>
  </div>

  <div class="form-buttons">
    <input type="submit" value="Salvar">
    <a href="clientes.php"><input type="button" value="Cancelar"></a>
  </div>
</form>


</body>
</html>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const cepInput = document.getElementById("cep");

    // 🧠 Máscara de CEP enquanto digita
    cepInput.addEventListener("input", function () {
        let cep = cepInput.value.replace(/\D/g, ''); // Remove não dígitos
        if (cep.length > 5) {
            cep = cep.slice(0, 5) + '-' + cep.slice(5, 8);
        }
        cepInput.value = cep;
    });

    // 🔍 Busca de endereço ao sair do campo
    cepInput.addEventListener("blur", function() {
        let cep = cepInput.value.replace(/\D/g, ''); // Remove tudo que não é número

        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao buscar o CEP');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.erro) {
                        alert("CEP não encontrado.");
                        return;
                    }
                    // Preenche os campos do formulário
                    document.getElementById("rua").value = data.logradouro;
                    document.getElementById("bairro").value = data.bairro;
                })
                .catch(error => {
                    console.error("Erro na busca do CEP: ", error);
                    alert("Não foi possível buscar o endereço.");
                });
        } else {
            alert("Formato de CEP inválido. Deve conter 8 dígitos numéricos.");
        }
    });
});
</script>


 