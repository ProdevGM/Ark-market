<?php

include '../inc/init.inc.php';
include '../inc/fonction.inc.php';


// Contrôle connexion utilisateur
if(!user_is_connect()){
    header('location:http://ark-market/index.php');
}

// Déclaration des variables
$mail = $_SESSION["utilisateur"]["mail"];
$mdp_actuel = "";
$mdp_nouveau = "";
$mdp_confirme = "";
$nom_perso = "";
$nom_discord = "";

// Variable de message pour le contrôle des variables $_POST
$msg_compte_mail = "";
$msg_compte_mdp_actuel = "";
$msg_compte_mdp_nouveau = "";
$msg_compte_mdp_confirme = "";
$msg_compte_nom_perso = "";
$msg_compte_nom_discord = "";
$msg_compte_supprimer = "";





/* ********************************** */
/* ********************************** */
// SUPPRESSION D'UNE ENTRÉE INFO_SERVEUR
/* ********************************** */
/* ********************************** */

if(isset($_POST["valide_serveur"]) && isset($_POST["supprimer"]) && isset($_GET['id'])){

    $nom_serveur = trim($_POST["supprimer"]);


    /* ***************************** */
    // Contrôle de la variable d'entrée
    /* ***************************** */

    // Contrôle que l'id soit bien numérique
    if(!is_numeric($_GET["id"]))
        header('location:http://ark-market/index.php');


    // Vérification que l'id corresponde à une entrée de l'utilisateur et récupération du nom du serveur
    $PDO_verif = $pdo->query("SELECT i.nom_perso, s.nom_serveur FROM info_serveur i, serveur s WHERE i.id_serveur = s.id_serveur
                                                                        AND i.id_info_serveur = ".$_GET["id"]."
                                                                        AND i.id_utilisateur = ".$_SESSION["utilisateur"]["id_utilisateur"]."");
                                                                      
    if($PDO_verif->rowcount()){

        $verif = $PDO_verif->fetch(PDO::FETCH_ASSOC);

        // CONTRÔLE NOM SERVEUR : Identique à $_POST["supprimer"]                                                                  
        if($verif['nom_serveur'] != $nom_serveur){
            $controle_variables = false;
            $msg_compte_supprimer .= "<p class=\"alerte-msg\"> Le nom du serveur est incorrect </p>";
        }

        if($controle_variables){

            $delete = $pdo->exec("DELETE FROM info_serveur WHERE id_info_serveur = ".$_GET['id']."");

            if($delete !== false)
                $annonce_top .= "<p class=\"alerte-msg\"> Suppression effectuée </p>";
            else
                $annonce_top .= "<p class=\"alerte-msg\"> Erreur lors de la suppression. Veuillez réessayer ultérieurement </p>";            
        }
    }else   
        header('location:http://ark-market/index.php');
        
    $controle_variables = true; //Réinitialisation
}




/* ******************************************** */
// RECUPÉRATION DES INFOS SERVEUR DE L'UTILISATEUR
/* ******************************************** */
$PDO_info_serveur = $pdo->query("SELECT * FROM serveur s, info_serveur i
                                            WHERE s.id_serveur = i.id_serveur
                                            AND i.id_utilisateur = ".$_SESSION["utilisateur"]['id_utilisateur']."
                                            ORDER BY i.principal DESC, s.nom_serveur DESC");


/* *************************************************** */
// RECUPÉRATION DE LA LISTE DES SERVEURS DE L'UTILISATEUR
/* *************************************************** */
$PDO_liste_serveur = $pdo->query("SELECT * FROM serveur s, info_serveur i WHERE s.id_serveur = i.id_serveur
                                                                        AND i.id_utilisateur = ".$_SESSION['utilisateur']['id_utilisateur']."");




/* ************************************** */
/* ************************************** */
// MODIFICATION DES DONNÉES DE L'UTILISATEUR
/* ************************************** */
/* ************************************** */

if((isset($_POST["valide_compte"]) || isset($_POST["valide_mdp"])
                                  || isset($_POST["valide_principal"])
                                  || isset($_POST["valide_serveur"]))
                                  && empty($_POST['supprimer'])){
                                    
    // Modification de la partie "Mon compte" (mail)
    if(isset($_POST["valide_compte"]) && isset($_POST["mail"])){

        $mail = trim($_POST["mail"]);


        /* ***************************** */
        // Contrôle des variables d'entrées
        /* ***************************** */

        // CONTRÔLE MAIL : Validité format mail
        $verif_mail = preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/", $mail);
        if(empty($mail) || !$verif_mail){
            $controle_variables = false;
            $msg_compte_mail .= "<p class=\"alerte-msg\"> Veuillez entrer une adresse mail valide </p>";
        }

        if($controle_variables){

            //Contrôle sur l'existance de ce mail
            $verif = $pdo->prepare("SELECT id_utilisateur FROM utilisateur WHERE mail = :mail");
            $verif->bindParam(':mail', $mail, PDO::PARAM_STR);
            $verif->execute();

            if($verif->rowcount()){
                $controle_variables = false;
                $msg_compte_mail = "<p class=\"alerte-msg\"> Cette adresse mail est déjà utilisée </p>";
            
            }else{

                $enregistrer = $pdo->prepare("UPDATE utilisateur SET mail = :mail WHERE id_utilisateur = ".$_SESSION['utilisateur']['id_utilisateur']."");
                $enregistrer->bindParam(':mail', $mail, PDO::PARAM_STR);

            }
        }


    // Modification de la partie "Changement de mot de passe"
    }elseif(isset($_POST["valide_mdp"]) && isset($_POST['mdp_actuel'])
                                        && isset($_POST['mdp_nouveau'])
                                        && isset($_POST['mdp_confirme'])){

        $mdp_actuel = trim($_POST['mdp_actuel']);
        $mdp_nouveau = trim($_POST['mdp_nouveau']);
        $mdp_confirme = trim($_POST['mdp_confirme']);


        /* ***************************** */
        // Contrôle des variables d'entrées
        /* ***************************** */

        // CONTRÔLE MDP : Une minuscule, une majuscule, un chiffre, un caractère spécial et entre 8 et 20 caractères. mdp_nouveau et mdp_confirme doivent être identique
        $verif_mdp = preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,20}$#', $mdp_nouveau);
        if(empty($verif_mdp) || !$verif_mdp){
            $controle_variables = false;
            $msg_compte_mdp_nouveau = "<p class=\"alerte-msg\"> Veuillez remplir toutes les conditions </p>";
        }

        if($mdp_nouveau !== $mdp_confirme){
            $controle_variables = false;
            $msg_compte_mdp_confirme = "<p class=\"alerte-msg\"> Le mot de passe de confirmation doit être similaire au nouveau mot de passe </p>";
        }    

        if(empty($controle_variables)){

            // Récupération du mdp correspondant à ce mail
            $PDO_verif_mdp = $pdo->prepare("SELECT mdp FROM utilisateur WHERE mail = :mail
                                                                        AND id_utilisateur = ".$_SESSION['utilisateur']['id_utilisateur']."");
            $PDO_verif_mdp->bindParam(":mail", $mail, PDO::PARAM_STR);
            $PDO_verif_mdp->execute();

            $verif_mdp = $PDO_verif_mdp->fetch(PDO::FETCH_ASSOC);

            // Vérification mdp valide
            if(password_verify($mdp_actuel, $verif_mdp['mdp'])){

                $enregistrer = $pdo->prepare("UPDATE utilisateur SET mdp = :mdp_nouveau WHERE mail = :mail
                                                                                        AND id_utilisateur = ".$_SESSION["utilisateur"]["id_utilisateur"]."");

                // Cryptage du mdp
                $mdp_nouveau = password_hash($mdp_nouveau, PASSWORD_DEFAULT);
                $enregistrer->bindParam(':mdp_nouveau', $mdp_nouveau, PDO::PARAM_STR);
                $enregistrer->bindParam(':mail', $mail, PDO::PARAM_STR);

            }else
                $msg_compte_mdp_actuel = "<p class=\"alerte-msg\"> Mot de passe est incorrect </p>";

        }

    
    // Modification du serveur principal
    }elseif(isset($_POST["valide_principal"]) && isset($_POST["choix_serveur"])){

        $choix_serveur = trim($_POST["choix_serveur"]);


        /* ***************************** */
        // Contrôle de la variable d'entrée
        /* ***************************** */

        // CONTRÔLE $CHOIX_SERVEUR : Numérique
        if(!is_numeric($choix_serveur))
            header('location:http://ark-market/index.php');


        // Contrôle que l'utilisateur possède une entrée sur ce serveur
        $verif = $pdo->prepare("SELECT id_info_serveur FROM info_serveur WHERE id_serveur = :choix_serveur");
        $verif->bindParam(":choix_serveur", $choix_serveur, PDO::PARAM_STR);
        $verif->execute();

        if($verif->rowcount()){
            
            // Passage à "0" de l'ancien serveur principal
            $enregistrer = $pdo->exec("UPDATE info_serveur SET principal = 0 WHERE principal = 1
                                                                            AND id_utilisateur = ".$_SESSION['utilisateur']['id_utilisateur']."");

            if($enregistrer !== false){
                $enregistrer = $pdo->prepare("UPDATE info_serveur SET principal = 1 WHERE id_info_serveur = :id_info_serveur 
                                                                                AND id_utilisateur = ".$_SESSION['utilisateur']['id_utilisateur']."");
                $enregistrer->bindParam(":id_info_serveur", $_POST['choix_serveur'], PDO::PARAM_STR);                                                    
            }else
                $annonce_top = "<p class=\"alerte-msg\"> Erreur lors de la modification. Veuillez réessayer ultérieurement </p>";


        }else
            header('location:http://ark-market/index.php');


    // Modification de la partie "mes serveurs"
    }elseif(isset($_POST["valide_serveur"]) && isset($_POST["nom_perso"]) 
                                            && isset($_POST["nom_discord"])
                                            && isset($_GET["id"])){


        $nom_perso = trim($_POST["nom_perso"]);
        $nom_discord = trim($_POST["nom_discord"]);
        $id_info_serveur = $_GET["id"];


        /* ***************************** */
        // Contrôle des variables d'entrées
        /* ***************************** */                                  

        // CONTRÔLE NOM PERSO : Oligatoire et entre 2 et 20 caractères
        if(empty($nom_perso) || strlen($nom_perso) < 2 || strlen($nom_perso) > 20){
            $controle_variables = false;
            $msg_compte_nom_perso = "<p class=\"alerte-msg\"> Entre 2 et 20 caractères </p>";
        }

        // CONTRÔLE NOM DISCORD : Entre 2 et 20 caractères
        if(!empty($nom_perso)){
            if(strlen($nom_perso) < 2 || strlen($nom_perso) > 20){
                $controle_variables = false;
                $msg_compte_nom_discord = "<p class=\"alerte-msg\"> Entre 2 et 20 caractères </p>";
            }
        }        

        if($controle_variables){

            // CONTRÔLE ID : Corresponde à une entrée de notre utilisateur
            $verif = $pdo->query("SELECT nom_perso FROM info_serveur WHERE id_info_serveur = $id_info_serveur
                                                                    AND id_utilisateur = ".$_SESSION['utilisateur']['id_utilisateur']."");

            if($verif->rowcount()){

                $enregistrer = $pdo->prepare("UPDATE info_serveur SET nom_perso = :nom_perso, nom_discord = :nom_discord WHERE id_info_serveur = :id_info_serveur");
                $enregistrer->bindParam(":nom_perso", $nom_perso, PDO::PARAM_STR);
                $enregistrer->bindParam(":nom_discord", $nom_discord, PDO::PARAM_STR);
                $enregistrer->bindParam(":id_info_serveur", $id_info_serveur, PDO::PARAM_STR);

            }else
                header('location:http://ark-market/index.php');
        }
    }else
        header('location:http://ark-market/index.php');

    // Si pas d'erreur on exécute les prepare()
    if(empty($control_variables)){

        $enregistrer->execute();

        if($enregistrer)
            $annonce_top = "<p class=\"alerte-msg\"> Modification effectuée </p>";

        else
            $annonce_top = "<p class=\"alerte-msg\"> Erreur lors de la modification. Veuillez réessayer ultérieurement </p>";
    }
}

include '../inc/header.inc.php';
?>

<main class="connexion">
    <div class="container">

    <p> <?= $annonce_top ?> </p>

        <div class="block-profil">
            
            <p class="titre-partie">Mon compte</p>

            <form action="" method="post" class="compte initmarg row justify-content-center">

                <div class="champ">
                    <label for="mail" class="form-label">Adresse mail</label>
                    <input class="form-control" type="email" id="mail" name="mail" placeholder="Mail" value="<?= $mail ?>">
                </div>
                <div class="submit">
                    <input type="submit" class="btn btn-success" name ="valide_compte" value="Valider la modification">
                    <?= $msg_compte_mail ?>
                </div>

            </form>

            <p class="titre-partie">Changement de mot de passe</p>
            <form action="" method="post" class="mdp initmarg row justify-content-center" onsubmit="return compteMdp(this);">

                <div class="champ">
                    <label for="mdp-actuel" class="form-label">Ancien mot de passe</label>
                    <input class="form-control" type="text" id="mdp-actuel" name="mdp_actuel" placeholder="Votre ancien mot de passe">
                    <?= $msg_compte_mdp_actuel ?>
                </div>
                
                <div class="champ">
                    <label for="mdp-nouveau" class="form-label">Nouveau mot de passe</label>
                    <input class="form-control" type="text" id="mdp-nouveau" name="mdp_nouveau" placeholder="Votre nouveau mot de passe">
                    <?= $msg_compte_mdp_nouveau ?>
                </div>

                <div class="champ">
                    <label for="mdp-confirme" class="form-label">Confirmation</label>
                    <input class="form-control" type="text" id="mdp-confirme" name="mdp_confirme" placeholder="Confirmez votre nouveau mot de passe">
                    <?= $msg_compte_mdp_confirme ?>
                    <p class="alerte-champ" id="alerte-mdp-confirme">Le mot de passe de confirmation doit être similaire au nouveau mot de passe</p>
                    <div class="block-conditions-mdp">
                        <p>Conditions :</p>
                        <ul>
                            <li class="mdp-nbr-caractere">Entre 8 et 20 caractères</li>
                            <li class="mdp-maj">Une majuscule</li>
                            <li class="mdp-min">Une minuscule</li>
                            <li class="mdp-chiffre">Un chiffre</li>
                            <li class="mdp-caractere-special">Un caractère spécial</li>
                        </ul>
                    </div>
                </div>

                <div class="submit">
                    <input type="submit" class="btn btn-success" name ="valide_mdp" value="Valider les modifications">
                </div>
            </form>
        </div>

        <div class="block-serveur">

            <p class="titre-partie">Mes serveurs</p>
<?php
            if($PDO_liste_serveur->rowcount()){
?>
                <form action="" method="post" class="choix-serveur initmarg row justify-content-center">
                    <div class="champ">
                        <label for="choix-serveur" class="form-label">Serveur principal</label>
                        <select name="choix_serveur" id="choix-serveur" class="form-select">
<?php
                        while($liste_serveur = $PDO_liste_serveur->fetch(PDO::FETCH_ASSOC)){
?>
                            <option <?= ($liste_serveur['principal'] == 1)?"selected":"" ?>  value="<?= $liste_serveur['id_serveur'] ?>"> <?= $liste_serveur['nom_serveur'] ?> </option>
<?php
                        }
?>
                        </select>
                    </div>

                    <div class="submit">
                        <input type="submit" class="btn btn-success" name ="valide_principal" value="Changer">
                    </div>
                </form>

                <div class="accordion" id="accordeon-serveur">
<?php
                    $i = 0;
                    while($info_serveur = $PDO_info_serveur->fetch(PDO::FETCH_ASSOC)){

                        // Récupération nombre de produit de l'utilisateur pour affichage
                        $PDO_somme_article_creature = $pdo->query("SELECT COUNT(*) AS total_creature FROM creature WHERE id_serveur = ".$info_serveur["id_serveur"]." AND id_utilisateur = ".$_SESSION['utilisateur']['id_utilisateur']."");
                        $PDO_somme_article_selle = $pdo->query("SELECT COUNT(*) AS total_selle FROM selle WHERE id_serveur = ".$info_serveur["id_serveur"]." AND id_utilisateur = ".$_SESSION['utilisateur']['id_utilisateur']."");
                        $PDO_somme_article_arme = $pdo->query("SELECT COUNT(*) AS total_arme FROM arme WHERE id_serveur = ".$info_serveur["id_serveur"]." AND id_utilisateur = ".$_SESSION['utilisateur']['id_utilisateur']."");
                        $PDO_somme_article_armure = $pdo->query("SELECT COUNT(*) AS total_armure FROM armure WHERE id_serveur = ".$info_serveur["id_serveur"]." AND id_utilisateur = ".$_SESSION['utilisateur']['id_utilisateur']."");

                        $somme_article_creature = $PDO_somme_article_creature->fetch(PDO::FETCH_ASSOC);
                        $somme_article_selle = $PDO_somme_article_selle->fetch(PDO::FETCH_ASSOC);
                        $somme_article_arme = $PDO_somme_article_arme->fetch(PDO::FETCH_ASSOC);
                        $somme_article_armure = $PDO_somme_article_armure->fetch(PDO::FETCH_ASSOC);

                        $total_produit = $somme_article_creature['total_creature'] + $somme_article_selle['total_selle'] + $somme_article_arme['total_arme'] + $somme_article_armure['total_armure'];

                        $i++;
?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="head<?= $i ?>">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>" aria-expanded="true" aria-controls="collapse<?= $i ?>">
                                    <div class="nom-serveur">
                                        <p> <?= $info_serveur["nom_serveur"] ?> </p>
                                    </div>
                                    <div class="info-serveur">
                                        <p> Nombre de produit : <?= $total_produit ?> </p>
                                    </div>
<?php
                                    // Au regarde de la requête SQL le premier de la liste est le serveur principal
                                    if($i == 1){
?>
                                    <div class="serveur-principal">
                                        <p> Serveur principal</p>
                                    </div>
<?php
                                    }
?>
                                </button>
                            </h2>
                            <div id="collapse<?= $i ?>" class="accordion-collapse collapse <?= ($i++ == 1 )?"show":"" ?>" aria-labelledby="heading<?= $i ?>" data-bs-parent="#accordeon-serveur">
                                <form action="<?= URL ?>gestion/compte.php?id=<?= $info_serveur["id_info_serveur"] ?>" method="post" class="accordion-body">

                                    <div class="champ">
                                        <label for="nom-perso-<?= $i ?>" class="form-label">Nom du joueur</label>
                                        <input class="form-control" type="text" id="nom-perso-<?= $i ?>" name="nom_perso" placeholder="Joueur" value="<?= (isset($_POST["nom_perso"]))?$_POST["nom_perso"]:$info_serveur["nom_perso"] ?>">
                                        <?= $msg_compte_nom_perso ?>
                                    </div>

                                    <div class="champ">
                                        <label for="nom-discord-<?= $i ?>" class="form-label">Nom Discord</label>
                                        <input class="form-control" type="text" id="nom-discord-<?= $i ?>" name="nom_discord" placeholder="Discord" value="<?= (isset($_POST["nom_discord"]))?$_POST["nom_discord"]:$info_serveur["nom_discord"] ?>">
                                        <?= $msg_compte_nom_discord ?>
                                    </div>

                                    <div class="champ supprimer">
                                        <label for="supprimer<?= $i ?>" class="form-label">Supprimer le serveur</label>
                                        <input class="form-control supprimer" type="text" id="supprimer-<?= $i ?>" name="supprimer" placeholder="Écrivez le nom du serveur">
                                        <?= $msg_compte_supprimer ?>
                                    </div>

                                    <div class="submit">
                                        <input type="submit" class="btn btn-success" name ="valide_serveur" value="Valider les modifications">
                                    </div>

                                </form>
                            </div>
                        </div>
<?php
                    }
?>
                </div>                
<?php
            }else{
?>
            <p>Vous n'avez pas encore sélectionner de serveur</p>
<?php
            }
?>

        </div>
    </div>



<?php
include '../inc/footer.inc.php';
?>