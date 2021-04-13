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
        <link href="../css/styles.css" rel="stylesheet" type="text/css">
        <link href="../css/resetmdp.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="../favicon.ico">
    </head>
    <body>
        <?php
        include('../includes/header.html.inc.php');
        ?>
        <section id="resetsec">
            <form id="form_reset" name="form_reset" action="reset-mdp.php" method="POST">
                <fieldset id="reset">
                    <legend>Réinitialiser votre mot de passe : </legend>
                    <div><label for="mail">Votre mail :</label> <input type="email" name="mail" id="mail" size="35" required></div>
                    <div><input type="submit" name="envoie" value="Envoyer"></div>
                    <?php
                    session_start();

                    /*                     * ******Actualisation de la session...********* */
                    include('../includes/fonctions.php');
                    connexionbdd();
                    actualiser_session();

                    $email = filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL); // utlisation de filter_input pour ne pas utiliser la variable superglobale 
                    if (isset($email)) {
                        $recup = 'select `login` from `membres` where `mail`="' . $email .'"'; // récupération du login grâce au mail rentré
                        $cnx = new PDO('mysql:host=127.0.0.1;dbname=nolarkphp', 'userLuke', 'Football64@');
                        // Requête SQL
                        $req = $recup;
                        $res = $cnx->prepare($req);
                        $res->execute();
                        $pseudo = $res->fetch(PDO::FETCH_ASSOC);
                        
                        $log = $pseudo['login'];
                        if (mdp_mail($email, $log)) {
                            echo' Un mail vient de vous être envoyé';
                        } else {
                            echo' Un mail devait vous être envoyé, mais une erreur est survenue, veuillez essayer à nouveau.';
                        }
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
