<?php
$password = "12345678";

$hashedPassword = md5($password);

echo "Original Password: " . $password . "<br>";
echo "MD5 Hashed Password: " . $hashedPassword;
?>
The password is 12345678
The login is not working
