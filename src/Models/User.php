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
 * @param string|null $login     Login affiché aux autres membres (null pour les anciens comptes)
 * @param string|null $createdAt Date d'inscription (format SQL DATETIME)
 */
public function __construct(
public readonly int $id,
public readonly string $email,
public readonly string $passwordHash,
public readonly bool $verified,
public readonly ?string $login = null,
public readonly ?string $createdAt = null,
) {
}

/**
 * Indique si un login respecte le format : 3 à 30 caractères, lettres, chiffres, - ou _.
 *
 * @param string $login Login saisi dans un formulaire
 * @return bool true si le format est valide
 */
public static function isValidLogin(string $login): bool
{
return preg_match('/^[A-Za-z0-9_-]{3,30}$/', $login) === 1;
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
