<?php
	$connection = mysqli_connect("localhost","root","","krackers");
	if($_SERVER['REQUEST_METHOD']=="POST"){
	//if(isset($_POST["submit"])){
		$customer_name=$_POST["customer_name"];
		$customer_age = $_POST["customer_age"];
		$customer_gender = $_POST["customer_gender"];
		$customer_address = $_POST["customer_address"];
		$customer_phoneno = $_POST["customer_phoneno"];

		$sql = "INSERT INTO customers(customer_name,customer_age,customer_gender,customer_address,customer_phoneno) VALUES('$customer_name', '$customer_age', '$customer_gender', '$customer_address', '$customer_phoneno')";
		mysqli_query($connection, $sql);
		echo 
			"<script>alert('Data Insertd successfully.....');</script>";
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>CUSTOMERS</title>
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
		input[type="text"]{
			background-color:darkgray;
			color:red;
			border-radius:12px;
			height:20px;
			font-size:18px;
			padding:10px;
			border:0;
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
<h1>CUSTOMERS ENTRY BOOK</h1>
<form action="" method="post" autocomplete="off">
	<label for="">CUSTOMER NAME:</label>
	<input type="text" name="customer_name" required value=""/><br/><br/>
		
	<label for="">CUSTOMER AGE:</label>
	<input type="text" name="customer_age" required value=""/><br/><br/>
		
	<label for="">CUSTOMER GENDER:</label>
    <input type="radio" name="customer_gender" value="m" required/>Male
    <input type="radio" name="customer_gender" value="f" />Female </br/><br/>
		
	<label for="">CUSTOMER ADDRESS:</label>
    <input type="text" name="customer_address" required value=""/><br/><br/>
	
	<label for="">CUSTOMER PHONENO</label>
	<input type="text" name="customer_phoneno" value="" required/></br/><br/>

	<button type="submit" id="btn" name="submit">submit</button>
</form>
</div>

</body>
</html>
