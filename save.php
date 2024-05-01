// Récupérer les données envoyées par la requête POST
$id = $_POST['id'];
$url = $_POST['url'];

// Chemin vers le fichier de stockage
$cheminFichier = 'chemin/vers/le/fichier.json';

// Lire le contenu actuel du fichier (s'il existe)
$contenu = [];
if (file_exists($cheminFichier)) {
    $contenu = json_decode(file_get_contents($cheminFichier), true);
}

// Ajouter la nouvelle entrée
$contenu[] = array('id' => $id, 'url' => $url);

// Écrire les données dans le fichier
file_put_contents($cheminFichier, json_encode($contenu));

echo 'Données enregistrées dans le fichier avec succès.';
existex