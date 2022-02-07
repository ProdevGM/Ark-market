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


// Test si utilisateur = 1 = admin
/* function user_is_admin() {
	if(user_is_connect() && $_SESSION['membre']['statut'] == 1) {
		return true;
	} else {
		return false;
	}
} */


/* vd($_SESSION); */
/* vd($_COOKIE); */
vd($_POST);




