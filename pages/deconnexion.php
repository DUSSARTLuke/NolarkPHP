<?php
session_start(); 
session_unset();
session_destroy(); // on décrit la session que l'on avait ouverte et on revient sur la page d'accueil
header('Location: ../index.php');
exit();


