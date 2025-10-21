<?php
include('conndb.php');
session_start();

if(!isset($_SESSION['id_usuario'])){
    $_SESSION['msg'] = "Faça Login para adicionar cliente";
    header('location:login.php');
    exit();
}

if (!isset($_SESSION['msg'])) {
    $_SESSION['msg'] = '';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $cep = $_POST['cep'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $telefone = $_POST['telefone'];
    $celular = $_POST['celular'];

    // Verifica se o email já está cadastrado
    $stmt_check = $link->prepare("SELECT COUNT(*) FROM tb_contatos WHERE email_cliente = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->bind_result($quant);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($quant > 0) {
        $_SESSION['msg'] = 'E-mail já existente!';
        header('location:addcliente.php');
        exit();
    }

    // Iniciar transação
    $link->autocommit(false);

    try {
        // 1. Inserir cliente
        $stmt0 = $link->prepare("INSERT INTO tb_clientes (nome_cliente, sobrenome_cliente, cpf_cliente) VALUES (?, ?, ?)");
        $stmt0->bind_param("sss", $nome, $sobrenome, $cpf);
        if (!$stmt0->execute()) throw new Exception("Erro ao salvar cliente.");
        $id_cliente = $stmt0->insert_id;
        $stmt0->close();

        // 2. Inserir contato
        $stmt1 = $link->prepare("INSERT INTO tb_contatos (id_cliente, telefone_cliente, celular_cliente, email_cliente) VALUES (?, ?, ?, ?)");
        $stmt1->bind_param("isss", $id_cliente, $telefone, $celular, $email);
        if (!$stmt1->execute()) throw new Exception("Erro ao salvar contato.");
        $stmt1->close();

        // 3. Inserir endereço
        $stmt2 = $link->prepare("INSERT INTO tb_enderecos (cep_cliente, bairro_cliente, rua_cliente, numero_cliente) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("sssi", $cep, $bairro, $rua, $numero);
        if (!$stmt2->execute()) throw new Exception("Erro ao salvar endereço.");
        $id_endereco = $stmt2->insert_id;
        $stmt2->close();

        // 4. Vincular cliente e endereço
        $stmt3 = $link->prepare("INSERT INTO tb_clientes_enderecos (id_cliente, id_endereco_cliente) VALUES (?, ?)");
        $stmt3->bind_param("ii", $id_cliente, $id_endereco);
        if (!$stmt3->execute()) throw new Exception("Erro ao vincular cliente ao endereço.");
        $stmt3->close();

        // Tudo OK — commit
        $link->commit();
        $_SESSION['msg'] = 'Cliente cadastrado com sucesso!';
        $link->close();
        header('location:clientes.php');
        exit();

    } catch (Exception $e) {
        // Algo deu errado — rollback
        $link->rollback();
        $_SESSION['msg'] = $e->getMessage();
        $link->close();
        header('location:addcliente.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastra  Cliente</title>
    <link rel="stylesheet" href="style.css/add.css">
</head>

<body>
   <h1>Cadastrar Cliente</h1>
    <p><?= $_SESSION['msg']; ?></p>

    <form action="addcliente.php" method="post">
        <div class="form-column">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" maxlength="15" required>

            <label for="cpf">CPF</label>
<input type="text" name="cpf" id="cpf" maxlength="14" required>
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
            <input type="text" name="cep" id="cep" maxlength="9" required>

            <label for="numero">Número:</label>
            <input type="text" name="numero" id="numero" maxlength="5" required>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" maxlength="14" required>

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
            <input type="text" name="sobrenome" id="sobrenome" maxlength="15" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" maxlength="50" required>

            <label for="rua">Logradouro:</label>
            <input type="text" name="rua" id="rua" maxlength="50" required>

            <label for="bairro">Bairro:</label>
            <input type="text" name="bairro" id="bairro" maxlength="30" required>

            <label for="celular">WhatsApp:</label>
            <input type="text" name="celular" id="celular" maxlength="15" required>

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
            <input type="submit" value="Enviar">
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
