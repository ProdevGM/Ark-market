/* event.preventDefault(); */

/* ****** */
// FONCTIONS
/* ****** */

// Contrôle si champs est bien numérique par l'envoie d'un tableau
function controleNumerique(tab, text){

    for(var v in tab){

        if(isNaN(tab[v])){
            console.log(tab[v]);
            $('#message').text(text);
            break;
        }else
            $('#message').text('');
    }
}

// Contrôle de la sélection d'un input type checkbox par l'envoie d'un tableau
function controleCheckbox(msg, tab){

    var i = 0;
    for(var v in tab){
        if(tab[v])
            i++;
    }
    
    if(i == 0){
        $('#message').text(msg);
        return false;
    }else{
        $('#message').text('');
        return true;
    }
}

// Contrôle format adresse mail
function controleMail($idMail, $idMsgMail){

    var mail = $($idMail).val().match(/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})/g);
    $('#msg-mail-js').remove();
    if(!mail){
        $($idMsgMail).append("<p class=\"text-danger\" id=\"msg-mail-js\"> Une adresse mail valide, c'est mieux ! </p>");
        return false;
    }else{
        $('#msg-mail-js').remove();
    }

}

// Contrôle mdp en temps réel avec class ajoutée ou retirée d'une liste de condition
function controleMotDePasseTpsReel($this){

    // Entre 8 et 20 caractères
    if($($this).val().length >= 8 && $($this).val().length <= 20){
        $('.mdp-nbr-caractere').addClass('condition-mdp-valide');
        $('.mdp-nbr-caractere').removeClass('condition-mdp-non-valide');
     }else
        $('.mdp-nbr-caractere').removeClass('condition-mdp-valide');
 
     // Une majuscule
     var maj = $($this).val().match(/[A-Z]/g);
     if(maj){
        $('.mdp-maj').addClass('condition-mdp-valide');
        $('.mdp-maj').removeClass('condition-mdp-non-valide');
     }else
        $('.mdp-maj').removeClass('condition-mdp-valide');
        
     // Une minuscule
     var min = $($this).val().match(/[a-z]/g);
     if(min){
        $('.mdp-min').addClass('condition-mdp-valide');
        $('.mdp-min').removeClass('condition-mdp-non-valide');
     }else
        $('.mdp-min').removeClass('condition-mdp-valide');
 
     // Un chiffre
     var chiffre = $($this).val().match(/[0-9]/g);
     if(chiffre){
        $('.mdp-chiffre').addClass('condition-mdp-valide');
        $('.mdp-chiffre').removeClass('condition-mdp-non-valide');
     }else
        $('.mdp-chiffre').removeClass('condition-mdp-valide');
     
     // Un caractère spécial
     var caractSpe = $($this).val().match(/[!«#$%&'()*+,-./:;<>=?@\[\]\\^_|\{\}]/g);
     if(caractSpe){
        $('.mdp-caractere-special').addClass('condition-mdp-valide');
        $('.mdp-caractere-special').removeClass('condition-mdp-non-valide');
     }else
        $('.mdp-caractere-special').removeClass('condition-mdp-valide');
}

// Contrôle mdp au submit
function controleMotDePasse(){

    var retour;
    // Entre 8 et 20 caractères
    if($('#mdp-nouveau').val().length < 8 || $('#mdp-nouveau').val().length > 20){
       $('.mdp-nbr-caractere').addClass('condition-mdp-non-valide');
       retour = false;
    }else
        $('.mdp-nbr-caractere').removeClass('condition-mdp-non-valide');

    // Une majuscule
    var maj = $('#mdp-nouveau').val().match(/[A-Z]/g);
    if(!maj){
       $('.mdp-maj').addClass('condition-mdp-non-valide');
       retour = false;
    }else
       $('.mdp-maj').removeClass('condition-mdp-non-valide');
       
    // Une minuscule
    var min = $('#mdp-nouveau').val().match(/[a-z]/g);
    if(!min){
       $('.mdp-min').addClass('condition-mdp-non-valide');
       retour = false;
    }else
       $('.mdp-min').removeClass('condition-mdp-non-valide');

    // Un chiffre
    var chiffre = $('#mdp-nouveau').val().match(/[0-9]/g);
    if(!chiffre){
       $('.mdp-chiffre').addClass('condition-mdp-non-valide');
       retour = false;
    }else
       $('.mdp-chiffre').removeClass('condition-mdp-non-valide');
    
    // Un caractère spécial
    var caractSpe = $('#mdp-nouveau').val().match(/[!«#$%&'()*+,-./:;<>=?@\[\]\\^_|\{\}]/g);
    if(!caractSpe){
       $('.mdp-caractere-special').addClass('condition-mdp-non-valide');
       retour = false;
    }else
       $('.mdp-caractere-special').removeClass('condition-mdp-non-valide');

    return retour;  
}

// Contrôle mdp et mdp de confirmation soit identitique
function controleMotDePasseIdentique(){

    $('#alerte-mdp-confirme').remove();
    if($('#mdp-confirme').val() !== $('#mdp-nouveau').val()){           
        $('#block-mdp-confirme').append('<p class="text-danger" id="alerte-mdp-confirme">Le mot de passe de confirmation doit être similaire au nouveau mot de passe</p>');
        retour = false;
    }else
        $('#alerte-mdp-confirme').remove();

}




/* ********* */
/* ********* */
// Page nav.php
/* ********* */
/* ********* */

// Animation sur le burger
$(".burger").on("click", function(){
    $(".burger").toggleClass('burger-active');
})


// Burger
$('.burger').on('click', function(){
    if($('.nav-mobile').hasClass('active')){
        $('.nav-mobile').removeClass('active');
        $('.mobile-overlay').removeClass('active');
    }else{
        $('.nav-mobile').addClass('active');
        $('.mobile-overlay').addClass('active');
    }
});
// Overley
$('.mobile-overlay').on('click', function(){
    if($('.nav-mobile').hasClass('active')){
        $('.nav-mobile').removeClass('active');
        $('.mobile-overlay').removeClass('active');
    }else{
        $('.nav-mobile').addClass('active');
        $('.mobile-overlay').addClass('active');
    }
});

/* const navMobile = document.querySelector('.nav-mobile');
if(navMobile.classList.contains('active')){
    navMobile.classList.remove('active');
}else{
    navMobile.classList.add('active');
} */

/* *************** */
/* *************** */
// Page connexion.php
/* *************** */
/* *************** */

// Contrôle des conditions du mdp et maj en temps réel
$('#mdp-nouveau').on('input', function(){
    controleMotDePasseTpsReel(this);
});

//Contrôle au submit
function connexionInscription(){
    
    var retour = true;

    // Contrôle format adresse mail
    if(controleMail('#mail', '#champ-mail') == false)
        retour = false;

    // Contrôle conditions mot de passe
    if(controleMotDePasse() == false)
        retour = false;

    // Contrôle mdp et mdp de confirmation soit identitique
    if(controleMotDePasseIdentique() == false)
        retour = false;

    return false;

}




/* ************ */
/* ************ */
// Page compte.php
/* ************ */
/* ************ */

    /* ******** */
    // Partie mail
    /* ******** */

// Contrôle au submit de la partie mail
function compteMail(){

    var retour = true;

    // Contrôle format adresse mail
    if(controleMail('#mail', '#champ-mail') == false)
        retour = false;

    return retour;
}


    /* **************** */
    // Partie mot de passe
    /* **************** */

// Contrôle des conditions du mdp et maj en temps réel
$('#mdp-nouveau').on('input', function(){
    controleMotDePasseTpsReel(this);
});

// Contrôle au submit de la partie mdp
function compteMdp(){

    var retour = true;

    // Contrôle conditions mot de passe
    if(controleMotDePasse() == false)
        retour = false;

    // Contrôle mdp et mdp de confirmation soit identitique
    if(controleMotDePasseIdentique() == false)
        retour = false;
    
    return retour;
}


    /* **************************** */
    // Partie ajout serveur à la liste
    /* **************************** */

// Ouverture fermeture du formulaire d'ajout de serveur
$('#ajout-serveur').on('click', function(){
    $('.form-ajout-serveur').slideToggle(400);
});

// Garder ouvert si erreur sur les champs
if(!ajout){
    $('.form-ajout-serveur').show();
}

// Contrôle au submit de la partie ajout de serveur à la liste
function compteAjoutServeur(){

    var retour = true;

    // Contrôle que le serveur soit bien dans la liste
    var tabListeServeur = listeServeur.split(',');

    $('#msg-select-nom-serveur').remove();
    if(jQuery.inArray($('#select-nom-serveur').val(), tabListeServeur) == -1){
        $('#div-select-nom-serveur').append('<p id="msg-select-nom-serveur"  class="text-danger"> On choisit dans la liste svp </p>');
        retour = false;
    }else   
        $('#msg-select-nom-serveur').remove();

    // Contrôle que le nom joueur soit entre 2 et 20 caractères
    var longueurNomPerso = $('#select-nom-perso').val().length;

    $('#msg_compte_select_nom_perso').remove();
    if(longueurNomPerso < 2 || longueurNomPerso > 20){
        $('#select-nom-joueur').append('<p id="msg_compte_select_nom_perso" class="text-danger"> Entre 2 et 20 caractères </p>');
        retour = false;
    }else
        $('#msg_compte_select_nom_perso').remove();


    // Contrôle que le nom discord soit entre 2 et 20 caractères s'il n'est pas vide
    var longueurNomDiscord = $('#select-nom-discord').val().length;

    $('#msg_compte_select_nom_discord').remove();
    if(longueurNomDiscord){
        if(longueurNomDiscord < 2 || longueurNomDiscord > 20){
            $('#select-nom-discord').append('<p id="msg_compte_select_nom_discord" class="text-danger"> Entre 2 et 20 caractères </p>');
            retour = false;
        }else
            $('#msg_compte_select_nom_discord').remove();
    }

    return retour;
}


    /* *********** */
    // Partie serveur
    /* *********** */

// Toggle sur l'accordeon
$('.accordion-button').on('click', function(){
    var idAccordeon = $(this).attr('id').substr(8);
    $('.accordion-button').removeClass('active-menu-min');
    $('#boutton-'+idAccordeon).toggleClass('active-menu-min');
});


// Contrôle au submit de la partie serveur
// Impossible de récupérer l'id au submit donc récup au clic avant
var recupIdServeur;
$('.valide-serveur').on('click', function(){
    recupIdServeur = $(this).attr('id').substr(15);
});

function compteServeur(){
 
    var retour = true;

    // Contrôle que le nom joueur soit entre 2 et 20 caractères
    var longueurNomPerso = $('#nom-perso-'+recupIdServeur).val().length;

    $('#msg_compte_nom_perso_'+recupIdServeur).remove();
    if(longueurNomPerso < 2 || longueurNomPerso > 20){
        $('#div-nom-perso-'+recupIdServeur).append('<p id="msg_compte_nom_perso_'+recupIdServeur+'" class="text-danger"> Entre 2 et 20 caractères </p>');
        retour = false;
    }else
        $('#msg_compte_nom_perso'+recupIdServeur).remove();


    // Contrôle que le nom discord soit entre 2 et 20 caractères s'il n'est pas vide
    var longueurNomDiscord = $('#nom-discord-'+recupIdServeur).val().length;

    $('#msg_compte_select_nom_discord_'+recupIdServeur).remove();
    if(longueurNomDiscord){
        if(longueurNomDiscord < 2 || longueurNomDiscord > 20){
            $('#div-non-discord-'+recupIdServeur).append('<p id="msg_compte_select_nom_discord_'+recupIdServeur+'" class="text-danger"> Entre 2 et 20 caractères </p>');
            retour = false;
        }else
            $('#msg_compte_select_nom_discord_'+recupIdServeur).remove();
    }

    return retour;
}


    /* **************************** */
    // Partie suppression d'un serveur
    /* **************************** */

// Garder ouvert si erreur sur les champs
if(supprimer){
    $('#form-supprimer-'+supprimer).show();
    $('#btn-supprimer-'+supprimer).toggle();
}

// Action sur le bouton "supprimer ce serveur"
$('.btn-supprimer').on('click', function(){
    $(this).hide();

    // Récupération de l'id
    var id = $(this).attr('id').substr(14);
    // Toogle uniquement sur ce serveur
    $('#form-supprimer-'+id+'').slideToggle(400);
});

// Action sur le bouton "annuler" de la partie suppression
$('.btn-annuler').on('click', function(){
    // Récupération de l'id
    var id = $(this).attr('id').substr(12);
    // Toogle uniquement sur ce serveur
    $('#form-supprimer-'+id).slideUp(400);  
    $('#btn-supprimer-'+id).show();  
})

// Contrôle au submit de la partie suppression d'un serveur
// Impossible de récupérer l'id au submit donc récup au clic avant
var recupIdSupprimer;
$('.valide-supprimer').on('click', function(){
    recupIdSupprimer = $(this).attr('id').substr(17);
});

function compteSupprimerServeur(){
 
    var retour = true;

    // Contrôle que l'utilisateur ait recopier le nom du serveur correctement
    var nomServeur = $('#nom-serveur-'+recupIdSupprimer).text().trim();
    var nomServeurInput = $('#supprimer-'+recupIdSupprimer).val();

    $('#msg_compte_supprimer').remove();
    if(nomServeur != nomServeurInput){
        $('#champ-supprimer-serveur-'+recupIdSupprimer).append('<p id="msg_compte_supprimer" class="text-danger"> Le nom du serveur est incorrect (Respectez les majuscules) </p>');
        retour = false;
    }

     return retour;
}


    /* ************************* */
    // Partie suppression du compte
    /* ************************* */

$('#bouton-supprimer-compte').on('click', function(){
    $('#confirmation-supprimer-compte').slideToggle(400);
});




/* *********** */
/* *********** */
// Page ajout.php
/* *********** */
/* *********** */

// Toogle sur les checkbox plan et objet afin de toogle l'input prix1 et prix2
$('#objet').change(function(){
    $('.prix1').toggle();
    $('#prix1').val('');
});

$('#plan').change(function(){
    $('.prix2').toggle();
    $('#prix2').val('');
});


// Gère l'affichage de l'input type radio concernant les cas ou les créatures peuvent porter des selles et plateformes (affichage pour choix de l'utilisateur), ou uniquement des selles (non affichage mais checked de l'input correspondant)
$('#nom').change(function(){
    var nom = $(this).val();
    if(plateforme.indexOf(nom) != -1 && plateformeSeule.indexOf(nom) == -1){
        $('.taille').show();
        $('.selle').attr('checked', true);    
        $('.plateforme').attr('checked', false);    
    }else if(plateformeSeule.indexOf(nom) != -1){
        $('.taille').hide();
        $('.plateforme').attr('checked', true);    
        $('.selle').attr('checked', false);    
    }else{
        $('.taille').hide();
        $('.plateforme').attr('checked', false);    
        $('.selle').attr('checked', true);
    }
});


// Contrôle sur l'input name="nom" afin de vérifier s'il fait bien partie de la datalist
var liste = '',
    infoTypeJs;
switch(infoTypeJs){
    case 'creature' :
        liste = creature;
    break;
    case 'selle' :
        liste = selle;
    break;
    case 'arme' :
        liste = arme;
    break;
    case 'armure' :
        liste = armure;
    break;
}
// Contrôle si le nom fait bien partie de la datalist
$('#nom').on('blur', function(){

    if(liste.indexOf($(this).val()) == -1){
        $('#message').text('Veuillez choisir un nom de la liste');
    }else
        $('#message').text('');
})


// Contrôle si prix1 est bien numérique
$('#prix1').on('blur', function(){
    controleNumerique($(this).val(), messageChiffre)
});

// Contrôle si prix2 est bien numérique
$('#prix2').on('blur', function(){
    controleNumerique($(this).val(), messageChiffre)
});

// Contrôle si les caractéristiques sont bien numériques
$('#vie').on('blur', function(){
    var tabCaracteristique = [$('#vie').val(), $('#energie').val(),
                                           $('#oxygene').val(),
                                           $('#nourriture').val(),
                                           $('#poids').val(),
                                           $('#attaque').val(),
                                           $('#vitesse').val()];
    controleNumerique(tabCaracteristique, messageChiffre)
});
$('#energie').on('blur', function(){
    var tabCaracteristique = [$('#vie').val(), $('#energie').val(),
                                           $('#oxygene').val(),
                                           $('#nourriture').val(),
                                           $('#poids').val(),
                                           $('#attaque').val(),
                                           $('#vitesse').val()];
    controleNumerique(tabCaracteristique, messageChiffre)
});
$('#oxygene').on('blur', function(){
    var tabCaracteristique = [$('#vie').val(), $('#energie').val(),
                                           $('#oxygene').val(),
                                           $('#nourriture').val(),
                                           $('#poids').val(),
                                           $('#attaque').val(),
                                           $('#vitesse').val()];
    controleNumerique(tabCaracteristique, messageChiffre)
});
$('#nourriture').on('blur', function(){
    var tabCaracteristique = [$('#vie').val(), $('#energie').val(),
                                           $('#oxygene').val(),
                                           $('#nourriture').val(),
                                           $('#poids').val(),
                                           $('#attaque').val(),
                                           $('#vitesse').val()];
    controleNumerique(tabCaracteristique, messageChiffre)
});
$('#poids').on('blur', function(){
    var tabCaracteristique = [$('#vie').val(), $('#energie').val(),
                                           $('#oxygene').val(),
                                           $('#nourriture').val(),
                                           $('#poids').val(),
                                           $('#attaque').val(),
                                           $('#vitesse').val()];
    controleNumerique(tabCaracteristique, messageChiffre)
});
$('#attaque').on('blur', function(){
    var tabCaracteristique = [$('#vie').val(), $('#energie').val(),
                                           $('#oxygene').val(),
                                           $('#nourriture').val(),
                                           $('#poids').val(),
                                           $('#attaque').val(),
                                           $('#vitesse').val()];
    controleNumerique(tabCaracteristique, messageChiffre);
});
$('#vitesse').on('blur', function(){
    var tabCaracteristique = [$('#vie').val(), $('#energie').val(),
                                           $('#oxygene').val(),
                                           $('#nourriture').val(),
                                           $('#poids').val(),
                                           $('#attaque').val(),
                                           $('#vitesse').val()];
    controleNumerique(tabCaracteristique, messageChiffre);
});

// Contrôle si niveau est bien numérique
$('#niveau').on('blur', function(){
    controleNumerique($(this).val(), messageChiffre);
});

// Contrôle si armure est bien numérique
$('input[name="armure"]').on('blur', function(){
    controleNumerique($(this).val(), messageChiffre);
});

// Contrôle si résistance au froid est bien numérique
$('#froid').on('blur', function(){
    controleNumerique($(this).val(), messageChiffre);
});

// Contrôle si résistance à la chaleur est bien numérique
$('#chaleur').on('blur', function(){
    controleNumerique($(this).val(), messageChiffre)
});

// Contrôle si durabilité est bien numérique
$('#durabilite').on('blur', function(){
    controleNumerique($(this).val(), messageChiffre)
});

// Contrôle qu'un "sexe" a bien été sélectionné au click d'un des checkbox
/* $('input[value="mâle"]').on('click', function(){
    var tabSexe = [$('input[value="mâle"]').is(':checked'), $('input[value="femelle"]').is(':checked'),
                                                            $('input[value="castré"]').is(':checked')];
    controleCheckbox(messageSexe, tabSexe);
});
$('input[value="femelle"]').on('click', function(){
    var tabSexe = [$('input[value="mâle"]').is(':checked'), $('input[value="femelle"]').is(':checked'),
                                                            $('input[value="castré"]').is(':checked')];
    controleCheckbox(messageSexe, tabSexe);
});
$('input[value="castré"]').on('click', function(){
    var tabSexe = [$('input[value="mâle"]').is(':checked'), $('input[value="femelle"]').is(':checked'),
                                                            $('input[value="castré"]').is(':checked')];
    controleCheckbox(messageSexe, tabSexe);
}); */

// Contrôle qu'un "type" a bien été sélectionné au click d'un des checkbox
/* $('input[value="objet"]').on('click', function(){
    var tabType = [$('input[value="objet"]').is(':checked'), $('input[value="plan"]').is(':checked')];
    controleCheckbox(messageType, tabType);
});
$('input[value="plan"]').on('click', function(){
    var tabType = [$('input[value="objet"]').is(':checked'), $('input[value="plan"]').is(':checked')];
    controleCheckbox(messageType, tabType);
}); */

// Contrôle au submit de la partie ajout.php
function verif(){

    var retour = true;

    if(infoTypeJs == 'creature'){
        // Contrôle qu'un "sexe" a bien été sélectionné
        var tabSexe = [$('input[value="mâle"]').is(':checked'), $('input[value="femelle"]').is(':checked'),
                                                                $('input[value="castré"]').is(':checked')];
        if(!controleCheckbox(messageSexe, tabSexe))
            retour = false;

    }else if(infoTypeJs == 'selle'){   
        // Contrôle qu'un "type" a bien été sélectionné
        var tabType = [$('input[value="objet"]').is(':checked'), $('input[value="plan"]').is(':checked')];
        if(!controleCheckbox(messageType, tabType))
            retour = false;
    }

    return retour;
}


