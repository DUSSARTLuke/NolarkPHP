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
        include('../includes/inscription.inc.php');
        ?>
        <section>
            <h1>Inscription: </h1>
            <form action="" method="POST">
                <fieldset>
                    <legend> Vous inscrire : </legend>
                    <div class="form-group">
                        <label for="">Votre pseudo : </label>
                        <input type="text" name="pseudo" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="">Votre mail : </label>
                        <input type="email" name="email" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="">Mot de passe : </label>
                        <input type="password" name="password" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="">Confirmez votre mot de passe : </label>
                        <input type="password" name="password_confirm" class="form-control" />
                    </div>
                    <button type="submit" class="btn btn-primary">M'inscrire</button>
                </fieldset>
            </form>
        </section>
        <?php
        include('../includes/footer.inc.php');
        ?>
    </body>
</html> 

