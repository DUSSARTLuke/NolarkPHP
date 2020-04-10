<?php

// Autochargement des dépendances
require_once '../vendor/autoload.php';

try {
    // Requête SQL
    $cnx = new PDO('mysql:host=127.0.0.1;dbname=nolark', 'nolarkuser', 'nolarkpwd');
    $req = 'SELECT * FROM casque INNER JOIN type ON casque.type=type.id';
    $req .= ' INNER JOIN marque ON casque.marque=marque.id';
    $req .= ' WHERE libelle="' . substr($pageActuelle, 0, -4) . '"';

    $res = $cnx->prepare($req);
    $res->execute();
    $res->fetch(PDO::FETCH_OBJ);

    // Fermeture connexion
    unset($cnx);

    // Définition du répertoire vers les templates
    $loader = new Twig\Loader\FilesystemLoader('../tpl');

    // Initialisation de l'environnement Twig
    $twig = new Twig\Environment($loader, array(
        'cache' => '../cache'
    ));

    // Chargemement du template
    $template = $twig->load('casques.twig');

    // Affectation des variables du template
    echo $template->render(array(
        'casques' => $res
    ));
} catch (PDOException $e) {
    echo 'Erreur: ' . $e->getMessage();
}