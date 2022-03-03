<?php

include 'inc/init.inc.php';
include 'inc/fonction.inc.php';

//Déclaration de variable
$mail = '';

$mdp = '';
$mdp_confirme = '';

// Variables de message pour le contrôle des variables $_POST
$msg_connexion_mail = "";
$msg_connexion_mdp_nouveau = "";
$msg_compte_mdp_confirme = "";



// si l'utilisateur est connecté, on le renvoie sur la page d'accueil
if(user_is_connect()){
	header('location:index.php');
}



if(isset($_GET['action'])){

    // Déconnexion et renvoie sur la page principale
    if($_GET['action'] == 'deconnexion'){
        session_destroy();
/*         setcookie('PHPSESSID'); // Suppression du cookie lié à la session ATTENTION pour le moment création à chaque script*/
	    header('location:index.php');

    // Inscription dans la BDD
    }elseif($_GET['action'] == 'inscription' && isset($_POST['creation']) 
                                             && isset($_POST['mail']) 
                                             && isset($_POST['mdp']) 
                                             && isset($_POST['mdp_confirme'])){

        $mail = trim($_POST['mail']);
        $mdp_nouveau = trim($_POST['mdp']);
        $mdp_confirme = trim($_POST['mdp_confirme']);

        
        /* ***************************** */
        // Contrôle des variables d'entrées
        /* ***************************** */

        // CONTRÔLE MAIL : Validité format mail
        $verif_mail = preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/", $mail);
        if(empty($mail) || !$verif_mail){
            $controle_variables = false;
            $msg_connexion_mail = "<p class=\"text-danger\"> Une adresse mail valide, c'est mieux ! </p>";
            $annonce_top = "<p class=\"alert alert-danger\"> Au moinds l'un des champs est incorrect </p>";
        }        

        // CONTRÔLE MDP : Une minuscule, une majuscule, un chiffre, un caractère spécial et entre 8 et 20 caractères. mdp_nouveau et mdp_confirme doivent être identique
        $verif_mdp = preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,20}$#', $mdp_nouveau);
        if(empty($verif_mdp) || !$verif_mdp){
            $controle_variables = false;
            $msg_connexion_mdp_nouveau = "<p class=\"text-danger\"> Veuillez remplir toutes les conditions </p>";
            $annonce_top = "<p class=\"alert alert-danger\"> Au moinds l'un des champs est incorrect </p>";
        }

        if($mdp_nouveau !== $mdp_confirme){
            $controle_variables = false;
            $msg_compte_mdp_confirme = "<p class=\"text-danger\"> Le mot de passe de confirmation doit être similaire au nouveau mot de passe </p>";
            $annonce_top = "<p class=\"alert alert-danger\"> Au moinds l'un des champs est incorrect </p>";
        } 


        if($controle_variables){

            // Vérification si existance du mail
            $verif_mail = $pdo->prepare("SELECT * FROM utilisateur WHERE mail = :mail");
            $verif_mail->bindParam(":mail", $mail, PDO::PARAM_STR);
            $verif_mail->execute();
            
            if($verif_mail->rowCount() > 0) 
                $annonce_top .= '<div class="alert alert-danger mt-3">Adresse e-mail déjà utilisée</div>';

            if($controle_variables) {

                $creation = $pdo->prepare("INSERT INTO utilisateur (id_utilisateur, mail, mdp, date_creation) VALUES (NULL, :mail, :mdp, CURDATE())");

                // Cryptage du mdp
                $mdp = password_hash($mdp, PASSWORD_DEFAULT);
                $creation->bindParam(':mail', $mail, PDO::PARAM_STR);
                $creation->bindParam(':mdp', $mdp, PDO::PARAM_STR);
                $retour = $creation->execute();

                if($retour)
                    header('location:http://ark-market/gestion/compte.php');
                else
                    $annonce_top = "<p class=\"alert alert-danger\"> Erreur lors de l'inscription. Veuillez réessayer ultérieurement </p>";
            }
        }

    // Connexion
    }elseif($_GET['action'] == 'connexion' && isset($_POST['connexion'])
                                           && isset($_POST['mail'])
                                           && isset($_POST['mdp'])){

        $mail = trim($_POST['mail']);
        $mdp = trim($_POST['mdp']);

        // Récupération des données correspondant à ce mail
        $verif_connexion = $pdo->prepare("SELECT * FROM utilisateur WHERE mail = :mail");
        $verif_connexion->bindParam(":mail", $mail, PDO::PARAM_STR);
        $verif_connexion->execute();
        
        if($verif_connexion->rowCount() > 0) {
            // Compte existe
            $infos = $verif_connexion->fetch(PDO::FETCH_ASSOC);
            
            // Comparaison du mot de passe
            if(password_verify($mdp, $infos['mdp'])) {

                // Mot de passe correct, stockage des informations dans session			
                foreach($infos AS $indice => $valeur) {
                    if($indice != 'mdp') {
                        $_SESSION['utilisateur'][$indice] = $valeur;
                    }				
                }
                $_SESSION['serveur']['monnaie'] = "po"; // En attendant l'enregistrement de la monnaie dans la page du compte utilisateur
                $_SESSION['serveur']['id_serveur'] = "1"; // En attendant 

                header('location:index.php');
            } else {
                $msg .= '<div class="alert alert-danger mt-3">Erreur sur le pseudo et / ou le mot de passe !</div>';
            }
        } else {
            $msg .= '<div class="alert alert-danger mt-3">Erreur sur le pseudo et / ou le mot de passe !</div>';	
        }
    }
}

include 'inc/header.inc.php';
?>

<main class="connexion">
    <div class="container">


        <!-- ------------------------------ -->
        <!-- ----- PARTIE INSCRIPTION ----- -->
        <!-- ------------------------------ -->
<?php
        if(isset($_GET["action"]) && $_GET['action'] == 'inscription'){
?>
            <div class="block-titre mb-4">
                <p class="text-center">S'inscrire, <a href="<?= URL.'connexion.php?action=connexion' ?>" class="titre-second">ou Se connecter</a></p>
                <p class="lead"><?= $msg ?></p>
            </div>

            <div class="block-inscription col-md-8 col-lg-7 col-xl-6 mx-auto">
                <form action="" method="POST" class="row" onsubmit="return connexionInscription(this);">
                    <div class="champ row initmarg">
                        <label class="col-form-label col-sm-3 col-md-4" for="mail">Adresse e-mail</label>
                        <div id="champ-mail" class="col-sm-9 col-md-8">
                            <input class="form-control col-sm-8" type="mail" name="mail" id="mail" value="<?= $mail ?>" placeholder="raptor@ark.com">
                            <?= $msg_connexion_mail ?>
                        </div>
                    </div>
                    <div class="champ row initmarg">
                        <label class="col-form-label col-sm-3 col-md-4" for="mdp-nouveau">Mot de passe</label>
                        <div class="col-sm-9 col-md-8">
                            <input class="form-control col-sm-8" type="password" name="mdp" id="mdp-nouveau" placeholder="azerty">
                            <?= $msg_connexion_mdp_nouveau ?>
                        </div>
                    </div>
                    <div class="champ row initmarg">
                        <label class="col-form-label col-sm-3 col-md-4" for="mdp-confirme">Confirmez le mot de passe</label>
                        <div id="block-mdp-confirme" class="col-sm-9 col-md-8">
                            <input class="form-control col-sm-8" type="password" name="mdp_confirme" id="mdp-confirme" placeholder="azerty">
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
                    <div class="text-center mt-4">
                        <button type="submit" name="creation" class="btn btn-success rounded-0">Créer mon compte</button>
                    </div>
                </form>
            </div>


        <!-- ---------------------------- -->
        <!-- ----- PARTIE CONNEXION ----- -->
        <!-- ---------------------------- -->
<?php
        // Page relative à la connexion de son compte
        }else{
?>
            <div class="block-titre mb-4">
                <p class="text-center" >Se connecter, <a href="<?= URL.'connexion.php?action=inscription' ?>" class="titre-second">ou S'inscrire</a></p> 
            </div>

            <div class="block-connexion col-md-8 col-lg-7 col-xl-6 mx-auto">
                <form method="POST" class="row">
                    <div class="champ row initmarg">
                        <label class="col-form-label col-sm-3 col-md-4" for="mail">Mail</label>
                        <div class="col-sm-9 col-md-8">
                            <input class="form-control col-sm-8" type="mail" name="mail" id="mail" value="<?= $mail ?>" placeholder="raptor@ark.com">
                        </div>
                    </div>
                    <div class="champ row initmarg">
                        <label class="col-form-label col-sm-3 col-md-4" for="mdp">Mot de passe</label>
                        <div class="col-sm-9 col-md-8">
                            <input class="form-control col-sm-8" type="password" autocomplete="off" name="mdp" id="mdp" placeholder="Votre mot de passe">
                        </div>
                    </div>
                    <div class="text-center mt-4 position-relative">
                        <button type="submit" name="connexion" class="btn btn-success rounded-0">Connexion</button>
                        <a href="<?= URL ?>connexion.php?oubli=1" class="mdp-oublie text-center position-absolute end-0 bottom-0 d-block d-sm-inline-block">Mot de passe oublié ?</a>
                    </div>
                </form>	
            </div>		
<?php
        }
?>
    </div>

<?php
include 'inc/footer.inc.php';
?>