<?php
include('config.php'); // Ühendus andmebaasiga

// Kontrollime, kas vormi on esitatud
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kasutajanimi = mysqli_real_escape_string($yhendus, $_POST['kasutajanimi']);
    $kommentaar = mysqli_real_escape_string($yhendus, $_POST['kommentaar']);
    $hinnang = (int) $_POST['hinnang']; // Eeldame, et hinnang on arv
    $asutused_id = (int) $_POST['asutused_id'];

    // Kontrollime, kas kõik väljad on täidetud
    if (empty($kasutajanimi) || empty($kommentaar) || empty($hinnang) || empty($asutused_id)) {
        echo "Kõik väljad on kohustuslikud!";
    } else {
        // Sisestame andmed andmebaasi
        $query = "INSERT INTO hinnangud (kasutajanimi, kommentaar, hinnang, asutused_id) VALUES ('$kasutajanimi', '$kommentaar', $hinnang, $asutused_id)";
        if (mysqli_query($yhendus, $query)) {
            echo "Andmed on salvestatud!";
            // Suuname kasutaja tagasi avalehele
            header('Location: index.php');
            exit();
        } else {
            echo "Viga andmete salvestamisel: " . mysqli_error($yhendus);
        }
    }
}

// Siin on kood, et näidata hindamise vormi
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $asutused_id = (int) $_GET['id'];
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Hinda kohta</title>
</head>
<body>
    <h1>Hinda kohta</h1>
    <form action="hindamine.php" method="post">
        <label for="kasutajanimi">Nimi:</label>
        <input type="text" name="kasutajanimi" required><br>

        <label for="kommentaar">Kommentaar:</label>
        <textarea name="kommentaar" required></textarea><br>

        <label for="hinnang">Hinnang:</label>
        <input type="radio" name="hinnang" value="1" required>1
        <input type="radio" name="hinnang" value="2">2
        <input type="radio" name="hinnang" value="3">3
        <input type="radio" name="hinnang" value="4">4
        <input type="radio" name="hinnang" value="5">5
        <input type="radio" name="hinnang" value="6">6
        <input type="radio" name="hinnang" value="7">7
        <input type="radio" name="hinnang" value="8">8
        <input type="radio" name="hinnang" value="9">9
        <input type="radio" name="hinnang" value="10">10<br>

        <!-- Sisestame asutuse ID peidetud väljana -->
        <input type="hidden" name="asutused_id" value="<?php echo $asutused_id; ?>">

        <button type="submit">Saada</button>
    </form>

    <a href="index.php">Tagasi</a>
</body>
</html>

<?php
}
?>
