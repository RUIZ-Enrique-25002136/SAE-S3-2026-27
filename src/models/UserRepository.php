<?php
namespace App\Model;
final class UserRepository
{
    public function __construct(private readonly PDO $pdo) {}

    public function findByEmail(string $email): ?User  {
        $query = $this->pdo->prepare('SELECT * FROM `users` WHERE `email` = :email LIMIT 1');
        $query->execute(['email' => $email]);
        $row = $query->fetch(PDO::FETCH_ASSOC);
        if (!$row){
            return null;
        }
        return $this->hydrater($row);
    }  // null si absent
    public function emailExists(string $email): bool {
        $query = $this->pdo->prepare('SELECT 1 FROM `users` WHERE `email` = :email LIMIT 1');
        $query->execute(['email' => $email]);
        return $query->fetchColumn() !== false;
    }
    public  function create(string $email, string $motDePasseClair): User {
        $passwordHash = password_hash($motDePasseClair, PASSWORD_DEFAULT);
        $query = $this->pdo->prepare('INSERT INTO `users` (`email`, `password`) VALUES (:email, :password)');
        $query->execute(['email' => $email, 'password' => $passwordHash]);
        return new User((int) $this->pdo->lastInsertId(), $email, $passwordHash);
    }

    private function hydrater(array $ligne): User {
        return new User($ligne['id'], $ligne['email'], $ligne['password']);
    }     // une ligne SQL -> un objet, en un seul endroit
}