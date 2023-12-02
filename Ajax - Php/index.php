<?php 
//global $conn;
$servername = "localhost";
$username = "root";
$password = "";
$database = "krackers";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

/*
$product_id = "";
$product_name = "";
$product_amount = "";
$product_quantity = "";
$expiry_date = "";
$pollution_affection = "";*/

//print_r($_GET);
// exit;   
//print_r($_POST);
//var_dump($_POST);
// echo 'hello edit';
//print_r($_GET);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    if($_POST["action"] == 'insert'){
       /* $product_id = $_POST["product_id"];
        $product_name = $_POST['product_name'];
        $product_amount = $_POST['product_amount'];
        $product_quantity = $_POST['product_quantity'];
        $expiry_date = $_POST['expiry_date'];
        $pollution_affection =  isset($_POST['pollution_affection']) ? 'yes' : 'no';*/
        //echo "$product_id";
        if (empty($_POST["product_id"])) {

            insertProduct($conn);
        }
        else{

            updateProduct($conn);
        }
    }else if($_POST["action"] == 'delete'){

        $product_id = $_POST["product_id"];
        deleteProduct($conn,$product_id);

    }else if ($_POST["action"] == 'edit') {
        $product_id = $_POST["product_id"];
        //editProduct($conn,$product_id);
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
            echo form($product_id,$product_name,$product_amount,$product_quantity,$expiry_date,$pollution_affection);
        }
    }

    echo displayTable($conn);
    exit;
}

//form();

 
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



<script type="text/javascript">
    function inserted() {
        //let formData = $('#insertForm').serialize();
        //console.log(formData);
        let product_id=$("#product_id").val();
        let product_name=$("#product_name").val();
        let product_amount=$("#product_amount").val();
        let product_quantity=$("#product_quantity").val();
        let expiry_date=$("#expiry_date").val();
        let pollution_affection=$("input[name='pollution_affection']:checked").val();
        
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {action:"insert",product_id:product_id,product_name:product_name,product_amount:product_amount,product_quantity:product_quantity,expiry_date:expiry_date,pollution_affection:pollution_affection},
            success: function(response) {
                alert(response);
                $('#t1').appendTo('#resultTable');
                $('#resultTable').html(response);
                $("#save").val("Add Product");
                //$('#insertForm')[0].reset();
               //$(':input','#insertForm').not(':button',':radio').val('').removeAttr('checked');
               //$("input[name='pollution_affection']").prop('checked',false);
                
            },
            error: function(error) {
                console.log(error);
            }
        });
        
    }

    function updated(product_id) {
        if (confirm('Are you sure you want to EDIT this product?')) {
            console.log("EDIT (SCRIPT)id is->"+product_id);
            $.ajax({
                url: 'index.php?action=edit&id='+product_id,
                type: 'POST',
                data: {action:"edit",product_id:product_id},
                success: function(response) {
                    alert(response);
                    console.log('Editing product with ID: ' + product_id);
                     $('#t1').appendTo('#resultTable');
                    $('#resultTable').html(response);
                    //$('#insertForm')[0].reset();
                    $("#save").val("Update product");
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    }

    function deleted(product_id) {
        if (confirm('Are you sure you want to DELETE this product?')) {
            console.log("DELETE (SCRIPT)id is->"+product_id);
            $.ajax({
                url: 'index.php?action=delete&id='+ product_id,
                type: 'POST',
                data: {action:"delete",product_id:product_id},
                success: function(response) {
                    alert(response);
                    console.log('Deleting product with ID: ' + product_id);
                    $('#t1').appendTo('#resultTable');
                    $('#resultTable').html(response);
                    $("#product_id").val("");
                    //$('#insertForm')[0].reset();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    }
  
</script>


<div id="resultTable">

<!--
</div>

<div id="resultForm"> -->
<?php echo form($product_id="", $product_name ="", $product_amount ="", $product_quantity ="", $expiry_date ="", $pollution_affection = ""); ?>
<?php echo displayTable($conn); ?>
</div>




</body>
</html>










<?php 
function insertProduct($conn){
   // global $conn;
    $product_name = $_POST['product_name'];
    $product_amount = $_POST['product_amount'];
    $product_quantity = $_POST['product_quantity'];
    $expiry_date = $_POST['expiry_date'];
    $pollution_affection =  isset($_POST['pollution_affection']) ? 'yes' : 'no';

    $insertsql = "INSERT INTO products (product_name, product_amount, product_quantity, expiry_date, pollution_affection) VALUES ('$product_name', '$product_amount', '$product_quantity', '$expiry_date', '$pollution_affection')";
    mysqli_query($conn,$insertsql);
    //$product_id = $product_name = $product_amount = $product_quantity = $expiry_date = $pollution_affection = "";
    echo form($product_id="", $product_name ="", $product_amount ="", $product_quantity ="", $expiry_date ="", $pollution_affection = "");
}
function updateProduct($conn){
    //global $conn;
    $product_id = $_POST["product_id"];
    $product_name = $_POST['product_name'];
    $product_amount = $_POST['product_amount'];
    $product_quantity = $_POST['product_quantity'];
    $expiry_date = $_POST['expiry_date'];
    $pollution_affection =  isset($_POST['pollution_affection']) ? 'yes' : 'no';

    $updatesql = "UPDATE products SET product_name='$product_name', product_amount='$product_amount', product_quantity='$product_quantity', expiry_date='$expiry_date', pollution_affection='$pollution_affection' WHERE product_id='$product_id'";
    mysqli_query($conn,$updatesql);
   // $product_id = $product_name = $product_amount = $product_quantity = $expiry_date = $pollution_affection = "";
    echo form($product_id="", $product_name ="", $product_amount ="", $product_quantity ="", $expiry_date ="", $pollution_affection = "");
}

function deleteProduct($conn,$product_id){
    $sql = "DELETE FROM products WHERE product_id='$product_id'";
    mysqli_query($conn,$sql);

    //$product_id = $product_name = $product_amount = $product_quantity = $expiry_date = $pollution_affection = "";
    echo form($product_id="", $product_name ="", $product_amount ="", $product_quantity ="", $expiry_date ="", $pollution_affection = "");
}

function form($product_id,$product_name,$product_amount,$product_quantity,$expiry_date,$pollution_affection){
    //global $product_id,$product_name,$product_amount,$product_quantity,$expiry_date,$pollution_affection; ?>
    
    <form id = "insertForm" method = "post" style="margin-top:-150px;">
        <table>
            <tr colspan="8"><td><h2>Insert Data</h2></td></tr>
            <tr><td><input type = "hidden" id = "product_id" name = "product_id" value="<?php echo $product_id; ?>"></td>
            </tr>

            <tr>
            <td><label for = "product_name">Product Name:</label></td>
            <td><input type = "text" id = "product_name" name = "product_name" required value="<?php echo $product_name; ?>"></td><br>
            </tr>

            <tr>
            <td><label for = "product_amount">Product Amount:</label></td>
            <td><input type = "number" id = "product_amount" name = "product_amount" required value="<?php echo $product_amount; ?>"></td><br>
            </tr>

            <tr>
            <td><label for = "product_quantity">Product Quantity:</label></td>
            <td><input type = "number" id = "product_quantity" name = "product_quantity" required value="<?php echo $product_quantity; ?>"></td><br>
            </tr>

            <tr>
            <td><label for = "expiry_date">Expiry Date:</label></td>
            <td><input type = "date" id = "expiry_date" name = "expiry_date" required value="<?php echo $expiry_date; ?>"></td><br>
            </tr>

            <tr>
            <td><label for = "PollutionAffection">Pollution Affection:</label></td>
            <td><label><input type = "checkbox" id = "pollution_affection" name = "pollution_affection"   <?php echo ($pollution_affection == 'yes' ? 'checked' : ''); ?>> Yes (Means Tick)</label></td><br>
            </tr>
            <br>
            <tr>
            <td><input type = "button" name = "submit_form" id="save" onclick = "inserted()"  value = "Add Product"></td><br>
            </tr>
        </table>
    </form>

<?php
              
 }

function displayTable($conn) {
   // global $conn;
    $displaysql = "SELECT * FROM products";
    $result = $conn->query($displaysql);
    
?>

    <div id="t1">
        <h2>Product Details</h2>
        <table border="2px" id="table1" cellpadding="3px" style="border-collapse:collapse;text-align:center;">
        <thead>
        <tr>
            <th><i>Product ID</i></th>
            <th><i>Product Name</i></th>
            <th><i>Product Amount</i></th>
            <th><i>Product Quantity</i></th>
            <th><i>Expiry Date</i></th>
            <th><i>Pollution Affection</i></th>
            <th><i>Actions</i></th>
        </tr> 
        </thead>
        <tbody>
        <?php
        if($result->num_rows > 0){
            while ($row = $result->fetch_assoc()) {
            ?>
            
                <tr>
                <td> <?php echo $row["product_id"] ?> </td>
                <td> <?php echo $row["product_name"] ?> </td>
                <td> <?php echo $row["product_amount"] ?> </td>
                <td> <?php echo $row["product_quantity"] ?> </td>
                <td> <?php echo date("d/m/Y", strtotime($row["expiry_date"])) ?> </td>
                <td> <?php echo $row["pollution_affection"] ?> </td>
                <td>
                    <button><a href="javascript:void(0);" id="editing" onclick="updated(<?php echo  $row['product_id'] ?> )" style="text-decoration:none; color:black;">Edit</a></button> <br>
                    <button><a href="javascript:void(0);" id="deleting" onclick="deleted(<?php echo  $row['product_id']  ?> )" style="text-decoration:none; color:black;">Delete</a></button>
                </td>
                </tr>

            <?php
            }
        }else {
            echo "<tr><td colspan='8'><b><i><center>No products found</center></b></i></td></tr>";
        }
        ?>

        </tbody>
        </table>
    </div>
<?php
}
?>
