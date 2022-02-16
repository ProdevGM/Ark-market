<?php

include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


// Contrôle connexion utilisateur
if(!user_is_connect()){
    header('location:http://ark-market/index.php');
}



include 'inc/header.inc.php';
?>

<main class="connexion">
    <div class="container">





    </div>



<?php
include 'inc/footer.inc.php';
?>