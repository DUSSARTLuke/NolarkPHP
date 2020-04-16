<?php

require_once '../includes/functions.php';
if (!empty($_POST)) {

    $errors = array();
    if (empty($_POST['pseudo']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['pseudo'])) {
        $errors['pseudo'] = "Votre pseudo n'est pas valide";
    }

    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Votre email n'est pas valide";
    }

    if (empty($_POST['password']) || ($_POST['password'] != $_POST['password_confirm'])) {
        $errors['password'] = "Vous devez rentrer un mot de passe valide";
    }

    if (empty($errors)) {
        $cnx = new PDO('mysql:host=127.0.0.1;dbname=nolark', 'root', '');
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $req = $cnx->prepare('INSERT INTO membres(login, password, mail) values("' . $_POST['pseudo'] . '","' . $password . '","'. $_POST['email'] .'")');
        $req->execute();
        $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $cnx->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        die('Votre compte a bien été crée');
    }

    debug($errors);
}
 