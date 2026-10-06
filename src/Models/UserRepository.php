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
        return new User($row['id'], $row['email'], $row['password'], (bool) $row['verified']);
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