Aqui é um Debug para caso alguma coisa no login dê errado

Ferramentas de diagnóstico e reset de senha admin para o projeto PHP Login Roles

Arquivos:
- tools/seed_admin.php → Cria/atualiza o admin (admin@example.com / admin123) com password_hash().
Testa conexão com BD
- tools/test_db.php → Testa a conexão e exibe até 5 usuários da tabela.


2) Acesse no navegador:
   - http://localhost/seu-projeto/tools/test_db.php  (confira a conexão e usuários)
   - http://localhost/seu-projeto/tools/seed_admin.php (recria/atualiza o admin)
3) Tente logar novamente em `index.php`.
