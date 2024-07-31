<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RayaanUddin Shop</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/form.css">
    <script src="https://kit.fontawesome.com/a7ff570bc3.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="form-container">
    <?php
    include 'partials/header.php';
    ?>
    <form action="models/create_account.php" method="post" id="create-account-form">
        <h1>Signup</h1>
        <div class="wrap-input">
            <input type="text" id="fname" name="fname" pattern="[A-Za-z]{3,50}" title="Name must contain alphabet letters. Length must be between 3 and 50." placeholder>
            <label for="fname">First Name</label>
        </div>
        <div class="wrap-input">
            <input type="text" id="lname" name="lname" pattern="[A-Za-z]{3,50}" title="Name must contain alphabet letters. Length must be between 3 and 50." placeholder>
            <label for="lname">Last Name</label>
        </div>
        <div class="wrap-input">
            <input type="email" name="email" placeholder="" id="email">
            <label for="email">Email</label>
        </div>
        <div class="wrap-input">
            <input type="password" id="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least a number, a uppercase, and at least 8 or more characters" placeholder>
            <label for="password">Password</label>
        </div>
        <div class="wrap-input">
            <input type="password" id="reenter-password" name="reenter-password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least a number, a uppercase, and at least 8 or more characters" placeholder>
            <label for="reenter-password">Repeat Password</label>
        </div>
        <div class="check-input">
            <input type="checkbox" id="allow-junk-mail" name="allow-junk-mail">
            <p>I agree to receive general emails and product offers</p>
        </div>
        <?php if (isset($_GET['error'])) {
            echo "<div class='error'><p>".$_GET['error']."</p></div>";
        }
        ?>
        <input type="submit" value="Create Account">
    </form>
    <?php
    include 'partials/footer.php';
    ?>
</div>
</body>
</html>