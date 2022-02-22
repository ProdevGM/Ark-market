<?php
/* error_reporting(E_ALL); //Permet d'afficher les erreurs
ini_set("display_errors", 1); */

// Connexion à la BDD
$host_db = 'mysql:host=localhost;dbname=ark-market'; 
$login = 'root'; 
$password = ''; 
$options = array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, 
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' 
				);				
$pdo = new PDO($host_db, $login, $password, $options);


// Création d'une variable destinée à afficher des messages utilisateur
$msg = "";
$annonce_top = "";
$controle_variables = true;


// ouverture d'une session
session_start();


// déclaration de constante
// URL racine du projet
define('URL', 'http://ark-market/'); // lien absolu racine du projet
// Chemin racine du serveur
define('SERVER_ROOT', $_SERVER['DOCUMENT_ROOT']);	
// Chemin racine du dossier du site depuis le serveur
define('SITE_ROOT', '/ark-market/');

?>
<script>
	var info_type_js;
</script>
<?php


// Tableau qualité des produits (sauf créatures)
$tab_qualite = ['Commun', 'Inhabituel', 'Rare', 'Épique', 'Légendaire', 'Mythique'];

// Tableau sexe des créatures
$tab_sexe = ['mâle', 'femelle', 'castré'];

// Tableau type des produits (sauf créatures)
$tab_type = ['objet', 'plan'];

// Tableau recenssant toutes les catégories en fonction de la nature du produit
$tab_categorie_creature_selle = ['terrestre', 'volant', 'aquatique'];
$tab_categorie_arme = ['outil', 'mélé', 'bouclier', 'jet', 'feu', 'accessoire', 'explosif', 'piège', 'tourelle', 'tek'];
$tab_categorie_armure = ['tissu', 'cuir', 'fourrure', 'desert', 'camouflage', 'chitine', 'métal', 'radiation', 'plongé', 'emeute', 'tek'];

// Tableau recenssant toutes les créatures, selles, armes, armures et catégorie
$tab_creature = [
	'terrestre' => ['Achatina', 'Allosaure', 'Amargasaurus', 'Andrewsarchus', 'Ankylosaure', 'Aranéo', 'Arthropleura', 'Baryonyx', 'Basilic', 'Beelzebufo', 'Bloodstalker', 'Bousier', 'Brontosaure', 'Bulbdog', 'Carbonemys', 'Carnotaure', 'Castoroides', 'Chalicotherium', 'Compy', 'Daeodon', 'Deinonychus', 'Dilophosaure', 'Dimétrodon', 'Dimorphodon', 'Dinopithèque', 'Diplocaulus', 'Diplodocus', 'Dodo', 'Doedicurus', 'Dragon Rocheux', 'Equus', 'Exécuteur', 'Exo-Mek', 'Faucheur', 'Ferox', 'Gacha', 'Gallimimus', 'Gecko luisant', 'Giganotosaure', 'Gigantopithèque', 'Golem de pierre', 'Hesperornis', 'Hyène', 'Iguanodon', 'Jerboa', 'Kairuku', 'Kaprosuchus', 'Karkinos', 'Kentrosaure', 'Lézard épineux', 'Loup Sinistre', 'Loutre', 'Lumicorne', 'Lystrosaure', 'Magmasaure', 'Mammouth', 'Mantis', 'Megalania', 'Mégalocéros', 'Mégalosaure', 'Mégathérium', 'Mek', 'Mésopithèque', 'Microraptor', 'Morellatops', 'Moschops', 'Noglin', 'Ours Sinistre', 'Oviraptor', 'Ovis', 'Pachycéphalosaure', 'Pachyrhinosaure', 'Paraceratherium', 'Parasaure', 'Pegomastax', 'Phiomia', 'Plumineux', 'Procoptodon', 'Pulmonoscorpius', 'Purlovia', 'Raptor', 'Rat des profondeurs', 'Ravageur', 'Rhinocéros laineux', 'Sangsue', 'Sarcosuchus', 'Shadowmane', 'Smilodon', 'Spinosaure', 'Stégosaure', 'Stryder Tek', 'T-Rex', 'Thérizinosaure', 'Thylacoleo', 'Titan de Glace', 'Titan des Forêts', 'Titan du Désert', 'Titanoboa', 'Titanosaure', 'Tricératops', 'Trilobite', 'Troodon', 'Vélonasaure', 'Yutyrannus'],
	'volant' => ['Abeille géante', 'Archaeopteryx', 'Astrocetus', 'Argentavis', 'Astrodelphis', 'Éclaireur', 'Griffon', 'Harfang des neiges', 'Ichthyornis', 'Lymantria', 'Maewing', 'Managarm', 'Oiseau-Terreur', 'Onyc', 'Pelagornis', 'Phénix', 'Ptéranodon', 'Quetzal', 'Sacagaz', 'Sinomacrops', 'Tapejara', 'Tropéognathus', 'Vautour', 'Voidwyrm', 'Wyverne', 'Wyverne de Cristal', 'Wyverne de Glace'],
	'aquatique' => ['Basilosaure', 'Baudroie abyssale', 'Coelacanthe', 'Dunkleosteus', 'Electrophorus', 'Ichthyosaure', 'Lamproie', 'Leedsichthys', 'Liopleurodon', 'Manta', 'Mégachelon', 'Mégalodon', 'Mosasaure', 'Piranha', 'Plésiosaure', 'Saumon', 'Tusoteuthis']
];

$creature_js = '';
foreach($tab_creature AS $indice => $valeur){
	$creature_js .= ' '.implode(" ", $tab_creature[$indice]);
}

$tab_selle = [
	'terrestre' => ['Allosaure', 'Amargasaurus', 'Ankylosaure', 'Aranéo', 'Arthropleura', 'Baryonyx', 'Basilic', 'Beelzebufo', 'Brontosaure', 'Carbonemys', 'Carnotaure', 'Castoroides', 'Chalicotherium', 'Daeodon', 'Deinonychus', 'Diplodocus', 'Doedicurus', 'Dragon Rocheux', 'Equus', 'Gacha', 'Gallimimus', 'Giganotosaure', 'Golem_de_pierre', 'Hyène', 'Iguanodon', 'Kaprosuchus', 'Karkinos', 'Lézard épineux', 'Magmasaure', 'Mammouth', 'Mantis', 'Megalania', 'Mégalocéros', 'Mégalosaure', 'Mégathérium', 'Morellatops', 'Ours sinistre', 'Pachycéphalosaure', 'Pachyrhinosaure', 'Paraceratherium', 'Parasaure', 'Phiomia', 'Brontosaure', 'Paraceratherium', 'Procoptodon', 'Pulmonoscorpius', 'Raptor', 'Rat des profondeurs', 'Ravageur', 'Rhinocéros laineux', 'Sarcosuchus', 'Smilodon', 'Spinosaure', 'Stégosaure', 'Thérizinosaure', 'Thylacoleo', 'T-rex', 'Tricératops', 'Vélonasaure', 'Yutyrannus'],
	'volant' => ['Argentavis', 'Astrocetus', 'Astrodelphis', 'Harfang des neiges', 'Lymantria', 'Maewing', 'Managarm', 'Oiseau-terreur', 'Pelagornis', 'Ptéranodon', 'Quetzal', 'Sacagaz', 'Tapejara', 'Tropéognathus'],
	'aquatique' => ['Basilosaure', 'Dunkleosteus', 'Ichthyosaure', 'Manta', 'Mégalodon', 'Mosasaure', 'Plésiosaure', 'Tusoteuthis']
];

$tab_plateforme = [
	'terrestre' => ['Brontosaure', 'Paraceratherium', 'Titanosaure'],
	'volant' => ['Quetzal'],
	'aquatique' => ['Mégachelon', 'Plésiosaure', 'Mosasaure',]
];

// Diff entre $tab_selle et $tab_plateforme afin de savoir quelle créature ne peut être équipée que d'une plateforme
// Stockage dans un string afin d'être lu par la suite en js
$plateforme_seule = '';
$plateforme = '';


foreach($tab_selle AS $indice => $valeur){
	$plateforme_seule .= ' '.implode(" ", array_diff($tab_plateforme[$indice], $tab_selle[$indice]));
}

foreach($tab_plateforme AS $indice => $valeur){
	$plateforme .= ' '.implode(" ", $tab_plateforme[$indice]);
}

$selle_js = '';
foreach($tab_selle AS $indice => $valeur){
	$selle_js .= ' '.implode(" ", $tab_selle[$indice]);
}
$selle_js .= $plateforme_seule; // Ajout des plateforme seule


$tab_arme = [
	'outil' => ['Pioche en pierre', 'Hache en pierre', 'Torche', 'Bâton lumineux', 'Pioche en métal', 'Hache en métal', 'Faucille', 'Piolet', 'Outil de taxidermie', 'Pinces', 'Lasso', 'Tronçonneuse', 'Foreuse'],
	'mélé' => ['Lance en bois', 'Lance en métal', 'Massue', 'Épée', 'Matraque électrique', 'Lance de joute', 'Épée Tek', 'Éventreuses Tek'],
	'bouclier' => ['Bouclier en bois', 'Bouclier en métal', 'Bouclier anti-émeute', 'Bouclier Tek'],
	'jet' => ['Lance-pierre', 'Boomerang', 'Bolas', 'Fouet', 'Arc', 'Arbalète', 'Arc à poulie', 'Arc Tek'],
	'feu' => ['Revolver', 'Fusil à canon long', 'Fusil à canon scié', 'Pistolet semi-auto', 'Fusil d\'assaut', 'Fusil de précision', 'Harpon', 'Lance-flamme', 'Lanterne de Charge', 'Minigun', 'Fusil Tek', 'Sniper Tek', 'Missile de croisière', 'Lance-grenade Tek', 'Canon d\'épaule Tek', 'Pistolet Tek'],
	'accessoire' => ['Lampe tactique', 'Viseur Holographique', 'Viseur Laser', 'Lunette de visée', 'Silencieux'],
	'explosif' => ['Grenade', 'Bocal de pétrole', 'Piège explosif improvisé', 'Grenade à fragmentation', 'Charge de C4', 'Lance-Roquettes', 'Mines sous-marines', 'Grenade Tek', 'Grenade de Gravité Tek'],
	'piège' => ['Grenade fumigène', 'Grenade empoisonnée', 'Piège avec alarme sonore', 'Piège narcotique', 'Piège à Ours', 'Grand Piège à Ours'],
	'tourelle' => ['Tourelle automatique', 'Tourelle automatique lourde', 'Tourelle Baliste', 'Tourelle Catapulte', 'Tourelle Minigun', 'Tourelle Lance-Roquettes', 'Canon', 'Tourelle Tek'],
	'tek' => ['Fusil Tek', 'Grenade Tek', 'Tourelle Tek', 'Épée Tek', 'Bouclier Tek', 'Sniper Tek', 'Grenade de Gravité Tek', 'Missile de croisière', 'Éventreuses Tek', 'Lance-grenade Tek', 'Canon d\'épaule Tek', 'Pistolet Tek', 'Arc Tek']
];

$arme_js = '';
foreach($tab_arme AS $indice => $valeur){
	$arme_js .= ' '.implode(" ", $tab_arme[$indice]);
}

$tab_armure = [
	'tissu' => ['Foulard en tissu', 'Chemise en tissu', 'Gants en tissu', 'Pantalon en tissu', 'Chaussures en tissu'],
	'cuir' => ['Casque en cuir', 'Veste en cuir', 'Gants en cuir', 'Pantalon en cuir', 'Bottes en cuir'],
	'fourrure' => ['Capuche en fourrure', 'Veste en fourrure', 'Gants en fourrure', 'Pantalon en fourrure', 'Chaussons en fourrure'],
	'désert' => ['Chapeau et lunettes du désert', 'Veste du désert', 'Gants du désert', 'Pantalon du désert', 'Bottes du désert'],
	'camouflage' => ['Masque de Camouflage', 'Veste de Camouflage', 'Gants de Camouflage', 'Pantalon de Camouflage', 'Bottes de Camouflage'],
	'chitine' => ['Casque en chitine', 'Plastron en chitine', 'Gantelets en chitine', 'Jambières en chitine', 'Bottes en chitine'],
	'métal' => ['Casque en métal', 'Plastron en métal', 'Gantelets en métal', 'Jambières en métal', 'Bottes en métal'],
	'radiation' => ['Casque anti-radiation', 'Veste anti-radiation', 'Gants anti-radiation', 'Pantalon anti-radiation', 'Bottes anti-radiation'],
	'plongé' => ['Masque de Plongée', 'Bouteille de Plongée', 'Combinaison de Plongée', 'Palmes de Plongée'],
	'émeute' => ['Casque Anti-Émeute', 'Veste Anti-Émeute', 'Gants Anti-Émeute', 'Pantalon Anti-Émeute', 'Bottes Anti-Émeute'],
	'tek' => ['Casque Tek', 'Plastron Tek', 'Gantelets Tek', 'Jambières Tek', 'Bottes Tek'],
	'utilitaire' => ['Attache pour moteur de tyrolienne', 'Casque de mineur', 'Lunettes de vision nocturne', 'Masque à gaz', 'Planeur dorsal', 'Menottes']
];

$armure_js = '';
foreach($tab_armure AS $indice => $valeur){
	$armure_js .= ' '.implode(" ", $tab_armure[$indice]);
}
?>






