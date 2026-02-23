<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Créneaux disponibles</title>
<link rel="stylesheet" href="style.css">
</head>

<body style="min-height: 100vh; display: flex; flex-direction: column;">

<footer style="background-color: #240046; margin-top: 3rem;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 4rem 1rem; text-align: center;">

        <h3 style="color: #ffffff; font-weight: 700; letter-spacing: 1px;">SallePro</h3>
        <br>
        <br>
        <br>

        <p style="color: #1c1c1c;background-color:  #ffffff; font-size: 0.9rem;
    font-weight: 700;
    border: none;
    border-radius: 50px;
    padding: 0.8rem 1rem;
    cursor: default;
        font-family: 'Trebuchet MS', sans-serif;

        display: inline-block;

    letter-spacing: 0.5px;;">
            Plateforme de gestion des réservations de salle
        </p>
        <br>
        <br>

        <p style="color: #f8f9fa; font-size: 0.8rem; margin-bottom: 0;">
            © <span id="year"></span> SallePro . Tous droits réservés.
        </p>

    </div>
</footer>

<script>
    document.getElementById("year").textContent = new Date().getFullYear();
</script>
</script>


</body>
</html>
