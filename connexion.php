<?php

include 'inc/init.inc.php';
include 'inc/fonction.inc.php';

//Déclaration de variable
$mail = '';

$mdp = '';
$mdp_confirmation = '';



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
                                             && isset($_POST['mdp_confirmation'])){

        $mail = trim($_POST['mail']);
        $mdp = trim($_POST['mdp']);
        $mdp_confirmation = trim($_POST['mdp_confirmation']);
    /* 
        // Condition pour le champ mdp : Au moins une minuscule, une majuscule, un chiffre, un caractère spécial et longueur entre 10 et 20 caractère
        $verif_mdp = preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{10,20}$#', $mdp);

        // Vérification de la confirmation de mot de passe
        if($mdp !== $mdp_confirmation)
            $msg .= '<div class="alert alert-danger mt-3">Les mots de passe sont différents</div>';

        // Vérification validité mdp
        if(!$verif_mdp || empty($mdp)){
            $msg .= '<div class="alert alert-danger mt-3">Mot de passe invalide (au moins une majuscule, une minuscule, un caractère spécial, un chiffre et doit contenir entre 10 et 20 caractères </div>';
        }
    */
        // Si pas d'erreur
        if(empty($msg)){

            // Vérification si existance du mail
            $verif_mail = $pdo->prepare("SELECT * FROM utilisateur WHERE mail = :mail");
            $verif_mail->bindParam(":mail", $mail, PDO::PARAM_STR);
            $verif_mail->execute();
            
            if($verif_mail->rowCount() > 0) {
                $msg .= '<div class="alert alert-danger mt-3">Adresse e-mail déjà utilisée</div>';
            }

            if(empty($msg)) {

                $creation = $pdo->prepare("INSERT INTO utilisateur (id_utilisateur, mail, mdp, date_creation) VALUES (NULL, :mail, :mdp, CURDATE())");

                // Cryptage du mdp
                $mdp = password_hash($mdp, PASSWORD_DEFAULT);
                $creation->bindParam(':mail', $mail, PDO::PARAM_STR);
                $creation->bindParam(':mdp', $mdp, PDO::PARAM_STR);
                $creation->execute();
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
            <div class="titre">
                <p class="text-center gtitre">Inscription / <a href="<?= URL.'connexion.php?action=connexion' ?>">Connexion</a></p>
                <p class="lead"><?= $msg ?></p>
            </div>

            <div class="formulaire-inscription col-md-8 col-lg-7 col-xl-6 mx-auto">
                <form action="" method="POST" class="row">
                    <div class="champ row initmarg">
                        <label class="col-form-label col-sm-3 col-md-4" for="mail">Adresse e-mail</label>
                        <div class="col-sm-9 col-md-8">
                            <input class="form-control col-sm-8" type="mail" name="mail" id="mail" value="<?= $mail ?>" placeholder="raptor@ark.com">
                        </div>
                    </div>
                    <div class="champ row initmarg">
                        <label class="col-form-label col-sm-3 col-md-4" for="mdp">Mot de passe</label>
                        <div class="col-sm-9 col-md-8">
                            <input class="form-control col-sm-8" type="password" name="mdp" id="mdp" placeholder="azerty">
                        </div>
                    </div>
                    <div class="champ row initmarg">
                        <label class="col-form-label col-sm-3 col-md-4" for="mdp-confirmation">Confirmez le mot de passe</label>
                        <div class="col-sm-9 col-md-8">
                            <input class="form-control col-sm-8" type="password" name="mdp_confirmation" id="mdp-confirmation" placeholder="azerty">
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
            <div class="titre">
                <p class="text-center gtitre" >Connexion / <a href="<?= URL.'connexion.php?action=inscription' ?>">Inscription</a></p> 
                <p class="lead"><?= $msg; ?></p>
            </div>

            <div class="formulaire-connexion col-md-8 col-lg-7 col-xl-6 mx-auto">
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