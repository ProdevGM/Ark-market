

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



