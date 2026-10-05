<?php

namespace App\Core;

/**
 * Représente la requête HTTP reçue par l'application.
 */
class Request
{
    /**
     * @param array<string, mixed> $get    Données de l'URL ($_GET)
     * @param array<string, mixed> $post   Données du formulaire ($_POST)
     * @param array<string, mixed> $server Données de l'environnement ($_SERVER)
     */
    public function __construct(
        private array $get = [],
        private array $post = [],
        private array $server = []
    ) {}

    /**
     * Crée une requête à partir des superglobales PHP actuelles.
     */
    public static function createFromGlobals(): self
    {
        return new self($_GET, $_POST, $_SERVER);
    }

    /**
     * Renvoie la méthode HTTP (ex. GET, POST).
     */
    public function getMethod(): string
    {
        $method = $this->server['REQUEST_METHOD'] ?? 'GET';
        return is_string($method) ? strtoupper($method) : 'GET';
    }

    /**
     * Indique si la méthode est POST.
     */
    public function isPost(): bool
    {
        return $this->getMethod() === 'POST';
    }

    /**
     * Renvoie le chemin demandé dans l'URL (ex. /login), sans paramètres ni slash final.
     */
    public function getPath(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $path = parse_url(is_string($uri) ? $uri : '/', PHP_URL_PATH);
        return rtrim(is_string($path) ? $path : '/', '/') ?: '/';
    }

    /**
     * Récupère une valeur envoyée en POST ou GET, ou la valeur par défaut.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    /**
     * @return array<string, mixed>
     */
    public function getPost(): array
    {
        return $this->post;
    }

    /**
     * @return array<string, mixed>
     */
    public function getQuery(): array
    {
        return $this->get;
    }
}