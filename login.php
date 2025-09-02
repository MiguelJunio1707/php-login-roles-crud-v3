<?php

session_start();
require __DIR__ . '/config/db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
  header('Location: index.php?error=Preencha e-mail e senha.');
  exit;
}

$stmt = $pdo->prepare('SELECT id, first_name, last_name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
  // Guarda o essencial na sessão
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['first_name'] = $user['first_name'];
  $_SESSION['last_name'] = $user['last_name'];
  $_SESSION['email'] = $user['email'];
  $_SESSION['role'] = $user['role'];

  if ($user['role'] === 'admin') {
    header('Location: admin.php');
  } else {
    header('Location: user.php');
  }
  exit;
}

header('Location: index.php?error=Credenciais inválidas.');
exit;
