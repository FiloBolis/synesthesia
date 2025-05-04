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
    <title>Synesthesia - Registrazione</title>
    <link rel="icon" href="images/logo_no_scritta.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/registrazione.css">
</head>
<body>
    <div class="particles-container" id="particles-js"></div>

    <div class="container login-container">
        <div class="row">
            <div class="col-lg-6 login-form-container">
                <div class="login-card">
                    <div class="brand-container">
                        <h1 class="brand-name">Synesthesia</h1>
                        <p class="brand-tagline">Crea il tuo account</p>
                    </div>

                    <?php
                        if (!empty($error)) {
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }

                    ?>

                    <!-- Messaggio di validazione -->
                    <div id="validationMessage" class="alert alert-danger d-none"></div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Conferma Password" required>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-login" onclick="registrazione()">Registrati</button>
                    </div>

                    <div class="alternative-actions">
                        <span>Hai già un account?</span>
                        <a href="index.php" class="register">Accedi</a>
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
    <script src="js/registrazione.js"></script>
</body>
</html>