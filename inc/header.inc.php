<!DOCTYPE html>
  <html lang="fr">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="Commerce dans l'univers ARK">
      <meta name="author" content="Devix">
      <title>Ark-Market</title>


      <!-- Bootstrap core CSS -->
      <link href="<?php echo URL; ?>css/bootstrap.min.css" rel="stylesheet">
      <!-- OWN CSS -->
      <link href="<?php echo URL; ?>css/style.css" rel="stylesheet">
      <!-- FONT AWESOME -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0/css/all.min.css" rel="stylesheet">    
      <!-- FONT FAMILY  -->
      <link href="https://fonts.googleapis.com/css2?family=Karla&family=Lato:wght@300&display=swap" rel="stylesheet">

    <body>
      <header>
        <div class="container text-center">
          <a href="<?= URL ?>index.php" class="mt-5 mb-4">ARK Market</a>
          <p>Mettez en vente vos dinosaures ou équipements et trouver ce qui vous manque pour poursuivre l'aventure</p>
          <p class="mt-1 mb-5">Déposez des quêtes afin d'obtenir de l'aide dans n'importe quelle situation</p>
          <div class="connexion">
<?php
            if(isset($_SESSION['utilisateur'])){
?>
            <a href="<?= URL ?>connexion.php?action=deconnexion">Se déconnecter</a>
<?php
            }else{
?>
            <a href="<?= URL ?>connexion.php?action=connexion">Se connecter</a>
<?php
            }
?>
          </div>
        </div>
      </header>
