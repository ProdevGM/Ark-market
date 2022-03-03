<!-- Navigation -->
<div class="menu">
    <nav>

    <div class="logo w-auto">
        <a href="<?= URL ?>index.php">LOGO</a>
    </div>

    <div class="block-menu d-none d-lg-flex row justify-content-center">

        <div class="w-auto">
            <a href="<?= URL ?>index.php" class="titre-menu"> MARCHÉ </a>
        </div>
        <div class="w-auto">
            <a href="<?= URL ?>gestion/gestion.php" class="gtitre-hover <?= (strpos($_SERVER["PHP_SELF"], "gestion.php"))?"active":"" ?>"> INVENTAIRE </a>
        </div>
        <div class="w-auto">
            <a href="<?= URL ?>gestion/compte.php" class="gtitre-hover <?= (strpos($_SERVER["PHP_SELF"], "compte.php"))?"active":"" ?>"> BUREAU </a>
        </div>
    </div>

    <div class="block-burger d-lg-none row justify-content-end">
        <div class="burger">
            <span></span>
        </div>
    </div>

    </nav>
</div>

