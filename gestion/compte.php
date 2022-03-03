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

// Variables de la sélection d'un serveur
$select_nom_serveur = "";
$select_nom_perso = "";
$select_nom_discord = "";

$supprimer_js = ""; // Action de suppression en cours. Pour permettre le show() sur le block de suppression du serveur
$ajout_js = true; // Action d'ajout de serveur en cours. Pour permettre le show() sur le block d'ajout de serveur
$action = false; // Résultat de l'action sur la BDD
$tab_datalist_serveur = array();

// Variables de message pour le contrôle des variables $_POST
$pre_annonce_top = "";
$annonce_top = "";
$msg_compte_mail = "";
$msg_compte_mdp_actuel = "";
$msg_compte_mdp_nouveau = "";
$msg_compte_mdp_confirme = "";
$msg_compte_nom_perso = "";
$msg_compte_nom_discord = "";
$msg_compte_select_nom_serveur = "";
$msg_compte_select_nom_perso = "";
$msg_compte_select_nom_discord = "";
$msg_compte_supprimer = "";



/* ****************************************** */
// RECUPÉRATION DE LA LISTE DE TOUS LES SERVEURS
/* ****************************************** */

$PDO_liste_serveur = $pdo->query("SELECT nom_serveur FROM serveur");

while($liste_serveur = $PDO_liste_serveur->fetch(PDO::FETCH_ASSOC)){
    $tab_datalist_serveur[] = $liste_serveur["nom_serveur"];
}

 // Pour contrôle js du champ
$liste_serveur_js = "";
$liste_serveur_js .= implode(",", $tab_datalist_serveur);

?>
<script>
    var listeServeur = '<?= $liste_serveur_js ?>';
</script>
<?php




/* ************************************** */
/* ************************************** */
/* ************************************** */
// MODIFICATION DES DONNÉES DE L'UTILISATEUR
/* ************************************** */
/* ************************************** */
/* ************************************** */

if(isset($_POST["valide_compte"]) || isset($_POST["valide_mdp"])
                                  || isset($_POST["valide_principal"])
                                  || isset($_POST["valide_serveur"])
                                  || isset($_POST["valide_select_serveur"])
                                  || isset($_POST["valide_supprimer"])
                                  || isset($_GET["action"])){
            
              
    /* ***************** */
    /* ***************** */
    // MODIFICATION DU MAIL
    /* ***************** */
    /* ***************** */

    if(isset($_POST["valide_compte"]) && isset($_POST["mail"])){

        $mail = trim($_POST["mail"]);


        /* ***************************** */
        // Contrôle des variables d'entrées
        /* ***************************** */

        // CONTRÔLE MAIL : Validité format mail
        $verif_mail = preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/", $mail);
        if(empty($mail) || !$verif_mail){
            $controle_variables = false;
            $msg_compte_mail = "<p class=\"text-danger\"> Une adresse mail valide, c'est mieux ! </p>";
            $annonce_top = "<p class=\"alert alert-danger\"> Au moinds l'un des champs est incorrect </p>";
        }

        if($controle_variables){

            //Contrôle sur l'existance de ce mail
            $verif = $pdo->prepare("SELECT id_utilisateur FROM utilisateur WHERE mail = :mail");
            $verif->bindParam(':mail', $mail, PDO::PARAM_STR);
            $verif->execute();

            if($verif->rowcount()){

                $controle_variables = false;
                $msg_compte_mail = "<p class=\"text-danger\"> Cette adresse mail est déjà utilisée </p>";
                $annonce_top = "<p class=\"alert alert-danger\"> Au moinds l'un des champs est incorrect </p>";
            
            }else{

                $enregistrer = $pdo->prepare("UPDATE utilisateur SET mail = :mail WHERE id_utilisateur = ".$_SESSION['utilisateur']['id_utilisateur']."");
                $enregistrer->bindParam(':mail', $mail, PDO::PARAM_STR);
                
                $pre_annonce_top = "<p class=\"alert alert-success\"> Modification du mail effectué </p>";
            }
        }




    /* ************************* */
    /* ************************* */
    // MODIFICATION DU MOT DE PASSE
    /* ************************* */
    /* ************************* */

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
            $msg_compte_mdp_nouveau = "<p class=\"text-danger\"> Veuillez remplir toutes les conditions </p>";
            $annonce_top = "<p class=\"alert alert-danger\"> Au moinds l'un des champs est incorrect </p>";
        }

        if($mdp_nouveau !== $mdp_confirme){
            $controle_variables = false;
            $msg_compte_mdp_confirme = "<p class=\"text-danger\"> Le mot de passe de confirmation doit être similaire au nouveau mot de passe </p>";
            $annonce_top = "<p class=\"alert alert-danger\"> Au moinds l'un des champs est incorrect </p>";
        }    

        
        if($controle_variables){

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

                $pre_annonce_top = "<p class=\"alert alert-success\"> Modification du mot de passe effectué </p>";

            }else{
                $controle_variables = false;
                $msg_compte_mdp_actuel = "<p class=\"text-danger\"> Mot de passe incorrect </p>";
                $annonce_top = "<p class=\"alert alert-danger\"> Au moinds l'un des champs est incorrect </p>";
            }
        }




    /* ****************************** */    
    /* ****************************** */
    // MODIFICATION DU SERVEUR PRINCIPAL
    /* ****************************** */
    /* ****************************** */

    }elseif(isset($_POST["valide_principal"]) && isset($_POST["choix_serveur"])){

        $choix_serveur = trim($_POST["choix_serveur"]);


        /* ***************************** */
        // Contrôle de la variable d'entrée
        /* ***************************** */

        // CONTRÔLE $CHOIX_SERVEUR : Numérique
        if(!is_numeric($choix_serveur))
            header('location:http://ark-market/index.php');


        // Contrôle que l'utilisateur possède une entrée sur ce serveur
        $verif = $pdo->prepare("SELECT id_serveur FROM info_serveur WHERE id_info_serveur = :choix_serveur");
        $verif->bindParam(":choix_serveur", $choix_serveur, PDO::PARAM_STR);
        $verif->execute();

        $recup_id_serveur = $verif->fetch(PDO::FETCH_ASSOC);

        if($verif->rowcount()){
            
            // Passage à "0" de l'ancien serveur principal
            $enregistrer = $pdo->exec("UPDATE info_serveur SET principal = 0 WHERE principal = 1
                                                                            AND id_utilisateur = ".$_SESSION['utilisateur']['id_utilisateur']."");

            if($enregistrer !== false){

                $enregistrer = $pdo->prepare("UPDATE info_serveur SET principal = 1 WHERE id_info_serveur = :id_info_serveur 
                                                                                AND id_utilisateur = ".$_SESSION['utilisateur']['id_utilisateur']."");
                $enregistrer->bindParam(":id_info_serveur", $choix_serveur, PDO::PARAM_STR);
                
                $pre_annonce_top = "<p class=\"alert alert-success\"> Modification du serveur principal effectué </p>";

            }else
                $annonce_top = "<p class=\"alert alert-danger\"> Erreur lors de la modification. Veuillez réessayer ultérieurement </p>";
        }else
            header('location:http://ark-market/index.php');




    /* ************************** */
    /* ************************** */
    // AJOUT D'UN SERVEUR A SA LISTE
    /* ************************** */
    /* ************************** */

    }elseif(isset($_POST["valide_select_serveur"]) && isset($_POST["select_nom_serveur"]) 
                                                    && isset($_POST["select_nom_perso"])
                                                    && isset($_POST["select_nom_discord"])){

        $select_nom_serveur = $_POST["select_nom_serveur"];
        $select_nom_perso = $_POST["select_nom_perso"];
        $select_nom_discord = $_POST["select_nom_discord"];

        /* ***************************** */
        // Contrôle des variables d'entrées
        /* ***************************** */

        // Récupération de la liste des serveurs
        $PDO_liste_serveur = $pdo->query("SELECT nom_serveur FROM serveur");

        while($liste_serveur = $PDO_liste_serveur->fetch(PDO::FETCH_ASSOC)){
            $tab_datalist_serveur[] = $liste_serveur["nom_serveur"];
        }

        // CONTRÔLE NOM SERVEUR : Obligatoire et dans la liste proposé
        if(array_search($select_nom_serveur, $tab_datalist_serveur) === false){
            $controle_variables = false;
            $msg_compte_select_nom_serveur = "<p class=\"text-danger\"> On choisit dans la liste svp </p>";
        }

        // CONTRÔLE NOM PERSO : Oligatoire et entre 2 et 20 caractères
        if(empty($select_nom_perso) || strlen($select_nom_perso) < 2 || strlen($select_nom_perso) > 20){
            $controle_variables = false;
            $msg_compte_select_nom_perso = "<p class=\"text-danger\"> Entre 2 et 20 caractères </p>";
        }

        // CONTRÔLE NOM DISCORD : Entre 2 et 20 caractères
        if(!empty($select_nom_discord)){
            if(strlen($select_nom_discord) < 2 || strlen($select_nom_discord) > 20){
                $controle_variables = false;
                $msg_compte_select_nom_discord = "<p class=\"text-danger\"> Entre 2 et 20 caractères </p>";
            }
        }

        // CONTRÔLE ENTRÉE SERVEUR : Pas d'entrée serveur préexistante pour ce serveur  
        $PDO_verif = $pdo->query("SELECT i.id_info_serveur FROM info_serveur i, serveur s WHERE i.id_serveur = s.id_serveur
                                                                                        AND s.nom_serveur = '$select_nom_serveur'
                                                                                        AND i.id_utilisateur = ".$_SESSION["utilisateur"]["id_utilisateur"]."");

        if($PDO_verif->rowcount()){
            $controle_variables = false;
            $msg_compte_select_nom_serveur = "<p class=\"text-danger\"> Ce serveur est déjà dans votre liste ! </p>";
        }

        if($controle_variables){

            // Si absence d'information concernant un serveur dans la SESSION C'est qu'il n'y a pas de serveur dans la liste de l'utilisateur. Du coup la nouvelle entrée sera le serveur principal
            if(empty($_SESSION["serveur"]["id_serveur"])){
                $principal = 1;
            }else{
                $principal = 0;

            }
            $enregistrer = $pdo->prepare("INSERT INTO info_serveur (id_info_serveur, nom_perso, nom_discord, principal, id_utilisateur, date_creation, id_serveur)
                                                            VALUES (NULL, :nom_perso, :nom_discord, :principal, ".$_SESSION["utilisateur"]["id_utilisateur"].", NOW(), 
                                                                            (SELECT id_serveur FROM serveur WHERE nom_serveur = '$select_nom_serveur'))");

            $enregistrer->bindParam(":nom_perso", $select_nom_perso, PDO::PARAM_STR);
            $enregistrer->bindParam(":nom_discord", $select_nom_discord, PDO::PARAM_STR);
            $enregistrer->bindParam(":principal", $principal, PDO::PARAM_STR);

            $pre_annonce_top = "<p class=\"alert alert-success\"> Serveur ajouter à votre liste </p>";

        }else{
            $ajout_js = false;
?>
            <script>
                var ajout = '<?= $ajout_js ?>';
            </script>
<?php 
        }




    /* ********************************** */
    /* ********************************** */
    // MODIFICATION DES DONNÉES D'UN SERVEUR
    /* ********************************** */
    /* ********************************** */

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
            $msg_compte_nom_perso = "<p class=\"text-danger\"> Entre 2 et 20 caractères </p>";
            $annonce_top = "<p class=\"alert alert-danger\"> Au moinds l'un des champs est incorrect </p>";
        }

        // CONTRÔLE NOM DISCORD : Entre 2 et 20 caractères
        if(!empty($nom_discord)){
            if(strlen($nom_discord) < 2 || strlen($nom_discord) > 20){
                $controle_variables = false;
                $msg_compte_nom_discord = "<p class=\"text-danger\"> Entre 2 et 20 caractères </p>";
                $annonce_top = "<p class=\"alert alert-danger\"> Au moinds l'un des champs est incorrect </p>";
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

                $pre_annonce_top = "<p class=\"alert alert-success\"> Modification effectué </p>";

            }else
                header('location:http://ark-market/index.php');
        }




    /* ********************************** */
    /* ********************************** */
    // SUPPRESSION D'UNE ENTRÉE INFO_SERVEUR
    /* ********************************** */
    /* ********************************** */

    }elseif(isset($_POST["valide_supprimer"]) && isset($_POST["supprimer"])
                                              && isset($_GET['id'])){

        $nom_serveur = trim($_POST["supprimer"]);

        // Pour gérer le show() du block de suppression en js
        $supprimer_js = $_GET['id'];
?>
        <script>
            var supprimer = '<?= $supprimer_js ?>';
        </script>
<?php

        /* ***************************** */
        // Contrôle de la variable d'entrée
        /* ***************************** */

        // Contrôle que l'id soit bien numérique
        if(!is_numeric($_GET["id"]))
            header('location:http://ark-market/index.php');


        // Vérification que l'id corresponde à une entrée de l'utilisateur et récupération du nom du serveur
        $PDO_verif = $pdo->query("SELECT i.nom_perso, s.nom_serveur, i.principal FROM info_serveur i, serveur s WHERE i.id_serveur = s.id_serveur
                                                                            AND i.id_info_serveur = ".$_GET["id"]."
                                                                            AND i.id_utilisateur = ".$_SESSION["utilisateur"]["id_utilisateur"]."");
                                                                        
        if($PDO_verif->rowcount()){

            $verif = $PDO_verif->fetch(PDO::FETCH_ASSOC);

            // CONTRÔLE NOM SERVEUR : Identique à $_POST["supprimer"]                                                                  
            if($verif["nom_serveur"] != $nom_serveur){
                $controle_variables = false;
                $msg_compte_supprimer .= "<p class=\"text-danger\"> Le nom du serveur est incorrect (Respectez les majuscules) </p>";
                $annonce_top = "<p class=\"alert alert-danger\"> Au moinds l'un des champs est incorrect </p>";
            }

            if($controle_variables){

                // Si le serveur supprimé est le principal, on le remplace par le dernier ajouté, si il en reste encore
                if($verif["principal"]){

                    $PDO_dernier_serveur = $pdo->query("SELECT id_info_serveur FROM info_serveur WHERE id_utilisateur = ".$_SESSION["utilisateur"]["id_utilisateur"]."
                                                                                            AND id_info_serveur != ".$_GET["id"]."
                                                                                            ORDER BY date_creation DESC LIMIT 1");

                    if($PDO_dernier_serveur->rowcount()){
                        
                        $dernier_serveur = $PDO_dernier_serveur->fetch(PDO::FETCH_ASSOC);
                        $pdo->exec("UPDATE info_serveur SET principal = 1 WHERE id_info_serveur = ".$dernier_serveur["id_info_serveur"]."");

                    }
                }


                $enregistrer = $pdo->prepare("DELETE FROM info_serveur WHERE id_info_serveur = :id_info_serveur");
                $enregistrer->bindParam(":id_info_serveur", $_GET["id"], PDO::PARAM_STR);

                $pre_annonce_top = "<p class=\"alert alert-success\"> Serveur supprimé de votre liste </p>";         
            }
        }else   
            header('location:http://ark-market/index.php');




    /* ****************** */
    /* ****************** */
    // SUPPRESSION DU COMPTE
    /* ****************** */
    /* ****************** */

    }elseif(isset($_GET["action"]) && $_GET["action"] == "supprimer"){

        $supprimer_compte = $pdo->exec("DELETE FROM utilisateur WHERE id_utilisateur = ".$_SESSION["utilisateur"]["id_utilisateur"]."");
        session_destroy();
        header('location:http://ark-market/index.php');

    }else
        header('location:http://ark-market/index.php');




    /* ******************* */
    /* ******************* */
    // EXECUTION DES REQUÊTES
    /* ******************* */
    /* ******************* */

    if($controle_variables){

        $retour = $enregistrer->execute();

        if($retour){

            $annonce_top = $pre_annonce_top;
            $action = true;

            // Vide les champs d'ajout d'un serveur à la liste
            $select_nom_serveur = "";
            $select_nom_perso = "";
            $select_nom_discord = "";

            // Maj de l'ardresse mail dans la SESSION
            if(isset($_POST["valide_compte"]))
                $_SESSION["utilisateur"]["mail"] = $mail;

            // Maj du serveur principal
            if(isset($_POST["valide_principal"]))
                $_SESSION["serveur"]["id_serveur"] = $recup_id_serveur["id_serveur"];

        }else
            $annonce_top = "<p class=\"alert alert-danger\"> Erreur lors de la modification. Veuillez réessayer ultérieurement </p>";
    }
}


/* *************************************************** */
// RECUPÉRATION DE LA LISTE DES SERVEURS DE L'UTILISATEUR
/* *************************************************** */
$PDO_liste_serveur_utilisateur = $pdo->query("SELECT * FROM serveur s, info_serveur i WHERE s.id_serveur = i.id_serveur
                                                                        AND i.id_utilisateur = ".$_SESSION['utilisateur']['id_utilisateur']."");


/* ******************************************** */
// RECUPÉRATION DES INFOS SERVEUR DE L'UTILISATEUR
/* ******************************************** */
$PDO_info_serveur = $pdo->query("SELECT * FROM serveur s, info_serveur i
                                            WHERE s.id_serveur = i.id_serveur
                                            AND i.id_utilisateur = ".$_SESSION["utilisateur"]['id_utilisateur']."
                                            ORDER BY i.principal DESC, s.nom_serveur DESC");


include '../inc/header.inc.php';
include '../inc/nav_etale.inc.php';
?>

<main class="compte">
    <div class="container">
        <div class="block-formulaire row justify-content-center">
            <div class="col-lg-9 col-xl-8 col-xxl-7">

                <?= $annonce_top ?>

                <div class="block-profil">
                    
                    <!-- ----------------------- -->
                    <!-- ----- PARTIE MAIL ----- -->
                    <!-- ----------------------- -->
                    <div class="titre-partie">
                        <p class="text-center text-sm-start">Mon compte</p>
                    </div>

                    <form action="" method="post" class="initmarg row justify-content-center" onsubmit="return compteMail(this);">

                        <div class="champ row">
                            <label for="mail" class="col-form-label col-sm-3 col-md-4">Adresse mail</label>
                            <div id="champ-mail" class="col-sm-9 col-md-8">
                                <input type="text" id="mail" name="mail" class="form-control" placeholder="Mail" value="<?= $mail ?>">
                                <?= $msg_compte_mail ?>
                            </div>
                        </div>
                        <div class="submit">
                            <input type="submit" name ="valide_compte" class="btn btn-success rounded-0" value="Valider la modification ">
                        </div>

                    </form>


                    <!-- ---------------------- -->
                    <!-- ----- PARTIE MDP ----- -->
                    <!-- ---------------------- -->
                    <div class="titre-partie">
                        <p class="text-center text-sm-start">Changement de mot de passe</p>
                    </div>

                    <form action="" method="post" class="initmarg row justify-content-center" onsubmit="return compteMdp(this);">

                        <div class="champ row">
                            <label for="mdp-actuel" class="col-form-label col-sm-3 col-md-4">Ancien mot de passe</label>
                            <div class ="col-sm-9 col-md-8">
                                <input type="password" id="mdp-actuel" name="mdp_actuel" class="form-control" placeholder="Votre ancien mot de passe">
                                <?= $msg_compte_mdp_actuel ?>
                            </div>
                        </div>
                        
                        <div class="champ row">
                            <label for="mdp-nouveau" class="col-form-label col-sm-3 col-md-4">Nouveau mot de passe</label>
                            <div class ="col-sm-9 col-md-8">
                                <input type="password" id="mdp-nouveau" name="mdp_nouveau" class="form-control" placeholder="Votre nouveau mot de passe">
                                <?= $msg_compte_mdp_nouveau ?>
                            </div>
                        </div>

                        <div class="champ row">
                            <label for="mdp-confirme" class="col-form-label col-sm-3 col-md-4">Confirmation</label>
                            <div id="block-mdp-confirme" class ="col-sm-9 col-md-8">
                                <input type="password" id="mdp-confirme" name="mdp_confirme" class="form-control" placeholder="Confirmez votre nouveau mot de passe">
                                <?= $msg_compte_mdp_confirme ?>
                            </div>
                            <div class="d-none d-sm-block col-sm-3 col-md-4"></div>
                            <div id="block-conditions-mdp" class="mt-3 col-sm-9 col-md-6">
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
                            <input type="submit" name ="valide_mdp" class="btn btn-success rounded-0" value="Valider la modification">
                        </div>
                    </form>
                </div>

                <div class="block-serveur">

                    <div class="titre-partie row">
                        <p class="initpad text-center text-sm-start" >Mes serveurs</p>
                    </div>    


                    <!-- ------------------------------------------ -->
                    <!-- ----- PARTIE CHOIX SERVEUR PRINCIPAL ----- -->
                    <!-- ------------------------------------------ -->
<?php
                    if($PDO_liste_serveur_utilisateur->rowcount()){
?>
                        <form action="" method="post" class="initmarg mb-4 row justify-content-center">
                            <div class="champ row">
                                <label for="choix-serveur" class="col-form-label col-sm-3 col-md-4">Serveur principal</label>
                                <div class="col-sm-9 col-md-8">
                                    <select name="choix_serveur" id="choix-serveur" class="form-select">
<?php
                                    while($liste_serveur_utilisateur = $PDO_liste_serveur_utilisateur->fetch(PDO::FETCH_ASSOC)){
?>
                                        <option <?= ($liste_serveur_utilisateur['principal'] == 1)?"selected":"" ?>  value="<?= $liste_serveur_utilisateur['id_info_serveur'] ?>"> <?= $liste_serveur_utilisateur['nom_serveur'] ?> </option>
<?php
                                }
?>
                                    </select>
                                </div>
                            </div>

                            <div class="submit">
                                <input type="submit" name ="valide_principal" class="btn btn-success rounded-0" value="Valider la modification">
                            </div>
                        </form>
<?php
                    }
?>

                    <!-- ------------------------------------------------ -->
                    <!-- ----- PARTIE AJOUT D'UN SERVEUR A LA LISTE ----- -->
                    <!-- ------------------------------------------------ -->

                    <button class="btn btn-primary align-baseline w-auto mb-3" id="ajout-serveur">Ajouter un serveur</button>

                    <form action="" method="post" style="display:none;" class="form-ajout-serveur initmarg mt-3 row justify-content-center" onsubmit="return compteAjoutServeur(this);">
                        <div class="row">
                            <label for="select-nom-serveur" class="col-form-label col-sm-3 col-md-4">Sélectionner un serveur</label>
                            <div id="div-select-nom-serveur" class ="col-sm-9 col-md-8">
                                <input type="text" id="select-nom-serveur" name="select_nom_serveur" class="form-control" placeholder="Serveur" list="list-serveur" value="<?= $select_nom_serveur ?>">
                                <datalist id="list-serveur">
<?php
                                    foreach($tab_datalist_serveur AS $valeur){
?>
                                        <option value="<?= $valeur ?>">
<?php
                                    }       
?> 
                                </datalist>
                                <?= $msg_compte_select_nom_serveur ?>
                            </div>
                        </div>

                        <div class="champ row">
                            <label for="select-nom-perso" class="col-form-label col-sm-3 col-md-4">Nom du joueur</label>
                            <div id="select-nom-joueur" class ="col-sm-9 col-md-8">
                                <input type="text" id="select-nom-perso" name="select_nom_perso" class="form-control" placeholder="Joueur" value="<?= (isset($_POST["select_nom_perso"]))?$select_nom_perso:"" ?>">
                                <?= $msg_compte_select_nom_perso ?>
                            </div>
                        </div>

                        <div class="champ row">
                            <label for="select-nom-discord" class="col-form-label col-sm-3 col-md-4">Nom Discord</label>
                            <div id="select-nom-discord" class ="col-sm-9 col-md-8">
                                <input type="text" id="select-nom-discord" name="select_nom_discord" class="form-control" placeholder="Discord" value="<?= (isset($_POST["select_nom_discord"]))?$select_nom_discord:"" ?>">
                                <?= $msg_compte_select_nom_discord ?>
                            </div>
                        </div>

                        <div class="submit">
                            <input type="submit" name ="valide_select_serveur" class="btn btn-success rounded-0" value="Enregistrer ce serveur">
                        </div>
                    </form>
<?php
                    if($PDO_liste_serveur_utilisateur->rowcount()){
?>
                        <div class="accordion mt-3" id="accordeon-serveur">
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


                                <!-- -------------------------------------------------- -->
                                <!-- ----- PARTIE POUR CHAQUE SERVEUR DE LA LISTE ----- -->
                                <!-- -------------------------------------------------- -->

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading<?= $i ?>">
                                        <button type="button" id="boutton-<?= $info_serveur["id_info_serveur"] ?>" class="accordion-button <?= ($i != 1)?"collapsed ":""?>" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $info_serveur["id_info_serveur"] ?>" aria-expanded="true" aria-controls="collapse-<?= $info_serveur["id_info_serveur"] ?>">
                                            <div class="nom-serveur w-50">
                                                <p id="nom-serveur-<?= $i ?>"> <?= $info_serveur["nom_serveur"] ?> </p>
                                            </div>
                                            <div class="info-serveur d-none d-sm-inline-block">
                                                <p> Offre<?= ($total_produit == 0)?"":"s" ?> : <?= $total_produit ?> </p>
                                            </div>
<?php
                                            // Au regarde de la requête SQL le premier de la liste est le serveur principal
                                            if($i == 1){
?>
<!--                                            <div class="serveur-principal d-none d-sm-inline-block">
                                                    <p> Principal</p>
                                                </div> -->
<?php
                                            }
?>
                                        </button>
                                    </h2>
                                    <div id="collapse-<?= $info_serveur["id_info_serveur"] ?>" class="accordion-collapse collapse <?= ($i == 1 )?"show":"" ?>" aria-labelledby="heading<?= $i ?>" data-bs-parent="#accordeon-serveur">
                                        <div class="accordion-body">
                                            <form action="<?= URL ?>gestion/compte.php?id=<?= $info_serveur["id_info_serveur"] ?>" method="post" class="row justify-content-center" onsubmit="return compteServeur(this);">

                                                <div class="champ row">
                                                    <label for="nom-perso-<?= $i ?>" class="col-form-label col-sm-3 col-md-4">Nom du joueur</label>
                                                    <div id="div-nom-perso-<?= $i ?>" class="col-sm-9 col-md-8">
                                                        <input type="text" id="nom-perso-<?= $i ?>" name="nom_perso" class="form-control" placeholder="Joueur" value="<?= (isset($_POST["nom_perso"]) && !$action && (isset($_GET["id"]) && $_GET["id"] == $info_serveur["id_info_serveur"]))?$_POST["nom_perso"]:$info_serveur["nom_perso"] ?>">
                                                        <?= (isset($_GET["id"]) && $_GET["id"] == $info_serveur["id_info_serveur"])?$msg_compte_nom_perso:"" ?>
                                                    </div>
                                                </div>

                                                <div class="champ row"">
                                                    <label for="nom-discord-<?= $i ?>" class="col-form-label col-sm-3 col-md-4">Nom Discord</label>
                                                    <div id="div-non-discord-<?= $i ?>" class ="col-sm-9 col-md-8">
                                                        <input type="text" id="nom-discord-<?= $i ?>" name="nom_discord" class="form-control" placeholder="Discord" value="<?= (isset($_POST["nom_discord"]) && !$action && (isset($_GET["id"]) && $_GET["id"] == $info_serveur["id_info_serveur"]))?$_POST["nom_discord"]:$info_serveur["nom_discord"] ?>">
                                                        <?= (isset($_GET["id"]) && $_GET["id"] == $info_serveur["id_info_serveur"])?$msg_compte_nom_discord:"" ?>
                                                    </div>
                                                </div>

                                                <div class="submit">
                                                    <input type="submit" name ="valide_serveur" id="valide-serveur-<?= $i ?>" class="valide-serveur btn btn-success rounded-0" value="Valider les modifications">
                                                </div>

                                            </form>


                                            <!-- ----------------------------------------------------- -->
                                            <!-- ----- PARTIE SUPPRESSION DU SERVEUR DE LA LISTE ----- -->
                                            <!-- ----------------------------------------------------- -->   

                                            <div class="block-supprimer row justify-content-center">

                                                <button id="btn-supprimer-<?= $info_serveur["id_info_serveur"] ?>" class="btn-supprimer btn btn-danger w-auto rounded-0"> Supprimer ce serveur </button>

                                                <form action="<?= URL ?>gestion/compte.php?id=<?= $info_serveur["id_info_serveur"] ?>" method="post" id="form-supprimer-<?= $info_serveur["id_info_serveur"] ?>" class="initpad row justify-content-center" style="display:none;" onsubmit="return compteSupprimerServeur(this);">
                                                    <div class="avertissement alert alert-danger text-center">
                                                        <p>En poursuivant vous perdrez définitivement toutes vos offres sur ce serveur</p>
                                                    </div>

                                                    <div class="champ row">
                                                        <label for="supprimer-<?= $i ?>" class="col-form-label col-sm-3 col-md-4">Nom du serveur</label>
                                                        <div id="champ-supprimer-serveur-<?= $i ?>" class ="col-sm-9 col-md-8">
                                                            <input type="text" id="supprimer-<?= $i ?>" name="supprimer" class=" form-control" placeholder="Recopiez le nom du serveur">
                                                            <?= (isset($_GET["id"]) && $_GET["id"] == $info_serveur["id_info_serveur"])?$msg_compte_supprimer:"" ?>
                                                        </div>
                                                    </div>

                                                    <div class="submit text-center w-100 row justify-content-center ">
                                                        <div class="col-sm-3">
                                                            <button type="button" id="btn-annuler-<?= $info_serveur["id_info_serveur"] ?>" class="btn-annuler btn btn-success rounded-0 mb-3 mb-sm-0">Annuler</button>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="submit" id="valide-supprimer-<?= $i ?>" name ="valide_supprimer" class="valide-supprimer btn btn-danger rounded-0" value="Supprimer ce serveur">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
<?php
                            }
?>
                        </div>                
<?php
                    }else{
?>
                        <p class="alert alert-info"> Aucun serveur dans votre liste </p>
<?php
                    }
?>
                </div>


                <!-- ---------------------------------------- -->
                <!-- ----- PARTIE SUPPRESSION DU COMPTE ----- -->
                <!-- ---------------------------------------- -->  
                
                <div class="block-supprimer-compte mt-5">

                    <div class="text-end">
                        <button type="button" class="bouton-supprimer" id="bouton-supprimer-compte"> Supprimer mon compte </button>
                    </div>

                    <div id="confirmation-supprimer-compte" class="text-center" style="display:none;">
                        <p class="titre-partie fw-bold text-start">Supprimer mon compte</p>

                        <div class="avertissement alert alert-danger" role="alert">
                            <p class="fs-2">Attention !!!</p>
                            <p>La suppression du compte engendrera la suppression de vos serveurs et par conséquent de toutes vos offres</p>
                        </div>
                        <a href="<?= URL ?>gestion/compte.php?action=supprimer" class="confirmation-supprimer-compte btn btn-danger rounded-0">Supprimer mon compte</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php
include '../inc/footer.inc.php';
?>