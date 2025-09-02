<?php
// tools/test_db.php
// Testa a conexão e lista rapidamente os usuários para diagnóstico.

require __DIR__ . '/../config/db.php';

try {
  $count = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
  echo "<p>Conexão OK. Usuários na tabela: <strong>{$count}</strong></p>";
  $stmt = $pdo->query('SELECT id, first_name, last_name, email, role, created_at FROM users ORDER BY id DESC LIMIT 5');
  echo "<table border='1' cellpadding='6'><tr><th>ID</th><th>Nome</th><th>E-mail</th><th>Perfil</th><th>Criado</th></tr>";
  foreach ($stmt as $u) {
    echo "<tr><td>{$u['id']}</td><td>".htmlspecialchars($u['first_name'].' '.$u['last_name'])."</td><td>".htmlspecialchars($u['email'])."</td><td>{$u['role']}</td><td>{$u['created_at']}</td></tr>";
  }
  echo "</table>";
} catch (PDOException $e) {
  http_response_code(500);
  echo "Erro ao consultar: " . $e->getMessage();
}
