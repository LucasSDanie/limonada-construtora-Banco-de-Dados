<?php
// Conectar ao banco de dados
$conexao = new mysqli("localhost", "root", "", "construtora limonada");

// Verificar a conexão
if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

// Receber os dados do formulário
$nome = $_POST['nome'];
$senha = $_POST['senha'];
$cpf = $_POST['cpf'];

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
