<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "krackers";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle form submission and insert data into the database
function handleFormSubmission($conn) {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_form"])) {
        $name = $_POST["name"];
        $gender = $_POST["gender"];

        $sql = "INSERT INTO users (name, gender) VALUES ('$name', '$gender')";
        if ($conn->query($sql) === TRUE) {
            echo "Record inserted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Function to display the user form
function displayUserForm() {
    echo "<h2>User Form</h2>";
    echo "<form id='userForm' method='post'>";
    echo "<input type='hidden' name='id' ><br>";

    echo "<label for='name'>Name:</label>";
    echo "<input type='text' name='name' required><br>";

    echo "<label for='gender'>Gender:</label>";
    echo "<select name='gender' required>";
    echo "<option value='male'>Male</option>";
    echo "<option value='female'>Female</option>";
    echo "</select><br>";

    echo "<button type='submit' name='submit_form'>Submit</button>";
    echo "</form>";

    // JavaScript section for AJAX
    echo "<script>
        
    </script>";
}

// Function to display the user table
function displayUserTable($conn) {
    echo "<h2>User Table</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Gender</th></tr>";

    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["id"] . "</td><td>" . $row["name"] . "</td><td>" . $row["gender"] . "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='2'>No users found</td></tr>";
    }

    echo "</table>";
}

// Handle form submission
handleFormSubmission($conn);

// Display user form
displayUserForm();

// Display user table
echo "<div id='userTable'>";
displayUserTable($conn);
echo "</div>";

$conn->close();
?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
            $('#userForm').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                sendAjaxRequest(formData);
            });

            function sendAjaxRequest(formData) {
                $.ajax({
                    url: 's1.php', // Replace with your PHP file handling form submission
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Handle the response, e.g., display a success message
                        alert(response);
                        // Optionally, update the user table on the page
                        $('#userTable').html(response);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        });
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajax with PHP and MySQL</title>
    
</head>
<body>

<div id="result"></div>


</body>
</html>

