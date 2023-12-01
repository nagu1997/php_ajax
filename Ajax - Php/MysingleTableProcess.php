<?php 
$conn = new mysqli("localhost", "root", "", "krackers");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// ------> Display Table <-------------
function displayData() {
    global $conn;
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    echo "<h1><i>Product List</i></h1>";
    
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Product Amount</th>
                    <th>Product Quantity</th>
                    <th>Expiry Date</th>
                    <th>Pollution Affection</th>
                    <th colspan='2'>Actions</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            $formattedExpiryDate = date("d/m/Y", strtotime($row["expiry_date"]));
            echo "<tr>
                    <td>" . $row["product_id"] . "</td>
                    <td>" . $row["product_name"] . "</td>
                    <td>" . $row["product_amount"] . "</td>
                    <td>" . $row["product_quantity"] . "</td>
                    <td>" . $formattedExpiryDate . "</td>
                    <td>" . $row["pollution_affection"] . "</td>
                    <td><a href='?action=edit&id=" . $row["product_id"] . "'>Edit</a></td>
                    <td><a href='?action=delete&id=" . $row["product_id"] . "'>Delete</a></td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ------> Inserting Data <-------------
    if (isset($_POST["insert"])) {
        $product_name = $_POST["product_name"];
        $product_amount = $_POST["product_amount"];
        $product_quantity = $_POST["product_quantity"];
        $expiry_date = $_POST["expiry_date"];
        $pollution_affection = isset($_POST["pollution_affection"]) ? 'yes' : 'no';

        $insertSql = "INSERT INTO products (product_name, product_amount, product_quantity, expiry_date, pollution_affection) 
                VALUES ($product_name, $product_amount, $product_quantity, $expiry_date, $pollution_affection)";
        mysqli_query($conn, $insertSql);
        echo "Inserted Successfully";
    }

    
    // ------> Updating Data <-------------
    if (isset($_POST["update"])) {
        $product_id = $_POST["product_id"];
        $product_name = $_POST["product_name"];
        $product_amount = $_POST["product_amount"];
        $product_quantity = $_POST["product_quantity"];
        $expiry_date = $_POST["expiry_date"];
        $pollution_affection = $_POST["pollution_affection"];

        $updateSql = "UPDATE products SET product_name='$product_name', product_amount='$product_amount', product_quantity='$product_quantity', expiry_date='$expiry_date', pollution_affection='$pollution_affection' WHERE product_id=$product_id";
        mysqli_query($conn, $updateSql);
        echo "Updated Successfully";
    }

    // ------> Deleting Data <-------------
    if (isset($_POST["delete"])) {
        $product_id = $_POST["product_id"];

        $deletesql = "DELETE FROM products WHERE product_id='$product_id'";
        mysqli_query($conn,$deletesql);
        echo "Deleted Successfully";
    }
}

// --------------> Edit and Delete operations based on actions in the URL <-----------------
if (isset($_GET["action"])) {
    if ($_GET["action"] == "edit" && isset($_GET["id"])) {
        $product_id = $_GET["id"];
        $editSql = "SELECT * FROM products WHERE product_id = '$product_id'";
        $editresult = $conn->query($editSql);
        if($editresult->num_rows>0){
            $editRow = $editresult->fetch_assoc();

            // ------> Display the form for editing <-----------
            echo "<h2>Edit Product</h2>
                <form method='post' action='" . $_SERVER["PHP_SELF"] . "'>
                        <input type='hidden' name='product_id' value='" . $editRow["product_id"] . "'>
                    <label for='product_name'>Product Name:</label>
                        <input type='text' name='product_name' value='" . $editRow["product_name"] . "' required><br>

                    <label for='product_amount'>Product Amount:</label>
                        <input type='number' name='product_amount' value='" . $editRow["product_amount"] . "' required><br>

                    <label for='product_quantity'>Product Quantity:</label>
                        <input type='number' name='product_quantity' value='" . $editRow["product_quantity"] . "' required><br>

                    <label for='expiry_date'>Expiry Date:</label>
                        <input type='date' name='expiry_date' value='" . $editRow["expiry_date"] . "' required><br>
                    <label>Pollution Affection:</label>
                    <label><input type='radio' name='pollution_affection' value='yes' " . ($editRow["pollution_affection"] == 'yes' ? 'checked' : '') . "> Yes</label>
                    <label><input type='radio' name='pollution_affection' value='no' " . ($editRow["pollution_affection"] == 'no' ? 'checked' : '') . "> No</label>
                        <br>

                        <input type='submit' name='update' value='Update'>
            </form>";
        }
    } elseif ($_GET["action"] == "delete" && isset($_GET["id"])) {
        $product_id = $_GET["id"];
        $deletesql = "DELETE FROM products WHERE product_id='$product_id'";
        mysqli_query($conn,$deletesql);
        echo "deleted successfully";
    }
}

///-----------------> Display the form for inserting new data<---------------
echo "<h1><i>New/Edit Product</i></h1>
    <form method='post' action='" . $_SERVER["PHP_SELF"] . "'>
        <label for='product_name'>Product Name:</label>
        <input type='text' name='product_name' required><br>

        <label for='product_amount'>Product Amount:</label>
        <input type='number' name='product_amount' required><br>

        <label for='product_quantity'>Product Quantity:</label>
        <input type='number' name='product_quantity' required><br>

        <label for='expiry_date'>Expiry Date:</label>
        <input type='date' name='expiry_date' required value=''><br>

        <label>Pollution Affection:</label>
        <label><input type='radio' name='pollution_affection' value='yes'> Yes</label>
        <label><input type='radio' name='pollution_affection' value='no'> No</label>
        <br>

        <input type='submit' name='insert' value='Insert'>
    </form>";


displayData();

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My All Performingg...</title>
</head>
<style>
    table{
        border:1px solid black;
        border-collapse:collapse;
        padding:5px;
        text-align:center;  
    }
<body>
    
</body>
</html>
                       
