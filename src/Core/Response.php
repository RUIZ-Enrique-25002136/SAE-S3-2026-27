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
        public function withHeader(string $name, string $value): self
        {
            $clone = clone $this;
            $clone->headers[$name] = $value;

            return $clone;
        }
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