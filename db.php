<?php
$conn = new mysqli('localhost', 'root', 'root', 'bookworm2');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>