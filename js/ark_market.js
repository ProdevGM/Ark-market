

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






/* *** */
// AUTRES
/* *** */

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
var liste = 'salut',
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
    console.log('salut');
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

// Contrôle au submit
function verif(){

    var ok = true;

    if(infoTypeJs == "creature"){
        // Contrôle qu'un "sexe" a bien été sélectionné
        var tabSexe = [$('input[value="mâle"]').is(':checked'), $('input[value="femelle"]').is(':checked'),
                                                                $('input[value="castré"]').is(':checked')];
        if(!controleCheckbox(messageSexe, tabSexe))
            ok = false;

    }else if(infoTypeJs == "selle"){   
        // Contrôle qu'un "type" a bien été sélectionné
        var tabType = [$('input[value="objet"]').is(':checked'), $('input[value="plan"]').is(':checked')];
        if(!controleCheckbox(messageType, tabType))
            ok = false;
    }

    return ok;
}


