<?php
/*
  Neoterranos & LkY
  Page fonctions.php

  Contient quelques fonctions globales.

  Quelques indications : (utiliser l'outil de recherche et rechercher les mentions donnÃ©es)

  Liste des fonctions :
  --------------------------
  sqlquery($requete,$number)
  connexionbdd()
  actualiser_session()
  vider_cookie()
  --------------------------


  Liste des informations/erreurs :
  --------------------------
  Mot de passe de session incorrect
  Mot de passe de cookie incorrect
  L'id de cookie est incorrect
  --------------------------
 */

function sqlquery($requete, $number) {
    $query = mysql_query($requete) or exit('Erreur SQL : ' . mysql_error() . ' Ligne : ' . __LINE__ . '.'); //requête
    queries();

    /*
      Deux cas possibles ici :
      Soit on sait qu'on a qu'une seule entrée qui sera
      retournée par SQL, donc on met $number à 1
      Soit on ne sait pas combien seront retournées,
      on met alors $number à 2.
     */

    if ($number == 1) {
        $query1 = mysql_fetch_assoc($query);
        mysql_free_result($query);
        /* mysql_free_result($query) libère le contenu de $query, je
          le fais par principe, mais c'est pas indispensable. */
        return $query1;
    } else if ($number == 2) {
        while ($query1 = mysql_fetch_assoc($query)) {
            $query2[] = $query1;
            /* On met $query1 qui est un array dans $query2 qui
              est un array. Ca fait un array d'arrays :o */
        }
        mysql_free_result($query);
        return $query2;
    } else { //Erreur
        exit('Argument de sqlquery non renseigné ou incorrect.');
    }
}

function queries($num = 1) {
    global $queries;
    $queries = $queries + intval($num);
}

function connexionbdd() {
    //Définition des variables de connexion à la base de données
    $bd_nom_serveur = 'localhost';
    $bd_login = 'root';
    $bd_mot_de_passe = '';
    $bd_nom_bd = 'nolark';

    //Connexion à la base de données
    mysql_connect($bd_nom_serveur, $bd_login, $bd_mot_de_passe);
    mysql_select_db($bd_nom_bd);
    mysql_query("set names 'utf8'");
}

function actualiser_session() {
    if (isset($_SESSION['membre_id']) && intval($_SESSION['membre_id']) != 0) { //Vérification id
        //utilisation de la fonction sqlquery, on sait qu'on aura qu'un résultat car l'id d'un membre est unique.
        $retour = sqlquery("SELECT id, login, password FROM membres WHERE id = " . intval($_SESSION['id']), 1);

        //Si la requête a un résultat (c'est-à-dire si l'id existe dans la table membres)
        if (isset($retour['login']) && $retour['login'] != '') {
            if ($_SESSION['password'] != $retour['password']) {
                //Dehors vilain pas beau !
                $informations = Array(/* Mot de passe de session incorrect */
                    true,
                    'Session invalide',
                    'Le mot de passe de votre session est incorrect, vous devez vous reconnecter.',
                    '',
                    'membres/membres.php',
                    3
                );
                require_once('../membres.php');
                vider_cookie();
                session_destroy();
                exit();
            } else {
                //Validation de la session.
                $_SESSION['id'] = $retour['id'];
                $_SESSION['login'] = $retour['login'];
                $_SESSION['password'] = $retour['password'];
            }
        }
    } else { //On vérifie les cookies et sinon pas de session
        if (isset($_COOKIE['id']) && isset($_COOKIE['password'])) { //S'il en manque un, pas de session.
            if (intval($_COOKIE['id']) != 0) {
                //idem qu'avec les $_SESSION
                $retour = sqlquery("SELECT id, login, password FROM membres WHERE id = " . intval($_COOKIE['id']), 1);

                if (isset($retour['login']) && $retour['login'] != '') {
                    if ($_COOKIE['password'] != $retour['password']) {
                        //Dehors vilain tout moche !
                        $informations = Array(/* Mot de passe de cookie incorrect */
                            true,
                            'Mot de passe cookie erroné',
                            'Le mot de passe conservé sur votre cookie est incorrect vous devez vous reconnecter.',
                            '',
                            'membres/membres.php',
                            3
                        );
                        require_once('../membres.php');
                        vider_cookie();
                        session_destroy();
                        exit();
                    } else {
                        //Bienvenue :D
                        $_SESSION['id'] = $retour['id'];
                        $_SESSION['login'] = $retour['login'];
                        $_SESSION['password'] = $retour['password'];
                    }
                }
            } else { //cookie invalide, erreur plus suppression des cookies.
                $informations = Array(/* L'id de cookie est incorrect */
                    true,
                    'Cookie invalide',
                    'Le cookie conservant votre id est corrompu, il va donc être détruit vous devez vous reconnecter.',
                    '',
                    'membres/membres.php',
                    3
                );
                require_once('../membres.php');
                vider_cookie();
                session_destroy();
                exit();
            }
        } else {
            //Fonction de suppression de toutes les variables de cookie.
            if (isset($_SESSION['id']))
                unset($_SESSION['id']);
            vider_cookie();
        }
    }
}

function vider_cookie() {
    foreach ($_COOKIE as $cle => $element) {
        setcookie($cle, '', time() - 3600);
    }
}
