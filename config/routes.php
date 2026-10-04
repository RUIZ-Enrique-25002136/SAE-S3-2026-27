<?php
return [
    '/'                => ['action' => 'home', 'title' => 'Accueil', 'sitemap' => true],
    '/login'           => ['action' => 'login', 'title' => 'Connexion', 'sitemap' => true],
    '/register'        => ['action' => 'register', 'title' => 'Inscription', 'sitemap' => true],
    '/forgot-password' => ['action' => 'forgot_password', 'title' => 'Mot de passe oublié', 'sitemap' => true],
    '/legal-notice'    => ['action' => 'legal_notice', 'title' => 'Mentions légales', 'sitemap' => true],
    '/sitemap'         => ['action' => 'sitemap', 'title' => 'Plan du site', 'sitemap' => true],
    '/logout'          => ['action' => 'logout', 'title' => 'Déconnexion', 'sitemap' => false],
    '/reset-password'  => ['action' => 'reset_password', 'title' => 'Réinitialisation du mot de passe', 'sitemap' => false],
    '/verify'          => ['action' => 'verify', 'title' => 'Vérification du compte', 'sitemap' => false],
];