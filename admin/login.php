
<?php
session_start();
#ob_start();
#include('config.php');


$error_message = '';

#$kasutajanimi = 'admin';
#$parool = 'Parool123';

#if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['kasutajanimi']) && !empty($_POST['parool'])) {
    $kasutajanimi = $_POST['kasutajanimi'];
    $parool = $_POST['parool'];

    #$paring = "SELECT * FROM kasutajad WHERE kasutajanimi='$kasutajanimi";
    #$valjund = mysqli_query($yhendus, $paring);

    if ($kasutajanimi === 'admin' && $parool === 'Parool123') {
    #if (mysqli_num_rows($valjund)==1) {
        $_SESSION['kasutajanimi']=1;
        header('Location: index.php');
        #exit;
    } else {

        $error_message = 'Vale kasutajanimi või parool!';
        #echo "Vale kasutajanimi või parool!";
}
}
#ob_end_flush();
?>

<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logi sisse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .login-box {
            width: 300px;
            padding: 20px;
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        .login-box h3 {
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h3>Logi sisse</h3>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div> 
        <?php endif; ?> 
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="kasutajanimi" class="form-label">Kasutajanimi</label>
                <input type="text" class="form-control" id="kasutajanimi" name="kasutajanimi" required>
            </div>
            <div class="mb-3">
                <label for="parool" class="form-label">Parool</label>
                <input type="parool" class="form-control" id="parool" name="parool" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Logi sisse</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>