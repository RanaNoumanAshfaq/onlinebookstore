//connection//
<?php

$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "nouman";  


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//sign up
if (isset($_POST['signup'])) {
  
    $signup_username = mysqli_real_escape_string($conn, $_POST['username']);
    $signup_password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($signup_password, PASSWORD_DEFAULT); 

   
    $sql_check = "SELECT * FROM users WHERE username = '$signup_username'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
       
        $error_signup = "Username already exists. Please choose another one.";
    } else {
        
        $sql_insert = "INSERT INTO users (username, password) VALUES ('$signup_username', '$hashed_password')";
        if ($conn->query($sql_insert) === TRUE) {
            
            header("Location: login.php"); 
            exit();
        } else {
            $error_signup = "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    }
}

//login
if (isset($_POST['login'])) {
    
    $login_username = mysqli_real_escape_string($conn, $_POST['username']);
    $login_password = mysqli_real_escape_string($conn, $_POST['password']);

    
    $sql = "SELECT * FROM users WHERE username = '$login_username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        
        if (password_verify($login_password, $user['password'])) {
           
            session_start();
            $_SESSION['username'] = $user['username'];  
            $_SESSION['profile_picture'] = $user['profile_picture'];  
            header("Location: homepage.php"); 
            exit();
        } else {
            $error_login = "Incorrect password. Please try again.";
        }
    } else {
        $error_login = "No user found with that username.";
    }
}
?>
//css
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login and Registration</title>
    <style>
        /* Basic Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .login-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 350px;
            padding: 20px;
            text-align: center;
        }
        .avatar {
            width: 80px;
            height: 80px;
            background: url('https://via.placeholder.com/80') no-repeat center center/cover;
            border-radius: 50%;
            margin: 0 auto 20px;
        }
        h2 {
            margin-bottom: 20px;
            color: #4facfe;
        }
        input[type="text"], input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            width: 90%;
            padding: 10px;
            background: #4facfe;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #00b4d8;
        }
        .remember {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
            font-size: 14px;
        }
        .forgot {
            color: #4facfe;
            text-decoration: none;
            font-size: 12px;
        }
        .forgot:hover {
            text-decoration: underline;
        }
        p {
            margin-top: 15px;
            font-size: 14px;
        }
        a {
            color: #4facfe;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        
        #signupForm {
            display: none;
        }
    </style>
</head>
//html//
<body>

    <div class="login-container">
        <div class="avatar"></div>
        
        
        <form action="" method="POST">
            <h2>Sign In</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <div class="remember">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
                <a href="#" class="forgot">Forgot Password?</a>
            </div>
            <button type="submit" name="login">Login</button>
            <?php
            if (isset($error_login)) {
                echo "<p style='color: red;'>$error_login</p>"; 
            }
            ?>
            <p>Don't have an account? <a href="#" id="showSignup">Sign Up</a></p>
        </form>

       
        <form action="" method="POST" id="signupForm">
            <h2>Sign Up</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="signup">Sign Up</button>
            <?php
            if (isset($error_signup)) {
                echo "<p style='color: red;'>$error_signup</p>"; 
            }
            ?>
            <p>Already have an account? <a href="#" id="showSignin">Sign In</a></p>
        </form>
    </div>

    <script>
        const signupForm = document.getElementById('signupForm');
        const showSignup = document.getElementById('showSignup');
        const showSignin = document.getElementById('showSignin');
        const loginForm = document.querySelector('form[action=""]');

        showSignup.addEventListener('click', (e) => {
            e.preventDefault();
            signupForm.style.display = 'block';
            loginForm.style.display = 'none';
        });

        showSignin.addEventListener('click', (e) => {
            e.preventDefault();
            signupForm.style.display = 'none';
            loginForm.style.display = 'block';
        });
    </script>
</body>
</html>
