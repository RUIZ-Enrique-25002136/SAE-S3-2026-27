# SAE-S3-2026-27

Site PHP en architecture MVC (sans framework ni Composer), réalisé dans le cadre de la SAÉ S3 du BUT Informatique (IUT d'Aix-Marseille).

- **Site en ligne** : https://beghin.alwaysdata.net
- **Maquette Figma** : https://www.figma.com/site/UInhXhIDhdZQ0ArzY0gfpc/Sans-titre?node-id=12-533&t=pdyE0wRAgghEqfpx-0
- **Équipe** : Hugo BANTI, Timothée BEGHIN, Vladislav DUMONT, Leny ROSSINES, Enrique RUIZ

## Architecture

```
public/              seul dossier servi par le serveur web
├── index.php        point d'entrée unique : charge le code, puis le routeur appelle le bon contrôleur
├── .htaccess        envoie toutes les URL vers index.php (sauf les vrais fichiers)
├── css/, assets/
config/routes.php    table des routes : [méthode HTTP, URL, [contrôleur, méthode], titre, visible dans le plan du site]
src/
├── Controller/      HomeController, AuthController, PasswordController
├── Models/          User, UserRepository (toutes les requêtes SQL)
└── views/           l'affichage uniquement (aucune logique ni SQL)
includes/            env(), getConnection(), render(), jeton CSRF
autoload.php         chargement automatique des classes App\... depuis src/
sql/schema.sql       structure de la base
tests/               tests PHPUnit
```

Une requête suit toujours le même chemin : `public/index.php` → routeur → méthode du contrôleur → modèle (base de données) → vue.

## Lancer le projet en local

1. **Configuration** : copier `.env.example` en `.env` à la racine (ce fichier est ignoré par Git), puis renseigner ses valeurs.
   ```bash
   cp .env.example .env
   ```
   `DB_HOST`, `DB_NAME`, `DB_USER` et `DB_PASS` sont obligatoires, `DB_PORT` vaut 3306 par défaut. Mettre `DB_PASS` entre guillemets s'il contient des caractères spéciaux (`;`, `#`, `!`…). `SITE_URL` sert à construire les liens envoyés par mail.

2. **Base de données** : démarrer MySQL / MariaDB, puis importer le schéma, au choix :
   - `php sql/init_db.php` (crée la base, importe le schéma et vérifie tout) ;
   - `mysql -u root -p sae_s3 < sql/schema.sql` ;
   - phpMyAdmin : onglet **Importer**, fichier `sql/schema.sql`.

3. **Serveur** :
   ```bash
   php -S localhost:8000 -t public public/index.php
   ```
   puis ouvrir http://localhost:8000. L'extension `pdo_mysql` doit être activée.

## Qualité et intégration continue

À chaque pull request, GitHub Actions lance :

- `php -l` sur tous les fichiers ;
- **PHPStan** (niveau 5) ;
- les **tests PHPUnit** (`tests/`) ;
- l'import du schéma SQL sur MariaDB 11.4.

Quand tout passe sur `main`, le site est déployé automatiquement sur Alwaysdata. En local : `phpstan analyse` et `phpunit`.

## Sécurité

Requêtes préparées partout, mots de passe hachés (`password_hash` / `password_verify`), jeton CSRF sur tous les formulaires, nouvel identifiant de session à la connexion, comptes non vérifiés refusés, affichage échappé (`htmlspecialchars`), et code privé (`.env`, `src/`, `sql/`…) placé hors du dossier servi par le web.

> **Important :** sur Alwaysdata, le `.env` de production est déjà configuré. Ne pas toucher à la configuration distante.
