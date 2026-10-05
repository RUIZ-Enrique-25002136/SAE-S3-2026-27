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
──────
### 2. src/Core/Response.php

<?php

    namespace App\Core;

    /**
     * Représente la réponse HTTP envoyée au client par un contrôleur.
     */
    class Response
    {
        /**
         * @param string                $content    Corps de la réponse (ex. HTML)
         * @param int                   $statusCode Code de statut HTTP (200, 302, 404...)
         * @param array<string, string> $headers    En-têtes HTTP (ex. Location)
         */
        public function __construct(
            private string $content = '',
            private int $statusCode = 200,
            private array $headers = []
        ) {}

        /**
         * Raccourci pour créer une redirection HTTP.
         */
        public static function redirect(string $url, int $statusCode = 302): self
        {
            return new self('', $statusCode, ['Location' => $url]);
        }

        public function getContent(): string
        {
            return $this->content;
        }

        public function getStatusCode(): int
        {
            return $this->statusCode;
        }

        /**
         * @return array<string, string>
         */
        public function getHeaders(): array
        {
            return $this->headers;
        }

        /**
         * Envoie les en-têtes HTTP et le corps au navigateur.
         */
        public function send(): void
        {
            http_response_code($this->statusCode);

            foreach ($this->headers as $name => $value) {
                header("$name: $value");
            }

            echo $this->content;
        }
    }