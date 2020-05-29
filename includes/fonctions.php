<?php

/*
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

/**
 * Méthode qui permet de faire une requête sur la base de données
 * @param string $requete la requete que l'on veut executer
 */
function sqlquery($requete) {
    $cnx = new PDO('mysql:host=127.0.0.1;dbname=nolark', 'root', '');
    // Requête SQL
    $req = $requete;
    $res = $cnx->prepare($req);
    $res->execute();
}
/**
 * Méthode permettant d'établir une connexion à la base de données
 */
function connexionbdd() {
    //Connexion à la base de données
    $cnx = new PDO('mysql:host=127.0.0.1;dbname=nolark', 'nolarkuser', 'nolarkpwd');
}

/**
 * Méthode permettant d'effectuer l'actualisation de la session sur les pages
 * en fonction de la variable $_SESSION ou $_COOKIE
 */
function actualiser_session() {
    if (isset($_SESSION['membre_id']) && intval($_SESSION['membre_id']) != 0) { //Vérification id
        //utilisation de la fonction sqlquery, on sait qu'on aura qu'un résultat car l'id d'un membre est unique.
        $retour = sqlquery("SELECT id, login, password FROM membres WHERE id = " . intval($_SESSION['id']));

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
                $retour = sqlquery("SELECT id, login, password FROM membres WHERE id = " . intval($_COOKIE['id']));

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

/**
 * Méthode permettant de vider les cookies
 */
function vider_cookie() {
    foreach ($_COOKIE as $cle => $element) {
        setcookie($cle, '', time() - 3600);
    }
}

/**
 * Fonction permettant de retourner si le pseudo est bon ou l'erreur
 * @param string $pseudo
 * @return string réponse en fonction des règles mises en place
 */
function checkpseudo($pseudo) {
    if ($pseudo == '')
        return 'empty';
    else if (strlen($pseudo) < 3)
        return 'tooshort';
    else if (strlen($pseudo) > 32)
        return 'toolong';

    else {
        $result = sqlquery("SELECT COUNT(*) AS nbr FROM membres WHERE login = '" . $pseudo . "'");


        if ($result['nbr'] > 0)
            return 'exists';
        else
            return 'ok';
    }
}

/**
 * Fonction permettant de retourner si le mot de passe est bon ou l'erreur
 * @param string $mdp
 * @return string réponse en fonction des règles mises en place
 */
function checkmdp($mdp) {
    if ($mdp == '')
        return 'empty';
    else if (strlen($mdp) < 4)
        return 'tooshort';

    else {
        if (!preg_match('#[0-9]{1,}#', $mdp))
            return 'nofigure';
        else if (!preg_match('#[A-Z]{1,}#', $mdp))
            return 'noupcap';
        else
            return 'ok';
    }
}

/**
 * Fonction permettant de retourner si le mot de passe de confirmation
 * est le même ou l'erreur
 * @param string $mdp
 * @param string $mdp_verif
 * @return string réponse en fonction des règles mises en place
 */
function checkmdpS($mdp, $mdp_verif) {
    if ($mdp != $mdp_verif && $mdp != '' && $mdp_verif != '')
        return 'different';
    else
        return checkmdp($mdp);
}

/**
 * Fonction permettant de retourner si le mail est correct ou l'erreur
 * @param type $mail
 * @return string réponse en fonction des règles mises en place
 */
function checkmail($mail) {
    if ($mail == '')
        return 'empty';
    else if (!preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#is', $mail))
        return 'isnt';

    else {
        $result = sqlquery("SELECT COUNT(*) AS nbr FROM membres WHERE mail = '" . $mail . "'");

        if ($result['nbr'] > 0)
            return 'exists';
        else
            return 'ok';
    }
}

/**
 * Fonction permettant de retourner si le mail de confirmation
 * est le même ou l'erreur
 * @param string $mail
 * @param string $mail_verif
 * @return string réponse en fonction des règles mises en place
 */
function checkmailS($mail, $mail_verif) {
    if ($mail != $mail_verif && $mail != '' && $mail_verif != '')
        return 'different';
    else
        return 'ok';
}

/**
 * Fonction permettant de retourner si la date de naissance est correcte
 * @param string $date
 * @return string réponse en fonction des règles mises en place
 */
function birthdate($date) {
    if ($date == '')
        return 'empty';

    else if (substr_count($date, '/') != 2)
        return 'format';
    else {
        $DATE = explode('/', $date);
        if (date('Y') - $DATE[2] <= 4)
            return 'tooyoung';
        else if (date('Y') - $DATE[2] >= 135)
            return 'tooold';

        else if ($DATE[2] % 4 == 0) {
            $maxdays = Array('31', '29', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31');
            if ($DATE[0] > $maxdays[$DATE[1] - 1])
                return 'invalid';
            else
                return 'ok';
        } else {
            $maxdays = Array('31', '28', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31');
            if ($DATE[0] > $maxdays[$DATE[1] - 1])
                return 'invalid';
            else
                return 'ok';
        }
    }
}

/**
 * Méthode permettant de vider une session après le passage dans la page
 */
function vidersession() {
    foreach ($_SESSION as $cle => $element) {
        unset($_SESSION[$cle]);
    }
}

/**
 * Fonction envoyant un mail(utilisation de la fonction mail()) personnalisé
 * pour l'inscription au site
 * @param string $mail
 * @param string $pseudo
 * @param string $passe
 * @return boolean renvoie si le mail a été envoyé ou non
 */
function inscription_mail($mail, $pseudo, $passe) {
    $to = $mail;
    $subject = 'Inscription sur Nolark - ' . $pseudo;
    $message = '<html>
					<head>
						<title></title>
					</head>
					
					<body>
						<div>Bienvenue sur Nolark !<br/>
						Vous avez complété une inscription avec le pseudo
						' . htmlspecialchars($pseudo, ENT_QUOTES) . ' à l\'instant.<br/>
						Votre mot de passe est : ' . htmlspecialchars($passe, ENT_QUOTES) . '.<br/>
						Veillez à le garder secret et à ne pas l\'oublier.<br/><br/>
						
						En vous remerciant.<br/><br/>
						Moi - Wembaster de Nolark 
					</body>
				</html>';
//headers principaux.
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
//headers supplémentaires
    $headers .= 'From: "Nolark" <contact@supersite.com>' . "\r\n";
    $headers .= 'Cc: "Duplicata" <duplicata@supersite.com>' . "\r\n";
    $headers .= 'Reply-To: "Membres" <membres@supersite.com>' . "\r\n";
    $email = mail($to, $subject, $message, $headers); //marche

    if ($email)
        return true;
    return false;
}

/**
 * Fonction envoyant un mail(utilisation de la fonction mail()) personnalisé
 * pour la confirmation de réinitialisation de mot de passe
 * @param string $mail
 * @param string $pseudo
 * @return boolean renvoie si le mail a été envoyé ou non
 */
function reinimdp_mail($mail, $pseudo) {
    $to = $mail;
    $subject = 'Réinitialisation de votre mot de passe - ' . $pseudo;
    $message = '<html>
					<head>
						<title></title>
					</head>
					
					<body>
						<div>Bonjour '.$pseudo.' !<br/>
						Vous venez de réinitialiser votre mot de passe sur notre compte.<br/>
                                                Si ce n\'est pas vous, veuillez contacter le service client.<br/>
						En vous remerciant.<br/><br/>
						Moi - Wembaster de Nolark 
					</body>
				</html>';
//headers principaux.
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
//headers supplémentaires
    $headers .= 'From: "Nolark" <contact@supersite.com>' . "\r\n";
    $headers .= 'Cc: "Duplicata" <duplicata@supersite.com>' . "\r\n";
    $headers .= 'Reply-To: "Membres" <membres@supersite.com>' . "\r\n";
    $email = mail($to, $subject, $message, $headers); //marche

    if ($email)
        return true;
    return false;
}

/**
 * Fonction envoyant un mail(utilisation de la fonction mail()) personnalisé
 * pour la réintialisation de mot de passe (envoi d'un lien)
 * @param string $mail
 * @param string $pseudo
 * @return boolean renvoie si le mail a été envoyé ou non
 */
function mdp_mail($mail, $pseudo) {
    $to = $mail;
    $subject = 'Réinitialisation de votre mot de passe - ' . $pseudo;
    $message = '<html>
					<head>
						<title></title>
					</head>
					
					<body>
						<div>Bonjour '.$pseudo.' !<br/>
						Vous avez oublié votre mot de passe ? Pas de soucis, voilà le lien pour le réinitialiser<br/>
						<a href="localhost/nolark/pages/reini-mdp.php?login='.$pseudo.'">localhost/nolark/pages/reini-mdp.php?login='.$pseudo.'</a><br/>
						En vous remerciant.<br/><br/>
						Moi - Wembaster de Nolark 
					</body>
				</html>';
//headers principaux.
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
//headers supplémentaires
    $headers .= 'From: "Nolark" <contact@supersite.com>' . "\r\n";
    $headers .= 'Cc: "Duplicata" <duplicata@supersite.com>' . "\r\n";
    $headers .= 'Reply-To: "Membres" <membres@supersite.com>' . "\r\n";
    $email = mail($to, $subject, $message, $headers); //marche

    if ($email)
        return true;
    return false;
}