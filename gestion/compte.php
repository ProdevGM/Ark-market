<?php

include '../inc/init.inc.php';
include '../inc/fonction.inc.php';


// Contrôle connexion utilisateur
if(!user_is_connect()){
    header('location:http://ark-market/index.php');
}




/* ******************************************** */
/* ******************************************** */
// RECUPÉRATION DES INFOS SERVEUR DE L'UTILISATEUR
/* ******************************************** */
/* ******************************************** */

$PDO_info_serveur = $pdo->query("SELECT * FROM info_serveur i, serveur s
                                            WHERE i.id_serveur = s.id_serveur
                                            AND i.id_utilisateur = ".$_SESSION["utilisateur"]['id_utilisateur']."");


include '../inc/header.inc.php';
?>

<main class="connexion">
    <div class="container">

    <form method="post" action="">

        <div class="block-profil">
            
            <p>Informations relatives à mon compte</p>
            <div class="compte">

                <div class="mail">
                    <label for="mail">Adresse mail</label>
                    <input type="text" id="mail" name="mail" placeholder="Mail">
                </div>

                <div>
                    <p>Nombre de produits</p>
                    <p>php</p>
                </div>

            </div>

            <p>Changement de mot de passe</p>
            <div class="mdp">

                <div class="mdp-actuel">
                    <label for="mail">Ancien mot de passe</label>
                    <input type="text" id="mail" name="mail" placeholder="Votre ancien mot de passe">
                </div>
                
                <div class="nouveau-mdp">
                    <label for="mail">Nouveau mot de passe</label>
                    <input type="text" id="mail" name="mail" placeholder="Votre nouveau mot de passe">
                </div>

                <div class="confirme-mdp">
                    <label for="mail">Confirmation</label>
                    <input type="text" id="mail" name="mail" placeholder="Confirmez votre nouveau mot de passe">
                </div>
            </div>
        </div>

        <div class="blockserveur">

            <p>Mes serveurs</p>

            <div class="accordion" id="accordeon-serveur">
<?php
                $i = 0;
                while($info_serveur = $PDO_info_serveur->fetch(PDO::FETCH_ASSOC)){
                    $i++;
?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="head<?= $i ?>">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>" aria-expanded="true" aria-controls="collapse<?= $i ?>">
                                <?= $info_serveur["nom_serveur"] ?>
                            </button>
                        </h2>
                        <div id="collapse<?= $i ?>" class="accordion-collapse collapse show" aria-labelledby="heading<?= $i ?>" data-bs-parent="#accordeon-serveur">
                            <div class="accordion-body">
                                <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>

<?php
                }
?>
<!--                 <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Accordion Item #1
                    </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordeon-serveur">
                        <div class="accordion-body">
                            <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Accordion Item #2
                    </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordeon-serveur">
                        <div class="accordion-body">
                            <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Accordion Item #3
                    </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordeon-serveur">
                        <div class="accordion-body">
                            <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                        </div>
                    </div>
                </div> -->
            </div>










            

            <div class="nom-perso">
                <label for="nom-perso">Nom du joueur</label>
                <input type="text" id="nom-perso" name="nom_perso" placeholder="Joueur">
            </div>

            <div class="nom-discord">
                <label for="nom-discord">Surnom du joueur</label>
                <input type="text" id="nom-discord" name="nom_discord" placeholder="Discord">
            </div>
        </div>



    </form>



    </div>



<?php
include '../inc/footer.inc.php';
?>