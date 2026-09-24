# SAE-S3-2026-27


## Configuration locale et utilisation de la base de données

### 1. Variables d'environnement (`.env`)
Créer un fichier `.env` à la racine du projet (ce fichier est ignoré par Git) :

DB_HOST=localhost
DB_NAME=sae_s3
DB_USER=root
DB_PASS=votre_mot_de_passe_local

> **Important :** Sur Alwaysdata, le `.env` de production est déjà configuré. Ne touchez pas à la configuration distante.

---

### 2. Initialiser votre base de données locale
1. Démarrez votre serveur MySQL / MariaDB local.
2. Créez une base vide (ex. `sae_s3`).
3. Importez le fichier `sql/schema.sql` dans cette base :
   - En ligne de commande : `mysql -u root -p sae_s3 < sql/schema.sql`
   - Ou via phpMyAdmin local : onglet **Importer** > choisir `sql/schema.sql`.

---

### 3. Utiliser la connexion dans le code PHP
Pour exécuter vos requêtes SQL, incluez `includes/db.php` et appelez `connexion()`. Cette fonction lit le `.env` et renvoie une instance `PDO` :

```php
require_once __DIR__ . '/includes/db.php';

$pdo = connexion();

// Exemple de requête préparée sécurisée :
$stmt = $pdo->prepare('SELECT * FROM users WHERE login = :login');
$stmt->execute(['login' => $login]);
$user = $stmt->fetch();
