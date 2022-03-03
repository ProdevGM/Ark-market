<?php

include 'inc/init.inc.php';
include 'inc/fonction.inc.php';

// Déclarations des variables
$couleur_qualite = ""; // Couleur en fonction de la qualité

// ***** GESTION DES REQUÊTES ***** //
// Affichage du nom du serveur si utilisateur non connecté ou absence de serveur favori
if(empty($_SESSION['serveur']['id_serveur'])){

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
	$requete_creature .= " AND categorie = '".$_GET['categorie']."'";
	$requete_selle .= " AND categorie = '".$_GET['categorie']."'";
	$requete_arme .= " AND categorie = '".$_GET['categorie']."'";
	$requete_armure .= " AND categorie = '".$_GET['categorie']."'";
}

// Classement par date de création DESC
if(empty($_SESSION['serveur']['id_serveur'])){
	$requete_creature .= " ORDER BY c.date_creation DESC";
	$requete_selle .= " ORDER BY se.date_creation DESC";
	$requete_arme .= " ORDER BY a.date_creation DESC";
	$requete_armure .= " ORDER BY a.date_creation DESC";
}else{
	$requete_creature .= " ORDER BY date_creation DESC";
	$requete_selle .= " ORDER BY date_creation DESC";
	$requete_arme .= " ORDER BY date_creation DESC";
	$requete_armure .= " ORDER BY date_creation DESC";
}

// Uniquement les 3 derniers produits ajoutés sur la page d'accueil
if(!isset($_GET['action'])){
	$requete_creature .= ' LIMIT 3';
	$requete_selle .= ' LIMIT 3';
	$requete_arme .= ' LIMIT 3';
	$requete_armure .= ' LIMIT 3';
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
include 'inc/nav_marche.inc.php';
?>

<main class="accueil">
	<div class="container">
		<div class="block-section row initpad ">
<?php
			if(isset($_GET['action'])){
?>
				<section class="section1 col-12 col-xl-2">
					<div class="block-categorie row initpad initmarg justify-content-center">
						<p class="gtitre text-center col-12">CATEGORIE</p>
<?php
						foreach($categorie AS $valeur){
?>
							<a href="<?= URL ?>index.php?action=<?= $_GET['action'] ?>&categorie=<?= $valeur ?>#<?= $_GET['action'] ?>" class="categorie hover-bleu-bg text-center col-auto col-xl-8 <?= (isset($_GET["categorie"]) && $_GET["categorie"] == $valeur)?"active-menu":"" ?>"><?= ucfirst($valeur) ?></a>
<?php
						}		
?>
					</div>
				</section>
<?php
			}
?>


			<section class="section2 <?= (isset($_GET['action']))?'col-xl-10':'' ?>">

				<div id="creature" class="block-offres creature row justify-content-center justify-content-md-around initmarg <?= (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'creature'))?'':'d-none' ?>">

					<a href="<?= URL ?>index.php?action=creature" class="gtitre hover-bleu-bg col-12">CRÉATURES</a>
<?php
					while($creature = $pdo_creature->fetch(PDO::FETCH_ASSOC)){
?>
						<a href="#" class="block col-12 col-sm-11 col-md-3 row initpad justify-content-center align-content-between align-items-start">
							<div class="ss-block row initpad justify-content-md-center align-items-center">
								<div class="block-nom text-md-center col-5 col-sm-4 col-md-12">
									<p class="nom"><?= $creature['nom'] ?></p>
								</div>
								<div class="block-niveau text-center col-3 col-sm-4 col-md-12">
									<p class="italic align-middle d-none d-sm-inline-block">Niveau :</p>
									<p class="align-middle niveau d-sm-inline-block"><?= ' '.$creature['niveau'] ?></p>
								</div>
								<div class="block-caracteristique row initpad d-none d-lg-flex">
									<div class="detail col-6 row initpad justify-content-between align-items-center initmarg">
										<div class="col-4 text-center">
											<img src="<?= URL ?>image/site/caracteristique/vie.png" alt="">
										</div>
											<p class="col-8"><?= (empty($creature['vie']))?"/":$creature['vie'] ?></p>
									</div>
									<div class="detail col-6 row initpad justify-content-between align-items-center initmarg">
										<div class="col-4 text-center">
											<img src="<?= URL ?>image/site/caracteristique/energie.png" alt="">
										</div>
											<p class="col-8"><?= (empty($creature['energie']))?"/":$creature['energie'] ?></p>
									</div>
									<div class="detail col-6 row initpad justify-content-between align-items-center initmarg">
										<div class="col-4 text-center">
											<img src="<?= URL ?>image/site/caracteristique/oxygene.png" alt="">
										</div>
											<p class="col-8"><?= (empty($creature['oxygène']))?"/":$creature['oxygène'] ?></p>
									</div>
									<div class="detail col-6 row initpad justify-content-between align-items-center initmarg">
										<div class="col-4 text-center">
											<img src="<?= URL ?>image/site/caracteristique/nourriture.png" alt="">
										</div>
											<p class="col-8"><?= (empty($creature['nourriture']))?"/":$creature['nourriture'] ?></p>
									</div>
									<div class="detail col-6 row initpad justify-content-between align-items-center initmarg">
										<div class="col-4 text-center">
											<img src="<?= URL ?>image/site/caracteristique/poids.png" alt="">
										</div>
											<p class="col-8"><?= (empty($creature['poids']))?"/":$creature['poids'] ?></p>
									</div>
									<div class="detail col-6 row initpad justify-content-between align-items-center initmarg">
										<div class="col-4 text-center">
											<img src="<?= URL ?>image/site/caracteristique/attaque.png" alt="">
										</div>
											<p class="col-8"><?= (empty($creature['attaque']))?"/":$creature['attaque'] ?></p>
									</div>
									<div class="detail col-6 row initpad justify-content-between align-items-center initmarg">
										<div class="col-4 text-center">
											<img src="<?= URL ?>image/site/caracteristique/vitesse.png" alt="">
										</div>
											<p class="col-8"><?= (empty($creature['vitesse']))?"/":$creature['vitesse'] ?></p>
									</div>
								</div>
								<div class="block-precision d-none">
									<p class="sexe"><?= $creature['sexe'] ?></p>
									<p class="niveau_initial"><?= $creature['niveau_initial'] ?></p>
									<p class="commentaire"><?= $creature['commentaire'] ?></p>
								</div>
								<div class="block-prix text-center text-md-center col-4 col-md-12">
									<p class="prix"><?= ($creature['prix1'] == "A négocier")?$creature['prix1']:$creature['prix1'].' '.$creature['monnaie'] ?></p>
								</div>
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

				<div id="selle" class="block-offres selle row initpad justify-content-center justify-content-md-around initmarg <?= (!isset($_GET['action']) || (isset($_GET["action"]) && $_GET["action"] == "selle"))?"":"d-none" ?>">
 
					<a href="<?= URL ?>index.php?action=selle" class="gtitre hover-bleu-bg col-12">SELLES</a>
<?php
					while($selle = $pdo_selle->fetch(PDO::FETCH_ASSOC)){

						$couleur_qualite = couleurQualite($selle["qualité"]);
						$couleur_text_qualite = "text".couleurQualite($selle["qualité"]);
?>
						<a href="#" class="block col-12 col-sm-11 col-md-3 row initpad initpad initpad justify-content-center align-content-between align-items-start <?= $couleur_qualite ?>">
							<div class="ss-block row initpad justify-content-md-center">
								<div class="block-nom text-md-center col-5 col-md-12">
									<p class="nom <?= $couleur_text_qualite ?>"><?= $selle['nom'] ?><?= ($selle['type'] == "plan" || $selle['type'] == "deux") ? ' (BP)' : '' ?></p>
								</div>
								<div class="block-illustration d-none d-xxl-flex col-xxl-4">
									<img src="<?= URL ?>image/produit/selle/<?= $selle['categorie'] ?>/<?= str_replace(' ', '_', $selle['nom']) ?>.png" alt="image de la selle" class="">
								</div>
								<div class="block-d col-3 col-md-12 col-xxl-8 row initpad align-items-center align-content-center initmarg">
									<div class="block-armure text-center">
										<p class="italic align-middle d-none d-sm-inline-block <?= $couleur_text_qualite ?>">Armure :</p>
										<p class="niveau align-middle d-sm-inline-block <?= $couleur_text_qualite ?>"><?= ' '.$selle['armure'] ?></p>
									</div>
									<div class="block-qualite text-center d-none d-md-block col-md-12">
										<p class="qualite <?= $couleur_text_qualite ?>"><?= $selle['qualité'] ?></p>
									</div>
								</div>
								<div class="block-prix text-center text-md-center col-4 col-md-12 p-0 <?= ($selle['prix2'] != NULL) ? 'row initpad' : ''; ?> initmarg">
									<div class="block-prix1 <?= ($selle['prix2'] != NULL) ? 'col-xl-6' : ''; ?>">
										<p class="prix1 <?= $couleur_text_qualite ?>" title="objet"><?= ($selle['prix1'] == "A négocier")?$selle['prix1']:$selle['prix1'].' '.$selle['monnaie'] ?></p>
									</div>
									<div class="block-prix2 d-none <?= ($selle['prix2'] != NULL) ? 'd-xl-block col-xl-6' : ''; ?>">
										<p class="prix2 <?= $couleur_text_qualite ?>" title="plan"><?= ($selle['prix2'] == "A négocier")?$selle['prix2']:$selle['prix2'].' '.$selle['monnaie'] ?></p>
									</div>
								</div>
							</div>
<?php
							if($serveur == false){ // Affichage du nom du serveur si non connecté ou absence de serveur favori
?>
								<div class="block-serveur text-center">
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

				<div id="arme" class="block-offres arme row initpad justify-content-center justify-content-md-around initmarg <?= (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'arme'))?'':'d-none' ?>">
 
					<a href="<?= URL ?>index.php?action=arme" class="gtitre hover-bleu-bg col-12">ARMES</a>
<?php
					while($arme = $pdo_arme->fetch(PDO::FETCH_ASSOC)){

						$couleur_qualite = couleurQualite($arme["qualité"]);	
						$couleur_text_qualite = "text".couleurQualite($arme["qualité"]);	
?>
						<a href="#" class="block col-12 col-sm-11 col-md-3 row initpad initpad justify-content-center align-content-between align-items-start <?= $couleur_qualite ?>">
							<div class="ss-block row initpad justify-content-md-center">
								<div class="block-nom text-md-center col-5 col-md-12">
									<p class="nom <?= $couleur_text_qualite ?>"><?= $arme['nom'] ?><?= ($arme['type'] == "plan" || $arme['type'] == "deux") ? ' (BP)' : '' ?></p>
								</div>
								<div class="block-illustration d-none d-xxl-flex col-xxl-4">
									<img src="<?= URL ?>image/produit/arme/<?= $arme['categorie'] ?>/<?= str_replace(' ', '_', $arme['nom']) ?>.png" alt="image de l'arme" class="">
								</div>
								<div class="block-d col-3 col-md-12 col-xxl-8 row initpad align-items-center align-content-center initmarg">
									<div class="block-armure text-center">
										<p class="italic align-middle d-none d-sm-inline-block <?= $couleur_text_qualite ?>">Dégât :</p>
										<p class="niveau align-middle d-sm-inline-block <?= $couleur_text_qualite ?>"><?= ' '.$arme['dégât'] ?></p>
									</div>
									<div class="block-qualite text-center d-none d-md-block col-md-12">
										<p class="qualite <?= $couleur_text_qualite ?>"><?= ucfirst($arme['qualité']) ?></p>
									</div>
								</div>
								<div class="block-prix text-center text-md-center col-4 col-md-12 <?= ($arme['prix2'] != NULL) ? 'row initpad' : ''; ?> initmarg">
									<div class="block-prix1 <?= ($arme['prix2'] != NULL) ? 'col-xl-6' : ''; ?>">
										<p class="prix1 <?= $couleur_text_qualite ?>" title="objet"><?= ($arme['prix1'] == "A négocier")?$arme['prix1']:$arme['prix1'].' '.$arme['monnaie'] ?></p>
									</div>
									<div class="block-prix2 d-none <?= ($arme['prix2'] != NULL) ? 'd-xl-block col-xl-6' : ''; ?>">
										<p class="prix2 <?= $couleur_text_qualite ?>" title="plan"><?= ($arme['prix2'] == "A négocier")?$arme['prix2']:$arme['prix2'].' '.$arme['monnaie'] ?></p>
									</div>
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

				<div id="armure" class="block-offres armure row initpad justify-content-center justify-content-md-around initmarg <?= (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'armure'))?'':'d-none' ?>">
 
					<a href="<?= URL ?>index.php?action=armure" class="gtitre hover-bleu-bg col-12">ARMURES</a>
<?php
					while($armure = $pdo_armure->fetch(PDO::FETCH_ASSOC)){
	
						$couleur_qualite = couleurQualite($armure["qualité"]);
						$couleur_text_qualite = "text".couleurQualite($armure["qualité"]);
						
?>
						<a href="#" class="block col-12 col-sm-11 col-md-3 row initpad initpad justify-content-center align-content-between align-items-start <?= $couleur_qualite ?>">
							<div class="ss-block row initpad justify-content-md-center">
								<div class="block-nom text-md-center col-5 col-md-12">
									<p class="nom <?= $couleur_text_qualite ?>"><?= $armure['nom'] ?><?= ($armure['type'] == "plan" || $armure['type'] == "deux") ? ' (BP)' : '' ?></p>
								</div>						
								<div class="block-illustration d-none d-xxl-flex col-xxl-4">
									<img src="<?= URL ?>image/produit/armure/<?= $armure['categorie'] ?>/<?= str_replace(' ', '_', $armure['nom']) ?>.png" alt="image de l'armure" class="">
								</div>
								<div class="block-d col-3 col-md-12 col-xxl-8 row initpad justify-content-between align-items-center align-items-md-between initmarg">
									<div class="block-armure text-center">
										<p class="italic align-middle d-none d-sm-inline-block <?= $couleur_text_qualite ?>">Armure :</p>
										<p class="niveau align-middle d-sm-inline-block <?= $couleur_text_qualite ?>"><?= ' '.$armure['armure'] ?></p>
									</div>
									<div class="block-res-f text-center d-none d-md-block col-4 col-sm-5 col-md-12">
										<p class="italic align-middle d-none d-sm-inline-block <?= $couleur_text_qualite ?>">Froid :</p>
										<p class="niveau align-middle d-sm-inline-block <?= $couleur_text_qualite ?>"><?= ' '.$armure['froid'] ?></p>
									</div>
									<div class="block-res-c text-center d-none d-md-block col-4 col-sm-5 col-md-12">
										<p class="italic align-middle d-none d-sm-inline-block <?= $couleur_text_qualite ?>">Chaleur :</p>
										<p class="niveau align-middle d-sm-inline-block <?= $couleur_text_qualite ?>"><?= ' '.$armure['chaleur'] ?></p>
									</div>
									<div class="block-qualite text-center d-none d-md-block col-md-12">
										<p class="qualite <?= $couleur_text_qualite ?>"><?= ucfirst($armure['qualité']) ?></p>
									</div>
								</div>
								<div class="block-prix text-center text-md-center col-4 col-md-12 <?= ($armure['prix2'] != NULL) ? 'row' : ''; ?> initmarg">
									<div class="block-prix1 <?= ($armure['prix2'] != NULL) ? 'col-xl-6' : ''; ?>">
										<p class="prix1 <?= $couleur_text_qualite ?>"><?= ($armure['prix1'] == "A négocier")?$armure['prix1']:$armure['prix1'].' '.$armure['monnaie'] ?></p>
									</div>
									<div class="block-prix2 d-none <?= ($armure['prix2'] != NULL) ? 'd-xl-block col-xl-6' : ''; ?>">
										<p class="prix2 <?= $couleur_text_qualite ?>"><?= ($armure['prix2'] == "A négocier")?$armure['prix2']:$armure['prix2'].' '.$armure['monnaie'] ?></p>
									</div>
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
		</div>
	<div>


<?php 
include 'inc/footer.inc.php';
?>