<?php
require __DIR__ . '/protect.php';
require __DIR__ . '/config/db.php';
require __DIR__ . '/helpers.php';
ensure_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: admin.php');
  exit;
}
csrf_check();
$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
  flash_set('danger', 'ID inválido.');
  header('Location: admin.php');
  exit;
}

// Evita deletar a si mesmo
if ($id === (int)$_SESSION['user_id']) {
  flash_set('warning', 'Você não pode excluir o próprio usuário logado.');
  header('Location: admin.php');
  exit;
}

$stmt = $pdo->prepare('DELETE FROM users WHERE id=?');
$stmt->execute([$id]);
flash_set('success', 'Usuário excluído.');
header('Location: admin.php');
exit;
