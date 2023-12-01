<?php 
global $conn;
$servername = "localhost";
$username = "root";
$password = "";
$database = "krackers";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}



$product_id = "";
$product_name = "";
$product_amount = "";
$product_quantity = "";
$expiry_date = "";
$pollution_affection = "";
//print_r($_GET);
// print_r($_POST);
// exit;   
//print_r($_POST);
//var_dump($_POST);
// echo 'hello edit';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_form"])) {
     
    $product_id = isset($_POST["product_id"]) ? $_POST["product_id"] : "";
    $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
    $product_amount = isset($_POST['product_amount']) ? $_POST['product_amount'] : '';
    $product_quantity = isset($_POST['product_quantity']) ? $_POST['product_quantity'] : '';
    $expiry_date = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : '';
    $pollution_affection = isset($_POST["pollution_affection"]) ? 'yes' : 'no';

    if ($product_id == "") {
        $sql = "INSERT INTO products (product_name, product_amount, product_quantity, expiry_date, pollution_affection) VALUES ('$product_name', '$product_amount', '$product_quantity', '$expiry_date', '$pollution_affection')";
        mysqli_query($conn,$sql);
        exit;   
    }
    else{
        
        $updatesql = "UPDATE products SET product_name='$product_name', product_amount='$product_amount', product_quantity='$product_quantity', expiry_date='$expiry_date', pollution_affection='$pollution_affection' WHERE product_id='$product_id'";
        mysqli_query($conn,$updatesql);
        exit;
    }
}
    
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'edit') {
        $product_id = $_GET["id"];
        editProduct($product_id);
        exit;
            
    } elseif ($action == 'delete') {
        $product_id = $_GET["id"];
        deleteProduct($product_id);
        exit;
    }
}


form();
displayTable($conn);
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajax with PHP and MySQL</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

<div id="result"></div>
<script type="text/javascript">
    function inserted() {
        let formData = $('#insertForm').serialize();
        console.log(formData);
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                alert(response);
                $('#t1').appendTo('#result');
                $('#result').html(response);
                $('#insertForm')[0].reset();
                
            },
            error: function(error) {
                console.log(error);
            }
        });
        
    }

    function edited(product_id) {
        //let formData = $('#insertForm').serialize();
       // console.log(formData);
        console.log("EDIT (SCRIPT)id is->"+product_id);
        $.ajax({
            url: 'index.php?action=edit&id='+product_id,
            type: 'POST',
            data: {action:"edit",product_id:product_id},
            success: function(response) {
                alert(response);
                console.log('Editing product with ID: ' + product_id);
                //$('#t1').appendTo('#result');
                //$('#result').html(response);
                //$('#insertForm')[0].reset();
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function deleted(product_id) {
        if (confirm('Are you sure you want to delete this product?')) {
            let formData = $('#insertForm').serialize();
            console.log(formData);
            console.log("DELETE (SCRIPT)id is->"+product_id);
            $.ajax({
                url: 'index.php?action=delete&id='+product_id,
                type: 'POST',
                data: {action:"delete",formData},
                success: function(response) {
                    alert(response);
                    console.log('Deleting product with ID: ' + product_id);
                    $('#t1').appendTo('#result');
                    $('#result').html(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    }
    



    function updated() {
       // let formData = $('#insertForm').serialize();
        //console.log(formData);
        $.ajax({
            url: 'index.php',
            type: 'GET',
           // data: formData,
            success: function(response) {
                alert(response);
                //$('#insertForm').addClass('hidden');
                //$('#result').html(response).removeClass('hidden');
                console.log('Updating product with ID: ' + product_id);
               // $('#result').html(response);
                $('#insertForm')[0].reset();
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    
    
</script>

</body>
</html>













<?php

function form(){
   global $product_id,$product_name,$product_amount,$product_quantity,$expiry_date,$pollution_affection;
    echo '<h2>Insert Data</h2>
                <form id="insertForm" method="post" >
                    <input type="hidden" id="productID" name="product_id" value="' . $product_id. '">
                    <label for="productName">Product Name:</label>
                    <input type="text" id="productName" name="product_name" value="' . $product_name . '" required><br>
    
                    <label for="productAmount">Product Amount:</label>
                    <input type="number" id="productAmount" name="product_amount" value="' . $product_amount . '" required><br>
    
                    <label for="productQuantity">Product Quantity:</label>
                    <input type="number" id="productQuantity" name="product_quantity" value="' . $product_quantity . '" required><br>
    
                    <label for="expiryDate">Expiry Date:</label>
                    <input type="date" id="expiryDate" name="expiry_date" value="' . $expiry_date . '" required><br>
    
                    <label for="PollutionAffection">Pollution Affection:</label>
                    <label><input type="radio" id="PollutionAffection" name="pollution_affection" value="yes" ' . ($pollution_affection == "yes" ? "checked" : "") . '> Yes</label>
                    <label><input type="radio" id="PollutionAffection" name="pollution_affection" value="no" ' . ($pollution_affection == "no" ? "checked" : "") . '> No</label>
                    <br>
    
                    <input type="button" name="submit_form" onclick="' . (empty($product_id) ? "inserted()" : "updated()") . '"  value="' . (empty($product_id) ? "Add Product" : "Update Product") . '"><br>
                </form>';
            
}


function deleteProduct($product_id) {
    //echo '<br>DELETE -' .$product_id;
    global $conn;
    $sql = "DELETE FROM products WHERE product_id='$product_id'";
    mysqli_query($conn,$sql);
}
function editProduct($product_id) {
    global $conn;
    $sql = "SELECT * FROM products WHERE product_id='$product_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $product_id = $row["product_id"];
        $product_name = $row["product_name"];
        $product_amount = $row["product_amount"];
        $product_quantity = $row["product_quantity"];
        $expiry_date = $row["expiry_date"];
        $pollution_affection = $row['pollution_affection'];
    }
    echo "<br>edit=> $product_id";
    echo "<br>edit=> $product_name";
    echo "<br>edit=> $product_amount";
    echo "<br>edit=> $product_quantity";
    echo "<br>edit=> $expiry_date";
    echo "<br>edit=> $pollution_affection";
}


function displayTable($conn) {
    global $conn;
    $sql = "SELECT * FROM products";
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
                    echo "<tr>";
                    echo "<td>" . $row["product_id"] . "</td>";
                    echo "<td>" . $row["product_name"] . "</td>";
                    echo "<td>" . $row["product_amount"] . "</td>";
                    echo "<td>" . $row["product_quantity"] . "</td>";
                    echo "<td>" . date("d/m/Y", strtotime($row["expiry_date"])) . "</td>";
                    echo "<td>" . $row["pollution_affection"] . "</td>";
                    echo "<td><a href='javascript:void(0);' id='editing' onclick='edited(" . $row["product_id"] . ")'>Edit</a></td>";
                    echo "<td><a href='javascript:void(0);' id='deleting' onclick='deleted(" . $row["product_id"] . ")'>Delete</a></td>";
                    echo "</tr>";
                }
                echo '
                </tbody>
                </table>
            </div>';
    } 
    else{
        echo '0 results';
    }
}



?>