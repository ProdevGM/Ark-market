<!-- Navigation -->
<nav class="col-11 mx-auto navbar navbar-expand-lg">
    <div class="container-fluid">
            <a class="navbar-brand" href="#">
              <img src="" alt="">
            </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="burger"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-lg-center" id="navbarNav">
            <ul class="navbar-nav text-center">
            <li class="nav-item">
                <a id="creature" class="nav-link" aria-current="page" href="<?= URL ?>index.php?action=creature">CREATURES</a>
            </li>
            <li class="nav-item">
                <a id="selle" class="nav-link" href="<?= URL ?>index.php?action=selle">SELLES</a>
            </li>
            <li class="nav-item">
                <a id="arme" class="nav-link" href="<?= URL ?>index.php?action=arme">ARMES</a>
            </li>
            <li class="nav-item">
                <a id="armure" class="connecter nav-link" href="<?= URL ?>index.php?action=armure">ARMURES</a>
            </li>
            <li class="nav-item">
                <a id="gestion" class="connecter nav-link" href="<?= URL ?>gestion/gestion.php">Mon étale</a>
            </li>
            </ul>
        </div>
    </div>
</nav>