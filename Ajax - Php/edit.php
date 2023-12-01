<?php 
$servername = "localhost";
$username = "root";
$password = "";
$database = "krackers";
$connection = new mysqli($servername, $username, $password, $database);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $product_name = $_POST["product_name"];
    $product_amount = $_POST["product_amount"];
    $product_quantity = $_POST["product_quantity"];
    $expiry_date = $_POST["expiry_date"];
    $pollution_affection = isset($_POST["pollution_affection"]) ? 'yes' : 'no';

    $sql = "UPDATE products 
            SET product_name='$product_name', product_amount=$product_amount, 
                product_quantity=$product_quantity, expiry_date='$expiry_date', 
                pollution_affection='$pollution_affection' 
            WHERE product_id=$product_id";

    if ($connection->query($sql) === TRUE) {
        header("Location: display.php");
        exit();
    } else {
        echo "Error updating record: " . $connection->error;
    }
}

if (isset($_GET["id"])) {
    $product_id = $_GET["id"];
    $sql = "SELECT * FROM products WHERE product_id=$product_id";
    $result = $connection->query($sql);

   if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $product_name = $row["product_name"];
        $product_amount = $row["product_amount"];
        $product_quantity = $row["product_quantity"];
        $expiry_date = $row["expiry_date"];
        $pollution_affection = $row["pollution_affection"]; 
   } else {
        echo "Product not found";
        exit();
    } 
} else {
    echo "<br/> Product ID not provided!";
    exit();
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
	<style>
		#main{
			display:flex;
			justify-content:center;
			align-items:center;
			flex-direction:column;
		}
		form{
			border-radius:25px;
			padding:40px;
			background-color:lightgray;
			text-align:right;
		}
		h1{
			text-align:center;
			font-style:italic;
		}
		input[type="submit"]{
			border:0;
			width:100px;
			height:50px;
			padding:10px;
			text-align:center;
			color:white;
			background-color:violet;
			border-radius:15px;
			cursor:pointer;
			font-size:15px;
			letter-spacing:1.5px;
		}
		input[type="text"],input[type="number"],input[type="date"]{
			background-color:darkgray;
			color:red;
			border-radius:12px;
			height:30px;
			font-size:18px;
			padding:20px;
			border:0;
			letter-spacing:3px;
		}
		input[type="checkbox"]{
			accent-color:red;
		}
		input:focus{
			outline:0;
		}
		input[type="submit"]:hover{
			color:black;
			background-color:red;
		}
		label{
			font-size:25px;
		}
	</style>
</head>
<body>
	<div id="main">
    <h1>Edit Products</h1>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" value="<?php echo $product_name; ?>" required><br><br>

        <label for="product_amount">Product Amount:</label>
        <input type="number" name="product_amount" value="<?php echo $product_amount; ?>" required><br><br>

        <label for="product_quantity">Product Quantity:</label>
        <input type="number" name="product_quantity" value="<?php echo $product_quantity; ?>" required><br><br>

        <label for="expiry_date">Expiry Date:</label>
        <input type="date" name="expiry_date" value="<?php echo $expiry_date; ?>" required><br><br>

        <label for="pollution_affection">Pollution Affection:</label>
        <input type="checkbox" name="pollution_affection" <?php if ($pollution_affection == 'yes') echo 'checked'; ?>><br><br><br>
        <br>

        <input type="submit" value="Update">
    </form>
	</div>
</body>
</html>
