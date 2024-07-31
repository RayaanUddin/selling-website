<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RayaanUddin Shop</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/form.css">
    <script src="https://kit.fontawesome.com/a7ff570bc3.js" crossorigin="anonymous"></script>
    <script src="javascript/script.js" type="application/javascript" defer></script>
</head>
<body>
<div class="form-container">
    <?php include 'partials/header.php'; ?>
    <form action="models/login_verification.php" method="post">
        <h1>Login</h1>
        <div class="wrap-input">
            <input type="email" name="email" placeholder="" id="email">
            <label for="email">Email</label>
        </div>
        <div class="wrap-input">
            <input type="password" name="password" id="password" placeholder=""/>
            <label for="password">Password</label>
        </div>
        <?php if (isset($_GET['error'])) {
            echo "<div class='error'><p>".$_GET['error']."</p></div>";
        }
        ?>
        <input type="submit" value="Sign in">
        <aside>
            <h2>Want an account?</h2>
            <p>
                Signup today, <a href="signup.php">here</a>,
                <br>or reset your password, <a href="reset_password.php">here</a>, if you have an existing account.
            </p>
        </aside>
    </form>
    <?php
    include 'partials/footer.php';
    ?>
</div>
</body>
</html>