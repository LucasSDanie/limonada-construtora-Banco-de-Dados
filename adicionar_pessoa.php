<?php
// Conectar ao banco de dados
$conexao = new mysqli("localhost", "root", "", "construtora limonada");

// Verificar a conexão
if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

if(isset($_POST['entrar'])) {
    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];

    // Verificar se o CPF existe no banco de dados
    $query_select = "SELECT * FROM login WHERE cpf=?";
    $stmt_select = $conexao->prepare($query_select);
    $stmt_select->bind_param("s", $cpf);
    $stmt_select->execute();
    $result = $stmt_select->get_result();

    if($result->num_rows > 0) {
        // CPF encontrado, verificar se o nome e a senha correspondem
        $user = $result->fetch_assoc();
        $db_nome = $user['nome'];
        $db_senha = $user['senha'];

        if ($nome === $db_nome && $senha === $db_senha) {
            // Nome e senha correspondem, redirecionar para page1.html
            header("Location: page1.html?cpf=$cpf&nome=$nome&senha=$senha");
            exit;
        } else {
            // Nome ou senha não correspondem
            echo "Nome ou senha incorretos para o CPF fornecido.";
            exit;
        }
    } else {
        echo "Este CPF não está associado a nenhuma conta.";
        exit;
    }

    $stmt_select->close();
}

// Verificar se o CPF já existe no banco de dados ao criar uma conta
$cpf = $_POST['cpf'];
$query_check_cpf = "SELECT * FROM login WHERE cpf=?";
$stmt_check_cpf = $conexao->prepare($query_check_cpf);
$stmt_check_cpf->bind_param("s", $cpf);
$stmt_check_cpf->execute();
$result_check_cpf = $stmt_check_cpf->get_result();

if($result_check_cpf->num_rows > 0) {
    // CPF já existe, mostrar mensagem de erro
    echo "Este CPF já está associado a uma conta.";
    exit;
}

// Se o CPF não existir, proceder com a criação da conta
$nome = $_POST['nome'];
$senha = $_POST['senha'];

// Preparar a query de inserção
$query = "INSERT INTO login (nome, senha, cpf) VALUES (?, ?, ?)";
$stmt = $conexao->prepare($query);

// Verificar se a preparação foi bem-sucedida
if ($stmt === false) {
    die("Erro na preparação da consulta: " . $conexao->error);
}

// Bind dos parâmetros e execução da query
$stmt->bind_param("sss", $nome, $senha, $cpf);
if ($stmt->execute()) {
    // Redirecionar para page1.html
    header("Location: page1.html");
    exit;
} else {
    echo "Erro ao adicionar pessoa: " . $conexao->error;
}

// Fechar a declaração e a conexão
$stmt->close();
$conexao->close();
?>
