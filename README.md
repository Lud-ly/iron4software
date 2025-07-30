# Iron4Software - Application Vulnérable

Application web de la société Iron4Software SARL  avec vulnérabilités intentionnelles pour tests de pénétration.

## Pentesters
- Ludo
- Damien  
- Emilio

## Installation

- git clone https://github.com/Lud-ly/iron4software.git
- cd iron4software
- sudo cp -r . /var/www/html/iron4software/
- sudo chown -R www-data:www-data /var/www/html/iron4software/
- sudo mkdir -p /var/www/html/iron4software/{uploads,logs}
- sudo chmod 777 /var/www/html/iron4software/{uploads,logs}
- mysql -u root -p < setup.sql
- sudo systemctl restart apache2

## Accès
- URL: http://127.0.0.1
- Comptes: admin/admin123, jdupont/password, mmartin/test123, sgarcia/iron4

## Vulnérabilités
- File upload non sécurisé
- Mots de passe en clair
- Configuration PHP vulnérable

## ⚠️ après deploy
- chmod 755 /var/www/html/iron4software/logs
- sudo mkdir -p /var/www/html/uploads
- sudo chown www-data:www-data /var/www/html/uploads
- sudo chmod 755 /var/www/html/uploads

# Site web Iron4software

![tools](assets/images/i1.png)
![tools](assets/images/i2.png)
![tools](assets/images/i3.png)
![tools](assets/images/i4.png)
![tools](assets/images/i5.png)
![tools](assets/images/i6.png)


## FAILLES

## 1. Fichier reports.php — inclusion de fichier et formulaire de rapport
   
   a) Vulnérabilité LFI (Local File Inclusion) - Inclusion non filtrée

``` php
   if (isset($_GET['include'])) {
    $file = $_GET['include'];
    echo "<p><strong>Inclusion du fichier :</strong> " . htmlspecialchars($file) . "</p>";
    echo "<div class='include-output'>";
    include $file; // << Inclusion directe sans validation
    echo "</div>";
    }
```

Il est possible de passer n'importe quel chemin dans ?include=, par exemple /etc/passwd ou tout fichier accessible par PHP même à l’extérieur du projet. Il est aussi possible de jouer avec les filtres PHP 


b) Injection SQL possible via generateReport($type)

``` php
    if ($_GET && isset($_GET['type'])) {
    $type = $_GET['type'];
    $reportData = generateReport($type);
    }
```
Si generateReport ne prépare pas ses requêtes (et utilise directement $type interpolé dans SQL), il y aura une injection SQL possible via le paramètre type.


## 2. Fichier admin.php — Recherche employés
   
   a) Injection SQL classique dans le paramètre search

```php

   if ($_GET && isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM employees WHERE username LIKE '%$search%' OR department LIKE '%$search%'";
    $searchResults = $db->query($sql);
    }
```

Injection directe du paramètre utilisateur $search dans la requête sans préparation ni échappement.
Un attaquant peut provoquer une injection SQL via la variable search. Fuite de données, modification ou suppression de données, voire prise de contrôle via injection.

b) Affichage non sécurisé de la recherche

Affichage protégé par htmlspecialchars(), aucune faille XSS dans ce code.

## 3. Fichier tools.php — Affichages et requêtes

Permet exploitation d’une CVE connue via simple upload.

Aucune validation côté serveur → tous les classiques des failles upload sont présents.

   ![tools](assets/images/upload_failles.png)
