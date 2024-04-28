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
        die("Connection failed: ". $conn->connect_error);
    }

    return $conn;
}

// Check form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $conn = db_connect();

    // Check if username is available
    $query = "SELECT * FROM user_details WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $usernameAvailable = mysqli_num_rows($result) == 0;

    if (!$usernameAvailable) {
        $error_message = "The username $username is already taken. Please choose a different one.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert user data into the database
        $query = "INSERT INTO user_details (username, Email, Password) VALUES (?,?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<h2>Sign Up Successful</h2>";
            echo "<p>Welcome, $username! You have successfully signed up with email $email.</p>";
            // Redirect to login page after processing
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Error: ". $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <link rel="stylesheet" href="Home.css">
    <link rel="stylesheet" href="sign-up.css">
</head>
<body>
<header id="header"><a href="/HOME1.html" ><img src="logo-no-background.png" alt="Go-Task.com" id="Logo"></a></header>
<nav>       
    <a href="/Home.html">Home</a>
    <a href="Calender1.html">Calendar</a>
    <a href="Categories.html">Categories</a>
    <a href="Login.html">Login</a>
    <a href="Signup.html">Sign Up</a>
</nav>

<div class="container3">
    <h2>Sign Up</h2>
    <?php if (isset($error_message)): ?>
        <p style='color:red;'><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group" id="buttons">
            <input type="submit" value="Sign Up">
            <a href="login.html">Login</a>
        </div>
    </form>
</div>
<footer>
    <p>&copy; 2024 Go-Task Web Develoment Project.</p>
    
    <div class="ref"><ul>
      <li><a href="https://www.flaticon.com/">Flaticon.com</a></li>
      <li><a href="https://logo.com">Logo.com</a></li>
      </ul>
    </div>
</footer>
</body>
</html>
