<?php
session_start();
/**
 * Page permettant l'inscription, elle ne récupère que les infos et affichent si l'inscription s'est bien effectuée
 */
/* * ******Actualisation de la session...********* */
include('../includes/fonctions.php');
connexionbdd();
actualiser_session();

/* * ******Fin actualisation de session...********* */

if (isset($_SESSION['login'])) {
    header('Location: membres.php');
    exit();
}


$_SESSION['erreurs'] = 0;

$pseu = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING); // utlisation de filter_input pour ne pas utiliser la variable superglobale 
$password = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING); // utlisation de filter_input pour ne pas utiliser la variable superglobale 
$pass_confirm = filter_input(INPUT_POST, 'mdp_verif', FILTER_SANITIZE_STRING); // utlisation de filter_input pour ne pas utiliser la variable superglobale 
$email = filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL); // utlisation de filter_input pour ne pas utiliser la variable superglobale 
$mail_confirm = filter_input(INPUT_POST, 'mail_verif', FILTER_SANITIZE_EMAIL); // utlisation de filter_input pour ne pas utiliser la variable superglobale 
$naissance = filter_input(INPUT_POST, 'date_naissance', FILTER_SANITIZE_STRING); // utlisation de filter_input pour ne pas utiliser la variable superglobale 
//Pseudo
if (isset($pseu)) {
    $pseudo = trim($pseu);
    $pseudo_result = checkpseudo($pseudo); // utilisation de la fonction checkpseudo dans fonctions.php
    if ($pseudo_result == 'tooshort') {
        $_SESSION['pseudo_info'] = '<span class="erreur">Le pseudo ' . htmlspecialchars($pseudo, ENT_QUOTES) . ' est trop court, vous devez en choisir un plus long (minimum 3 caractères).</span><br/>';
        $_SESSION['form_pseudo'] = '';
        $_SESSION['erreurs'] ++;
    } else if ($pseudo_result == 'toolong') {
        $_SESSION['pseudo_info'] = '<span class="erreur">Le pseudo ' . htmlspecialchars($pseudo, ENT_QUOTES) . ' est trop long, vous devez en choisir un plus court (maximum 32 caractères).</span><br/>';
        $_SESSION['form_pseudo'] = '';
        $_SESSION['erreurs'] ++;
    } else if ($pseudo_result == 'exists') {
        $_SESSION['pseudo_info'] = '<span class="erreur">Le pseudo ' . htmlspecialchars($pseudo, ENT_QUOTES) . ' est déjà pris, choisissez-en un autre.</span><br/>';
        $_SESSION['form_pseudo'] = '';
        $_SESSION['erreurs'] ++;
    } else if ($pseudo_result == 'ok') {
        $_SESSION['pseudo_info'] = '';
        $_SESSION['form_pseudo'] = $pseudo;
    } else if ($pseudo_result == 'empty') {
        $_SESSION['pseudo_info'] = '<span class="erreur">Vous n\'avez pas entré de pseudo.</span><br/>';
        $_SESSION['form_pseudo'] = '';
        $_SESSION['erreurs'] ++;
    }
} else {
    header('Location: membres.php');
    exit();
}

//Mot de passe
if (isset($password)) {
    $mdp = trim($password);
    $mdp_result = checkmdp($mdp, ''); // utilisation de la fonction checkmdp dans fonctions.php
    if ($mdp_result == 'tooshort') {
        $_SESSION['mdp_info'] = '<span class="erreur">Le mot de passe entré est trop court, changez-en pour un plus long (minimum 4 caractères).</span><br/>';
        $_SESSION['form_mdp'] = '';
        $_SESSION['erreurs'] ++;
    } else if ($mdp_result == 'toolong') {
        $_SESSION['mdp_info'] = '<span class="erreur">Le mot de passe entré est trop long, changez-en pour un plus court. (maximum 50 caractères)</span><br/>';
        $_SESSION['form_mdp'] = '';
        $_SESSION['erreurs'] ++;
    } else if ($mdp_result == 'nofigure') {
        $_SESSION['mdp_info'] = '<span class="erreur">Votre mot de passe doit contenir au moins un chiffre.</span><br/>';
        $_SESSION['form_mdp'] = '';
        $_SESSION['erreurs'] ++;
    } else if ($mdp_result == 'noupcap') {
        $_SESSION['mdp_info'] = '<span class="erreur">Votre mot de passe doit contenir au moins une majuscule.</span><br/>';
        $_SESSION['form_mdp'] = '';
        $_SESSION['erreurs'] ++;
    } else if ($mdp_result == 'ok') {
        $_SESSION['mdp_info'] = '';
        $_SESSION['form_mdp'] = $mdp;
    } else if ($mdp_result == 'empty') {
        $_SESSION['mdp_info'] = '<span class="erreur">Vous n\'avez pas entré de mot de passe.</span><br/>';
        $_SESSION['form_mdp'] = '';
        $_SESSION['erreurs'] ++;
    }
} else {
    header('Location: membres.php');
    exit();
}

//Mot de passe suite
if (isset($pass_confirm)) {
    $mdp_verif = trim($pass_confirm); 
    $mdp_verif_result = checkmdpS($mdp_verif, $mdp); // utilisation de la fonction checkmdpS dans fonctions.php
    if ($mdp_verif_result == 'different') {
        $_SESSION['mdp_verif_info'] = '<span class="erreur">Le mot de passe de vérification diffère du mot de passe.</span><br/>';
        $_SESSION['form_mdp_verif'] = '';
        $_SESSION['erreurs'] ++;
        if (isset($_SESSION['form_mdp']))
            unset($_SESSION['form_mdp']);
    } else {
        if ($mdp_verif_result == 'ok') {
            $_SESSION['form_mdp_verif'] = $mdp_verif;
            $_SESSION['mdp_verif_info'] = '';
        } else {
            $_SESSION['mdp_verif_info'] = str_replace('passe', 'passe de vérification', $_SESSION['mdp_info']);
            $_SESSION['form_mdp_verif'] = '';
            $_SESSION['erreurs'] ++;
        }
    }
} else {
    header('Location: membres.php');
    exit();
}

//mail
if (isset($email)) {
    $mail = trim($email);
    $mail_result = checkmail($mail); // utilisation de la fonction checkmail dans fonctions.php
    if ($mail_result == 'isnt') {
        $_SESSION['mail_info'] = '<span class="erreur">Le mail ' . htmlspecialchars($mail, ENT_QUOTES) . ' n\'est pas valide.</span><br/>';
        $_SESSION['form_mail'] = '';
        $_SESSION['erreurs'] ++;
    } else if ($mail_result == 'exists') {
        $_SESSION['mail_info'] = '<span class="erreur">Le mail ' . htmlspecialchars($mail, ENT_QUOTES) . ' est déjà pris';
        $_SESSION['form_mail'] = '';
        $_SESSION['erreurs'] ++;
    } else if ($mail_result == 'ok') {
        $_SESSION['mail_info'] = '';
        $_SESSION['form_mail'] = $mail;
    } else if ($mail_result == 'empty') {
        $_SESSION['mail_info'] = '<span class="erreur">Vous n\'avez pas entré de mail.</span><br/>';
        $_SESSION['form_mail'] = '';
        $_SESSION['erreurs'] ++;
    }
} else {
    header('Location: ../index.php');
    exit();
}

//mail suite
if (isset($mail_confirm)) {
    $mail_verif = trim($mail_confirm);
    $mail_verif_result = checkmailS($mail_verif, $mail); // utilisation de la fonction checkmailS dans fonctions.php
    if ($mail_verif_result == 'different') {
        $_SESSION['mail_verif_info'] = '<span class="erreur">Le mail de vérification diffère du mail.</span><br/>';
        $_SESSION['form_mail_verif'] = '';
        $_SESSION['erreurs'] ++;
    } else {
        if ($mail_result == 'ok') {
            $_SESSION['mail_verif_info'] = '';
            $_SESSION['form_mail_verif'] = $mail_verif;
        } else {
            $_SESSION['mail_verif_info'] = str_replace(' mail', ' mail de vérification', $_SESSION['mail_info']);
            $_SESSION['form_mail_verif'] = '';
            $_SESSION['erreurs'] ++;
        }
    }
} else {
    header('Location: membres.php');
    exit();
}

//date de naissance
if (isset($naissance)) {
    $date_naissance = trim($naissance);
    $date_naissance_result = birthdate($date_naissance); // utilisation de la fonction birthdate dans fonctions.php
    if ($date_naissance_result == 'format') {
        $_SESSION['date_naissance_info'] = '<span class="erreur">Date de naissance au mauvais format ou invalide.</span><br/>';
        $_SESSION['form_date_naissance'] = '';
        $_SESSION['erreurs'] ++;
    } else if ($date_naissance_result == 'tooyoung') {
        $_SESSION['date_naissance_info'] = '<span class="erreur">Vous êtes trop jeune pour vous inscrire ici.</span><br/>';
        $_SESSION['form_date_naissance'] = '';
        $_SESSION['erreurs'] ++;
    } else if ($date_naissance_result == 'tooold') {
        $_SESSION['date_naissance_info'] = '<span class="erreur">Plus de 135 ans ? Vous allez vous casser un os.</span><br/>';
        $_SESSION['form_date_naissance'] = '';
        $_SESSION['erreurs'] ++;
    } else if ($date_naissance_result == 'invalid') {
        $_SESSION['date_naissance_info'] = '<span class="erreur">Le ' . htmlspecialchars($date_naissance, ENT_QUOTES) . ' n\'existe pas.</span><br/>';
        $_SESSION['form_date_naissance'] = '';
        $_SESSION['erreurs'] ++;
    } else if ($date_naissance_result == 'ok') {
        $_SESSION['date_naissance_info'] = '';
        $_SESSION['form_date_naissance'] = $date_naissance;
    } else if ($date_naissance_result == 'empty') {
        $_SESSION['date_naissance_info'] = '<span class="erreur">Vous n\'avez pas entré de date de naissance.</span><br/>';
        $_SESSION['form_date_naissance'] = '';
        $_SESSION['erreurs'] ++;
    }
} else {
    header('Location: membres.php');
    exit();
}

/* * captcha
  if ($_POST['captcha'] == $_SESSION['captcha'] && isset($_POST['captcha']) && isset($_SESSION['captcha'])) {
  $_SESSION['captcha_info'] = '';
  } else {
  $_SESSION['captcha_info'] = '<span class="erreur">Vous n\'avez pas recopié correctement le contenu de l\'image.</span><br/>';
  $_SESSION['erreurs']++;
  }


  unset($_SESSION['captcha']);
 */

/* * ******Entête et titre de page******** */
if ($_SESSION['erreurs'] > 0)
    echo "Erreur lors de l'inscription";
else
    ;

include('../includes/hautinscription.php'); //contient le doctype, et head.
include ('../includes/header.html.inc.php');

if ($_SESSION['erreurs'] == 0) {

    $insertion = "INSERT INTO `membres` (`id`, `login`, `password`, `mail`, `membre_banni`, `datenaissance`) VALUES (NULL,'" . $pseudo . "','" . password_hash($mdp, PASSWORD_BCRYPT) . "','" . $mail . "','" . 0 . "','" . $date_naissance . "')";
    $cnx = new PDO('mysql:host=127.0.0.1;dbname=nolark', 'root', '');
    // Requête SQL
    $req = $insertion;
    $res = $cnx->prepare($req);

    if ($res->execute()) {
        if (inscription_mail($email, $pseudo, $mdp)) {
            $sent = 'Un mail de confirmation vous a été envoyé.';
        } else {
            $sent = 'Un mail de confirmation devait être envoyé, mais son envoi a échoué, vous êtes cependant bien inscrit.';
        }
        vidersession();
        $_SESSION['inscrit'] = $pseudo;
        /* informe qu'il s'est déjà inscrit s'il actualise, si son navigateur
          bugue avant l'affichage de la page et qu'il recharge la page, etc. */
        ?>
        <h1>Inscription validée !</h1>
        <p>Nous vous remercions de vous être inscrit sur notre site, votre inscription a été validée !<br/>
            Vous pouvez vous connecter avec vos identifiants <a href="connexion.php">ici</a>.
        </p>
        <?php
    }
} else if ($_SESSION['erreurs'] > 0) {
    if ($_SESSION['erreurs'] === 1)
        $_SESSION['nb_erreurs'] = '<span class="erreur">Il y a eu 1 erreur.</span><br/>';
    else
        $_SESSION['nb_erreurs'] = '<span class="erreur">Il y a eu ' . $_SESSION['erreurs'] . ' erreurs.</span><br/>';
    ?>
    <h1>Inscription non validée.</h1>
    <p>Vous avez rempli le formulaire d'inscription du site et nous vous en remercions, cependant, nous n'avons
        pas pu valider votre inscription, en voici les raisons :<br/>
        <?php
        echo $_SESSION['nb_erreurs'];
        echo $_SESSION['pseudo_info'];
        echo $_SESSION['mdp_info'];
        echo $_SESSION['mdp_verif_info'];
        echo $_SESSION['mail_info'];
        echo $_SESSION['mail_verif_info'];
        echo $_SESSION['date_naissance_info'];
        //echo $_SESSION['captcha_info'];

        if ($_SESSION["erreurs"] !== 0) {
            ?>
            Nous vous proposons donc de revenir à la page précédente pour corriger les erreurs. (Attention, que vous
            l'ayez correctement remplie ou non, la partie sur la charte et l'image est à refaire intégralement.)</p>
        <div class="center"><a href="inscription.php">Retour</a></div>
        <?php
    } else {
        ?>
        Une erreur est survenue dans la base de données, votre formulaire semble ne pas contenir d'erreurs, donc
        il est possible que le problème vienne de notre côté, réessayez de vous inscrire ou contactez-nous.</p>

        <div class="center"><a href="inscription.php">Retenter une inscription</a> - <a href="../contact.php">Contactez-nous</a></div>
        <?php
    }
}
unset($cnx);
?>
</div>

<?php
include('../includes/footer.inc.php');
?>
</body>
</html>