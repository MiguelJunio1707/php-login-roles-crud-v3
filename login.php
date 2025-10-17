<?php
session_start();
require __DIR__ . '/config/db.php'; // conecta com Fisiovida
require_once __DIR__ . '/helpers.php'; // flash_set()

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (!$email || !$password) {
    flash_set('danger', 'Preencha e-mail e senha.');
    header('Location: index.php');
    exit;
}

// Busca o usuário no banco
$sql = "SELECT id, nome, email, senha, tipo_usuario 
        FROM usuario 
        WHERE email = ? 
        LIMIT 1";
$stmt = $conn->prepare($sql); // $conn vem do db.php
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['senha'])) {
    // Guarda na sessão
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['first_name'] = $user['nome'];
    $_SESSION['email']      = $user['email'];
    $_SESSION['role']       = $user['tipo_usuario']; // 'admin' ou 'paciente'

    flash_set('success', 'Login realizado com sucesso!');

    if ($user['tipo_usuario'] === 'admin') {
        header('Location: admin.php');
    } else {
        header('Location: usuario.php');
    }
    exit;
}

// Se falhar
flash_set('danger', 'E-mail ou senha inválidos.');
header('Location: index.php');
exit;
