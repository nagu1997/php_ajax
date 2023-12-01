<?php 
$connection = new mysqli("localhost", "root", "", "krackers");
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if (isset($_GET["id"])) {
    $product_id = $_GET["id"];
    $sql = "DELETE FROM products WHERE product_id=$product_id";

    if ($connection->query($sql) === TRUE) {
        header("Location: display.php");
        exit();
    } else {
        echo "Error deleting record: " . $connection->error;
    }
} else {
    echo "Product ID not provided";
    exit();
}

$connection->close();
?>
