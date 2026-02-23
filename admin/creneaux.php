<?php
require '../config.php';


/* ===========================
   AJOUTER UN CRENEAU
=========================== */
if(isset($_POST['ajouter'])){

    $date = $_POST['date'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];
    $type = htmlspecialchars($_POST['type_activite']);

    $stmt = $pdo->prepare("INSERT INTO creneaux(date, heure_debut, heure_fin, type_activite) VALUES(?,?,?,?)");
    $stmt->execute([$date, $heure_debut, $heure_fin, $type]);

    header("Location: creneaux.php");
    exit();
}

/* ===========================
   SUPPRIMER
=========================== */
if(isset($_GET['supprimer'])){
    $id = intval($_GET['supprimer']);
    $pdo->prepare("DELETE FROM creneaux WHERE id = ?")->execute([$id]);
    header("Location: creneaux.php");
    exit();
}

/* ===========================
   MODIFIER
=========================== */
if(isset($_POST['modifier'])){

    $id = $_POST['id'];
    $date = $_POST['date'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];
    $type = htmlspecialchars($_POST['type_activite']);

    $stmt = $pdo->prepare("UPDATE creneaux SET date=?, heure_debut=?, heure_fin=?, type_activite=? WHERE id=?");
    $stmt->execute([$date, $heure_debut, $heure_fin, $type, $id]);

    header("Location: creneaux.php");
    exit();
}

/* ===========================
   RECUPERATION
=========================== */
$creneaux = $pdo->query("SELECT * FROM creneaux ORDER BY date ASC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Gestion des créneaux</h3>

    <a href="home.php" class="btn btn-secondary">
         Retour au Dashboard
    </a>
</div>

<!-- FORMULAIRE AJOUT -->
<div class="card p-4 mb-4 shadow-sm">
<form method="POST">
    <div class="row g-3">

        <div class="col-md-3">
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="col-md-2">
            <input type="time" name="heure_debut" class="form-control" required>
        </div>

        <div class="col-md-2">
            <input type="time" name="heure_fin" class="form-control" required>
        </div>

        <div class="col-md-3">
            <input type="text" name="type_activite" class="form-control" placeholder="Type activité" required>
        </div>

        <div class="col-md-2">
            <button name="ajouter" class="btn  w-100" style="background-color:  #007200; color: #fff ;">
                Ajouter
            </button>
        </div>

    </div>
</form>
</div>

<!-- TABLEAU -->
<table class="table table-bordered table-success" >
    <thead class="" >
        <tr >
            <th>Date</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

    <?php foreach($creneaux as $c): ?>

        <tr>
            <td><?= $c['date'] ?></td>
            <td><?= $c['heure_debut'] ?></td>
            <td><?= $c['heure_fin'] ?></td>
            <td><?= htmlspecialchars($c['type_activite']) ?></td>

            <td>
                <!-- Supprimer -->
                <a href="?supprimer=<?= $c['id'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Supprimer ce créneau ?')">
                   Supprimer
                </a>

                <!-- Modifier -->
                <button class="btn btn-warning btn-sm"
                        onclick="remplirFormulaire(
                        '<?= $c['id'] ?>',
                        '<?= $c['date'] ?>',
                        '<?= $c['heure_debut'] ?>',
                        '<?= $c['heure_fin'] ?>',
                        '<?= $c['type_activite'] ?>'
                        )">
                    Modifier
                </button>
            </td>
        </tr>

    <?php endforeach; ?>

    </tbody>
</table>

</div>

<script>
function remplirFormulaire(id, date, debut, fin, type){

    document.querySelector("input[name='date']").value = date;
    document.querySelector("input[name='heure_debut']").value = debut;
    document.querySelector("input[name='heure_fin']").value = fin;
    document.querySelector("input[name='type_activite']").value = type;

    // créer input caché id
    let inputId = document.createElement("input");
    inputId.type = "hidden";
    inputId.name = "id";
    inputId.value = id;

    document.querySelector("form").appendChild(inputId);

    // changer bouton
    let btn = document.querySelector("button[name='ajouter']");
    btn.name = "modifier";
    btn.textContent = "Mettre à jour";
    btn.classList.remove("btn-primary");
    btn.classList.add("btn-success");
}
</script>

</body>
</html>
