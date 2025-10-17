<?php
// helpers.php - CSRF, Flash e verificação de Admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se o usuário estiver logado com um perfil de admin
function ensure_admin() {
    // Compatível com o banco fisiovida
    $tipo = $_SESSION['tipo_usuario'] ?? $_SESSION['role'] ?? null;
    $id   = $_SESSION['id'] ?? $_SESSION['user_id'] ?? null;

    if (!$id || $tipo !== 'admin') {
        header('Location: index.php?error=Acesso negado.');
        exit;
    }
}

// Essa aqui é uma função que cria um token CSRF para segurança
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_input() {
    $t = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
    echo '<input type="hidden" name="csrf_token" value="'.$t.'">';
}

function csrf_check() {
    if (($_POST['csrf_token'] ?? '') !== ($_SESSION['csrf_token'] ?? '')) {
        http_response_code(400);
        die('Token CSRF inválido.');
    }
}

function flash_set($type, $msg) {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

/**
 * Aqui meus amigos traz o toasts Bootstrap a partir de flash_set().
 * Depende de showToast() "Função no arquivo" partials/footer.php.
 */
function flash_show() {
    if (!empty($_SESSION['flash'])) {
        $f    = $_SESSION['flash'];
        $type = $f['type'] ?? 'primary';
        $msg  = $f['msg']  ?? '';
        $payload = json_encode(
            [['type' => $type, 'msg' => $msg]],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        // Injeta JSON + JS que chama showToast() no carregamento
        echo '<script id="flashToastsScript" type="application/json">'.$payload.'</script>';
        echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            try {
                var node = document.getElementById("flashToastsScript");
                if (!node) return;
                var data = JSON.parse(node.textContent) || [];
                data.forEach(function(it){
                    if (typeof showToast === "function") {
                        showToast(it.msg, it.type);
                    }
                });
            } catch (e) {}
        });
        </script>';

        unset($_SESSION['flash']);
    }
}
