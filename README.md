# Login com PHP ETC - 2025/2
Esqueleto para projeto alunos 2025.2

## Requisitos
- PHP 8+ (XAMPP ou similar)
- MySQL/MariaDB
- Navegador

## Instalação
1. Crie um banco `login_roles` no MySQL.
2. Importe o arquivo `login_roles.sql` no phpMyAdmin (ou via CLI).
3. Edite `config/db.php` com seu usuário/senha do MySQL se necessário.
4. Copie a pasta para `C:\xampp\htdocs\php-login-roles` (Windows) ou `htdocs` equivalente.
5. Acesse `http://localhost/php-login-roles/`.

### Usuários de teste
- Admin: **admin@example.com** / **admin123**
- User: **user@example.com** / **user123**
- Cristiano: **cristiano@admin.com** / **123456**
- Micaela: **micaela@micaela.com** / **123456**

> As senhas já estão com `password_hash()` no SQL de seed.

## Estrutura
```
php-login-roles/
├─ index.php         # Tela de login
├─ login.php         # Processa o login
├─ logout.php        # Encerra sessão
├─ admin.php         # Dashboard do Admin (lista usuários)
├─ user.php          # Página do usuário padrão (nome no topo)
├─ protect.php       # Middleware simples de autenticação e autorização
├─ init.sql          # Script SQL para criar tabela e usuários de teste
├─ config/
│  └─ db.php         # Conexão PDO
└─ partials/
   ├─ header.php     # Navbar Bootstrap
   └─ footer.php
```

## Notas importantes!
- Usa `password_hash()` e `password_verify()`.
- Sessões PHP para manter o usuário logado. //Aula a ser ministrada no dia 9/9
- Redirecionamento por perfil (Admin → `admin.php`, User → `user.php`).

## CRUD para Admin
- **Criar usuário**: `users_create.php`
- **Editar usuário**: `users_edit.php`
- **Excluir usuário**: `users_delete.php` (POST + CSRF)
- Protegido por `ensure_admin()` em `helpers.php`.

### Segurança de código aplicada
- `password_hash()` / `password_verify()`
- **CSRF token** em formulários sensíveis (Estudem esse assunto que vou perguntar na hora da apresentação)
- Validação e tratamento de erros (e-mail único, senha mínima)
- Bloqueio para excluir o próprio usuário logado

