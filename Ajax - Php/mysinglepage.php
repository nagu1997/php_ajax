<?php 
$servername = "localhost";
$username = "root";
$password = "";
$database = "krackers";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$product_id = "";
$product_name = "";
$product_amount = "";
$product_quantity = "";
$expiry_date = "";
$pollution_affection = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit_product"])) {
        if (empty($_POST["product_id"])) {
            // Inserting a new product
            $product_name = $_POST["product_name"];
            $product_amount = $_POST["product_amount"];
            $product_quantity = $_POST["product_quantity"];
            $expiry_date = $_POST["expiry_date"];
            $pollution_affection = isset($_POST["pollution_affection"]) ? 'yes' : 'no';

            $sql = "INSERT INTO products (product_name, product_amount, product_quantity, expiry_date, pollution_affection) 
                    VALUES (?, ?, ?, STR_TO_DATE(?, '%Y-%m-%d'), ?)";

            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("siiss", $product_name, $product_amount, $product_quantity, $expiry_date, $pollution_affection);

                if ($stmt->execute()) {
                    echo "New product added successfully";
                    // Clear form fields after adding a new product
                    $product_name = $product_amount = $product_quantity = $expiry_date = $pollution_affection = "";
                } else {
                    echo "Error adding new product: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            // Updating an existing product
            $product_id = $_POST["product_id"];
            $product_name = $_POST["product_name"];
            $product_amount = $_POST["product_amount"];
            $product_quantity = $_POST["product_quantity"];
            $expiry_date = $_POST["expiry_date"];
            $pollution_affection = isset($_POST["pollution_affection"]) ? 'yes' : 'no';

            $sql = "UPDATE products 
                    SET product_name=?, product_amount=?, product_quantity=?, expiry_date=STR_TO_DATE(?, '%Y-%m-%d'), pollution_affection=? 
                    WHERE product_id=?";

            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("siissi", $product_name, $product_amount, $product_quantity, $expiry_date, $pollution_affection, $product_id);

                if ($stmt->execute()) {
                    echo "Record updated successfully";
                    // Clear form fields after updating an existing product
                    $product_id = $product_name = $product_amount = $product_quantity = $expiry_date = $pollution_affection = "";
                } else {
                    echo "Error updating record: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }
    if(isset($_POST["delete"])){ ///Not need......
        $sql = "DELETE FROM products WHERE product_id='$product_id'";
        mysqli_query($sql);
        echo "deleted successfully";
    }
}

// Populate form fields for editing and deleting
if (isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["id"])) {
    $product_id = $_GET["id"];
    $sql = "SELECT * FROM products WHERE product_id=$product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $product_name = $row["product_name"];
        $product_amount = $row["product_amount"];
        $product_quantity = $row["product_quantity"];
        $expiry_date = $row["expiry_date"];
        $pollution_affection = $row["pollution_affection"];
    }
}
else if (isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["id"])) {
    $product_id = $_GET["id"];
    $sql = "DELETE FROM products WHERE product_id=$product_id";
    mysqli_query($conn,$sql);
    echo "DELETED SUCCESSFULLY";

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
      <!-- Include jQuery -->
      <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    // Reload the page after a delay
    function reloadPage() {
        setTimeout(function () {
            location.reload();
        }, 4000); // 1 second delay
    }
</script>
</head>
<body>

<!-- Product Form Section -->
<h2>Product Form</h2>
<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <label for="product_name">Product Name:</label>
    <input type="text" name="product_name" value="<?php echo $product_name; ?>" required><br>

    <label for="product_amount">Product Amount:</label>
    <input type="number" name="product_amount" value="<?php echo $product_amount; ?>" required><br>

    <label for="product_quantity">Product Quantity:</label>
    <input type="number" name="product_quantity" value="<?php echo $product_quantity; ?>" required><br>

    <label for="expiry_date">Expiry Date:</label>
    <input type="date" name="expiry_date" value="<?php echo $expiry_date; ?>" required><br>

    <label for="pollution_affection">Pollution Affection:</label>
    <input type="checkbox" name="pollution_affection" <?php echo ($pollution_affection == 'yes') ? 'checked' : ''; ?>>  Yes (means Tick)<br>

    <input type="submit" name="submit_product" value="<?php echo (empty($product_id)) ? 'Add Product' : 'Update Product'; ?>">
</form>

<!-- Display Section -->
<h2>Product List</h2>
<table border="1">
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Product Amount</th>
        <th>Product Quantity</th>
        <th>Expiry Date</th>
        <th>Pollution Affection</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>

    <?php
    // Retrieve and display products
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["product_id"] . "</td>";
            echo "<td>" . $row["product_name"] . "</td>";
            echo "<td>" . $row["product_amount"] . "</td>";
            echo "<td>" . $row["product_quantity"] . "</td>";
            echo "<td>" . date("d/m/Y", strtotime($row["expiry_date"])) . "</td>";
            echo "<td>" . $row["pollution_affection"] . "</td>";
            echo "<td><a href='" . $_SERVER["PHP_SELF"] . "?action=edit&id=" . $row["product_id"] . "'>Edit</a></td>";
            echo "<td><a href='" . $_SERVER["PHP_SELF"] . "?action=delete&id=" . $row["product_id"] . "'>Delete</a></td>";
            echo "</tr>";
        }
        
        } else {
            echo "<tr><td colspan='8'>No products found</td></tr>";
        }
        ?>
        </table>
        
        </body>
        </html>
        
        <?php
        $conn->close();
        ?>
        