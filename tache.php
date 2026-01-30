<?php
$fichier = "tache.json";

// Charger les tâches depuis le JSON et decode permet de transformer le code json 
//en tableau associatif 
$taches = [];
if (file_exists($fichier)) {
    $taches = json_decode(file_get_contents($fichier), true);
}

// Ajouter ou modifier une tâche
if (isset($_POST['ajouter'])) {
    $tache = [
        'titre' => $_POST['titre'],
        'description' => $_POST['description'],
        'statut' => $_POST['statut']
    ];

    if (isset($_GET['modifier'])) {
        // Modification
        $indice = $_GET['modifier'];
        $taches[$indice] = $tache;
    } else {
        // Ajout
        $taches[] = $tache;

    }

    // Sauvegarder dans JSON
    file_put_contents($fichier, json_encode($taches, JSON_PRETTY_PRINT));

    // Redirection 
        header('location:tache.php');

}

// Supprimer une tâche
if (isset($_GET['supprimer'])) {
    $indice = $_GET['supprimer'];
    array_splice($taches, $indice, 1); 
    file_put_contents($fichier, json_encode($taches, JSON_PRETTY_PRINT));
        header('location:tache.php');
    
}

// Pré-remplir le formulaire si modification
$tache_a_modifier = null;
if (isset($_GET['modifier'])) {
    $indice = $_GET['modifier'];
    $tache_a_modifier = $taches[$indice];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Tâches</title>
    <link href="css/bootstrap.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="text-center mb-4">Gestion des Tâches</h2>

    <div class="row">
        <!-- Formulaire à gauche -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Ajouter  une tâche</div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label>Titre</label>
                            <input type="text" name="titre" class="form-control" required
                                   value="<?= $tache_a_modifier['titre'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" required><?= $tache_a_modifier['description'] ?? '' ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Statut</label>
                            <select name="statut" class="form-select">
                                <option value="En cours" <?= isset($tache_a_modifier) && $tache_a_modifier['statut']=="En cours" ? "selected" : "" ?>>En cours</option>
                                <option value="Terminé" <?= isset($tache_a_modifier) && $tache_a_modifier['statut']=="Terminé" ? "selected" : "" ?>>Terminé</option>
                            </select>
                        </div>

                        <button type="submit" name="ajouter" class="btn btn-success w-100">
                            <?= isset($tache_a_modifier) ? "Modifier" : "Ajouter" ?> la tâche
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des cartes à droite -->
        <div class="col-md-8">
            <div class="row">
                <?php foreach ($taches as $i => $t): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5><?= $t['titre'] ?></h5>
                                <p><?= $t['description'] ?></p>
                                <span class="badge <?= $t['statut']=="En cours" ? "bg-warning text-dark" : "bg-success" ?>">
                                    <?= $t['statut'] ?>
                                </span>
                                <hr>
                                <a href="?modifier=<?= $i ?>" class="btn btn-warning btn-sm">Modifier</a>
                                <a onclick="return confirm('Voulez-vous supprimer cette tâche ?');" href="?supprimer=<?= $i ?>" class="btn btn-danger btn-sm">Supprimer</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>

</body>
</html>
