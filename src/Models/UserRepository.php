<?php
namespace App\Models;
use PDO;
use PDOException;

/**
 * Accès à la table `users` : toutes les requêtes SQL sur les utilisateurs passent par cette classe.
 */
final class UserRepository
{
    /**
     * @param PDO $pdo Connexion à la base, obtenue avec connection()
     */
    public function __construct(private readonly PDO $pdo) {}

    /**
     * Cherche un utilisateur par son adresse email.
     *
     * @param string $email Adresse email recherchée
     * @return User|null L'utilisateur trouvé, ou null s'il n'existe pas
     */
    public function findByEmail(string $email): ?User  {
        $query = $this->pdo->prepare('SELECT * FROM `users` WHERE `email` = :email LIMIT 1');
        $query->execute(['email' => $email]);
        $row = $query->fetch(PDO::FETCH_ASSOC);
        if (!$row){
            return null;
        }
        return $this->hydrate($row);
    }  // null si absent

    /**
     * Cherche un utilisateur par son identifiant.
     *
     * @param int $id Identifiant du compte
     * @return User|null L'utilisateur trouvé, ou null s'il n'existe pas
     */
    public function findById(int $id): ?User
    {
        $query = $this->pdo->prepare('SELECT * FROM `users` WHERE `id` = :id LIMIT 1');
        $query->execute(['id' => $id]);
        $row = $query->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return $this->hydrate($row);
    }

    /**
     * Renvoie une page de la liste des membres vérifiés, du plus récent au plus ancien.
     *
     * @param int $limit  Nombre de membres par page
     * @param int $offset Nombre de membres à sauter (pages précédentes)
     * @return User[]
     */
    public function findPage(int $limit, int $offset): array
    {
        $query = $this->pdo->prepare('SELECT * FROM `users` WHERE `verified` = TRUE ORDER BY `created_at` DESC, `id` DESC LIMIT :limit OFFSET :offset');
        $query->bindValue('limit', $limit, PDO::PARAM_INT);
        $query->bindValue('offset', $offset, PDO::PARAM_INT);
        $query->execute();

        return array_map(fn(array $row) => $this->hydrate($row), $query->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Compte les membres vérifiés, pour calculer le nombre de pages.
     *
     * @return int Nombre de membres vérifiés
     */
    public function countMembers(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM `users` WHERE `verified` = TRUE')->fetchColumn();
    }

    /**
     * Remplace le login d'un compte.
     *
     * @param int    $id    Identifiant du compte
     * @param string $login Nouveau login, déjà validé
     * @return bool true si le login a été enregistré
     */
    public function updateLogin(int $id, string $login): bool
    {
        $query = $this->pdo->prepare('UPDATE `users` SET `login` = :login WHERE `id` = :id');
        try {
            return $query->execute(['login' => $login, 'id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime définitivement un compte.
     *
     * @param int $id Identifiant du compte
     * @return bool true si un compte a été supprimé
     */
    public function delete(int $id): bool
    {
        $query = $this->pdo->prepare('DELETE FROM `users` WHERE `id` = :id');
        $query->execute(['id' => $id]);
        return $query->rowCount() > 0;
    }
    /**
     * Indique si une adresse email est déjà utilisée par un compte.
     *
     * @param string $email Adresse email à tester
     * @return bool true si un compte utilise déjà cette adresse
     */
    public function emailExists(string $email): bool {
        $query = $this->pdo->prepare('SELECT 1 FROM `users` WHERE `email` = :email LIMIT 1');
        $query->execute(['email' => $email]);
        return $query->fetchColumn() !== false;
    }
    /**
     * Indique si un login est déjà utilisé par un compte.
     *
     * @param string $login Login à tester
     * @return bool true si un compte utilise déjà ce login
     */
    public function loginExists(string $login): bool {
        $query = $this->pdo->prepare('SELECT 1 FROM `users` WHERE `login` = :login LIMIT 1');
        $query->execute(['login' => $login]);
        return $query->fetchColumn() !== false;
    }
    /**
     * Crée un compte non vérifié avec son jeton de vérification d'email.
     *
     * @param string $email         Adresse email du compte
     * @param string $login         Login affiché aux autres membres
     * @param string $plainPassword Mot de passe en clair, haché avant l'enregistrement
     * @param string $token         Jeton envoyé par mail pour confirmer l'adresse
     * @return bool true si le compte a été créé
     */
    public  function create(string $email, string $login, string $plainPassword,string $token): bool {
        $passwordHash = password_hash($plainPassword, PASSWORD_DEFAULT);
        $query = $this->pdo->prepare('INSERT INTO `users` (`email`, `login`, `password`,`verify_token`) VALUES (:email, :login, :password ,:token)');
        try{
           return $query->execute(['email' => $email, 'login' => $login, 'password' => $passwordHash, 'token' => $token]);
        }
        catch(PDOException $e){
            return false;
        }

    }

    /**
     * Transforme une ligne de la table `users` en objet User.
     *
     * @param array<string, mixed> $row Ligne renvoyée par PDO
     * @return User
     */
    private function hydrate(array $row): User {
        return new User($row['id'], $row['email'], $row['password'], (bool) $row['verified'], $row['login'], $row['created_at']);
    }     // une ligne SQL -> un objet, en un seul endroit

    /**
     * Enregistre un jeton de réinitialisation du mot de passe pour un compte.
     *
     * @param string $email Adresse email du compte
     * @param string $token Jeton aléatoire de 64 caractères hexadécimaux
     * @return bool true si un compte correspond à cette adresse
     */
    public function setToken(string $email, string $token): bool
    {
        $stmt = $this->pdo->prepare("UPDATE users SET verify_token = ? WHERE email = ?");
        $stmt->execute([$token, $email]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Indique si un jeton correspond à un compte.
     *
     * @param string $token Jeton reçu dans le lien
     * @return bool true si le jeton est valide
     */
    public function tokenExists(string $token): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE verify_token = ?");
        $stmt->execute([$token]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Remplace le mot de passe du compte lié au jeton, puis invalide le jeton.
     *
     * @param string $token    Jeton reçu dans le lien de réinitialisation
     * @param string $password Nouveau mot de passe en clair, haché avant l'enregistrement
     * @return bool true si le mot de passe a été changé
     */
    public function resetPassword(string $token, string $password): bool
    {
        $stmt = $this->pdo->prepare("UPDATE users SET password = ?, verify_token = NULL WHERE verify_token = ?");
        $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $token]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Marque comme vérifié le compte lié au jeton, puis invalide le jeton.
     *
     * @param string $token Jeton reçu dans le lien de confirmation
     * @return bool true si un compte a été vérifié
     */
    public function verifyEmail(string $token): bool
    {
        $stmt = $this->pdo->prepare("UPDATE users SET verified = TRUE, verify_token = NULL WHERE verify_token = ?");
        $stmt->execute([$token]);
        return $stmt->rowCount() > 0;
    }
}