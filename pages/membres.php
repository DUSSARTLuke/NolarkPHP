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
        <link href="../css/membres.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="../favicon.ico">
    </head>
    <body>
        <?php
            include('../includes/header.html.inc.php');
        ?>
        <section id="connex">
            <h1> Espace Membre </h1>
         <form id="form_contact" name="form_contact" action="http://gil83.fr/nolark/testforms.php" method="POST">
            <fieldset id="connexion">
                <legend>Vous connecter</legend>
                <div><label for="i_pseudo">Votre pseudo :</label> <input type="text" name="i_pseudo" id="i_pseudo" size="35" required></div>
                <div><label for="i_passwd">Votre mot de passe :</label> <input type="password" name="i_passwd" id="i_passwd" size="35" required></div>
                <div><button class="button button__signin" type="submit">Se connecter</button>
                <a class="link_auth" href="reset-mdp.php">Mot de passe oublié ?</a>
                </div>
            </fieldset>
             <fieldset id="inscription">
                 <legend> S'inscrire</legend>
                 <div><label for="i_email">Votre e-mail :</label> <input type="email" name="i_email" id="i_email" size="35" pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required></div>
                 <div><input type="button" id="btn_envoyer" name="sub_envoyer" value="S'inscrire"></div>
         </form>     
        </section>               
        <?php
            include('../includes/footer.inc.php');
        ?>
    </body>
</html> 