<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RayaanUddin Shop</title>
    <link rel="stylesheet" href="style/reset.css">
    <link rel="stylesheet" href="style/style.css">
    <script src="https://kit.fontawesome.com/a7ff570bc3.js" crossorigin="anonymous"></script>
    <script src="javascript/script.js" type="application/javascript" defer></script>
</head>
<body>
<div class="container">
    <?php
    include('partials/header.php');
    if (!(isset($_SESSION['user']) && $_SESSION['user']->isLoggedIn())) {
        header('Location: login.php');
        exit();
    }

    $stripe = new \Stripe\StripeClient(STRIPE_PUBLISHABLE_KEY);
    $billing_portal = $stripe->billingPortal->sessions->create([
        'customer' => $_SESSION['user']->customer()->id(),
        'return_url' => ROOT_URL . ROOT_PATH . "/account.php",
    ]);
    ?>
    <main>
        <h1>Account</h1>
        <p>Manage your account</p>
        <div class="account">
            <section id="change_password">
                <h2>Change Password</h2>
                <form action="models/update_account.php" id="update_password_form" method="POST">
                    <label for="current_password">Current Password</label>
                    <input type="password" name="current_password" id="current_password" required>
                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" id="new_password" required>
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                    <button type="submit">Change Password</button>
                </form>
            </section>
            <section id="change_email">
                <h2>Change Email</h2>
                <form action="models/update_account.php" method="POST">
                    <label for="email">New Email</label>
                    <input type="email" name="email" id="email" required>
                    <button type="submit">Change Email</button>
                </form>
            </section>
            <section id="change_name">
                <h2>Change Name</h2>
                <form action="models/update_account.php" method="POST">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" required>
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" required>
                    <button type="submit">Change Name</button>
                </form>
            </section>
            <section id="buttons">
                <h2>Actions</h2>
                <a href="models/logout.php">Logout</a>
                <a href="models/delete_account.php">Delete Account</a>
                <a href="<?php echo $billing_portal->url; ?>">Billing Portal</a>
            </section>
        </div>
    </main>
    <?php include('partials/footer.php'); ?>
</div>

<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Price Options</h2>
        <div id="price-options"></div>
    </div>
</div>
</body>
</html>

