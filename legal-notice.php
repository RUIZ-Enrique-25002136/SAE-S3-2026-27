<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

$titre = 'Mentions légales';
buildHeader($titre);
?>

<div class="card mentions-card">
    <h1>Mentions légales</h1>

    <section class="mentions-section">
        <h2>1. Présentation du site et cadre pédagogique</h2>
        <p>Le présent site web est développé dans le cadre de la Situation d'Apprentissage et d'Évaluation (<strong>SAÉ S3</strong>) de 2<sup>e</sup> année du Bachelor Universitaire de Technologie (<strong>BUT</strong>) en Informatique à l'IUT d'Aix-Marseille (Aix-Marseille Université).</p>
        <p>Ce projet universitaire a pour objectif la conception, le développement et le déploiement d'une application web interactive côté serveur avec persistance des données.</p>
    </section>

    <section class="mentions-section">
        <h2>2. Éditeur et équipe de développement</h2>
        <p><strong>Projet :</strong> SAÉ S3 — Développement Web côté serveur</p>
        <p><strong>Équipe de réalisation :</strong> Hugo BANTI, Timothée BEGHIN, Vladislav DUMONT, Leny ROSSINES, Enrique RUIZ</p>
        <p><strong>Directeur de la publication :</strong> Enrique RUIZ (représentant de l'équipe étudiante SAÉ S3)</p>
        <p><strong>Établissement d'enseignement :</strong> IUT d'Aix-Marseille &bull; Département Informatique (Aix-Marseille Université)</p>
        <p><strong>Statut :</strong> Projet d'études universitaires sans finalité commerciale</p>
    </section>

    <section class="mentions-section">
        <h2>3. Hébergement</h2>
        <p>Le site est hébergé par la société <strong>ALWAYSDATA</strong> :</p>
        <p>
            <strong>ALWAYSDATA</strong>, SARL au capital de 200 000 €, RCS Paris 492 893 490<br>
            Siège social : 91 rue du Faubourg Saint-Honoré, 75008 Paris, France<br>
            Site web : <a href="https://www.alwaysdata.com" target="_blank" rel="noopener noreferrer">www.alwaysdata.com</a>
        </p>
    </section>

    <section class="mentions-section">
        <h2>4. Propriété intellectuelle & crédits</h2>
        <p>L'ensemble des contenus, codes sources, architectures et documentations élaborés dans le cadre de ce projet sont la propriété intellectuelle de leurs auteurs respectifs, conformément aux réglementations académiques applicables.</p>
        <p><strong>Ressources tierces et attributions :</strong></p>
        <ul>
            <li>Icônes : créations fournies par <a href="https://icons8.com" target="_blank" rel="noopener noreferrer">Icons8</a>, utilisées avec attribution conformément à leurs conditions d'utilisation.</li>
            <li>Typographies : polices système multiplateformes (pile sans-serif système standard).</li>
        </ul>
    </section>

    <section class="mentions-section">
        <h2>5. Données personnelles et confidentialité (RGPD)</h2>
        <p>Conformément au Règlement Général sur la Protection des Données (RGPD 2016/679) et à la loi « Informatique et Libertés » modifiée :</p>
        <ul>
            <li><strong>Données collectées :</strong> Les adresses e-mail ainsi que les mots de passe nécessaires à la création, à l'accès et à la gestion du compte utilisateur.</li>
            <li><strong>Finalité du traitement :</strong> Gestion exclusive de l'authentification et de l'accès aux fonctionnalités du site réservées aux membres.</li>
            <li><strong>Durée de conservation :</strong> Les données sont conservées strictement pendant la période d'évaluation et de soutenance pédagogique du projet. Aucune donnée n'est cédée, vendue ou transmise à des tiers.</li>
            <li><strong>Exercice de vos droits :</strong> Conformément à la réglementation, vous bénéficiez d'un droit d'accès, de rectification et d'effacement de vos données personnelles sur simple demande auprès des administrateurs du site.</li>
        </ul>
    </section>

    <section class="mentions-section">
        <h2>6. Cookies et sessions techniques</h2>
        <p>Ce site utilise uniquement des cookies techniques de session (PHP), indispensables au maintien de la connexion utilisateur et au bon fonctionnement de la navigation. Aucun cookie publicitaire, d'analyse d'audience ou traceur tiers n'est utilisé.</p>
    </section>

    <section class="mentions-section">
        <h2>7. Avertissement & limitation de responsabilité</h2>
        <p>Ce site est une réalisation étudiante à vocation strictement académique et expérimentale. L'équipe décline toute responsabilité en cas de dysfonctionnements temporaires, bogues ou interruptions de service liés à l'environnement d'hébergement ou de développement.</p>
    </section>

    <div class="card-actions">
        <a href="index.php" class="btn btn-secondary">Retour à l'accueil</a>
    </div>
</div>

<?php
buildFooter();
?>
