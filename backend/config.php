<?php
// ==== MySQL ====
define('DB_HOST', 'localhost');        // production par hostinger/cpanel ka host
define('DB_NAME', 'portfolio_db');     // tumhara DB name
define('DB_USER', 'db_username');      // DB user
define('DB_PASS', 'db_password');      // DB password

// ==== Email (Gmail SMTP) ====
// Gmail me 2-Step Verification ON karke "App Password" banao, wahi use hoga
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'siddhantkumar06448@gmail.com');      // yahan apna Gmail
define('SMTP_PASS', 'your_app_password');        // Gmail App Password
define('SMTP_FROM', 'yourgmail@gmail.com');      // From email
define('SMTP_FROM_NAME', 'Portfolio Website');   // From name
define('SMTP_TO', 'yourgmail@gmail.com');        // Jis email par notify chahiye (same bhi ho sakta)
