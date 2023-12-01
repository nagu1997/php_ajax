<?php 
$servername = "localhost";
$username = "root";
$password = "";
$database = "krackers";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$product_id = "";
$product_name = "";
$product_amount = "";
$product_quantity = "";
$expiry_date = "";
$pollution_affection = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit_product"])) {
        if (empty($_POST["product_id"])) {//-------->Insert-------------
            $product_name = $_POST["product_name"];
            $product_amount = $_POST["product_amount"];
            $product_quantity = $_POST["product_quantity"];
            $expiry_date = $_POST["expiry_date"];
            $pollution_affection = isset($_POST["pollution_affection"]) ? 'yes' : 'no';
            $sql = "INSERT INTO products (product_name, product_amount, product_quantity, expiry_date, pollution_affection) 
                VALUES ('$product_name', $product_amount, $product_quantity, '$expiry_date', '$pollution_affection')";
            $conn->query($sql);
            echo "INSERTED SUCCESSFULLY";
            $product_name = $product_amount = $product_quantity = $expiry_date = $pollution_affection = "";
        }
        else{//------->Update--------------------
            $product_id = $_POST["product_id"];
            $product_name = $_POST["product_name"];
            $product_amount = $_POST["product_amount"];
            $product_quantity = $_POST["product_quantity"];
            $expiry_date = $_POST["expiry_date"];
            $pollution_affection = isset($_POST["pollution_affection"]) ? 'yes' : 'no';
            $sql = "UPDATE products SET product_name='$product_name', product_amount='$product_amount', product_quantity='$product_quantity', expiry_date='$expiry_date', pollution_affection='$pollution_affection' WHERE product_id=$product_id";
            $result=$conn->query($sql);
            if($result== TRUE){
                echo "UPDATED SUCCESSFULLY";
                $product_id = $product_name = $product_amount = $product_quantity = $expiry_date = $pollution_affection = "";
            }
            
        }
    }
}

if (isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["id"])) { //--->Edit value fetch-----
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
else if (isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["id"])) {//---->delete-----
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
    <title>....Product Krackers Sale....</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    function inserted(){
        let myFormData = $('#myForm').serialize();
        $.ajax({
            url:'<?php echo $_SERVER["PHP_SELF"]; ?>',
            type:'post',
            data: myFormData,
            success:function (response){
                //alert(this.getResponseHeader("Content-Type"));
                //console.log(response);
                //alert(response);
                //$('#myResult').html(response);
                $('#productTable').load(location.href + ' #productTable');
                $('#myForm')[0].reset();
            },
           /* error: function(xhr, status, error){
                alertify.error(JSON.parse(xhr.responseText).error);
            }*/
            error:function(error){
                console.log(error);
            }
        });
    }
    function updated(pid){
        $.ajax({
            url:'<?php echo $_SERVER["PHP_SELF"]; ?>?action=edit?&id='+pid,
            type: 'get',
            success: function (response){
                //alert(this.getResponseHeader("Content-Type"));
                //console.log(response);
                $('#myResult').html(response);
                $('#myForm')[0].reset();
            },
            /*error: function (xhr) {
                var err = JSON.parse(xhr.responseText);
                alert(err.message);
            }*/
        });
    }
    function deleted(pid){
        if (confirm('Are you sure you want to delete this product?')) {
        $.ajax({
            url:'<?php echo $_SERVER["PHP_SELF"]; ?>?action=delete&id='+pid,
            type: 'get',
            success: function (response){
                //alert(this.getResponseHeader("Content-Type"));
                //console.log(response);
                //$('#productTable').load(location.href + ' #productTable');
                $('#myResult').html(response);
                
            }
            /*
            error: function (request, error) {
                console.log(arguments);
                alert(" Can't do because: " + error);
            },
            success: function (responseText) {
                alert(" Done ! "+responseText);
            }*/
        });
        }
    }
  
</script>
</head>
<style>
    h1{
        background-color:lightgray;
    }
    #productTable table{
        border-collapse:collapse;
        padding:10px;
        text-align:center;
        letter-spacing:1.2px;
    }
</style>
<body>
<div id="myResult" style="background-color:red;"></div>
<!--<h1><em>Product Form</em></h1> -->

<form method="post" id="myForm">
    <table style="margin-top:-100px;">
    <h1> <em>Product Form</em></h1>
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

    <tr>
    <td><label for="product_name">Product Name:</label></td>
    <td><input type="text" name="product_name" value="<?php echo $product_name; ?>" required></td><br>
    </tr>

    <tr>
    <td><label for="product_amount">Product Amount:</label></td>
    <td><input type="number" name="product_amount" value="<?php echo $product_amount; ?>" required></td><br>
    </tr>

    <tr>
    <td><label for="product_quantity">Product Quantity:</label></td>
    <td><input type="number" name="product_quantity" value="<?php echo $product_quantity; ?>" required></td><br>
    </tr>

    <tr>
    <td><label for="expiry_date">Expiry Date:</label></td>
    <td><input type="date" name="expiry_date" value="<?php echo $expiry_date; ?>" required></td><br>
    </tr>

    <tr>
    <td><label for="pollution_affection">Pollution Affection:</label></td>
    <td><input type="checkbox" name="pollution_affection" <?php echo ($pollution_affection == 'yes') ? 'checked' : ''; ?>>  Yes (means Tick)</td><br>
    </tr>

    <tr>
    <td><input type="submit" onclick="inserted()" name="submit_product" value="<?php echo (empty($product_id)) ? 'Add Product' : 'Update Product'; ?>"></td>
    </tr>
</table>
</form>


<h1><i>Product List</i></h1>
<div id="productTable">
<table border="1px"  cellpadding="5px">
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Product Amount</th>
        <th>Product Quantity</th>
        <th>Expiry Date</th>
        <th>Pollution Affection</th>
        <th colspan="2">Actions</th>
    </tr>
    <?php
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
            echo "<td><a href='" . $_SERVER["PHP_SELF"] . "?action=edit&id=" . $row["product_id"] . "' onclick='updated(". $row["product_id"] .")'>Edit</a></td>";
            echo "<td><a href='" . $_SERVER["PHP_SELF"] . "?action=delete&id=" . $row["product_id"] . "' onclick='deleted(". $row["product_id"] .")'>Delete</a></td>";
            echo "</tr>";
        }  
    }
    else{
        echo "<tr><td colspan='8'>No products found</td></tr>";
    }
    ?>
</table>
</div>




</body>
</html>
      