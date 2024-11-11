<?php include('config.php'); ?>
<?php

session_start();


$error_message = '';

#$correct_username = 'admin';
#$correct_password = 'Parool123';


#if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    if (!empty($_GET['kasutajanimi']) && !empty($_GET['parool'])) {
    $kasutajanimi = $_GET['kasutajanimi'];
    $parool = $_GET['parool'];

   
    if ($kasutajanimi === 'admin' && $parool === 'Parool123') {
        $_SESSION['login']="1";  
        header("Location: index.php");  
        exit;
    } else {
        echo "Vale kasutajanimi või parool";  
    }
}
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
        <form action="login.php" method="get">
            <div class="mb-3">
                <label for="username" class="form-label">Kasutajanimi</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Parool</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Logi sisse</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>