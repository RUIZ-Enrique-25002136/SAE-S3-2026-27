<?php
namespace App\Models;
/**
 * Utilisateur du site, tel qu'enregistré dans la table `users`.
 */
final class User
{
/**
 * @param int    $id           Identifiant en base
 * @param string $email        Adresse email, unique, qui sert d'identifiant de connexion
 * @param string $passwordHash Mot de passe haché avec password_hash()
 * @param bool   $verified     true si l'adresse email a été confirmée par le lien reçu
 */
public function __construct(
public readonly int $id,
public readonly string $email,
public readonly string $passwordHash,
public readonly bool $verified,
) {
}

/**
 * Vérifie un mot de passe saisi par rapport au hash enregistré.
 *
 * @param string $password Mot de passe en clair saisi dans le formulaire
 * @return bool true si le mot de passe est correct
 */
public function verifyPassword(string $password): bool
{
return password_verify($password, $this->passwordHash);
}
}
