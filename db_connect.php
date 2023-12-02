<?php
$servername = "mysql-nicatzeynalli.alwaysdata.net";
$username = "338098_nicat";
$password = "n_z13042004";
$dbname = "nicatzeynalli_project_be";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
