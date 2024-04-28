<!-- http://localhost/WP%20Practical/Practical_16/login.php -->

<?php
session_start();

// Function to connect to MySQL database
function db_connect() {
    $servername = "localhost"; // Change this to your MySQL server address
    $username = "root"; // Change this to your MySQL username
    $password = ""; // Change this to your MySQL password
    $dbname = "go-task"; // Change this to your MySQL database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to authenticate user
function authenticate_user($username, $password) {
    $conn = db_connect();
    
    // Prevent SQL injection by escaping special characters
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query the database to check if the username and password match
    $query = "SELECT * FROM user_details WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    // If there is a match, set session variable and return true
    if ($result && $result->num_rows > 0) {
        $_SESSION['username'] = $username;
        location("User_page.html");
    } else {
        return false;
    }

    $conn->close();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Authenticate user
    if (authenticate_user($username, $password)) {
        // Redirect to dashboard or any other authenticated page
        header("Location: dashboard.php");
        exit();
    } else {
        // Authentication failed, show error message
        $error_message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Authentication</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
