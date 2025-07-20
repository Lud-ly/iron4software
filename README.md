# Iron4Software - Application Vulnérable

Application web de la société Iron4Software SARL  avec vulnérabilités intentionnelles pour tests de pénétration.

## Pentesters
- Ludo
- Damien  
- Emilio

## Installation

git clone https://github.com/Lud-ly/iron4software.git
cd iron4software
sudo cp -r . /var/www/html/iron4software/
sudo chown -R www-data:www-data /var/www/html/iron4software/
sudo mkdir -p /var/www/html/iron4software/{uploads,logs}
sudo chmod 777 /var/www/html/iron4software/{uploads,logs}
mysql -u root -p < setup.sql
sudo systemctl restart apache2

## Accès
- URL: http://localhost/iron4software/
- Comptes: admin/admin123, jdupont/password, mmartin/test123, sgarcia/iron4

## Vulnérabilités
- SQL Injection (login, recherche, rapports)
- CVE-2024-1874 (proc_open command injection)  
- CVE-2022-31630 (GD font loading)
- CVE-2022-37454 (Keccak buffer overflow)
- File upload non sécurisé
- Mots de passe en clair
- Configuration PHP vulnérable

## ⚠️ Avertissement
Application VOLONTAIREMENT vulnérable pour tests de sécurité uniquement.

