<?php

session_start();
if (isset($_SESSION['user_id'])) {
  // Depois que fez o login → redireciona conforme o tipo de usuário
  if ($_SESSION['tipo_usuario'] === 'admin') {
    // Se admin
    header('Location: admin.php');
    exit;
  } else {
    // Se paciente ou fisioterapeuta
    header('Location: usuario.php');
    exit;
  }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-success d-flex align-items-center imagemfisio" style="min-height:100vh; background-image: url('Agendamento de Fisioterapia com Conforto.png' ">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h1 class="h4 mb-3 text-center">Fisiovida</h1>
            <?php if (!empty($_GET['error'])): ?>
              <div class="alert alert-danger py-2"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>
            <form method="post" action="login.php" autocomplete="off">
              <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control">
              </div>
              <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="password" class="form-control">
              </div>
              <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
            <hr>
            <p class="text-muted small mb-0">Aqui você pode colocar o que quiser!</p>
          </div>
        </div>
      </div>
    </div>
  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
