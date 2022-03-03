<!-- Navigation -->
<div class="menu">
    <nav>
        <div class="nav-desktop d-none d-lg-block ">

            <div class="block-menu row justify-content-center">
                
                <div class="logo d-none">
                    <a href="<?= URL ?>index.php"><img src="<?= URL ?>/image/site/logo.png" alt=""></a>
                </div>

                <div class="w-auto">
                    <a href="<?= URL ?>index.php?action=creature" class="hover-bleu <?= (isset($_GET["action"]) && $_GET["action"] == "creature")?"active-menu":"" ?>"> CRÉATURES </a>
                </div>
                <div class="w-auto">
                    <a href="<?= URL ?>index.php?action=selle" class="hover-bleu <?= (isset($_GET["action"]) && $_GET["action"] == "selle")?"active-menu":"" ?>"> SELLES </a>
                </div>
                <div class="w-auto">
                    <a href="<?= URL ?>index.php?action=arme" class="hover-bleu <?= (isset($_GET["action"]) && $_GET["action"] == "arme")?"active-menu":"" ?>"> ARMES </a>
                </div>
                <div class="w-auto">
                    <a href="<?= URL ?>index.php?action=armure" class="hover-bleu <?= (isset($_GET["action"]) && $_GET["action"] == "armure")?"active-menu":"" ?>"> ARMURES </a>
                </div>
                <div class="w-auto">
<?php
                    if(isset($_SESSION["utilisateur"])){
?>
                        <a href="<?= URL ?>/gestion/gestion.php" class="titre-menu"> MON ÉTALE </a>
<?php
                    }
?>
        
                </div>
            </div>
        </div>

        <div class="nav-mobile">

            <div class="block-burger d-lg-none row justify-content-end">
                <div class="burger">
                    <span></span>
                </div>
            </div>

            <div>
                <ul>
                    <li><a href="<?= URL ?>index.php?action=creature""></a>CRÉATURES</li>
                    <li><a href="<?= URL ?>index.php?action=creature""></a>SELLES</li>
                    <li><a href="<?= URL ?>index.php?action=creature""></a>ARMES</li>
                    <li><a href="<?= URL ?>index.php?action=creature""></a>ARMURES</li>
                </ul>
            </div>

            <div>
                <ul>
                    <li><a href="<?= URL ?>gestion/gestion.php"></a>INVENTAIRE</li>
                    <li><a href="<?= URL ?>gestion/compte.php"></a>BUREAU</li>
                </ul>
            </div>
        </div>

        <div class="mobile-overlay"></div>
    </nav>
</div>

