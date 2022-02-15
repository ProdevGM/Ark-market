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



// fonction recherche catégorie en fonction de la créature sélectionnée
function rechercheCategorie($tabSelect, $nomSelect){

		foreach($tabSelect AS $indice => $valeur){
			if(array_search($nomSelect, $tabSelect[$indice]) !== false){
				return $indice;
			}
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
}



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




