# SAE-S3-2026-27


## Configuration locale et utilisation de la base de données

### 1. Variables d'environnement (`.env`)
Copier `.env.example` en `.env` à la racine du projet (ce fichier est ignoré par Git), puis renseigner ses valeurs :

```bash
cp .env.example .env
```

`DB_HOST`, `DB_NAME`, `DB_USER` et `DB_PASS` sont obligatoires, `DB_PORT` vaut 3306 par défaut. Mettre `DB_PASS` entre guillemets s'il contient des caractères spéciaux (`;`, `#`, `!`…).

> **Important :** Sur Alwaysdata, le `.env` de production est déjà configuré. Ne touchez pas à la configuration distante.

---

### 2. Initialiser votre base de données locale
1. Démarrez votre serveur MySQL / MariaDB local.
2. Initialisez la base de données :
   - **Automatiquement via le script PHP fourni** : `php sql/init_db.php` (crée la base `sae_s3`, importe le schéma et vérifie tout).
   - Ou manuellement en ligne de commande : `mysql -u root -p sae_s3 < sql/schema.sql`
   - Ou via phpMyAdmin local : onglet **Importer** > choisir `sql/schema.sql`.

---

### 3. Utiliser la connexion dans le code PHP
Pour exécuter vos requêtes SQL, incluez `includes/database.php` et appelez `getConnection()`. Cette fonction lit le `.env` et renvoie une instance `PDO` :

```php
require_once __DIR__ . '/includes/database.php';

$pdo = getConnection();

// Exemple de requête préparée sécurisée :
$stmt = $pdo->prepare('SELECT * FROM users WHERE login = :login');
$stmt->execute(['login' => $login]);
$user = $stmt->fetch();
