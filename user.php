<?php
require __DIR__ . '/protect.php';

if ($_SESSION['role'] === 'admin') {
  header('Location: admin.php');
  exit;
}

include __DIR__ . '/partials/header.php';
?>
<div class="d-flex align-items-center justify-content-between mb-3">
  <h2 class="h4 mb-0">Página do Usuário</h2>
  <span class="badge text-bg-secondary">Perfil: User</span>
</div>

<div class="alert alert-success">
  <strong>Bem-vindo(a)!</strong> Você está logado como <u><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></u>.
</div>

<p>Este é um exemplo simples para aulas. No canto superior (navbar) aparece seu nome e o botão de sair.</p>

<?php include __DIR__ . '/partials/footer.php'; ?>
