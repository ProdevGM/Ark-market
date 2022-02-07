<?php

include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


// ***** GESTION DES REQUÊTES ***** //
// Affichage du nom du serveur si utilisateur non connecté ou absence de serveur favori
if(empty($_SESSION['utilisateur']['id_serveur'])){

	$serveur = false; // Afin d'éviter le contrôle empty de $_SESSION... par la suite
	$requete_creature = "SELECT * FROM creature c, serveur s WHERE s.id_serveur = c.id_serveur";
	$requete_selle = "SELECT * FROM selle se, serveur s WHERE s.id_serveur = se.id_serveur";
	$requete_arme = "SELECT * FROM arme a, serveur s WHERE s.id_serveur = a.id_serveur";
	$requete_armure = "SELECT * FROM armure a, serveur s WHERE s.id_serveur = a.id_serveur";

}else{

	$serveur = true; // Afin d'éviter le contrôle empty de $_SESSION... par la suite
	$requete_creature = "SELECT * FROM creature WHERE 1";
	$requete_selle = "SELECT * FROM selle WHERE 1";
	$requete_arme = "SELECT * FROM arme WHERE 1";
	$requete_armure = "SELECT * FROM armure WHERE 1";
}

// Prise en compte de la catégorie pour l'affichage des produits
if(isset($_GET['action']) && isset($_GET['categorie']) && !empty($_GET['categorie'])){
	$requete_creature = $requete_creature." AND categorie = '".$_GET['categorie']."'";
	$requete_selle = $requete_selle." AND categorie = '".$_GET['categorie']."'";
	$requete_arme = $requete_arme." AND categorie = '".$_GET['categorie']."'";
	$requete_armure = $requete_armure." AND categorie = '".$_GET['categorie']."'";
}

// Classement par date de création DESC
if(empty($_SESSION['utilisateur']['id_serveur'])){
	$requete_creature = $requete_creature." ORDER BY c.date_creation DESC";
	$requete_selle = $requete_selle." ORDER BY se.date_creation DESC";
	$requete_arme = $requete_arme." ORDER BY a.date_creation DESC";
	$requete_armure = $requete_armure." ORDER BY a.date_creation DESC";
}else{
	$requete_creature = $requete_creature." ORDER BY date_creation DESC";
	$requete_selle = $requete_selle." ORDER BY date_creation DESC";
	$requete_arme = $requete_arme." ORDER BY date_creation DESC";
	$requete_armure = $requete_armure." ORDER BY date_creation DESC";
}

// Uniquement les 3 derniers produits ajoutés sur la page d'accueil
if(!isset($_GET['action'])){
	$requete_creature = $requete_creature.' LIMIT 3';
	$requete_selle = $requete_selle.' LIMIT 3';
	$requete_arme = $requete_arme.' LIMIT 3';
	$requete_armure = $requete_armure.' LIMIT 3';
}


$pdo_creature = $pdo->query($requete_creature);
$pdo_selle = $pdo->query($requete_selle);
$pdo_arme= $pdo->query($requete_arme);
$pdo_armure = $pdo->query($requete_armure);

// ***** FIN GESTION DES REQUÊTE ***** //


// Gestion des catégories de la Section 1 (les tableaux se trouves dans init.inc.php)
if(isset($_GET['action'])){
	if($_GET['action'] == 'creature' || $_GET['action'] == 'selle')
		$categorie = $tab_categorie_creature_selle;
	elseif($_GET['action'] == 'arme')
		$categorie = $tab_categorie_arme;
	elseif($_GET['action'] == 'armure')
		$categorie = $tab_categorie_armure;
}


include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

<main class="accueil">
	<div class="container">
		<div class="block-section row">
<?php
			if(isset($_GET['action'])){
?>
				<section class="section1 col-12 col-xl-3">
					<div class="block-categorie row justify-content-center">
						<p class="gtitre text-center col-12">CATEGORIE</p>
<?php
						foreach($categorie AS $valeur){
?>
							<a href="<?= URL ?>index.php?action=<?= $_GET['action'] ?>&categorie=<?= $valeur ?>#<?= $_GET['action'] ?>" class="categorie text-center col-auto col-xl-8"><?= ucfirst($valeur) ?></a>
<?php
						}
						
?>

					</div>
				</section>
<?php
			}
?>


			<section class="section2 <?= (isset($_GET['action']))?'col-xl-9':'' ?>">

				<div id="creature" class="creature row justify-content-center justify-content-md-around initmarg <?= (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'creature'))?'':'d-none' ?>">

					<a href="<?= URL ?>index.php?action=creature" class="gtitre col-12">CREATURES</a>
<?php
					while($creature = $pdo_creature->fetch(PDO::FETCH_ASSOC)){
?>
						<a href="#" class="block col-12 col-sm-11 col-md-3 row justify-content-md-center">
							<div class="block-nom text-md-center col-5 col-sm-4 col-md-12">
								<p class="nom"><?= $creature['nom'] ?></p>
							</div>
							<div class="block-niveau text-center col-3 col-sm-4 col-md-12">
								<p class="align-middle d-none d-sm-inline-block">Niveau :</p>
								<p class="align-middle niveau d-sm-inline-block"><?= ' '.$creature['niveau'] ?></p>
							</div>
							<div class="block-caracteristique col-md-12 row d-none d-md-flex justify-content-between">
								<div class="detail col-6 row justify-content-between align-items-center initmarg">
										<img class="col-3" src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
										<p class="col-8"><?= $creature['vie'] ?></p>
								</div>
								<div class="detail col-6 row justify-content-between align-items-center initmarg">
										<img class="col-3" src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
										<p class="col-8"><?= $creature['energie'] ?></p>
								</div>
								<div class="detail col-6 row justify-content-between align-items-center initmarg">
										<img class="col-3" src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
										<p class="col-8"><?= $creature['oxygène'] ?></p>
								</div>
								<div class="detail col-6 row justify-content-between align-items-center initmarg">
										<img class="col-3" src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
										<p class="col-8"><?= $creature['nourriture'] ?></p>
								</div>
								<div class="detail col-6 row justify-content-between align-items-center initmarg">
										<img class="col-3" src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
										<p class="col-8"><?= $creature['poids'] ?></p>
								</div>
								<div class="detail col-6 row justify-content-between align-items-center initmarg">
										<img class="col-3" src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
										<p class="col-8"><?= $creature['attaque'] ?></p>
								</div>
								<div class="detail col-6 row justify-content-between align-items-center initmarg">
										<img class="col-3" src="<?= URL ?>image/site/caracteristique/temporaire.png" alt="">
										<p class="col-8"><?= $creature['vitesse'] ?></p>
								</div>
							</div>
							<div class="block-precision d-none">
								<p class="sexe"><?= $creature['sexe'] ?></p>
								<p class="niveau_initial"><?= $creature['niveau_initial'] ?></p>
								<p class="commentaire"><?= $creature['commentaire'] ?></p>
							</div>
							<div class="block-prix text-right text-md-center col-4 col-md-12">
								<p class="prix"><?= $creature['prix'].' '.$creature['monnaie'] ?></p>
							</div>
<?php
							if($serveur == false){ // Affichage du nom du serveur si non connecté ou absence de serveur favori
?>
								<div class="block-serveur text-center">
									<p class="serveur"><?= $creature['nom_serveur'] ?></p>
								</div>
<?php
							}
?>
						</a>
<?php
					}
?>
				</div>

				<div id="selle" class="selle row justify-content-center justify-content-md-around initmarg <?= (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'selle'))?'':'d-none' ?>">
 
					<a href="<?= URL ?>index.php?action=selle" class="gtitre col-12">SELLES</a>
<?php
					while($selle = $pdo_selle->fetch(PDO::FETCH_ASSOC)){
?>
						<a href="#" class="block col-12 col-sm-11 col-md-3 row">

							<div class="block-imllustration d-none d-lg-flex col-lg-4">
								<img src="image/site/temporaire.png" alt="image de la selle" class="w-100">
							</div>
							<div class="block-d col-8 col-md-12 col-lg-8 row justify-content-between align-items-between initmarg">
								<div class="block-nom text-md-center text-lg-left col-8 col-sm-7 col-md-12">
									<p class="nom"><?= $selle['nom'] ?><?= ($selle['type'] == "plan" || $selle['type'] == "deux") ? ' (BP)' : '' ?></p>
								</div>
								<div class="block-armure text-center text-lg-left col-4 col-sm-5 col-md-12">
									<p class="align-middle d-none d-sm-inline-block">Armure :</p>
									<p class="niveau align-middle d-sm-inline-block"><?= ' '.$selle['armure'] ?></p>
								</div>
								<div class="block-qualite text-center text-lg-left d-none d-md-block col-md-12">
									<p class="qualite"><?= $selle['qualité'] ?></p>
								</div>
							</div>
							<div class="block-prix text-right text-md-center col-4 col-md-12 <?= ($selle['prix2'] != NULL) ? 'row' : ''; ?> initmarg">
								<div class="block-prix1 <?= ($selle['prix2'] != NULL) ? 'col-lg-6' : ''; ?>">
									<p class="prix1" title="objet"><?= $selle['prix1'].' '.$selle['monnaie'] ?></p>
								</div>
								<div class="block-prix2 d-none <?= ($selle['prix2'] != NULL) ? 'd-lg-block col-lg-6' : ''; ?>">
									<p class="prix2" title="plan"><?= $selle['prix2'].' '.$selle['monnaie'] ?></p>
								</div>
							</div>
<?php
							if($serveur == false){ // Affichage du nom du serveur si non connecté ou absence de serveur favori
?>
								<div class="block-serveur text-center col-12">
									<p class="serveur"><?= $selle['nom_serveur'] ?></p>
								</div>
<?php
							}
?>
						</a>
<?php
					}
?>
				</div>

				<div id="arme" class="arme row justify-content-center justify-content-md-around initmarg <?= (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'arme'))?'':'d-none' ?>">
 
					<a href="<?= URL ?>index.php?action=arme" class=gtitre col-12">ARMES</a>
<?php
					while($arme = $pdo_arme->fetch(PDO::FETCH_ASSOC)){
?>
						<a href="#" class="block col-12 col-sm-11 col-md-3 row">

							<div class="block-imllustration d-none d-lg-flex col-lg-4">
								<img src="image/site/temporaire.png" alt="image de l'arme" class="w-100">
							</div>
							<div class="block-d col-8 col-md-12 col-lg-8 row justify-content-between align-items-between initmarg">
								<div class="block-nom text-md-center text-lg-left col-8 col-sm-7 col-md-12">
									<p class="nom"><?= $arme['nom'] ?><?= ($arme['type'] == "plan" || $arme['type'] == "deux") ? ' (BP)' : '' ?></p>
								</div>
								<div class="block-armure text-center text-lg-left col-4 col-sm-5 col-md-12">
									<p class="align-middle d-none d-sm-inline-block">Dégât :</p>
									<p class="niveau align-middle d-sm-inline-block"><?= ' '.$arme['dégât'] ?></p>
								</div>
								<div class="block-qualite text-center text-lg-left d-none d-md-block col-md-12">
									<p class="qualite"><?= ucfirst($arme['qualité']) ?></p>
								</div>
							</div>
							<div class="block-prix text-right text-md-center col-4 col-md-12 <?= ($arme['prix2'] != NULL) ? 'row' : ''; ?> initmarg">
								<div class="block-prix1 <?= ($arme['prix2'] != NULL) ? 'col-lg-6' : ''; ?>">
									<p class="prix1" title="objet"><?= $arme['prix1'].' '.$arme['monnaie'] ?></p>
								</div>
								<div class="block-prix2 d-none <?= ($arme['prix2'] != NULL) ? 'd-lg-block col-lg-6' : ''; ?>">
									<p class="prix2" title="plan"><?= $arme['prix2'].' '.$arme['monnaie'] ?></p>
								</div>
							</div>
<?php
							if($serveur == false){ // Affichage du nom du serveur si non connecté ou absence de serveur favori
?>
								<div class="block-serveur text-center col-12">
									<p class="serveur"><?= $arme['nom_serveur'] ?></p>
								</div>
<?php
							}
?>
						</a>
<?php
					}
?>
				</div>

				<div id="armure" class="armure row justify-content-center justify-content-md-around initmarg <?= (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'armure'))?'':'d-none' ?>">
 
					<a href="<?= URL ?>index.php?action=armure" class=gtitre col-12">ARMURES</a>
<?php
					while($armure = $pdo_armure->fetch(PDO::FETCH_ASSOC)){
?>
						<a href="#" class="block col-12 col-sm-11 col-md-3 row">


							<div class="block-nom text-md-center col-5 col-sm-6 col-md-12">
								<p class="nom"><?= $armure['nom'] ?><?= ($armure['type'] == "plan" || $armure['type'] == "deux") ? ' (BP)' : '' ?></p>
							</div>						
							<div class="block-imllustration d-none d-lg-flex col-lg-4">
								<img src="image/site/temporaire.png" alt="image de l'armure" class="w-100">
							</div>
							<div class="block-d col-3 col-sm-3 col-md-12 col-lg-8 row justify-content-between align-items-between initmarg">
								<div class="block-armure text-center text-lg-left col-md-12">
									<p class="align-middle d-none d-sm-inline-block">Armure :</p>
									<p class="niveau align-middle d-sm-inline-block"><?= ' '.$armure['armure'] ?></p>
								</div>
								<div class="block-res-f text-center text-lg-left d-none d-md-block col-4 col-sm-5 col-md-12">
									<p class="align-middle d-none d-sm-inline-block">R. Froid :</p>
									<p class="niveau align-middle d-sm-inline-block"><?= ' '.$armure['froid'] ?></p>
								</div>
								<div class="block-res-c text-center text-lg-left d-none d-md-block col-4 col-sm-5 col-md-12">
									<p class="align-middle d-none d-sm-inline-block">R. Chaleur :</p>
									<p class="niveau align-middle d-sm-inline-block"><?= ' '.$armure['chaleur'] ?></p>
								</div>
								<div class="block-qualite text-center text-lg-left d-none d-md-block col-md-12">
									<p class="qualite"><?= ucfirst($armure['qualité']) ?></p>
								</div>
							</div>
							<div class="block-prix text-right text-md-center col-4 col-sm-3 col-md-12 <?= ($armure['prix2'] != NULL) ? 'row' : ''; ?> initmarg">
								<div class="block-prix1 <?= ($armure['prix2'] != NULL) ? 'col-lg-6' : ''; ?>">
									<p class="prix1"><?= $armure['prix1'].' '.$armure['monnaie'] ?></p>
								</div>
								<div class="block-prix2 d-none <?= ($armure['prix2'] != NULL) ? 'd-lg-block col-lg-6' : ''; ?>">
									<p class="prix2"><?= $armure['prix2'].' '.$armure['monnaie'] ?></p>
								</div>
							</div>
<?php
							if($serveur == false){ // Affichage du nom du serveur si non connecté ou absence de serveur favori
?>
								<div class="block-serveur text-center col-12">
									<p class="serveur"><?= $armure['nom_serveur'] ?></p>
								</div>
<?php
							}
?>
						</a>
<?php
					}
?>
				</div>


			</section>


			
			<section class="section3">

			</section>



<?php 
include 'inc/footer.inc.php';
?>