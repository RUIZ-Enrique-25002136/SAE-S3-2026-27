<?php
final class UserRepository
{
    public function __construct(private readonly PDO $pdo) {}

    public function findByEmail(string $email): ?User  {
        $query = $this->pdo->prepare('SELECT * FROM `users` WHERE `email` = :email LIMIT 1');
        if (!$query->execute(['email' => $email])) {
            return null;
        }
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return new User($row[0], $row[1], $row[2]);
    }  // null si absent
    public function emailExists(string $email): bool {
        $res = $this->pdo->prepare('SELECT * FROM `users` WHERE `email` = :email');
        if (!$res) {
            return false;
        }
        return true;
    }
    public  function create(string $email, string $motDePasseClair): User {
        $id = $this->pdo->lastInsertId();
        $passwordHash = password_hash($motDePasseClair, PASSWORD_DEFAULT);
        return new User($id, $email, $passwordHash);
    }

    private function hydrater(array $ligne): User {
        return new User($ligne['id'], $ligne['email'], $ligne['password']);
    }     // une ligne SQL -> un objet, en un seul endroit
}