
<?php session_start(); ?>
<?php
if (isset($_SESSION['id'])) {
    header('Location: membres.php');
    exit();
}


include '../includes/hautinscription.php';
$queries = 0;
?>
<body>
    <?php
    include('../includes/header.html.inc.php');
?>
 <section id="inscrit">
        <h1>Formulaire d'inscription</h1>
        <p>Bienvenue sur la page d'inscription de mon site !<br/>
            Merci de remplir ces champs pour continuer.</p>
        <form action="trait-inscription.php" method="post" name="Inscription">
            <fieldset id="formulaire"><legend>Identifiants</legend>
                <div><label for="pseudo">Pseudo : (compris entre 3 et 32 caractères)</label> <input type="text" name="pseudo" id="pseudo" size="30" /></div>  
                <div><label for="mdp">Mot de passe : (compris entre 4 et 50 caractères)</label> <input type="password" name="mdp" id="mdp" size="30" /></div>
                <div><label for="mdp_verif">Mot de passe (vérification) :</label> <input type="password" name="mdp_verif" id="mdp_verif" size="30" /></div>
                <div><label for="mail">Mail :</label> <input type="text" name="mail" id="mail" size="30" /></div>
                <div><label for="mail_verif">Mail (vérification) :</label> <input type="text" name="mail_verif" id="mail_verif" size="30" /></div>
                <div><label for="date_naissance">Date de naissance : (format JJ/MM/AAAA)</label> <input type="text" name="date_naissance" id="date_naissance" size="30" /></div>
                <div class="inscription"><input type="submit" value="Inscription" /></div>
            </fieldset>
            <fieldset><legend>Protection anti-robot</legend>
                <p>Il est possible que certaines fois, l'image soit trop dure à lire ; le cas échéant, actualisez la page jusqu'à avoir une image lisible.</p>
                <p>Si vous êtes dans l'incapacité de lire plusieurs images d'affilée, <a href="nous-contacter.php">contactez-nous</a>, nous nous occuperons de votre inscription.</p>
                <label for="captcha" class="float">Entrez les 8 caractères (majuscules ou chiffres) contenus dans l'image :</label> <input type="text" name="captcha" id="captcha"><br/>
                <img src="../pages/captcha.php" alt="image avec des caractères permettant de vérifier la non-automatisation" />
            </fieldset>
            <div id="inscription"><input type="submit" value="Inscription" /></div>
        </form>
    </section>
    <?php
    include('../includes/footer.inc.php');
    ?>
</body>
</html> 

