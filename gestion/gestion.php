<?php

include '../inc/init.inc.php';
include '../inc/fonction.inc.php';


$requete_total_creature = "SELECT * FROM creature ORDER BY date_creation DESC";
$requete_total_selle = "SELECT * FROM selle ORDER BY date_creation DESC";
$requete_total_arme = "SELECT * FROM arme ORDER BY date_creation DESC";
$requete_total_armure = "SELECT * FROM armure ORDER BY date_creation DESC";

$pdo_total_creature = $pdo->query($requete_total_creature);
$pdo_total_selle = $pdo->query($requete_total_selle);
$pdo_total_arme = $pdo->query($requete_total_arme);
$pdo_total_armure = $pdo->query($requete_total_armure);


include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>

<main class="gestion">
    <div class="container">

        <p class="gtitre text-center"> MON ETALE</p>

        <!-- BLOCK CREATURE -->
        <div class="block block-creature">

            <div class="onglet row align-items-center">
                <button class="col-10 text-end" type="button" data-bs-toggle="collapse" data-bs-target="#total-creature" aria-expanded="false" aria-controls="total-creature">MES CREATURES</button>
                <a class="col-2 text-center" href="<?= URL ?>gestion/ajout.php?action=creature">Ajouter un produit</a>
            </div>

            <div class="collapse block-produit" id="total-creature">
                <div class="card card-body">
<?php
                    while($total_creature = $pdo_total_creature->fetch(PDO::FETCH_ASSOC)){
?>
                        <button type="button" class="initmarg produit row">

                                <p class="initpad col-4 col-md-2"><?= ucfirst($total_creature['nom']) ?></p>
                                <p class="initpad col-2 col-lg-1"><?= $total_creature['niveau'] ?></p>
                                <p class="initpad d-none d-lg-block col-lg-2"><?= ucfirst($total_creature['sexe']) ?></p>
                                <div class="initpad caracteristique d-none d-md-block col-md-5 col-lg-4">
                                    <p class="initpad d-inline"><?= $total_creature['vie'] ?> / </p>
                                    <p class="initpad d-inline"><?= $total_creature['energie'] ?> / </p>
                                    <p class="initpad d-inline"><?= $total_creature['oxygene'] ?> / </p>
                                    <p class="initpad d-inline"><?= $total_creature['nourriture'] ?> / </p>
                                    <p class="initpad d-inline"><?= $total_creature['poids'] ?> / </p>
                                    <p class="initpad d-inline"><?= $total_creature['attaque'] ?> / </p>
                                    <p class="initpad d-inline"><?= $total_creature['vitesse'] ?></p>
                                </div>
                                <p class="initpad col-4 col-md-2"><?= $total_creature['date_creation'] ?></p>
                                <div class="initpad action col-2 col-md-1 row justify-content-end">
                                    <a href="#" class="col-2"><img src="<?= URL ?>/image/site/crayon_vert.png" alt=""></a>
                                    <a href="#" class="col-2"><img src="<?= URL ?>/image/site/croix_rouge.png" alt=""></a>
                                </div>

                        </button>
<?php
                    }
?>
                </div>
            </div>

        </div>

        <!--  BLOCK SELLE -->
        <div class="block block-selle">

            <div class="onglet row align-items-center">
                <button class="col-10 text-end" type="button" data-bs-toggle="collapse" data-bs-target="#total-selle" aria-expanded="false" aria-controls="total-selle">MES SELLES</button>
                <a class="col-2 text-center" href="<?= URL ?>gestion/ajout.php?action=selle">Ajouter un produit</a>
            </div>

            <div class="collapse block-produit" id="total-selle">
                <div class="card card-body">
<?php
                    while($total_selle = $pdo_total_selle->fetch(PDO::FETCH_ASSOC)){
?>
                        <button type="button" class="initmarg produit row">

                                <p class="initpad col-4 col-md-2"><?= ucfirst($total_selle['nom']) ?></p>
                                <p class="initpad col-2 col-lg-1"><?= $total_selle['armure'] ?></p>
                                <p class="initpad d-none d-md-block col-md-5 col-lg-6"><?= ucfirst($total_selle['qualite']) ?></p>
                                <p class="initpad col-4 col-md-2"><?= $total_selle['date_creation'] ?></p>
                                <div class="initpad action col-2 col-md-1 row justify-content-end">
                                    <a href="#" class="col-2"><img src="<?= URL ?>/image/site/crayon_vert.png" alt=""></a>
                                    <a href="#" class="col-2"><img src="<?= URL ?>/image/site/croix_rouge.png" alt=""></a>
                                </div>

                        </button>
<?php
                    }
?>
                </div>
            </div>

        </div>

        <!--  BLOCK ARME -->
        <div class="block block-arme">

            <div class="onglet row align-items-center">
                <button class="col-10 text-end" type="button" data-bs-toggle="collapse" data-bs-target="#total-arme" aria-expanded="false" aria-controls="total-arme">MES ARMES</button>
                <a class="col-2 text-center" href="<?= URL ?>gestion/ajout.php?action=arme">Ajouter un produit</a>
            </div>

            <div class="collapse block-produit" id="total-arme">
                <div class="card card-body">
<?php
                    while($total_arme = $pdo_total_arme->fetch(PDO::FETCH_ASSOC)){
?>
                        <button type="button" class="initmarg produit row">

                                <p class="initpad col-4 col-md-2"><?= ucfirst($total_arme['nom']) ?></p>
                                <p class="initpad col-2 col-lg-1"><?= $total_arme['degat'] ?></p>
                                <p class="initpad d-none d-md-block col-md-5 col-lg-6"><?= ucfirst($total_arme['qualite']) ?></p>
                                <p class="initpad col-4 col-md-2"><?= $total_arme['date_creation'] ?></p>
                                <div class="initpad action col-2 col-md-1 row justify-content-end">
                                    <a href="#" class="col-2"><img src="<?= URL ?>/image/site/crayon_vert.png" alt=""></a>
                                    <a href="#" class="col-2"><img src="<?= URL ?>/image/site/croix_rouge.png" alt=""></a>
                                </div>

                        </button>
<?php
                    }
?>
                </div>
            </div>

        </div>

        <!--  BLOCK ARMURE -->
        <div class="block block-armure">

            <div class="onglet row align-items-center">
                <button class="col-10 text-end" type="button" data-bs-toggle="collapse" data-bs-target="#total-armure" aria-expanded="false" aria-controls="total-armure">MES ARMURES</button>
                <a class="col-2 text-center" href="<?= URL ?>gestion/ajout.php?action=armure">Ajouter un produit</a>
            </div>

            <div class="collapse block-produit" id="total-armure">
                <div class="card card-body">
<?php
                    while($total_armure = $pdo_total_armure->fetch(PDO::FETCH_ASSOC)){
?>
                        <button type="button" class="initmarg produit row">

                                <p class="initpad col-4 col-md-2"><?= ucfirst($total_armure['nom']) ?></p>
                                <p class="initpad col-2 col-lg-1"><?= $total_armure['armure'] ?></p>
                                <p class="initpad d-none d-md-block col-md-2 col-lg-3"><?= ucfirst($total_armure['qualite']) ?></p>
                                <div class="initpad caracteristique d-none d-md-flex col-md-3">
                                    <p class="initpad col-6"><?= $total_armure['froid'] ?></p>
                                    <p class="initpad col-6"><?= $total_armure['chaleur'] ?></p>
                                </div>
                                <p class="initpad col-4 col-md-2"><?= $total_armure['date_creation'] ?></p>
                                <div class="initpad action col-2 col-md-1 row justify-content-end">
                                    <a href="#" class="col-2"><img src="<?= URL ?>/image/site/crayon_vert.png" alt=""></a>
                                    <a href="#" class="col-2"><img src="<?= URL ?>/image/site/croix_rouge.png" alt=""></a>
                                </div>

                        </button>
<?php
                    }
?>
                </div>
            </div>

        </div>


    </div>

<?php
include '../inc/footer.inc.php';
?>