<?php
$conn = new mysqli('localhost', 'root', '', 'krackers');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit_product"])) {
        // Handle form submission for adding or updating a product
        if (empty($_POST["product_id"])) {
            // Inserting a new product
            insertProduct($conn, $_POST["product_name"], $_POST["product_amount"], $_POST["product_quantity"], $_POST["expiry_date"], isset($_POST["pollution_affection"]) ? 'yes' : 'no');
        } else {
            // Updating an existing product
            updateProduct($conn, $_POST["product_id"], $_POST["product_name"], $_POST["product_amount"], $_POST["product_quantity"], $_POST["expiry_date"], isset($_POST["pollution_affection"]) ? 'yes' : 'no');
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["action"])) {
    // Handle AJAX requests for deleting or editing
    if ($_GET["action"] == "delete" && isset($_GET["id"])) {
        deleteProduct($conn, $_GET["id"]);
    } elseif ($_GET["action"] == "edit" && isset($_GET["id"])) {
        // Fetch product data for editing
        $product_id = $_GET["id"];
        $productData = fetchProduct($conn, $product_id);

        // Check if product data is retrieved successfully
        if ($productData !== null) {
            // Return the product data as JSON
            header('Content-Type: application/json');
            echo json_encode($productData);
            exit;
        } else {
            // Return an error message if product data is not found
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Product not found']);
            exit;
        }
    }
}



// Fetch product data for editing
if (isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["id"])) {
    $product_id = $_GET["id"];
    $productData = fetchProduct($conn, $product_id);
    if ($productData !== null) {
        $product_id = $productData["product_id"];
        $product_name = $productData["product_name"];
        $product_amount = $productData["product_amount"];
        $product_quantity = $productData["product_quantity"];
        $expiry_date = $productData["expiry_date"];
        $pollution_affection = $productData["pollution_affection"];
    }
}

function fetchProduct($conn, $product_id) {
    $sql = "SELECT * FROM products WHERE product_id=?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    } else {
        return null;
    }
}
function insertProduct($conn, $product_name, $product_amount, $product_quantity, $expiry_date, $pollution_affection) {
    $sql = "INSERT INTO products (product_name, product_amount, product_quantity, expiry_date, pollution_affection) 
            VALUES (?, ?, ?, STR_TO_DATE(?, '%Y-%m-%d'), ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("siiss", $product_name, $product_amount, $product_quantity, $expiry_date, $pollution_affection);

        if ($stmt->execute()) {
            return "New product added successfully";
        } else {
            return "Error adding new product: " . $stmt->error;
        }

        $stmt->close();
    } else {
        return "Error: " . $conn->error;
    }
}

function updateProduct($conn, $product_id, $product_name, $product_amount, $product_quantity, $expiry_date, $pollution_affection) {
    $sql = "UPDATE products 
            SET product_name=?, product_amount=?, product_quantity=?, expiry_date=STR_TO_DATE(?, '%Y-%m-%d'), pollution_affection=? 
            WHERE product_id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("siissi", $product_name, $product_amount, $product_quantity, $expiry_date, $pollution_affection, $product_id);

        if ($stmt->execute()) {
            return "Record updated successfully";
        } else {
            return "Error updating record: " . $stmt->error;
        }

        $stmt->close();
    } else {
        return "Error: " . $conn->error;
    }
}

function deleteProduct($conn, $product_id) {
    $sql = "DELETE FROM products WHERE product_id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $product_id);
        if ($stmt->execute()) {
            return "Record deleted successfully";
        } else {
            return "Error deleting record: " . $stmt->error;
        }

        $stmt->close();
    } else {
        return "Error: " . $conn->error;
    }
}

function displayProductForm($productData = []) {
    echo '
    <h2>Product Form</h2>
    <form method="post" action="MyIndex.php">
    <input type="hidden" name="product_id" value="<?php echo isset($productData['product_id']) ? $productData['product_id'] : ''; ?>">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" value="<?php echo isset($productData['product_name']) ? $productData['product_name'] : ''; ?>" required><br>

        <label for="productAmount">Product Amount:</label>
        <input type="number" id="productAmount" name="product_amount" value="<?php echo isset($productData['product_amount']) ? $productData['product_amount'] : ''; ?>" required><br>

        <label for="productQuantity">Product Quantity:</label>
        <input type="number" id="productQuantity" name="product_quantity" value="<?php echo isset($productData['product_quantity']) ? $productData['product_quantity'] : ''; ?>" required><br>

        <label for="expiryDate">Expiry Date:</label>
        <input type="date" id="expiryDate" name="expiry_date" value="<?php echo isset($productData['expiry_date']) ? $productData['expiry_date'] : ''; ?>" required><br>

        <label for="pollutionAffection">Pollution Affection:</label>
        <input type="checkbox" id="pollutionAffection" name="pollution_affection" value="yes" <?php if($productData['expiry_date']==='yes') ? 'checked' : ''?>> Yes<br>

        <input type="submit" name="submit_product" value="Add Product">
    </form>';
    
}

function displayProductsTable($conn) {
    $sql = 'SELECT * FROM products';
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
         echo '
    <div id="t1">
        <h2>Product Details</h2>
        <table border="1px" id="table1">
        <thead>
        <tr>
              <th>Product ID</th>
              <th>Product Name</th>
              <th>Product Amount</th>
              <th>Product Quantity</th>
              <th>Expiry Date</th>
              <th>Pollution Affection</th>
              <th>Action</th>
          </tr> 
          </thead>
          <tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row["product_id"] . '</td>
                    <td>' . $row["product_name"] . '</td>
                    <td>' . $row["product_amount"] . '</td>
                    <td>' . $row["product_quantity"] . '</td>
                    <td>' . date("d/m/Y", strtotime($row["expiry_date"])) . '</td>
                    <td>' . $row["pollution_affection"] . '</td>
                    <td><a href="#" class="edit-link" data-id="' . $row["product_id"] . '">Edit</a></td>
                    echo "<td><a href="#" class="delete-link" data-id="' . $row["product_id"] . '">Delete</a></td>";
                    </td>
                  </tr>';
        }
        echo '</tbody>
        </table>
    </div>';
        
    } else {
        echo '0 results';
    }
    $conn->close();
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

    <!-- Add this script to the head section of your HTML -->
<script>
    $(document).ready(function () {
        // Handle form submission with AJAX
        $('form').submit(function (event) {
            event.preventDefault();

            // Serialize the form data
            var formData = $(this).serialize();

            // Perform AJAX request
            $.ajax({
                type: 'POST',
                url: 'MyIndex.php', // Replace with the actual PHP script name
                data: formData,
                success: function (response) {
                    // Display the response message
                    alert(response);
                    // Reload the page after 1 second
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            });
        });

        // Handle AJAX request for deleting
        $('a.delete-link').click(function (event) {
            event.preventDefault();

            // Get the product ID from the data-id attribute
            var productID = $(this).data('id');

            // Perform AJAX request for deleting
            $.ajax({
                type: 'GET',
                url: 'MyIndex.php?action=delete&id=' + productID, // Replace with the actual PHP script name
                success: function (response) {
                    // Display the response message
                    alert(response);
                    // Reload the page after 1 second
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            });
        });

        // Handle AJAX request for editing
        $('a.edit-link').click(function (event) {
            event.preventDefault();

            // Get the product ID from the data-id attribute
            var productID = $(this).data('id');

            // Perform AJAX request for editing
            $.ajax({
                type: 'GET',
                url: 'MyIndex.php?action=edit&id=' + productID, // Replace with the actual PHP script name
                success: function (response) {
                    // Assuming response is a JSON object with product data
                    var productData = JSON.parse(response);

                    // Populate the form fields with retrieved data
                    $('input[name="product_id"]').val(productData.product_id);
                    $('input[name="product_name"]').val(productData.product_name);
                    $('input[name="product_amount"]').val(productData.product_amount);
                    $('input[name="product_quantity"]').val(productData.product_quantity);
                    $('input[name="expiry_date"]').val(productData.expiry_date);
                    $('input[name="pollution_affection"]').prop('checked', (productData.pollution_affection === 'yes'));
                }
            });
        });
    });
</script>

</head>
<body>

<?php
    echo'
    displayProductsTable($conn);
    $productDataForEdit = fetchProduct($conn, $productIdToEdit);
    displayProductForm($productDataForEdit);';
    
?>

</body>
</html>


