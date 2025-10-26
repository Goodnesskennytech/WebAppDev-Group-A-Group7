<!-- <?php
try {
    $host = 'localhost'; // XAMPP default
    $dbname = 'simple_blog';
    $username = 'root';
    $password = ''; // XAMPP default (empty)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?> -->

<?php
    $host = "localhost";
    $user = "root";
    $pass = "";  
    $dbname = "simple_blog";

    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

?>