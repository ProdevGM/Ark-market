<?php

//Déclaration des variables
$info_ok = false; // Vérification de la bonne exécution de la requête

// Récupération des infos du serveur
if(!empty($_SESSION["serveur"]["id_serveur"])){

  $PDO_infos_serveur = $pdo->query("SELECT * FROM serveur WHERE id_serveur = ".$_SESSION["serveur"]["id_serveur"]."");
  
  if($PDO_infos_serveur->rowcount()){
    $info_ok = true;
    $infos_serveur = $PDO_infos_serveur->fetch(PDO::FETCH_ASSOC);
  }
}

?>
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

        <div class="nav-burger d-lg-none row initmarg justify-content-between align-items-center">

          <div class="logo w-auto">
              <a href="<?= URL ?>index.php"><img src="<?= URL ?>/image/site/logo.png" alt=""></a>
          </div>

          <div class="block-burger">
              <div class="burger">
                  <span></span>
              </div>
          </div>

        </div>

        <div class="container text-center">

          <div class="block-logo row align-items-center align-content-arround">
            <div class="logo">
              <a href="<?= URL ?>index.php">M<span class="ark">ARK</span>ET</a>
            </div>
            <div>
              <p>Proposez vos créatures ou équipements et trouvez ce qui vous manque pour poursuivre l'aventure</p>
              <button>MARCHE</button>
            </div>
          </div>

          <div class="block-serveur row justify-content-center align-items-center <?= ($info_ok)?"":"d-none" ?>" >

            <div class="block-nom col-12 order-lg-1">
<?php
              if(!empty($infos_serveur["page_web"])){
?>
                <a href="<?= $infos_serveur["page_web"] ?>" class="nom hover-bleu" target="_blank"><?= $infos_serveur["nom_serveur"] ?></a>
<?php
              }else{
?>
                <p class="nom"><?= $infos_serveur["nom_serveur"] ?></p>
<?php
              }
?>
            </div>

            <div class="block-lien ">
<?php
              // Logo top-serveur
              if(!empty($infos_serveur["page_ts"])){
?>
                <a href="<?= $infos_serveur["page_ts"] ?>" target="_blank">
                  <img src="<?= URL ?>/image/site/ts.png" class="hover-rotation" alt="">
                </a>
<?php
              }
?>
            </div>

            <div class="block-lien order-lg-2">
<?php
              // Logo discord
              if(!empty($infos_serveur["page_discord"])){
?>
                <a href="<?= $infos_serveur["page_discord"] ?>" target="_blank">
                  <img src="<?= URL ?>/image/site/discord.png" class="hover-rotation" alt="">
                </a>
<?php
              }
?>
            </div>
          </div>

          <div class="top-droit d-none d-lg-inline-block w-100">
  
            <div class="recherche d-inline-block" >
              <button>
                <svg xmlns="http://www.w3.org/2000/svg" class="hover-bleu-bg h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </button>
            </div>

            <div class="connexion d-inline-block">
<?php
              if(isset($_SESSION['utilisateur'])){
?>
                <a href="<?= URL ?>connexion.php?action=deconnexion" class="hover-bleu-bg">Se déconnecter</a>
<?php
              }else{
?>
                <a href="<?= URL ?>connexion.php?action=connexion" class="hover-bleu-bg">Se connecter</a>
<?php
              }
?>
            </div>
          </div>
        </div>
      </header>
