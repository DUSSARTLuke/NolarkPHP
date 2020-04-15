<?php
require_once 'functions.php' ; 
if (!empty($_POST)) {

    $errors = array();
    if (!empty($_POST['pseudo']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['pseudo'])) {
        $errors['pseudo'] = "Votre pseudo n'est pas valide";
    }

   if (!empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Votre email n'est pas valide";
    }
    
    if (!empty($_POST['password']) || ($_POST['password']!= $_POST['password_confirm'])) {
        $errors['password'] = "Vous devez rentrer un mot de passe valide";
    }
    
    if(empty($errors)){
        require_once 'db.php';
        $req= $cnx-> prepare("INSERT INTO membres(login, password, mail) values (?,?,?)");
        $password = password_hash( $_POST['password'], PASSWORD_BCRYPT);
        $req->execute([$_POST['pseudo'],$password, $_POST['email']]);
        die('Notre comptre a bien été crée');
    }
    
    debug($errors);
}

