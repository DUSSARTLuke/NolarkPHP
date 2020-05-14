<!DOCTYPE html>
<!-- 
     Page web créé dans le cadre du cours de web Dev le 03/04/2020
     Auteur : Luke DUSSART
     Email : lukedussart@hotmail.fr
-->

<html lang="fr-FR">
    <head>
        <title>Casques Nolark : Sécurité et confort, nos priorités !</title>
        <meta charset="UTF-8">
        <meta name="author" content="Luke DUSSART">
        <meta name="description" content="Découvrez des casques moto dépassant même les exigences des tests de sécurité. Tous les casques Nolark au meilleur prix et avec en prime la livraison gratuite !">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/resetmdp.css" rel="stylesheet" type="text/css">
        <link href="../css/styles.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="../favicon.ico">
    </head>
    <body>
        <?php
        include('../includes/header.html.inc.php');
        ?>
        <section id="resetsec">
            <form id="form_reset" name="form_reset" method="POST">
                <fieldset id="reset">
                    <legend>Réinitialiser votre mot de passe : </legend>
                    <div><label for="newpwd">Nouveau mot de passe :</label> <input type="password" name="newpwd" id="newpwd" size="35" required></div>
                    <div><label for="pwd_confirm">Confirmation :</label> <input type="password" name="pwd_confirm" id="pwd_confirm" size="35" required></div>
                    <div><input type="submit" name="envoie" value="Envoyer"></div>
                    <?php
                    session_start();

                    /*                     * ******Actualisation de la session...********* */
                    include('../includes/fonctions.php');
                    connexionbdd();
                    actualiser_session();

                    $pass = filter_input(INPUT_POST, 'newpwd');
                    $mdp_confirm = filter_input(INPUT_POST, 'pwd_confirm');
                    $passchiffre = password_hash($pass, PASSWORD_BCRYPT);
                    $pseudo = filter_input(INPUT_GET, 'login');
                    $cnx = new PDO('mysql:host=127.0.0.1;dbname=nolark', 'root', '');
                    $recuppwd = 'select `password` from `membres` where login = "' .$pseudo.'"';
                        $sql = $recuppwd;
                        $recupmdp = $cnx->prepare($sql);
                        $recupmdp->execute();
                        $pwd = $recupmdp->fetch();
                    if (isset($pass) && checkmdp($pass) == 'ok' && isset($mdp_confirm) && checkmdpS($pass, $mdp_confirm)) {
                        $reini = 'update `membres` set `password` = "' . $passchiffre . '" where `login` = "' .$pseudo . '"';
                        // Requête SQL
                        $req = $reini;
                        $res = $cnx->prepare($req);
                        $res->execute();
                        $recupmail = 'select `mail` from `membres` where login = "' .$pseudo.'"';
                        $sql = $recupmail;
                        $recup = $cnx->prepare($sql);
                        $recup->execute();
                        $email = $recup->fetch();
                        if (reinimdp_mail($email['mail'], $pseudo)) {
                            echo' Un mail vient de vous être envoyé';
                        } else {
                            echo' Un mail devait vous être envoyé, mais une erreur est survenue, veuillez essayer à nouveau.';
                        }
                    } else{
                        echo'Une erreur est survenue, veuillez vérifier que ce mot de passe n\'existe pas déjà ou que vous avez bien réécrit votre mot de passe';
                    }
                    ?>
                </fieldset>
            </form>
        </section>
        <?php
        include('../includes/footer.inc.php');
        ?>
    </body>
</html>
