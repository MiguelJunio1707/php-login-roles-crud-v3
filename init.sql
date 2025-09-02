-- Banco e tabela
CREATE DATABASE IF NOT EXISTS login_roles CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE login_roles;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name  VARCHAR(100) NOT NULL,
  email      VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seeds com senhas já hasheadas (password_hash)
-- admin123 / user123 geradas via PHP 8 PASSWORD_DEFAULT
INSERT IGNORE INTO users (first_name, last_name, email, password_hash, role) VALUES
('Site', 'Admin', 'admin@example.com', '$2y$10$8jmo0F5NOa9I5d9aNwI8PON0r6q7hZ9iMVMscJeJm2DrN2z6lFr9S', 'admin'),
('Jane', 'User',  'user@example.com',  '$2y$10$kO2wM3s3W9V7wWv0u6Q7y.8xKJ0qQ1m3m7sF1bT9pQ1b3QmQpQxMi', 'user');
