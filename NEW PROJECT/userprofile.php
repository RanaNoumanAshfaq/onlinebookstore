<?php
session_start();  


$host = 'localhost'; 
$dbname = 'nouman'; 
$user = 'root'; 
$password = ''; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];  
        $email = htmlspecialchars($_POST['email']);
        $phone_number = htmlspecialchars($_POST['phone_number']);
        $address = htmlspecialchars($_POST['address']);
        $postal_code = htmlspecialchars($_POST['postal_code']);
        $profilePicture = '';

        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $targetDir = "uploads/";
            $fileName = uniqid() . "_" . basename($_FILES['profile_picture']['name']);
            $targetFile = $targetDir . $fileName;

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
                    $profilePicture = $targetFile;
                } else {
                    $message = "Failed to upload profile picture.";
                }
            } else {
                $message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            }
        }

        $stmt = $conn->prepare("UPDATE users 
                                SET email = :email, 
                                    phone_number = :phone_number, 
                                    address = :address, 
                                    postal_code = :postal_code, 
                                    profile_picture = :profile_picture 
                                WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':postal_code', $postal_code);
        $stmt->bindParam(':profile_picture', $profilePicture);

        if ($stmt->execute()) {
            $message = "Profile updated successfully!";
        } else {
            $message = "Failed to update profile.";
        }
    } else {
        header("Location: login.php");
        exit();
    }
}

$userData = [];
if (isset($_SESSION['username'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
body, h2, p {
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.profile-container {
    background-color: #ffffff;
    width: 350px;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.profile-container h2 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
}

.home-btn {
    display: inline-block;
    margin-bottom: 15px;
    text-decoration: none;
    color: #ffffff;
    background-color: #007BFF;
    padding: 8px 16px;
    border-radius: 5px;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.home-btn:hover {
    background-color: #0056b3;
}

.profile-picture {
    width: 100px;
    height: 100px;
    background-size: cover;
    background-position: center;
    border-radius: 50%;
    margin: 0 auto 20px;
    border: 2px solid #007BFF;
}

form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 10px;
}

form input[type="text"],
form input[type="email"],
form input[type="file"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
}

form input[type="text"]:readonly {
    background-color: #f9f9f9;
    color: #666;
}

form button {
    background-color: #007BFF;
    color: #ffffff;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #0056b3;
}

.message {
    margin-top: 15px;
    font-size: 14px;
    color: green;
}    </style>
</head>
<body>
    <div class="profile-container">
        <a href="homepage.php" class="home-btn">Home</a>
        <h2>User Profile</h2>
        <div class="profile-picture" style="background-image: url('<?php echo isset($userData["profile_picture"]) ? $userData["profile_picture"] : "https://via.placeholder.com/100"; ?>');"></div>
        
        <form action="userprofile.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($userData['username'] ?? ''); ?>" required readonly>
            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>" required>
            <input type="text" name="phone_number" placeholder="Phone Number" value="<?php echo htmlspecialchars($userData['phone_number'] ?? ''); ?>" required>
            <input type="text" name="address" placeholder="Address" value="<?php echo htmlspecialchars($userData['address'] ?? ''); ?>" required>
            <input type="text" name="postal_code" placeholder="Postal Code" value="<?php echo htmlspecialchars($userData['postal_code'] ?? ''); ?>" required>
            <input type="file" name="profile_picture">
            <button type="submit">Update Profile</button>
        </form>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
