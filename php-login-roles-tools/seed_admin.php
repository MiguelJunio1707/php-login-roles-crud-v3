<?php
// tools/seed_admin.php
// Cria ou atualiza o admin com senha "admin123" usando password_hash.
// Copie este arquivo para a raiz do projeto (mesmo nível de index.php) dentro de uma pasta "tools/"
// e acesse: http://localhost/seu-projeto/tools/seed_admin.php

require __DIR__ . '/../config/db.php';

$email = 'admin@example.com';
$first = 'Site';
$last  = 'Admin';
$pass  = 'admin123';
$hash  = password_hash($pass, PASSWORD_DEFAULT);

try {
  // Verifica se existe
  $stmt = $pdo->prepare('SELECT id FROM users WHERE email=?');
  $stmt->execute([$email]);
  $row = $stmt->fetch();

  if ($row) {
    $upd = $pdo->prepare('UPDATE users SET first_name=?, last_name=?, password_hash=?, role=? WHERE id=?');
    $upd->execute([$first, $last, $hash, 'admin', $row['id']]);
    echo "Admin atualizado com sucesso. Login: {$email} / Senha: {$pass}";
  } else {
    $ins = $pdo->prepare('INSERT INTO users (first_name, last_name, email, password_hash, role) VALUES (?,?,?,?,?)');
    $ins->execute([$first, $last, $email, $hash, 'admin']);
    echo "Admin criado com sucesso. Login: {$email} / Senha: {$pass}";
  }
} catch (PDOException $e) {
  http_response_code(500);
  echo "Erro: " . $e->getMessage();
}
