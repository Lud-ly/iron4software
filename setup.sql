-- Iron4Software - Base de données
-- @authors Ludo, Damien, Emilio
-- @location Saint-Martin-de-Londres, France

CREATE DATABASE IF NOT EXISTS iron4software CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE iron4software;

-- Utilisateur de base de données
CREATE USER IF NOT EXISTS 'iron4_admin'@'localhost' IDENTIFIED BY 'Iron4Soft2024!';
GRANT ALL PRIVILEGES ON iron4software.* TO 'iron4_admin'@'localhost';
CREATE USER IF NOT EXISTS 'iron4_admin'@'%' IDENTIFIED BY 'Iron4Soft2024!';
GRANT ALL PRIVILEGES ON iron4software.* TO 'iron4_admin'@'%';
FLUSH PRIVILEGES;

-- Table des employés (mots de passe en clair - vulnérable)
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('admin', 'manager', 'developer', 'analyst') NOT NULL,
    department ENUM('IT', 'Development', 'Security', 'Management') NOT NULL,
    salary DECIMAL(10,2),
    hire_date DATE NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Employés de test
INSERT INTO employees (username, password, email, first_name, last_name, role, department, salary, hire_date) VALUES
('admin', 'admin123', 'admin@iron4software.fr', 'Pierre', 'Dubois', 'admin', 'IT', 65000.00, '2024-01-15'),
('jdupont', 'password', 'j.dupont@iron4software.fr', 'Jean', 'Dupont', 'developer', 'Development', 45000.00, '2024-02-01'),
('mmartin', 'test123', 'm.martin@iron4software.fr', 'Marie', 'Martin', 'manager', 'Management', 55000.00, '2024-01-20'),
('sgarcia', 'iron4', 's.garcia@iron4software.fr', 'Sophie', 'Garcia', 'analyst', 'Security', 50000.00, '2024-03-01'),
('lbernard', 'secure123', 'l.bernard@iron4software.fr', 'Louis', 'Bernard', 'developer', 'Development', 42000.00, '2024-03-15');

-- Table des clients
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    company VARCHAR(100),
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    industry VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Clients de la région
INSERT INTO clients (name, company, email, phone, industry) VALUES
('Marc Rousseau', 'Vignobles Rousseau', 'contact@vignobles-rousseau.fr', '0467123456', 'Viticulture'),
('Claire Fabre', 'Hôtel du Languedoc', 'direction@hotel-languedoc.fr', '0467123457', 'Hôtellerie'),
('David Blanc', 'Tech Montpellier', 'david@tech-montpellier.com', '0467123458', 'Technologie'),
('Isabelle Roux', 'Cabinet Roux', 'contact@cabinet-roux.fr', '0467123459', 'Juridique'),
('Thomas Leroy', 'Boulangerie Leroy', 'thomas@boulangerie-leroy.fr', '0467123460', 'Alimentaire');

-- Table des projets
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    client VARCHAR(100) NOT NULL,
    type ENUM('web', 'mobile', 'consulting', 'security') NOT NULL,
    status ENUM('en-cours', 'termine', 'attente') DEFAULT 'en-cours',
    budget DECIMAL(10,2),
    start_date DATE NOT NULL,
    deadline DATE NOT NULL,
    assigned_to INT,
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES employees(id)
);

-- Projets réalistes
INSERT INTO projects (name, description, client, type, status, budget, start_date, deadline, assigned_to) VALUES
('Site Web Vignobles', 'Site vitrine avec boutique', 'Vignobles Rousseau', 'web', 'en-cours', 15000.00, '2024-06-01', '2024-08-15', 2),
('App Mobile Hôtel', 'App de réservation', 'Hôtel du Languedoc', 'mobile', 'en-cours', 25000.00, '2024-05-15', '2024-09-30', 2),
('Audit Sécurité', 'Pentest complet', 'Tech Montpellier', 'security', 'termine', 8000.00, '2024-04-01', '2024-05-30', 4),
('Système CRM', 'CRM sur mesure', 'Cabinet Roux', 'web', 'attente', 35000.00, '2024-07-01', '2024-12-15', 3),
('Site E-commerce', 'Boutique en ligne', 'Boulangerie Leroy', 'web', 'en-cours', 12000.00, '2024-06-15', '2024-08-30', 5);

-- Configuration MySQL vulnérable
SET sql_mode = '';
SET GLOBAL local_infile = 1;

SELECT 'Iron4Software Database Created!' as message;
SELECT CONCAT('Employés: ', COUNT(*)) as count FROM employees;
SELECT CONCAT('Clients: ', COUNT(*)) as count FROM clients;
SELECT CONCAT('Projets: ', COUNT(*)) as count FROM projects;

SELECT 'Test Accounts:' as info;
SELECT username, password, role FROM employees WHERE status = 'active';
