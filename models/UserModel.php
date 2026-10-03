<?php

class UserModel {
    public function __construct(private PDO $pdo) {}

    public function setToken(string $email, string $token): bool {
        $stmt = $this->pdo->prepare("UPDATE utilisateur SET verify_token = ? WHERE email = ?");
        $stmt->execute([$token, $email]);
        return $stmt->rowCount() > 0;
    }

    public function tokenExists(string $token): bool {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE verify_token = ?");
        $stmt->execute([$token]);
        return $stmt->rowCount() > 0;
    }

    public function resetPassword(string $token, string $password): bool {
        $stmt = $this->pdo->prepare("UPDATE users SET password = ?, verify_token = NULL WHERE verify_token = ?");
        $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $token]);
        return $stmt->rowCount() > 0;
    }
}