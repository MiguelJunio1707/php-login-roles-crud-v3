<?php
require __DIR__ . '/protect.php';
require __DIR__ . '/config/db.php';
require __DIR__ . '/helpers.php';

$userId = (int)$_SESSION['user_id'];

// Busca usuário na tabela "usuario"
$stmt = $pdo->prepare('SELECT id, nome, email, tipo_usuario FROM usuario WHERE id=?');
$stmt->execute([$userId]);
$user = $stmt->fetch();
if (!$user) {
  die('Usuário não encontrado.');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $nome      = trim($_POST['first_name'] ?? '');
  $email     = trim($_POST['email'] ?? '');
  $password  = $_POST['password'] ?? '';
  $password2 = $_POST['password2'] ?? '';

  if ($nome === '') $errors[] = 'Nome é obrigatório.';
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail inválido.';

  if ($password !== '' || $password2 !== '') {
    if ($password !== $password2) $errors[] = 'As senhas não conferem.';
    if (strlen($password) > 0 && strlen($password) < 6) $errors[] = 'Senha deve ter pelo menos 6 caracteres.';
  }

  if (!$errors) {
    // verificar duplicidade de e-mail
    $chk = $pdo->prepare('SELECT id FROM usuario WHERE email=? AND id<>?');
    $chk->execute([$email, $userId]);
    if ($chk->fetch()) {
      $errors[] = 'Já existe um usuário com este e-mail.';
    } else {
      if ($password) {
        $stmt = $pdo->prepare('UPDATE usuario SET nome=?, email=?, senha=? WHERE id=?');
        $stmt->execute([$nome, $email, password_hash($password, PASSWORD_DEFAULT), $userId]);
      } else {
        $stmt = $pdo->prepare('UPDATE usuario SET nome=?, email=? WHERE id=?');
        $stmt->execute([$nome, $email, $userId]);
      }

      // Atualiza sessão
      $_SESSION['first_name'] = $nome;
      $_SESSION['email']      = $email;
      flash_set('success', 'Perfil atualizado com sucesso.');
      header('Location: profile.php');
      exit;
    }
  }
}

include __DIR__ . '/partials/header.php';
flash_show();
?>
<div class="d-flex align-items-center justify-content-between mb-3">
  <h2 class="h4 mb-0">Meu Perfil</h2>
  <a class="btn btn-outline-secondary btn-sm" href="<?php echo $_SESSION['tipo_usuario']==='admin' ? 'admin.php' : 'user.php'; ?>">Voltar</a>
</div>

<?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" class="card shadow-sm p-3">
  <?php csrf_input(); ?>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Nome</label>
      <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['nome']); ?>" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">E-mail</label>
      <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Nova Senha (opcional)</label>
      <input type="password" name="password" class="form-control" placeholder="Deixe em branco para manter">
    </div>
    <div class="col-md-3">
      <label class="form-label">Confirmar Senha</label>
      <input type="password" name="password2" class="form-control" placeholder="Repita a nova senha">
    </div>
  </div>
  <div class="mt-3 text-end">
    <button class="btn btn-primary">Salvar</button>
  </div>
</form>

<?php include __DIR__ . '/partials/footer.php'; ?>
