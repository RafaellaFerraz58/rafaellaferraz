<?php
include("conexao.php");

// Receber dados do formulário
$nome     = $_POST['nome'];
$telefone = $_POST['telefone'];
$email    = $_POST['email'];
$senha    = password_hash($_POST['senha'], PASSWORD_DEFAULT); // senha criptografada
$endereco = $_POST['endereco'];
$cpf      = $_POST['cpf'];
$cep      = $_POST['cep'];

// Preparar a query para evitar SQL Injection
$sql = "INSERT INTO usuarios (nome, telefone, email, senha, endereco, cpf, cep)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

// Preparar a declaração
$stmt = $con->prepare($sql);

// Verificar se a preparação foi bem-sucedida
if ($stmt === false) {
    die("Erro na preparação: " . $con->error);
}

// Vincular os parâmetros
$stmt->bind_param("sssssss", $nome, $telefone, $email, $senha, $endereco, $cpf, $cep);

// Executar a query
if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro: " . $stmt->error;
}

// Fechar a declaração e a conexão
$stmt->close();
$con->close();
?>