<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    // Usuário já logado, redireciona para dashboard
    header("Location: dashboard.php");
    exit;
}

$erro = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include("conexao.php");

    $email = isset($_POST['email']) ? trim($_POST['email']) : "";
    $senha = isset($_POST['senha']) ? $_POST['senha'] : "";

    if ($email === "" || $senha === "") {
        $erro = "Preencha e‑mail e senha.";
    } else {
        // Preparar consulta para buscar usuário por email
        $sql = "SELECT id, nome, email, senha FROM usuarios WHERE email = ? LIMIT 1";
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                // Verifica senha
                if (password_verify($senha, $user['senha'])) {
                    // Autenticação bem-sucedida
                    session_regenerate_id(true);
                    $_SESSION['usuario_id'] = $user['id'];
                    $_SESSION['usuario_nome'] = $user['nome'];
                    $_SESSION['usuario_email'] = $user['email'];

                    // Redireciona para dashboard
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $erro = "Senha incorreta.";
                }
            } else {
                $erro = "E‑mail não cadastrado.";
            }

            $stmt->close();
        } else {
            $erro = "Erro na consulta: " . $con->error;
        }

        $con->close();
}