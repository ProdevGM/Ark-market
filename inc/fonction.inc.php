<?php

// afficher
function vd($valeur){
	echo '<pre>';
	var_dump($valeur);
	echo '</pre>';
}



// fonction pour savoir si l'utilisateur est connecté
function user_is_connect() {
	if(!empty($_SESSION['utilisateur'])) {
		return true; // utilisateur est connecté
	}
	return false; // utilisateur n'est pas connecté
}



// Fonction recherche catégorie en fonction de la créature sélectionnée
function rechercheCategorie($tabSelect, $nomSelect){

	foreach($tabSelect AS $indice => $valeur){
		if(array_search($nomSelect, $tabSelect[$indice]) !== false){
			return $indice;
		}
	}
}


// Fonction qui recherche la présence de valeur d'un tableau dans un autre tableau
function rechercheTab($tab1, $tab2){

	for($i=0; $i<count($tab1); $i++){
		if(array_search($tab1[$i], $tab2) === false){
			return true;
		}                   
	}
}


// Fonction contrôlant la présence, le nombre de caractère et le caractère numérique d'une variable
function analyse($variable, $taille, $message){

	global $msg;
	
	if(empty($variable))
		$msg .= "<p class=\"alerte-msg\"> $message_obligatoire </p>";
	elseif(!is_numeric($variable))
		$msg .= "<p class=\"alerte-msg\"> $message_chiffre </p>";
	elseif(iconv_strlen($variable) > $taille)
		$msg .= "<p class=\"alerte-msg\"> $taille chiffres $message_limite_caractere</p>";
}


/* 	switch($tabSelect){
		case 'tabCreature' :
			global $tab_creature;
			$tab = $tab_creature;
			break;
		case 'tabArme' :
			global $tab_arme;
			$tab = $tab_arme;
			break;
		case 'tabArmure' :
			global $tab_armure;
			$tab = $tab_armure;
			break;
	} */



/* 	if($tab == 'tabCreature')
		global $tab_creature;

	if(array_search($nomSelect, $tab_creature['terrestre']) !== false)
		return 'terrestre';
	elseif(array_search($nomSelect, $tab_creature['volant']) !== false)
		return 'volant';
	elseif(array_search($nomSelect, $tab_creature['aquatique']) !== false)
		return 'aquatique';	 */			




// Test si utilisateur = 1 = admin
/* function user_is_admin() {
	if(user_is_connect() && $_SESSION['membre']['statut'] == 1) {
		return true;
	} else {
		return false;
	}
} */


vd($_SESSION);
/* vd($_COOKIE); */
vd($_POST);




