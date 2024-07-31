<?php

// DB Params
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "root");
define("DB_NAME", "store");

// Stripe API
define("STRIPE_PUBLISHABLE_KEY", "sk_test_51PTE5aP1xDsj8dmpsZ1XdSW0C1dHbT7J7e2JG3ZI2EZJWB4RvGBKrPl121g2iCxjC3UIw9aEn4vWdRh0iJVRj8n700DuZtuXh7");

// App URL
define("ROOT_PATH", "/selling-website/app");
define("ROOT_URL", "https://selling-website.rayaanuddin.co.uk");

// Site Name
define("SITE_NAME", "DAWME INC");

// Checkout Links
define("CHECKOUT_SUCCESS_URL", ROOT_URL . ROOT_PATH . "/success.php");
define("CHECKOUT_CANCEL_URL", ROOT_URL . ROOT_PATH . "/cancel.php");

//product limit
define("PRODUCT_LIMIT", 8);

// Code expire time
define("CODE_EXPIRE_TIME", 300); // 5 minutes