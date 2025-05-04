<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION["user"])) {
        header("location: home.php");
        exit;
    }

    $error = "";

    if (isset($_GET['error'])) {
        $error = $_GET['error'];
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synesthesia - Login</title>
    <link rel="icon" href="images/logo_no_scritta.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="particles-container" id="particles-js"></div>

    <div class="container login-container">
        <div class="row">
            <div class="col-lg-6 login-form-container">
                <div class="login-card">
                    <div class="brand-container">
                        <h1 class="brand-name">Synesthesia</h1>
                        <p class="brand-tagline">Dove musica e moda si fondono</p>
                    </div>

                    <?php
                        if (!empty($error)) {
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }

                    ?>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="username" class="form-control" id="username" placeholder="Username" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-login" onclick="login()">Accedi</button>
                    </div>

                    <div class="alternative-actions">
                        <a href="registrazione.php" class="register">Registrati</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block visual-container">
                <div class="mood-visualizer">
                    <div class="color-blob blob1"></div>
                    <div class="color-blob blob2"></div>
                    <div class="color-blob blob3"></div>
                    <div class="music-wave">
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>