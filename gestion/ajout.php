<?php

include '../inc/init.inc.php';
include '../inc/fonction.inc.php';


// Contrôle connexion utilisateur
if(!user_is_connect()){
    header('location:http://ark-market/index.php');
}


$tab_datalist = ''; 
$type_produit = ''; // Permettra d'afficher les différents champs du formulaire en fonction du type d'objet créé

$nom = '';
/* $type = ''; */ // Array
$typeBDD = 'objet'; // Format stockable dans la BDD
$qualite = '';
$degat = '';
$armure = '';
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

        if($info['id_utilisateur'] == $_SESSION['utilisateur']['id_utilisateur']){ // Contrôle si le produit appartient bien

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

            if( $_GET['type'] == 'selle')
                $armure = $recuperation['armure'];
            elseif($_GET['type'] == 'arme')
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
        
        $nom = ucfirst(trim($_POST['nom']));
        $description = trim($_POST['description']);
        $monnaie = trim($_POST['monnaie']);

        $id_serveur = $_SESSION['utilisateur']['id_serveur'];
        $id_utilisateur = $_SESSION['utilisateur']['id_utilisateur'];



        // Traitement des données propre aux créatures
        if($_GET['type'] == 'creature' && isset($_POST['vie'])
                                        && isset($_POST['energie'])
                                        && isset($_POST['oxygene'])
                                        && isset($_POST['nourriture'])
                                        && isset($_POST['poids'])
                                        && isset($_POST['attaque'])
                                        && isset($_POST['vitesse'])
                                        && isset($_POST['niveau'])
                                        && isset($_POST['sexe'])){


            // Si absence de prix définie par l'utilisateur
            if(empty($_POST['prix1'])){
                $prix1 = 'A négocier';
                $monnaie = '';
            }else
                $prix1 = trim($_POST['prix1']);
                                            

            $categorie = rechercheCategorie($tab_creature, $nom);
            $vie = trim($_POST['vie']);
            $energie = trim($_POST['energie']);
            $oxygene = trim($_POST['oxygene']);
            $nourriture = trim($_POST['nourriture']);
            $poids = trim($_POST['poids']);
            $attaque = trim($_POST['attaque']);
            $vitesse = trim($_POST['vitesse']);
            $niveau = trim($_POST['niveau']);
            $sexe = $_POST['sexe'];

            // Préparation format pour stockage dans la BDD
            foreach($_POST['sexe'] AS $valeur){
                $sexeBDD .= $valeur . ' ';
            }
            $sexeBDD = trim($sexeBDD);

            /* 
            CONTROLE DES VARIABLES D'ENTREES
            ...
            */

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
        }elseif(($_GET['type'] == 'selle' || $_GET['type'] == 'arme' 
                                            || $_GET['type'] == 'armure') 
                                            && isset($_POST['prix2'])
                                            && isset($_POST['qualite'])
                                            && isset($_POST['type'])){

            $qualite = trim($_POST['qualite']);
            $type = $_POST['type'];

            // Préparation format pour stockage dans la BDD
            if(!empty($type[1]))
                $typeBDD = 'deux';
            else
                $typeBDD = $type[0];


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


            // Traitement des données propre aux selles
            if($_GET['type'] == 'selle' && isset($_POST['armure'])){

                $categorie = rechercheCategorie($tab_selle, $nom);
                $armure = trim($_POST['armure']);

                /* 
                CONTROLE DES VARIABLES D'ENTREES
                ...
                */

                if(empty($msg)){

                    if(isset($_POST['creer'])){
                        $creation = $pdo->prepare("INSERT INTO selle (id_selle, nom, type, categorie, qualité, armure, prix1, prix2 , monnaie, description, date_creation, id_serveur, id_utilisateur)
                                                            VALUES (NULL, :nom, :type, :categorie, :qualite, :armure, :prix1, :prix2, :monnaie, :description, NOW(), $id_serveur, $id_utilisateur)");
                    }elseif(isset($_POST['modifier'])){
                        $creation = $pdo->prepare("UPDATE selle SET nom = :nom, type = :type, categorie = :categorie, qualité = :qualite, armure = :armure, 
                                                                    prix1 = :prix1, prix2 = :prix2, monnaie = :monnaie, description = :description
                                                                    WHERE id_selle = ".$_GET['id']."");
                    }
    
                    $creation->bindParam(':nom', $nom, PDO::PARAM_STR);
                    $creation->bindParam(':type', $typeBDD, PDO::PARAM_STR);
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

                /* 
                CONTROLE DES VARIABLES D'ENTREES
                ...
                */

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

                /* 
                CONTROLE DES VARIABLES D'ENTREES
                ...
                */

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
        $creation->execute();

        if($creation)
            header('location:http://ark-market/gestion/gestion.php?notif=creaModifTrue');
        else
            header('location:http://ark-market/gestion/gestion.php?notif=creaModifFalse');
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
            $tab_datalist = array_merge($tab_selle['terrestre'], $tab_selle['volant'], $tab_selle['aquatique']);
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
include '../inc/nav.inc.php';
?>

<main class="ajout">
    <div class="container">

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
        <form method="post" action="">

            <div class="block-nom">
                <input type="text" name="nom" placeholder="Nom" list="list-produit" value="<?= $nom ?>">
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
                <div class="taille" style="display: none;">
                    <input type="radio" name="taille" id="taille" value="selle"> Selle
                    <input type="radio" name="taille" id="taille" value="plateforme"> Plateforme
                </div>
<?php
            }
?>


<?php       // Bloc plan, objet ou les deux pour tous les produits sauf les créatures
            if($type_produit != 'creature'){
?>
            <div class="block-type-vente">
                <input type="checkbox" name="type[]" id="objet" <?= (isset($_POST['action']) && $_POST['action'] == 'creation')?'checked':'' ?> <?= (isset($typeBDD) && $typeBDD == 'objet' || $typeBDD == 'deux')?'checked':'' ?> value="objet"> Objet
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
                        <input type="text" name="vie" placeholder="Vie" value="<?= $vie ?>">
                    </div>
                    <div class="ss-caract">
                        <img src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
                        <input type="text" name="energie" placeholder="Énergie" value="<?= $energie ?>">
                    </div>
                    <div class="ss-caract">
                        <img src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
                        <input type="text" name="oxygene" placeholder="Oxygène" value="<?= $oxygene ?>">
                    </div>
                    <div class="ss-caract">
                        <img src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
                        <input type="text" name="nourriture" placeholder="Nourriture" value="<?= $nourriture ?>">
                    </div>
                    <div class="ss-caract">
                        <img src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
                        <input type="text" name="poids" placeholder="Poids" value="<?= $poids ?>">
                    </div>
                    <div class="ss-caract">
                        <img src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
                        <input type="text" name="attaque" placeholder="Attaque" value="<?= $attaque ?>">
                    </div>
                    <div class="ss-caract">
                        <img src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
                        <input type="text" name="vitesse" placeholder="Vitesse" value="<?= $vitesse ?>">
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
                            <option value="Commun" <?= ($qualite == 'commun')?"checked":'' ?>>Commun</option>
                            <option value="Inhabituel" <?= ($qualite == 'Inhabituel')?"checked":'' ?>>Inhabituel</option>
                            <option value="Rare" <?= ($qualite == 'Rare')?"checked":'' ?>>Rare</option>
                            <option value="Épique" <?= ($qualite == 'Épique')?"checked":'' ?>>Épique</option>
                            <option value="Légendaire" <?= ($qualite == 'Légendaire')?"checked":'' ?>>Légendaire</option>
                            <option value="Mythique" <?= ($qualite == 'Mythique')?"checked":'' ?>>Mythique</option>
                        </select>
                    </div>

<?php               // Bloc armure pour les selles et les armures
                    if($type_produit == 'selle' || $type_produit == 'armure'){
?>
                        <div class="armure">
                            <label for="armure">Armure :</label>
                            <input type="text" id="armure" name="armure" placeholder="Armure" value="<?= $armure ?>">
                        </div>

<?php                   // Bloc résistance chaleur, froid et durabilité pour les armures
                        if($type_produit == 'armure'){
?>
                            <div class="block-res">
                                <div class="res-froid">
                                    <label for="froid">Résistance au froid :</label>
                                    <input type="text" id="froid" name="froid" placeholder="Résistance au froid" value="<?= $froid ?>">
                                </div>
                                <div class="res-chaleur">
                                    <label for="chaleur">Résistance à la chaleur :</label>
                                    <input type="text" id="chaleur" name="chaleur" placeholder="Résistance à la chaleur" value="<?= $chaleur ?>">
                                </div>
                                <div class="durabilite">
                                    <label for="durabilite">Durabilité :</label>
                                    <input type="text" id="durabilite" name="durabilite" placeholder="Durabilite" value="<?= $durabilite ?>">
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
                        <input type="text" id="degat" name="degat" placeholder="Dégâts" value="<?= $degat ?>">
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
                        <input type="text" id="niveau" name="niveau" placeholder="Niveau" value="<?= $niveau ?>">
                    </div>
                    <div class="sexe">
                        <label>Sexe</label>
                        <input type="checkbox" name="sexe[]" <?= (isset($sexe[0]) && array_search('mâle', $sexe) !== false )?'checked':'' ?> value="mâle"> Mâle
                        <input type="checkbox" name="sexe[]" <?= (isset($sexe[0]) && array_search('femelle', $sexe) !== false )?'checked':'' ?> value="femelle"> Femelle
                        <input type="checkbox" name="sexe[]" <?= (isset($sexe[0]) && array_search('castré', $sexe) !== false )?'checked':'' ?> value="castré"> Castré
                    </div>
                </div>
<?php
            }
?>
            <div class="description">
                <label for="description">Description</label>
                <textarea name="description" id="description"><?= $description ?></textarea>
            </div>

            <div class="block-prix">
                <div class="prix1" style="<?= ($typeBDD == 'plan')?'display: none':'' ?>">
                    <label for="prix1">Prix de <?= ($type_produit == 'creature')?'la créature':'l\'objet' ?></label>
                    <input type="text" id="prix1" name="prix1" value="<?= (is_numeric($prix1))?$prix1:'' ?>">
                </div>
<?php
                if($type_produit != 'creature'){
?>
                    <div class="prix2" style="<?= ($typeBDD == 'deux' || $typeBDD == 'plan')?'':'display: none' ?>;">
                        <label for="prix2">Prix du plan</label>
                        <input type="text" id="prix2" name="prix2" value="<?= (is_numeric($prix2))?$prix2:'' ?>">
                    </div>
<?php
                }
?>
                <div class="monnaie">
                    <label for="monnaie">Monnaie</label>
                    <input type="text" id="monnaie" name="monnaie" value="<?php if(!empty($monnaie)) echo $monnaie; elseif(!empty($_SESSION['serveur']['monnaie'])) echo $_SESSION['serveur']['monnaie']; ?>">
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