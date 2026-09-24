<?php
final class User
{
public function __construct(
public readonly int $id,
public readonly string $email,
public readonly string $passwordHash,
) {
}

public function verifierMotDePasse(string $motDePasse): bool
{
return password_verify($motDePasse, $this->passwordHash);
}
}
