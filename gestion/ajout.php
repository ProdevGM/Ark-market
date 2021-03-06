<?php

include '../inc/init.inc.php';
include '../inc/fonction.inc.php';


// Contrôle connexion utilisateur
if(!user_is_connect()){
    header('location:http://ark-market/index.php');
}

// Taille des champs (prends en compte le contrôle php et le maxlength)
$taille_description = 500;
$taille_monnaie = 20;
$taille_prix = 6;
$taille_caracteristique = 7;
$taille_niveau = 3;
$taille_armure = 4;
$taille_degat = 4;

// Déclaration des variables
$tab_datalist = ''; 
$type_produit = ''; // Permettra d'afficher les différents champs du formulaire en fonction du type d'objet créé

// Messages d'alerte pour contrôle php et js
$message_chiffre = "En chiffre s\'il vous plait !"; // Message concernant la vérification numérique
$message_sexe = "De quel sexe est votre créature ?"; // Message concernant le sexe de la créature (obligatoire)
$message_type = "Selle ou plan ? Les deux peut-être ?"; // Message concernant le choix du type de la selle (obligatoire)
$message_limite_caractere = "devraient suffir"; //Message concernant le nombre de caractères
$message_obligatoire = "Sans cette info, je n'achète pas !" // Message concernant le caractère obligatoire du champ

?>
<script>
    var messageChiffre = '<?= $message_chiffre ?>', 
        messageSexe = '<?= $message_sexe ?>', 
        messageType = '<?= $message_type ?>'; 
</script>
<?php


// Information pour js
$info_type_js = '';

$nom = '';
/* $type = ''; */ // Array
$typeBDD = 'objet'; // Format stockable dans la BDD
$qualite = '';
$degat = '';
$armure = '';
$taille = '';
$froid = '';
$chaleur = '';
$durabilite = '';
$prix1 = '';
$prix2 = '';
$description = '';

$sexe = ''; // Array
$sexeBDD = ''; // Format stockable dans la BDD
$niveau = '';
$vie = '';
$energie = '';
$oxygene = '';
$nourriture = '';
$poids = '';
$attaque = '';
$vitesse = '';

$produit_plateforme_seule = true; // Variable de gestion d'affichage input type radio pour la partie selle. Init à true pour display=none au chargement initiale de la page
$produit_plateforme = '';

if(isset($_GET["type"])){
    // Utile pour les contrôle en js (cf ark_market.js)
    $info_type_js = $_GET['type'];
?>
    <script>
        var infoTypeJs = '<?= $info_type_js ?>';
    </script>
<?php

}



/* ********************* */
/* ********************* */
// SUPPRESSION D'UN PRODUIT
/* ********************* */
/* ********************* */

if(isset($_GET['action']) && $_GET['action'] == 'supprimer'
                          && isset($_GET['type'])
                          && isset($_GET['id'])){

    // Contrôle sur le propriétaire de ce produit
    $PDO_info = $pdo->query("SELECT id_utilisateur FROM ".$_GET['type']." WHERE id_".$_GET['type']." = ".$_GET['id']."");

    if($PDO_info->rowcount() == 1){ // Contrôle si le produit existe bien

        $info = $PDO_info->fetch(PDO::FETCH_ASSOC);

        if($info['id_utilisateur'] == $_SESSION['utilisateur']['id_utilisateur']){ // Contrôle si le produit appartient bien à l'utilisateur

            $delete = $pdo->exec("DELETE FROM ".$_GET['type']." WHERE id_".$_GET['type']." = ".$_GET['id']."");

            if($delete)
                header('location:http://ark-market/gestion/gestion.php?type='.$_GET['type'].'&notif=supTrue');
            else
                header('location:http://ark-market/gestion/gestion.php?type='.$_GET['type'].'&notif=supFalse');

        }else
            header('location:http://ark-market/index.php');
    }else
        header('location:http://ark-market/index.php');
}





/* ************************************************* */
/* ************************************************* */
// RECUPERATION DES DONNÉES DE LA BDD POUR MODIFICATION
/* ************************************************* */
/* ************************************************* */

if(isset($_GET['action']) && $_GET['action'] == 'modification'
                            && isset($_GET['type'])
                            && isset($_GET['id'])){

    $PDO_recuperation = $pdo->query("SELECT * FROM ".$_GET['type']." WHERE id_".$_GET['type']." = ".$_GET['id']."");
    $recuperation = $PDO_recuperation->fetch(PDO::FETCH_ASSOC);


    if($PDO_recuperation->rowcount() == 1 && $recuperation['id_utilisateur'] == $_SESSION['utilisateur']['id_utilisateur']){

        $nom = ucfirst($recuperation['nom']);
        $description = $recuperation['description'];
        $monnaie = $recuperation['monnaie'];
        $prix1 = $recuperation['prix1'];

        if($_GET['type'] == 'creature'){


            $sexe = preg_split('/ /', $recuperation['sexe']); // Stockage des données sous forme de tabeau
            $niveau = $recuperation['niveau'];
            $vie = $recuperation['vie'];
            $energie = $recuperation['energie'];
            $oxygene = $recuperation['oxygène'];
            $nourriture = $recuperation['nourriture'];
            $poids = $recuperation['poids'];
            $attaque = $recuperation['attaque'];
            $vitesse = $recuperation['vitesse'];

        }elseif($_GET['type'] == 'selle' || $_GET['type'] == 'arme' || $_GET['type'] == 'armure'){

            $qualite = $recuperation['qualité'];
            $prix2 = $recuperation['prix2'];
            $typeBDD = $recuperation['type'];

            if( $_GET['type'] == 'selle'){

                // Permettra de gérer l'affichage de l'input type radio concernant la taille. Cas possible : Créatures peuvant porter selle et plateforme (affichage pour choix de l'utilsiateur), pas de plateforme (non affichage), uniquement plateforme (non affichage mais checked sur input correspondant)
                $produit_plateforme_seule = strpos($plateforme_seule, $recuperation['nom']); // cf init.inc.php pour varialbe plateforme_seul. String regroupant les créatures ne pouvant porter que des plateforme
                $produit_plateforme = strpos($plateforme, $recuperation['nom']); // cf init.inc.php pour varialbe plateforme. String regroupant les créatures portant  que des plateforme

                $taille = $recuperation['taille'];
                $armure = $recuperation['armure'];

            }elseif($_GET['type'] == 'arme')
                $degat = $recuperation['dégât'];
            elseif($_GET['type'] == 'armure'){
                $armure = $recuperation['armure'];
                $froid = $recuperation['froid'];
                $chaleur = $recuperation['chaleur'];
                $durabilite = $recuperation['durabilité'];                
            }
        }

    }else
        header('location:http://ark-market/index.php');

}




/* ************************************************************************ */
/* ************************************************************************ */
// RECUPÉRATION ET CONTRÔLE DES DONNÉES PUIS CREATION / MODIFICATION DE LA BDD
/* ************************************************************************ */
/* ************************************************************************ */

if(isset($_GET['action']) && ($_GET['action'] == 'creation' || $_GET['action'] == 'modification')
                          && (isset($_POST['creer']) || isset($_POST['modifier']))){

    // Traitement des données communes à tous les articles                        
    if(isset($_GET['type']) && isset($_POST['nom'])
                            && isset($_POST['description'])
                            && isset($_POST['monnaie'])
                            && isset($_POST['prix1'])){
                               
        $id_serveur = $_SESSION['serveur']['id_serveur'];
        $id_utilisateur = $_SESSION['utilisateur']['id_utilisateur'];
        
        $nom = ucfirst(trim($_POST['nom']));
        $description = trim($_POST['description']);
        $monnaie = trim($_POST['monnaie']);
        

        /* ***************************** */
        // Contrôle des variables d'entrées
        /* ***************************** */

        // CONTRÔLE NOM : Obligatoire et doit être dans un des tableaux (cf init.inc.php)
        $i = '';
        $tab = 'tab_'.$_GET['type'];
                
        foreach($$tab AS $indice => $valeur){
            if(array_search($nom, $$tab[$indice]) !== false)
                $i++;   
        }

        // Plateforme n'est pas transmis via l'URL mais doit être parcouru
        foreach($tab_plateforme AS $indice => $valeur){
            if(array_search($nom, $tab_plateforme[$indice]) !== false)
                $i++;   
        }

        if($i == 0)
            $msg .= '<p class="alerte-msg"> On choisit dans la liste svp </p>';

        // CONTRÔLE DESCRIPTION : Limite du nombre de caractères
        if(iconv_strlen($description) > $taille_description){
            $msg .= "<p class=\"alerte-msg\"> $taille_description caractères $message_limite_caractere</p>";
        }

        // CONTRÔLE MONNAIE : Limite nombre de caractères
        if(iconv_strlen($monnaie) > $taille_monnaie){
            $msg .= "<p class=\"alerte-msg\">  $taille_monnaie caractères $message_limite_caractere </p>";
        }


        // Traitement des données propre aux créatures
        if($_GET['type'] == 'creature' && isset($_POST['vie'])
                                        && isset($_POST['energie'])
                                        && isset($_POST['oxygene'])
                                        && isset($_POST['nourriture'])
                                        && isset($_POST['poids'])
                                        && isset($_POST['attaque'])
                                        && isset($_POST['vitesse'])
                                        && isset($_POST['niveau'])){                                           

            $categorie = rechercheCategorie($tab_creature, $nom);
            $vie = trim($_POST['vie']);
            $energie = trim($_POST['energie']);
            $oxygene = trim($_POST['oxygene']);
            $nourriture = trim($_POST['nourriture']);
            $poids = trim($_POST['poids']);
            $attaque = trim($_POST['attaque']);
            $vitesse = trim($_POST['vitesse']);
            $niveau = trim($_POST['niveau']);

            // Si absence de prix définie par l'utilisateur
            if(empty($_POST['prix1'])){
                $prix1 = 'A négocier';
                $monnaie = '';
            }else
                $prix1 = trim($_POST['prix1']);


            /* ***************************** */
            // Contrôle des variables d'entrées
            /* ***************************** */

            // CONTRÔLE PRIX1 : Limite nombre de caractères et numérique sauf si champs vide ("à négocier")
            if(iconv_strlen($prix1) > $taille_prix)
                $msg .= "<p class=\"alerte-msg\"> $taille_prix chiffres $message_limite_caractere</p>";

            if(!empty($_POST['prix1']) && !is_numeric($prix1))
                $msg .= "<p class=\"alerte-msg\"> $message_chiffre </p>";

            // CONTRÔLE CARACTERISTIQUES : Limite nombre de caractères et si numériques
/*             if(empty($_POST['vie']) || empty($_POST['energie'])      // Si ne peut être vide
                                    || empty($_POST['oxygene'])
                                    || empty($_POST['nourriture'])
                                    || empty($_POST['poids'])
                                    || empty($_POST['attaque'])
                                    || empty($_POST['vitesse']))
                $msg .= "<p class=\"alerte-msg\"> Toutes les infos ne seraient pas du luxe... </p>";
            else */if((!empty($vie) && !is_numeric($vie)) || (!empty($energie) && !is_numeric($energie))
                                                          || (!empty($oxygene) && !is_numeric($oxygene))
                                                          || (!empty($nourriture) && !is_numeric($nourriture))
                                                          || (!empty($poids) && !is_numeric($poids))
                                                          || (!empty($attaque) && !is_numeric($attaque))
                                                          || (!empty($vitesse) && !is_numeric($vitesse)))
                $msg .= "<p class=\"alerte-msg\"> $message_chiffre </p>";
    
            if(iconv_strlen($vie) > $taille_caracteristique || iconv_strlen($energie) > $taille_caracteristique
                                                            || iconv_strlen($oxygene) > $taille_caracteristique
                                                            || iconv_strlen($nourriture) > $taille_caracteristique
                                                            || iconv_strlen($poids) > $taille_caracteristique
                                                            || iconv_strlen($attaque) > $taille_caracteristique
                                                            || iconv_strlen($vitesse) > $taille_caracteristique)
                $msg .= "<p class=\"alerte-msg\"> $taille_caracteristique cractères $message_limite_caractere</p>";

            // CONTRÔLE NIVEAU : Obligatoire, limite de nombre de caractères, numérique
            if(empty($niveau))
                $msg .= "<p class=\"alerte-msg\"> $message_obligatoire </p>";
            elseif(!is_numeric($niveau))
                $msg .= "<p class=\"alerte-msg\"> $message_chiffre </p>";
            elseif(iconv_strlen($niveau) > $taille_niveau)
                $msg .= "<p class=\"alerte-msg\"> $taille_niveau chiffres $message_limite_caractere </p>";

            // CONTRÔLE SEXE : Obligatoire et contrôle si les données réçus correspondent aux 3 valeurs possible
            if(!empty($_POST['sexe'])){

                $sexe = $_POST['sexe'];

                if(rechercheTab($sexe, $tab_sexe)) // $tab_sexe se trouve dans init.inc.php
                        $msg .= "<p class=\"alerte-msg\"> Petit malin... Sélectionnes un des sexes proposés initialement </p>";

                // Préparation format pour stockage dans la BDD
                foreach($_POST['sexe'] AS $valeur){
                    $sexeBDD .= $valeur . ' ';
                }
                $sexeBDD = trim($sexeBDD);

            }else
                $msg .= "<p class=\"alerte-msg\"> $message_sexe </p>";


            if(empty($msg)){

                if(isset($_POST['creer'])){
                    $creation = $pdo->prepare("INSERT INTO creature (id_creature, nom, categorie, sexe, niveau, vie, energie, oxygène, nourriture, poids, attaque, vitesse, prix1, monnaie, description, date_creation, id_serveur, id_utilisateur)
                                                        VALUES (NULL, :nom, :categorie, :sexe, :niveau, :vie, :energie, :oxygene, :nourriture, :poids, :attaque, :vitesse, :prix1, :monnaie, :description, NOW(), $id_serveur, $id_utilisateur)");
                }elseif(isset($_POST['modifier'])){
                    $creation = $pdo->prepare("UPDATE creature SET nom = :nom, categorie = :categorie, sexe = :sexe, niveau = :niveau, vie = :vie, energie = :energie, oxygène = :oxygene, nourriture = :nourriture, 
                                                                    poids = :poids, attaque = :attaque, vitesse = :vitesse, prix1 = :prix1, monnaie = :monnaie, description = :description
                                                                    WHERE id_creature = ".$_GET['id']."");
                }

                $creation->bindParam(':nom', $nom, PDO::PARAM_STR);
                $creation->bindParam(':categorie', $categorie, PDO::PARAM_STR);
                $creation->bindParam(':sexe', $sexeBDD, PDO::PARAM_STR);
                $creation->bindParam(':niveau', $niveau, PDO::PARAM_STR);
                $creation->bindParam(':vie', $vie, PDO::PARAM_STR);
                $creation->bindParam(':energie', $energie, PDO::PARAM_STR);
                $creation->bindParam(':oxygene', $oxygene, PDO::PARAM_STR);
                $creation->bindParam(':nourriture', $nourriture, PDO::PARAM_STR);
                $creation->bindParam(':poids', $poids, PDO::PARAM_STR);
                $creation->bindParam(':attaque', $attaque, PDO::PARAM_STR);
                $creation->bindParam(':vitesse', $vitesse, PDO::PARAM_STR);
                $creation->bindParam(':prix1', $prix1, PDO::PARAM_STR);
                $creation->bindParam(':monnaie', $monnaie, PDO::PARAM_STR);
                $creation->bindParam(':description', $description, PDO::PARAM_STR);
            }


        // Traitement des données des selles, armes et armures
        }elseif(($_GET['type'] == 'selle' || $_GET['type'] == 'arme' || $_GET['type'] == 'armure') 
                                          && isset($_POST['prix2'])
                                          && isset($_POST['qualite'])){

            $qualite = trim($_POST['qualite']);

            // Prise en compte de tous les combinaisons concernant les 2 prix possible (objet et plan)
            if($typeBDD == 'deux'){
                if(empty($_POST['prix1']) && empty($_POST['prix2'])){
                    $prix1 = 'A négocier';
                    $prix2 = 'A négocier';
                    $monnaie = '';
                }elseif(empty($_POST['prix1'])){
                    $prix1 = 'A négocier';
                    $prix2 = trim($_POST['prix2']);
                }elseif(empty($_POST['prix2'])){
                    $prix2 = 'A négocier';
                    $prix1 = trim($_POST['prix1']);
                }else{
                    $prix1 = trim($_POST['prix1']);
                    $prix2 = trim($_POST['prix2']);
                }
            }elseif($typeBDD == 'objet'){
                $prix2 = '';
                if(empty($_POST['prix1'])){
                    $prix1 = 'A négocier';
                    $monnaie = '';
                }else
                    $prix1 = trim($_POST['prix1']);
            }elseif($typeBDD == 'plan'){
                $prix1 = '';
                if(empty($_POST['prix2'])){
                    $prix2 = 'A négocier';
                    $monnaie = '';
                }else
                    $prix2 = trim($_POST['prix2']);
            }


            /* ***************************** */
            // Contrôle des variables d'entrées
            /* ***************************** */

            // CONTRÔLE PRIX1 et PRIX2 : Limite nombre de caractères et numérique sauf si champs vide ("à négocier")
            if(iconv_strlen($prix1) > $taille_prix || iconv_strlen($prix2) > $taille_prix){
                if($typeBDD == "deux" && !empty($_POST['prix1']) && !empty($_POST['prix2']))
                    $msg .= "<p class=\"alerte-msg\"> $taille_prix chiffres $message_limite_caractere </p>";
                else
                    $msg .= "<p class=\"alerte-msg\"> $taille_prix chiffres $message_limite_caractere </p>";
            }

            if((!empty($_POST['prix1']) && !is_numeric($prix1)) || (!empty($_POST['prix2']) && !is_numeric($prix2)))
                $msg .= "<p class=\"alerte-msg\"> $message_chiffre </p>";

            // CONTRÔLE QUALITE : Conforme à l'enum de la BDD
            if(array_search($qualite, $tab_qualite) === false)
                $msg .= "<p class=\"alerte-msg\"> Petit malin... Sélectionnes une des qualités proposées initialement </p>";

            // CONTRÔLE TYPE : Obligatoire et conforme aux 2 valeurs possible
            if(!empty($_POST['type'])){

                $type = $_POST['type'];

                if(rechercheTab($type, $tab_type)) // $tab_type se trouve dans init.inc.php
                    $msg .= "<p class=\"alerte-msg\"> Petit malin... Sélectionnes un des choix proposés initialement </p>";


                // Préparation format pour stockage dans la BDD
                if(!empty($type[1]))
                    $typeBDD = 'deux';
                else
                    $typeBDD = $type[0];
            }else
                $msg .= "<p class=\"alerte-msg\"> $message_type </p>";


            // Traitement des données propre aux selles
            if($_GET['type'] == 'selle' && isset($_POST['armure'])
                                        && isset($_POST['taille'])){
                                         
                // Permettra de gérer l'affichage de l'input type radio concernant la taille. Cas possible : Créatures peuvant porter selle et plateforme (affichage pour choix de l'utilsiateur), pas de plateforme (non affichage), uniquement plateforme (non affichage mais checked sur input correspondant)
                $produit_plateforme_seule = strpos($plateforme_seule, $_POST['nom']); // cf init.inc.php pour varialbe plateforme_seul. String regroupant les créatures ne pouvant porter que des plateformes
                $produit_plateforme = strpos($plateforme, $_POST['nom']); // cf init.inc.php pour varialbe plateforme. String regroupant les créatures pouvant porté des plateformes

                $categorie = rechercheCategorie($tab_selle, $nom);
                $armure = trim($_POST['armure']);
                $taille = trim($_POST['taille']);


                /* ***************************** */
                // Contrôle des variables d'entrées
                /* ***************************** */

                // CONTRÔLE ARMURE : Obligatoire, limite de nombre de caractères et numérique
                analyse($armure, $taille_armure, 'd\'armure');

                // CONTRÔLE TAILLE : Conforme à l'enum de la BDD
                if($taille != 'selle' && $taille != 'plateforme')
                    $msg .= "<p class=\"alerte-msg\"> Petit malin... Sélectionnes un des équipements proposés initialement </p>";                


                if(empty($msg)){

                    if(isset($_POST['creer'])){
                        $creation = $pdo->prepare("INSERT INTO selle (id_selle, nom, type, taille, categorie, qualité, armure, prix1, prix2 , monnaie, description, date_creation, id_serveur, id_utilisateur)
                                                            VALUES (NULL, :nom, :type, :taille, :categorie, :qualite, :armure, :prix1, :prix2, :monnaie, :description, NOW(), $id_serveur, $id_utilisateur)");
                    }elseif(isset($_POST['modifier'])){
                        $creation = $pdo->prepare("UPDATE selle SET nom = :nom, type = :type, taille = :taille, categorie = :categorie, qualité = :qualite, armure = :armure, 
                                                                    prix1 = :prix1, prix2 = :prix2, monnaie = :monnaie, description = :description
                                                                    WHERE id_selle = ".$_GET['id']."");
                    }
    
                    $creation->bindParam(':nom', $nom, PDO::PARAM_STR);
                    $creation->bindParam(':type', $typeBDD, PDO::PARAM_STR);
                    $creation->bindParam(':taille', $taille, PDO::PARAM_STR);
                    $creation->bindParam(':categorie', $categorie, PDO::PARAM_STR);
                    $creation->bindParam(':qualite', $qualite, PDO::PARAM_STR);
                    $creation->bindParam(':armure', $armure, PDO::PARAM_STR);
                    $creation->bindParam(':prix1', $prix1, PDO::PARAM_STR);
                    $creation->bindParam(':prix2', $prix2, PDO::PARAM_STR);
                    $creation->bindParam(':monnaie', $monnaie, PDO::PARAM_STR);
                    $creation->bindParam(':description', $description, PDO::PARAM_STR);
                }


            // Traitement des données propre aux armes
            }elseif($_GET['type'] == 'arme' && isset($_POST['degat'])){

                $categorie = rechercheCategorie($tab_arme, $nom);
                $degat = trim($_POST['degat']);


                /* ***************************** */
                // Contrôle des variables d'entrées
                /* ***************************** */

                // CONTRÔLE DÉGÂT : Obligatoire, limite de nombre de caractères et numérique
                analyse($degat, $taille_degat, 'des dégâts');


                if(empty($msg)){

                    if(isset($_POST['creer'])){
                        $creation = $pdo->prepare("INSERT INTO arme (id_arme, nom, type, categorie, qualité, dégât, prix1, prix2 , monnaie, description, date_creation, id_serveur, id_utilisateur)
                                                            VALUES (NULL, :nom, :type, :categorie, :qualite, :degat, :prix1, :prix2, :monnaie, :description, NOW(), $id_serveur, $id_utilisateur)");
                    }elseif(isset($_POST['modifier'])){
                        $creation = $pdo->prepare("UPDATE arme SET nom = :nom, type = :type, categorie = :categorie, qualité = :qualite, dégât = :degat, 
                                                                    prix1 = :prix1, prix2 = :prix2, monnaie = :monnaie, description = :description
                                                                    WHERE id_arme = ".$_GET['id']."");
                    }

                    $creation->bindParam(':nom', $nom, PDO::PARAM_STR);
                    $creation->bindParam(':type', $typeBDD, PDO::PARAM_STR);
                    $creation->bindParam(':categorie', $categorie, PDO::PARAM_STR);
                    $creation->bindParam(':qualite', $qualite, PDO::PARAM_STR);
                    $creation->bindParam(':degat', $degat, PDO::PARAM_STR);
                    $creation->bindParam(':prix1', $prix1, PDO::PARAM_STR);
                    $creation->bindParam(':prix2', $prix2, PDO::PARAM_STR);
                    $creation->bindParam(':monnaie', $monnaie, PDO::PARAM_STR);
                    $creation->bindParam(':description', $description, PDO::PARAM_STR);
                }


            // Traitement des données propre aux armures
            }elseif($_GET['type'] == 'armure' && isset($_POST['armure'])
                                            && isset($_POST['froid'])
                                            && isset($_POST['chaleur'])
                                            && isset($_POST['durabilite'])){

                $categorie = rechercheCategorie($tab_armure, $nom);
                $armure = trim($_POST['armure']);
                $froid = trim($_POST['froid']);
                $chaleur = trim($_POST['chaleur']);
                $durabilite = trim($_POST['durabilite']);


                /* ***************************** */
                // Contrôle des variables d'entrées
                /* ***************************** */

                // CONTRÔLE ARMURE : Obligatoire, limite de nombre de caractères et numérique
                analyse($armure, $taille_armure, 'd\'armure');

                // CONTRÔLE RÉSISTANCE FROID : Obligatoire, limite de nombre de caractères et numérique
                analyse($froid, $taille_armure, 'de résistance au froid');

                // CONTRÔLE RÉSISTANCE CHALEUR : Obligatoire, limite de nombre de caractères et numérique
                analyse($chaleur, $taille_armure, 'de résistance à la chaleur');
        
                // CONTRÔLE DURABILITÉ : Obligatoire, limite de nombre de caractères et numérique
                analyse($durabilite, $taille_armure, 'de durabilité');


                if(empty($msg)){

                    if(isset($_POST['creer'])){
                        $creation = $pdo->prepare("INSERT INTO armure (id_armure, nom, type, categorie, qualité, armure, froid, chaleur, durabilité, prix1, prix2 , monnaie, description, date_creation, id_serveur, id_utilisateur)
                                                            VALUES (NULL, :nom, :type, :categorie, :qualite, :armure, :froid, :chaleur, :durabilite, :prix1, :prix2, :monnaie, :description, NOW(), $id_serveur, $id_utilisateur)");
                    }elseif(isset($_POST['modifier'])){
                        $creation = $pdo->prepare("UPDATE armure SET nom = :nom, type = :type, categorie = :categorie, qualité = :qualite, armure = :armure, froid = :froid, chaleur = :chaleur, 
                                                                        durabilité = :durabilite, prix1 = :prix1, prix2 = :prix2, monnaie = :monnaie, description = :description
                                                                        WHERE id_armure = ".$_GET['id']."");
                    }

                    $creation->bindParam(':nom', $nom, PDO::PARAM_STR);
                    $creation->bindParam(':type', $typeBDD, PDO::PARAM_STR);
                    $creation->bindParam(':categorie', $categorie, PDO::PARAM_STR);
                    $creation->bindParam(':qualite', $qualite, PDO::PARAM_STR);
                    $creation->bindParam(':armure', $armure, PDO::PARAM_STR);
                    $creation->bindParam(':froid', $froid, PDO::PARAM_STR);
                    $creation->bindParam(':chaleur', $chaleur, PDO::PARAM_STR);
                    $creation->bindParam(':durabilite', $durabilite, PDO::PARAM_STR);
                    $creation->bindParam(':prix1', $prix1, PDO::PARAM_STR);
                    $creation->bindParam(':prix2', $prix2, PDO::PARAM_STR);
                    $creation->bindParam(':monnaie', $monnaie, PDO::PARAM_STR);
                    $creation->bindParam(':description', $description, PDO::PARAM_STR);
                }
            }
        }

        if(empty($msg)){
            $creation->execute();
            if($creation)
                header('location:http://ark-market/gestion/gestion.php?notif=creaModifTrue');
            else
                header('location:http://ark-market/gestion/gestion.php?notif=creaModifFalse');
        }
    }
}




// Préparation de la datalist en fonction du type de produit créé
if(!empty($_GET['type'])){
    switch($_GET['type']){
        case 'creature' :
            $tab_datalist = array_merge($tab_creature['terrestre'], $tab_creature['volant'], $tab_creature['aquatique']);
            $type_produit = 'creature';
            break;
        case 'selle' :
            $tab_datalist = array_merge($tab_selle['terrestre'], $tab_selle['volant'], $tab_selle['aquatique'], $tab_plateforme['terrestre'], $tab_plateforme['volant'], $tab_plateforme['aquatique']);
            $type_produit = 'selle';
            break;
        case 'arme' :
            $tab_datalist = array_merge($tab_arme['outil'], $tab_arme['mélé'], $tab_arme['bouclier'], $tab_arme['jet'], $tab_arme['feu'], $tab_arme['accessoire'], $tab_arme['explosif'], $tab_arme['piège'], $tab_arme['tourelle']);
            $type_produit = 'arme';
            break;
        case 'armure' :
            $tab_datalist = array_merge($tab_armure['tissu'], $tab_armure['cuir'], $tab_armure['fourrure'], $tab_armure['désert'], $tab_armure['camouflage'], $tab_armure['chitine'], $tab_armure['métal'], $tab_armure['radiation'], $tab_armure['plongé'], $tab_armure['émeute'], $tab_armure['tek']);
            $type_produit = 'armure';
            break;
    }
}


include '../inc/header.inc.php';
include '../inc/nav_etale.inc.php';
?>

<main class="ajout">
    <div class="container">
        <div id="message"> <?= $msg ?> </div>
        <p class="gtitre text-center"><?= (isset($_GET['action']) && $_GET['action'] == 'creer')?'Nouveau produit':'Modification de produit' ?></p>
<?php
        if(isset($_GET['action']) && $_GET['action'] == 'creation'){ // Affichage uniquement en cas de création de produit. Pas nécessaire dans le cas d'une modification
?>
            <div class="block-type">
                <a href="<?= URL ?>gestion/ajout.php?action=creation&type=creature">Créature</a>
                <a href="<?= URL ?>gestion/ajout.php?action=creation&type=selle">Selle</a>
                <a href="<?= URL ?>gestion/ajout.php?action=creation&type=arme">Arme</a>
                <a href="<?= URL ?>gestion/ajout.php?action=creation&type=armure">Armure</a
                >
            </div>
<?php
        }
?>
        <form method="post" action="" onsubmit="return verif(this);">

            <div class="block-nom">
                <input type="text" name="nom" id="nom" placeholder="Nom" required list="list-produit" <?= (isset($_GET['action']) && $_GET['action'] == 'modification')?'readonly':'' ?> value="<?= $nom ?>">
                <datalist id="list-produit">
<?php
                    foreach($tab_datalist AS $produit){
?>
                        <option value="<?= $produit ?>">
<?php
                    }       
?> 
                </datalist>
            </div>


<?php       // Bloc pour les selles (selle ou plateforme)
            if($type_produit == 'selle'){
?>
                <div class="taille"  style="<?= ($produit_plateforme_seule || !$produit_plateforme)?'display: none;':'' ?>">
                    <input type="radio" name="taille" id="taille" class="selle" value="selle" <?=((isset($_POST['taille']) && $_POST['taille'] == 'selle') || (!isset($_POST['taille']) && $taille == 'selle'))?'checked':''?> > Selle
                    <input type="radio" name="taille" id="taille" class="plateforme" value="plateforme" <?= ((isset($_POST['taille']) && ($produit_plateforme_seule || $_POST['taille'] == 'plateforme')) || (!isset($_POST['taille']) && $taille == 'plateforme'))?'checked':'' ?>> Plateforme
                </div>
<?php
            }
?>


<?php       // Bloc plan, objet ou les deux pour tous les produits sauf les créatures
            if($type_produit != 'creature'){
?>
            <div class="block-type-vente">
                <input type="checkbox" name="type[]" id="objet" <?= ((isset($_POST['action']) && $_POST['action'] == 'creation') || (isset($typeBDD) && $typeBDD == 'objet' || $typeBDD == 'deux'))?'checked':'' ?> value="objet"> Objet
                <input type="checkbox" name="type[]" id="plan" <?= (isset($typeBDD) && $typeBDD == 'plan' || $typeBDD == 'deux')?'checked':'' ?> value="plan"> Plan
            </div>
<?php
            }
?>


<?php       // Bloc caractéristique des créatures
            if($type_produit == 'creature'){
?>
                <div class="block-caract-creature">
                    <div class="ss-caract">
                        <img src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
                        <input type="text" name="vie" id="vie" placeholder="Vie" maxlength="<?= $taille_caracteristique ?>" value="<?= $vie ?>">
                    </div>
                    <div class="ss-caract">
                        <img src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
                        <input type="text" name="energie" id="energie" placeholder="Énergie" maxlength="<?= $taille_caracteristique ?>" value="<?= $energie ?>">
                    </div>
                    <div class="ss-caract">
                        <img src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
                        <input type="text" name="oxygene" id="oxygene" placeholder="Oxygène" maxlength="<?= $taille_caracteristique ?>" value="<?= $oxygene ?>">
                    </div>
                    <div class="ss-caract">
                        <img src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
                        <input type="text" name="nourriture" id="nourriture" placeholder="Nourriture" maxlength="<?= $taille_caracteristique ?>" value="<?= $nourriture ?>">
                    </div>
                    <div class="ss-caract">
                        <img src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
                        <input type="text" name="poids" id="poids" placeholder="Poids" maxlength="<?= $taille_caracteristique ?>" value="<?= $poids ?>">
                    </div>
                    <div class="ss-caract">
                        <img src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
                        <input type="text" name="attaque" id="attaque" placeholder="Attaque" maxlength="<?= $taille_caracteristique ?>" value="<?= $attaque ?>">
                    </div>
                    <div class="ss-caract">
                        <img src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
                        <input type="text" name="vitesse" id="vitesse" placeholder="Vitesse" maxlength="<?= $taille_caracteristique ?>" value="<?= $vitesse ?>">
                    </div>
                </div>
<?php
            }
?>


<?php       // Bloc caractéristique des objets (armure, protection et qualité)
            if($type_produit != 'creature'){
?>
                <div class="block-caract-objet">

                    <div class="qualite">
                        <label for="qualite">Qualité :</label>
                        <select name="qualite" id="qualite">
<?php
                            foreach($tab_qualite AS $valeur){ //$tab_qualite se trouve dans init.inc.php
?>
                                <option value="<?= $valeur ?>" <?= ((isset($_POST['qualite']) && $_POST['qualite'] == $valeur) || (!isset($_POST['qualite']) && $qualite == $valeur))?"selected":'' ?>><?= $valeur ?></option>
<?php
                            }
?>
                        </select>
                    </div>

<?php               // Bloc armure pour les selles et les armures
                    if($type_produit == 'selle' || $type_produit == 'armure'){
?>
                        <div class="armure">
                            <label for="armure">Armure :</label>
                            <input type="text" id="armure" name="armure" placeholder="Armure" required maxlength="<?= $taille_armure ?>" value="<?= $armure ?>">
                        </div>

<?php                   // Bloc résistance chaleur, froid et durabilité pour les armures
                        if($type_produit == 'armure'){
?>
                            <div class="block-res">
                                <div class="res-froid">
                                    <label for="froid">Résistance au froid :</label>
                                    <input type="text" id="froid" name="froid" placeholder="Résistance au froid" required maxlength="<?= $taille_armure ?>" value="<?= $froid ?>">
                                </div>
                                <div class="res-chaleur">
                                    <label for="chaleur">Résistance à la chaleur :</label>
                                    <input type="text" id="chaleur" name="chaleur" placeholder="Résistance à la chaleur" required maxlength="<?= $taille_armure ?>" value="<?= $chaleur ?>">
                                </div>
                                <div class="durabilite">
                                    <label for="durabilite">Durabilité :</label>
                                    <input type="text" id="durabilite" name="durabilite" placeholder="Durabilite" required maxlength="<?= $taille_armure ?>" value="<?= $durabilite ?>">
                                </div>
                            </div>
<?php
                        }
?>


<?php               // Bloc dégât pour les amres
                    }elseif($type_produit == 'arme'){
?>
                    <div class="degat">
                        <label for="degat">Dégâts :</label>
                        <input type="text" id="degat" name="degat" placeholder="Dégâts" required maxlength="<?= $taille_degat ?>" value="<?= $degat ?>">
                    </div>
<?php
                    }
?>
                    <div>

                    </div>

                </div>
<?php
            }
?>

<?php // Bloc précision des créatures (niveau et sexe)
            if($type_produit == 'creature'){
?>
                <div class="block-precision">
                    <div class="niveau">
                        <label for="niveau">Niveau</label>
                        <input type="text" id="niveau" name="niveau" placeholder="Niveau" required maxlength="<?= $taille_niveau ?>" value="<?= $niveau ?>">
                    </div>
                    <div class="sexe">
                        <label>Sexe</label>
<?php
                        foreach($tab_sexe AS $valeur){
?>
                            <input type="checkbox" name="sexe[]" <?= (isset($sexe[0]) && array_search($valeur, $sexe) !== false )?'checked':'' ?> value="<?= $valeur ?>"> <?= $valeur ?>
<?php
                        }
?>
                    </div>
                </div>
<?php
            }
?>
            <div class="description">
                <label for="description">Description</label>
                <textarea name="description" id="description" maxlength="<?= $taille_description ?>"> <?= $description ?></textarea>
            </div>

            <div class="block-prix">
                <div class="prix1" style="<?= ($typeBDD == 'plan')?'display: none':'' ?>">
                    <label for="prix1">Prix de <?= ($type_produit == 'creature')?'la créature':'l\'objet' ?></label>
                    <input type="text" id="prix1" name="prix1" maxlength="<?= $taille_prix ?>" value="<?= (is_numeric($prix1))?$prix1:'' ?>">
                </div>
<?php
                if($type_produit != 'creature'){
?>
                    <div class="prix2" style="<?= ($typeBDD == 'deux' || $typeBDD == 'plan')?'':'display: none;' ?>">
                        <label for="prix2">Prix du plan</label>
                        <input type="text" id="prix2" name="prix2" maxlength="<?= $taille_prix ?>" value="<?= (is_numeric($prix2))?$prix2:'' ?>">
                    </div>
<?php
                }
?>
                <div class="monnaie">
                    <label for="monnaie">Monnaie</label>
                    <input type="text" id="monnaie" name="monnaie" maxlength="<?= $taille_monnaie ?>" value="<?php if(!empty($monnaie)) echo $monnaie; elseif(!empty($_SESSION['serveur']['monnaie'])) echo $_SESSION['serveur']['monnaie']; ?>">
                </div>

            </div>

            <div class="validation">
<?php
                if($_GET['action'] == 'creation'){
?>
                    <input type="submit" name="creer" value="Créer mon produit">
<?php
                }else{
?>
                    <input type="submit" name="modifier" value="Modifier mon produit">
<?php
                }
?>
            </div>
        </form>

    </div>

<?php
include '../inc/footer.inc.php';
?>