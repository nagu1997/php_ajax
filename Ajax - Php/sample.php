<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajax with PHP and MySQL</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

    <form id="insertForm">
        <label for="productName">Product Name:</label>
        <input type="text" id="productName" name="productName" required><br>

        <label for="productAmount">Product Amount:</label>
        <input type="number" id="productAmount" name="productAmount" required><br>

        <label for="productQuantity">Product Quantity:</label>
        <input type="number" id="productQuantity" name="productQuantity" required><br>

        <label for="expiryDate">Expiry Date:</label>
        <input type="date" id="expiryDate" name="expiryDate" required><br>

        <label for="pollutionAffection">Pollution Affection:</label>
        <input type="checkbox" id="pollutionAffection" name="pollutionAffection" value="yes"> Yes<br>

        <button type="button" onclick="insertData()">Insert Data</button>
    </form>

    <div id="result"></div>

    <script>
        function insertData() {
            var formData = $('#insertForm').serialize();

            $.ajax({
                url: '<?php echo $_SERVER["PHP_SELF"]; ?>', 
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#result').html(response);
                   // $('#insertForm')[0].reset();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'krackers';
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }
        $product_name = $_POST['productName'];
        $product_amount = $_POST['productAmount'];
        $product_quantity = $_POST['productQuantity'];
        $expiry_date = $_POST['expiryDate'];
        $pollution_affection = isset($_POST['pollutionAffection']) ? 'Yes' : 'No';

        $sql = "INSERT INTO products (product_name, product_amount, product_quantity, expiry_date, pollution_affection) 
                VALUES ('$product_name', $product_amount, $product_quantity, '$expiry_date', '$pollution_affection')";
        $conn->query($sql);
        echo 'Data inserted successfully';

        $conn->close();
    }
    ?>

</body>
</html>
