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

function sqlquery($requete) {
    $cnx = new PDO('mysql:host=127.0.0.1;dbname=nolark', 'root', '');
    // Requête SQL
    $req = $requete;
    $res = $cnx->prepare($req);
    $res->execute();
}

function connexionbdd() {
    //Connexion à la base de données
    $cnx = new PDO('mysql:host=127.0.0.1;dbname=nolark', 'nolarkuser', 'nolarkpwd');
}

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

function vider_cookie() {
    foreach ($_COOKIE as $cle => $element) {
        setcookie($cle, '', time() - 3600);
    }
}

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

function checkmdp($mdp) {
    if ($mdp == '')
        return 'empty';
    else if (strlen($mdp) < 4)
        return 'tooshort';
    else if (strlen($mdp) > 50)
        return 'toolong';

    else {
        if (!preg_match('#[0-9]{1,}#', $mdp))
            return 'nofigure';
        else if (!preg_match('#[A-Z]{1,}#', $mdp))
            return 'noupcap';
        else
            return 'ok';
    }
}

function checkmdpS($mdp, $mdp_verif) {
    if ($mdp != $mdp_verif && $mdp != '' && $mdp_verif != '')
        return 'different';
    else
        return checkmdp($mdp);
}

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

function checkmailS($mail, $mail_verif) {
    if ($mail != $mail_verif && $mail != '' && $mail_verif != '')
        return 'different';
    else
        return 'ok';
}

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

function vidersession() {
    foreach ($_SESSION as $cle => $element) {
        unset($_SESSION[$cle]);
    }
}

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
						localhost/nolark/pages/reini-mdp.php?login='.$pseudo.'<br/>
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