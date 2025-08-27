<?php
$password = 'helloworld';
$user_email = 'ruslan@ub.ac.id';
echo "Email: " . $user_email . "\n";
echo "Password: " . $password . "\n";
echo "Hash: " . password_hash($password, PASSWORD_BCRYPT) . "\n";
