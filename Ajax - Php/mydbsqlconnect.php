<?php 
	$connection = mysqli_connect("localhost","root","","krackers");
	if(isset($_POST["submit"])){
		$product_name = $_POST["product_name"];
		$product_quantity = $_POST["product_quantity"];
		$product_amount = $_POST["product_amount"];
		$expiry_date = $_POST["expiry_date"];
		$pollution_affection = $_POST["pollution_affection"];

		$sql = "INSERT INTO products(product_name, product_amount, product_quantity, expiry_date, pollution_affection) VALUES('$product_name', '$product_amount', '$product_quantity', '$expiry_date', '$pollution_affection')";
		mysqli_query($connection, $sql);
			header("Location: display.php");
			exit();
		
		/*echo 
			"<script>alert('Inserted succesfully');</script>";*/
		
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>KRACKERS</title>
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
		#btn{
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
		input[type="radio"]{
			accent-color:red;
		}
		input:focus{
			outline:0;
		}
		#btn:hover{
			color:black;
			background-color:red;
		}
	</style>
</head>
<body>
<div id="main">
<h1>KRACKERS LIST ENTRY</h1>

<form action="" method="post" autocomplete="off"><br/><br/>
		
	<label for="">PRODUCT NAME:</label>
		<input type="text" name="product_name" required value=""/><br/><br/>
		
	<label for="">PRODUCT AMOUNT:</label>
		<input type="number" name="product_amount" required value=""/><br/><br/>
		
	<label for="">PRODUCT QUANTITY:</label>
		<input type="number" name="product_quantity" required value=""/><br/><br/>
	
	<label for="">POLLUTION AFFECTION</label>
		<input type="radio" name="pollution_affection" value="yes" required/>yes
		<input type="radio" name="pollution_affection" value="no" />no
	</br/><br/>
	
	<label for="">PRODUCT EXPIRY DATE:</label>
		<input type="date" name="expiry_date" required value=""/><br/><br/>
	
	<br/><br/>
	<button type="submit" id="btn" name="submit">submit</button>
</form>
</div>

</body>
</html>
