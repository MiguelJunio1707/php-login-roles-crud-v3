<?php
require __DIR__ . '/protect.php';
require __DIR__ . '/config/db.php';
require __DIR__ . '/helpers.php';
ensure_admin();
//Variavel de array vazio para receber futuros erros
$errors = [];
$first_name = $last_name = $email = $role = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $first_name = trim($_POST['first_name'] ?? '');
  $last_name  = trim($_POST['last_name'] ?? '');
  $email      = trim($_POST['email'] ?? '');
  $password   = $_POST['password'] ?? '';
  $role       = $_POST['role'] ?? 'user';

  if ($first_name === '') $errors[] = 'Primeiro nome é obrigatório.';
  if ($last_name === '')  $errors[] = 'Sobrenome é obrigatório.';
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail inválido.';
  if (strlen($password) < 6) $errors[] = 'Senha deve ter pelo menos 6 caracteres.';
  if (!in_array($role, ['admin','user'], true)) $errors[] = 'Perfil inválido.';

  if (!$errors) {
    try {
      $stmt = $pdo->prepare('INSERT INTO users (first_name, last_name, email, password_hash, role) VALUES (?,?,?,?,?)');
      $stmt->execute([$first_name, $last_name, $email, password_hash($password, PASSWORD_DEFAULT), $role]);
      flash_set('success', 'Usuário criado com sucesso.');
      header('Location: admin.php');
      exit;
      //O catch aqui vai verificar se deu algum erro ao salvar
    } catch (PDOException $e) {
      if ($e->getCode() === '23000') { // Se já existe um e-mail cadastrado
        $errors[] = 'Já existe um usuário com este e-mail.';
      } else {
        $errors[] = 'Erro ao salvar: ' . $e->getMessage();
      }
    }
  }
}

include __DIR__ . '/partials/header.php';
?>
<div class="d-flex align-items-center justify-content-between mb-3">
  <h2 class="h4 mb-0">Novo Usuário</h2>
  <a class="btn btn-outline-secondary btn-sm" href="admin.php">Voltar</a>
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
      <label class="form-label">Primeiro Nome</label>
      <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($first_name); ?>" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Sobrenome</label>
      <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($last_name); ?>" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">E-mail</label>
      <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Perfil</label>
      <select name="role" class="form-select">
        <option value="user" <?php echo $role==='user'?'selected':''; ?>>User</option>
        <option value="admin" <?php echo $role==='admin'?'selected':''; ?>>Admin</option>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">Senha</label>
      <input type="password" name="password" class="form-control" required>
    </div>
  </div>
  <div class="mt-3 text-end">
    <button class="btn btn-primary">Salvar</button>
  </div>
</form>

<?php include __DIR__ . '/partials/footer.php'; ?>
