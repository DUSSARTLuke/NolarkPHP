<!DOCTYPE html>
<!-- 
     Page web créé dans le cadre du cours de web Dev le 03/04/2020
     Auteur : LukeDUSSART
     Email : lukedussart@hotmail.fr
-->

<html lang="fr-FR">
    <head>
        <title>Casques Nolark : Sécurité et confort, nos priorités !</title>
        <meta charset="UTF-8">
        <meta name="author" content="Luke DUSSART">
        <meta name="description" content="Découvrez des casques moto dépassant même les exigences des tests de sécurité. Tous les casques Nolark au meilleur prix et avec en prime la livraison gratuite !">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/membres.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="../favicon.ico">
    </head>
    <body>
        <?php
            include('../includes/headeradmin.inc.php');
        ?>
         <form id="form_contact" name="form_contact" action="http://gil83.fr/nolark/testforms.php" method="POST">
                <fieldset id="connexion">
                    <legend>Pour mieux vous connaître</legend>
                    <div><label for="i_pseudo">Votre pseudo :</label> <input type="text" name="i_pseudo" id="i_pseudo" size="35" required></div>
                    <div><label for="i_passwd">Votre mot de passe :</label> <input type="password" name="i_passwd" id="i_passwd" size="35" required></div>
                        <?php
            include('../includes/footer.inc.php');
        ?>
    </body>
</html> 