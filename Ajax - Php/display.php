<html> 
<head>
	<style>
	table,th{
		border-collapse: collapse;
		padding:10px;
		border:0;
		border:1px solid black;
	}
	th{
		background-color:gray;
		color:pink;
	}
	tr:nth-child(odd){
		background: rgb(236,92,138);
		background: linear-gradient(90deg, rgba(236,92,138,1) 0%, rgba(0,193,191,1) 100%);
		color:black;
		border:0;
	}
	tr:nth-child(even){
		background: rgb(174,75,241);
		background: linear-gradient(90deg, rgba(174,75,241,1) 0%, rgba(255,44,44,1) 35%, rgba(200,137,143,1) 69%, rgba(252,176,69,1) 100%);
		color:black;
		border:0;
	}
	td{
		padding:10px;
		text-align:center;
		font-size:15px;
		font-family:optima;
		letter-spacing:0.7;
		font-weight:600;
	}
	body{
		display:flex;
		justify-content:center;
		align-items:center;
		flex-direction:column;
		padding:40px;
		padding-top:0px;
		background: rgb(2,0,36);
		background: linear-gradient(90deg, rgba(2,0,36,1) 16%, rgba(0,212,255,1) 45%, rgba(9,9,121,1) 91%);
	}
	a{
		height:35%;
		width:80%;
		background-color:lightgray;
		border:0;
		border-radius:10px;
		font-size:10px;
		cursor:pointer;
		padding:5px;
		text-decoration:none;	
		color:	maroon;
		font-family:optima;
		font-weight:bold;
	}
	</style>
</head>
<body>
<i>
<h1>MY KRACKERS LIST</h1></i>

	
	<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "krackers";
	$connection = new mysqli($servername, $username, $password, $dbname);
	if ($connection->connect_error) {
	    die("Connection failed: " . $connection->connect_error);
	}
	$sql = "SELECT * FROM products";
	$result = $connection->query($sql);
	if($result->num_rows>0){
		echo "<table>
		<thead>
		<tr>
			<th>PRODUCT ID</TH>
			<th>PRODUCT NAME</th>
			<th>PRODUCT AMOUNT</th>
			<th>PRODUCT QUANTITY</th>
			<th>EXPIRY DATE</th>
			<th>POLLUTION AFFECTION</th>
			<th>ACTIONS</th>
		</tr>
		</thead>";
    	while($row = $result->fetch_assoc()) {
			$formattedExpiryDate = date("d-m-Y", strtotime($row["expiry_date"]));
        	echo "
			<tbody>
			<tr>
        	<td>" .$row["product_id"]. "</td>
        	<td>" .$row["product_name"]. "</td>
        	<td>" .$row["product_amount"]. "</td>
        	<td>" .$row["product_quantity"]. "</td>
        	<td>" .$formattedExpiryDate. "</td>
        	<td>" .$row["pollution_affection"]. "</td>
			<td> <a href='edit.php?id=" . $row["product_id"] . "'>Edit</a>
			<a href='delete.php?id=" . $row["product_id"] . "'>Delete</a></td>
			</td>
        	</tr>
			</tbody>";
    	}
		echo "</table>";
		//$connection->close();
	}else{
		echo "0 results";
	}
	?>
</body>
</html>
